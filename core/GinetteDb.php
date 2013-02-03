<?php

/**
 *
 */
class GinetteDb
{

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
    }

    /**
     * Test if a record with the given id exists
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
     * @param string $modelId The model to find
     * @return GinetteRecord The related model. If not found, will return null.
     */
    public function getModelById($modelId)
    {
        //yet loaded?
        if (isset($this->modelReferences[$modelId])) {
            return $this->modelReferences[$modelId];
        }
        //else...load xml
        $xml=$this->loadRecordXml($modelId);
        $item = $this->fromXml($xml);
        $this->modelReferences[$modelId] = $item;
        return $item;
    }
    public function getLightModel($type,$modelId){
        if (isset($this->modelReferences[$modelId])) {
            return $this->modelReferences[$modelId];
        }
        $item=new $type($modelId,$this);
    }
    public function loadRecordXml($id){
        $file = $this->getModelXmlUrl($id);
        if (!file_exists($file)) {
            //throw new Exception("model node found");
            return false;
        }
        $xml = XmlUtils::load($file);
        return $xml;
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
        $xml = new DOMDocument();
        $xml->preserveWhiteSpace=false;
        $xml->load($file);

        $item = $this->fromXml($xml);
        $this->treeReferences[$treeId] = $item;
        return $item;
    }


    /**
     * Return list of models
     * @return GinetteRecord[]
     */
    public function getModelList(){
        $arr=array();
        $index=new GinetteDbIndex($this);
        $all=$index->allRecords->firstChild;
        for($i=0;$i<$all->childNodes->length;$i++){
            /** @var DOMElement $n  */
            $n=$all->childNodes->item($i);
            $modelType=$n->nodeName;
            $id=$n->getAttribute("id");
            //$arr[]=$this->getModelById($id);

            $arr[]=$modelType::getById($id,$this);
        }

        /*
        foreach(scandir($this->recordsPath) as $f){
            $file=$this->recordsPath."/".$f;
            $modelName=$this->extractNameXml($f);
            if(is_file($file) && $modelName){
                $arr[]=$this->getModelById($modelName);
            }
        }
        */
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
        $id = $xml->firstChild->getAttribute("id");
        if (class_exists($type)) {
            /** @var $model GinetteRecord */
            $model = new $type("FROM_DB_HACK",$this);
            $rc=new ReflectionClass($type);
            $_id=$rc->getProperty("id");
            $_id->setAccessible(true);
            $_id->setValue($model,$id);
            $_id->setAccessible(false);
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
