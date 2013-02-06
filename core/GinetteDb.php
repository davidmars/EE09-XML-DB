<?php

/**
 *
 */
class GinetteDb
{
    /**
     * @var GinetteDb[] Here are instances of the databases.
     * If ONLY ONE, no more no less, is set then, the database argument will be optional when you will access records.
     */
    private static $instances=array();

    /**
     * If there is only one instance database, this one is returned elsewhere throws an error.
     * @return GinetteDb The only ONE ginette database you use.
     * @throws Exception
     */
    public static function current(){
        if(count(self::$instances)==1){
            return self::$instances[0];
        }else{
            throw new Exception("Ginette says :
            Tu te compliques la vie toi !
            Database parameter is optional when you play with only ONE database !");
        }
    }

    /**
     * @var GinetteDbSettings Place where are stored globals variables like uids
     */
    public $settings;
    /**
     * @var M_fieldManager[]
     */
    public $definitions = array();

    /**
     * @var string Path to the database relative to the main php file. Useful to get web url
     */
    public $directory;
    /**
     * @param string $rootPath where is located your database?
     */
    public function __construct($rootPath)
    {
        self::$instances[]=$this;

        //include core files
        require_once(__DIR__) . "/utils/ClassAutoLoader.php";
        $autoLoader=new ClassAutoLoader();
        $autoLoader->addPath(__DIR__,true);

        //code generation templates
        View::$rootPaths[]=__DIR__."/mvc/v";



        //set directories
        $this->paths=new GinetteDbPaths($rootPath,__DIR__);

        //...and auto load the php classes relatives to THIS database :)
        $autoLoader->addPath($this->paths->definitions,true);

        $this->performTests();



        //boot the database
        $this->bootDefinitions();

        //
        $this->settings=new GinetteDbSettings($this);
        $this->index=new GinetteDbIndex($this);
    }

    /**
     * Test if a record with the given id exists.
     * To perform this operation it looks if the xml file exists.
     *
     * @param $id
     * @return bool true if it exists elsewhere false.
     */
    public function modelExists($id){
        if(file_exists($this->getModelXmlUrl($id))){
           return true;
        }
        return false;
    }

    /**
     * Test if a tree with the given id exists
     * @param string $id
     * @return bool true if it exists elsewhere false.
     */
    public function treeExists($id){
        if(file_exists($this->getTreeXmlUrl($id))){
            return true;
        }
        return false;
    }

    /**
     * performs some basics tests and die with message if fails.
     */
    private function performTests(){
        if(!is_dir($this->paths->root)){
            die("Error opening database : ".$this->paths->root." is not a valid directory");
        }
        if(!is_dir($this->paths->records)){
            die("Error opening database : ".$this->paths->records." is not a valid directory");
        }
        if(!is_dir($this->paths->definitions)){
            die("Error opening database : ".$this->paths->definitions." is not a valid directory");
        }

    }

    /**
     * Scan the definitions folder and boot all relative models
     */
    private function bootDefinitions()
    {
        //then load definitions (after, because we need model classes for associations fields)
        foreach (scandir($this->paths->definitions) as $file) {
            $f = $this->paths->definitions . "/" . $file;
            if (is_file($f)) {
                $modelName=$this->extractNameXml($file);
                if ($modelName) {
                    $this->bootModel($modelName);
                }
            }
        }
    }

    /**
     * @param string $file A file name (without path).
     * @return bool|string If not an xml will return false else will return the file name without extension.
     */
    public function extractNameXml($file){
        if (preg_match("#(.*)\.xml#", $file, $matches)) {
            return $matches[1];
        }
        return false;
    }
    /**
     * Loads the structure xml, include the related php file.
     * @param string $modelName The model name
     */
    private function bootModel($modelName)
    {
        //loads the xml
        $xml = new DOMDocument();
        $xml->preserveWhiteSpace=false;
        $xml->load($this->paths->definitions . "/" . $modelName . ".xml");

        $this->definitions[$modelName] = new M_fieldManager($xml);

        //php gen
        $view=new View("class/modelXml",$this->definitions[$modelName]);
        $code=$view->render();
        file_put_contents($this->paths->definitions."/generated/".$modelName."__gen.php",$code);
    }

    /**
     * @param string $modelName The model name
     * @return M_fieldManager Here are information about the given model.
     */
    public function getModelDefinition($modelName)
    {
        return $this->definitions[$modelName];
    }

    /**
     * @param $modelId
     * @return string
     */
    public function getModelXmlUrl($modelId)
    {
        return $this->paths->records . "/$modelId.xml";
    }
    public function getTreeXmlUrl($treeId)
    {
        return $this->paths->trees . "/$treeId.xml";
    }

    /**
     * @var GinetteRecord[] Here are the models which have been loaded. This array prevent multiple model references.
     */
    private $modelReferences = array();
    /**
     * @var GinetteTree[] Here are the trees which have been loaded. This array prevent multiple tree references.
     */
    private $treeReferences = array();

    /**
     *
     * Search a model by id.
     *
     * @param string $id The record to find
     * @return GinetteRecord The related model. If not found, will return null.
     */
    public function getModelById($id)
    {
        //existing model?
        if (isset($this->modelReferences[$id])) {
            return $this->modelReferences[$id];
        }

        //search in the index...maybe not a good idea
        $record=$this->index->getRecord($id);
        if($record){
            return $record;
        }

        //not found in the index, but if file exists.
        if($this->modelExists($id)){
           $xml=$this->loadRecordXml($id);
           $record=$this->fromXml($xml);
            if($record){
                //correct the index
                $this->index->add($record);
            }
           return $record;
        }
        return false;
    }

    /**
     * If the record is already created returns it else create it, index cache it and return it.
     * @param string $id Id of the record.
     * @param string $type Type of the record.
     * @return GinetteRecord The record, it can be a new one or an existing one if it has been previously acceded.
     */
    public function getRecordInstance($id,$type){
        if (isset($this->modelReferences[$id])) {
            return $this->modelReferences[$id];
        }else{
            $record=new $type($id,$this);
            $this->modelReferences[$id]=$record;
            return $record;
        }
    }


    /**
     * Loads the xml related to a record and return the related XML document.
     *
     * @param string $id The record id
     * @return DOMDocument If the file doesn't exists return an exception.
     * @throws Exception
     */
    public function loadRecordXml($id){
        $file = $this->getModelXmlUrl($id);
        if($this->modelExists($id)){
            traceCode("Load xml $id");
            $xml = XmlUtils::load($file);
            return $xml;
        }else{
            throw new Exception("
            Ginette says :
            T'as perdu quelque chose mon ptit gars?
            There is no file '$file' "
            );

        }

    }

    /**
     * Create a new record
     * @param string $id
     * @param string $type
     * @return GinetteRecord
     * @throws Exception
     */
    public function createRecord($id,$type){
        if(class_exists($type)){
            if(!$this->modelExists($id)){
                //set the xml from the structure
                $definition=$this->getModelDefinition($type);
                $xml = $definition->xml->cloneNode(true);
                /** @var $root DOMElement */
                $root=$xml->firstChild;
                $root->setAttribute("id",$id);
                $root->setAttribute("created",time());
                $root->setAttribute("updated",time());
                /** @noinspection PhpParamsInspection */
                XmlUtils::save($xml,$this->paths->records."/$id.xml");
                $record=$this->getRecordInstance($id,$type);
                $this->index->add($record);
                return $record;
            }else{
                throw new Exception("Ginette says : The record '$id' already exists!");
            }
        }else{
            throw new Exception("Ginette says : $type is not a valid record type");
        }
    }

    /**
     * @param string $treeId The tree to find
     * @return GinetteTree The related tree. If not found, will return null.
     */
    public function getTreeById($treeId)
    {
        //yet loaded?
        if (isset($this->treeReferences[$treeId])) {
            return $this->treeReferences[$treeId];
        }
        //else...load xml
        $file = $this->getTreeXmlUrl($treeId);
        if (!file_exists($file)) {
            return null;
        }
        $xml=XmlUtils::load($file);
        $item = $this->fromXml($xml);
        $this->treeReferences[$treeId] = $item;
        return $item;
    }

    /**
     * @var GinetteDbIndex
     */
    public $index;

    /**
     * Return list of models
     * @return GinetteRecord[]
     */
    public function getModelList(){
        $arr=array();

        $all=$this->index->allRecords->firstChild;
        for($i=0;$i<$all->childNodes->length;$i++){
            /** @var DOMElement $n  */
            $n=$all->childNodes->item($i);
            $type=$n->nodeName;
            $id=$n->getAttribute("id");
            $arr[]=$this->getRecordInstance($id,$type);
        }
        return $arr;
    }

    /**
     * Return a model from a DOMDocument xml object
     * @param DOMDocument $xml
     * @throws Exception
     * @return GinetteRecord The model object. In fact it will be a typed model according to xml type value, not a generic ModelXML object.
     */
    private function fromXml($xml)
    {
        $type = $xml->firstChild->nodeName;
        /** @noinspection PhpUndefinedMethodInspection */
        $id = $xml->firstChild->getAttribute("id");
        if (class_exists($type)) {
            /** @var $model GinetteRecord */
            $model = $this->getRecordInstance($id,$type);
            $model->xml=$xml;
            return $model;
        } else {
            throw new Exception("Ginette t'engueule ptit con! FromXml error! there is no class $type");
        }
    }

    public function deleteModel($id)
    {
        //TODO::write this class
    }

    public function __toString(){
        return "GinetteDb rootPath : ".$this->paths->root;
    }

}
