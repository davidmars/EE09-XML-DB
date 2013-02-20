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

        //indexes
        $this->settings=new GinetteDbSettings($this);
        $this->index=new GinetteDbIndex($this);

        $this->fileRoot=new GinetteDir($this->paths->files,$this);


    }

    /**
     * Use it to find records in this database.
     * @param string $type Type of records you search
     * @return GinetteRecordFinder
     */
    public function find($type){
        $finder=new GinetteRecordFinder($this);
        $finder->selectType($type);
        return $finder;
    }

    /**
     * Name of the database. In fact it is the directory name
     */
    public function name(){
        return Francis::get($this->paths->root)->fileName();
    }

    /**
     * @var GinetteDir
     */
    public $fileRoot;
    /**
     * Test if a record with the given id exists.
     * To perform this operation it looks if the xml file exists.
     *
     * @param $id
     * @return bool true if it exists elsewhere false.
     */
    public function recordExists($id){
        if(file_exists($this->getRecordXmlUrl($id))){
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

        //php code generation
        $view=new View("class/modelXml",$this->definitions[$modelName]);
        $code=$view->render();
        file_put_contents($this->paths->definitions."/".$modelName.".php",$code);
    }

    /**
     * Return information about the structure of a kind of record.
     * @param string $recordName The record name
     * @return M_fieldManager Here are information about the given record family.
     */
    public function getRecordDefinition($recordName)
    {
        return $this->definitions[$recordName];
    }

    /**
     * Return The url of the data xml relative to a record
     * @param $recordId
     * @return string
     */
    public function getRecordXmlUrl($recordId)
    {
        return $this->paths->records . "/$recordId.xml";
    }
    private function getTreeXmlUrl($treeId)
    {
        return $this->paths->trees . "/$treeId.xml";
    }

    /**
     * @var GinetteRecord[] Here are the records which have been loaded. This array prevent multiple record references.
     */
    private $recordsReferences = array();
    /**
     * @var GinetteTree[] Here are the trees which have been loaded. This array prevent multiple tree references.
     */
    private $treeReferences = array();
    /**
     * @var GinetteFileSystemEntry[] Here are the files and folders which have been loaded. This array prevent multiple file or folder references.
     */
    private $filesReferences=array();

    /**
     *
     * Search a record by id.
     *
     * @param string $id The record to find
     * @return GinetteRecord The related record. If not found, will return null.
     */
    public function getRecordById($id)
    {
        //existing record?
        if (isset($this->recordsReferences[$id])) {
            return $this->recordsReferences[$id];
        }

        //search in the index...maybe not a good idea
        $record=$this->index->getRecord($id);
        if($record){
            return $record;
        }

        //not found in the index, but if file exists.
        if($this->recordExists($id)){
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
     * @param bool $create If set to true and the record doesn't exists, will create it
     * @return GinetteRecord|bool The record, it can be a new one or an existing one if it has been previously acceded.
     */
    public function getRecordInstance($id,$type,$create=false){
        if (isset($this->recordsReferences[$id])) {
            return $this->recordsReferences[$id];
        }else{
            if($this->recordExists($id)){
                $record=new $type($id,$this);
                $this->recordsReferences[$id]=$record;
            }else if($create){
                $r=$this->createRecord($id,$type);
                return $r;
            }else{
                return false;
            }


            return $record;
        }
    }

    /**
     * @param string $id
     * @return GinetteTree
     */
    public function getTreeInstance($id){
        if(isset($this->treeReferences[$id])){
            return $this->treeReferences[$id];
        }else{
            $inst=new GinetteTree($id,$this);
            $this->treeReferences[$id]=$inst;
            return $this->treeReferences[$id];
        }
    }
    /**
     * @param $id
     * @return bool|GinetteFileSystemEntry
     */
    public function getFileInstance($id){
        if(!preg_match("#".$this->paths->files."/"."#",$id)){
            $id=$this->paths->files."/".$id;
        }
        if(isset($this->filesReferences[$id])){
            return $this->filesReferences[$id];
        }else{

            $abs=$id;
            if(file_exists($abs)){
                if( is_dir($abs)){
                    $this->filesReferences[$id]=new GinetteDir($abs,$this);
                }else if(is_file($abs)){
                    $file=new Francis($abs);
                    switch(strtolower($file->extension())){
                        case "jpg":
                        case "gif":
                        case "png":
                        case "bmp":
                            $this->filesReferences[$id]=new GinetteFileImage($abs,$this);
                            break;
                        default:
                            $this->filesReferences[$id]=new GinetteFile($abs,$this);
                    }
                }
                return $this->filesReferences[$id];
            }else{
                return false;
            }
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
        $file = $this->getRecordXmlUrl($id);
        if($this->recordExists($id)){
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
            if(!$this->recordExists($id)){
                //set the xml from the structure
                $definition=$this->getRecordDefinition($type);
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
     * Return list of records
     * @param string $type Specifies a type of records to list. If null, all kind of records are listed
     * @return GinetteRecord[]
     */
    public function getRecordList($type=null){
        $arr=array();

        $rootNode=$this->index->allRecords->firstChild;

        if($type){
            $xpath = new DOMXpath($this->index->allRecords);
            $list=$xpath->query("/Records/".$type."");
        }else{
            $list=$rootNode->childNodes;
        }


        for($i=0;$i<$list->length;$i++){
            /** @var DOMElement $n  */
            $n=$list->item($i);
            $type=$n->nodeName;
            $id=$n->getAttribute("id");
            $arr[]=$this->getRecordInstance($id,$type);
        }
        return $arr;
    }

    /**
     * Return a record from a DOMDocument xml object
     * @param DOMDocument $xml
     * @throws Exception
     * @return GinetteRecord The record object. In fact it will be a typed record according to xml type value, not a generic ModelXML object.
     */
    private function fromXml($xml)
    {
        $type = $xml->firstChild->nodeName;
        /** @noinspection PhpUndefinedMethodInspection */
        $id = $xml->firstChild->getAttribute("id");
        if (class_exists($type)) {
            /** @var $record GinetteRecord */
            if($type=="GinetteTree"){
                $tree=$this->getTreeInstance($id);
                $tree->xml=$xml;
                return $tree;
            }else{
                $record = $this->getRecordInstance($id,$type);
                if(!$record){
                    throw new Exception("pas de record id=$id type=$type");
                }
                $record->xml=$xml;
                return $record;
            }

        } else {
            throw new Exception("Ginette t'engueule ptit con! FromXml error! there is no class $type");
        }
    }



    public function deleteRecord($id)
    {
        //TODO::write this class
    }

    public function __toString(){
        return "GinetteDb rootPath : ".$this->paths->root;
    }

}
