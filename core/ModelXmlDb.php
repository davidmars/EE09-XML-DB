<?php
/**
 *
 */
class ModelXmlDb
{
    /**
     * @var string Where are the data?
     */
    public $rootPath;
    /**
     * @var string Where are the definitions?
     */
    private $definitionsPath;
    /**
     * @var string where are the data?
     */
    private $dataPath;
    /**
     * @var M_fieldManager[]
     */
    public $definitions = array();
    /**
     * @var ModelXmlDb
     */
    public static $current;
    /**
     * @param string $rootPath where is located your database?
     */
    public function __construct($rootPath)
    {
        //include core files
        require_once(__DIR__ . "/XmlUtils.php");
        require_once(__DIR__ . "/ModelXml.php");

        //fields
        require_once(__DIR__ . "/FieldManager.php");
        require_once(__DIR__ . "/fields/NodeField.php");
        require_once(__DIR__ . "/fields/File.php");
        require_once(__DIR__ . "/fields/FileImage.php");
        require_once(__DIR__ . "/fields/Association.php");

        //code generation
        require_once(__DIR__ . "/View.php");


        //okay...it's not a really good idea to put it in static. TODO::remove this ugly static.
        ModelXml::$db=$this;
        self::$current=$this;

        //set directories
        $this->rootPath = $rootPath;
        $this->dataPath = $this->rootPath . "/data";
        $this->definitionsPath = $this->rootPath . "/definitions";

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
     * performs some basics tests and die with message if fails.
     */
    private function performTests(){
        if(!is_dir($this->rootPath)){
            die("Error opening database : ".$this->rootPath." is not a valid directory");
        }
        if(!is_dir($this->dataPath)){
            die("Error opening database : ".$this->dataPath." is not a valid directory");
        }
        if(!is_dir($this->definitionsPath)){
            die("Error opening database : ".$this->definitionsPath." is not a valid directory");
        }
    }

    /**
     * Scan the definitions folder and boot all relative models
     */
    private function bootDefinitions()
    {

        //first include classes
        foreach (scandir($this->definitionsPath) as $file) {
            $f = $this->definitionsPath . "/" . $file;
            if (is_file($f)) {
                if (preg_match("#(.*)\.php$#", $file, $matches)) {
                    $modelName = $matches[1];
                    require_once($this->definitionsPath . "/$modelName.php");
                    traceCode("boot $modelName.php");
                }
            }
        }
        //then load definitions (after, because we need model classes for associations fields)
        foreach (scandir($this->definitionsPath) as $file) {
            $f = $this->definitionsPath . "/" . $file;
            if (is_file($f)) {
                if (preg_match("#(.*)\.xml$#", $file, $matches)) {
                    $modelName = $matches[1];
                    $this->bootModel($modelName);
                    traceCode("boot $modelName.xml");
                }
            }
        }
    }

    /**
     * Loads the structure xml, include the related php file.
     * @param string $modelName The model name
     */
    private function bootModel($modelName)
    {
        //loads the xml
        $xml = new DOMDocument();
        $xml->load($this->definitionsPath . "/" . $modelName . ".xml");
        $this->definitions[$modelName] = new M_fieldManager($xml);

        //php gen
        $view=new View("gen/class",$this->definitions[$modelName]);
        $code=$view->render();
        file_put_contents($this->definitionsPath."/generated/".$modelName."__gen.php",$code);
    }

    /**
     * @param string $modelName The model name
     * @return M_fieldManager Here are information about the given model.
     */
    public function getModelDefinition($modelName)
    {
        traceCode("getDefinition ".$modelName);
        return $this->definitions[$modelName];
    }

    public function getModelXmlUrl($modelId)
    {
        return $this->dataPath . "/$modelId.xml";
    }

    /**
     * @var ModelXml[] Here are the models which have been loaded. This array prevent multiple model references.
     */
    private $modelReferences = array();

    /**
     * @param string $modelId The model to find
     * @return ModelXml The related model. If not found, will return null.
     */
    public function getModelById($modelId)
    {
        //yet loaded?
        if (isset($this->modelReferences[$modelId])) {
            return $this->modelReferences[$modelId];
        }
        //else...load xml
        $file = $this->getModelXmlUrl($modelId);
        if (!file_exists($file)) {
            return null;
        }
        $xml = new DOMDocument();
        $xml->load($file);
        $item = self::fromXml($xml);
        $this->modelReferences[$modelId] = $item;
        return $item;
    }

    /**
     * Return a model from a DOMDocument xml object
     * @param DOMDocument $xml
     * @return ModelXml The model object. In fact it will be a typed model according to xml type value, not a generic ModelXML object.
     */
    private static function fromXml($xml)
    {
        $type = XmlUtils::getFirst($xml, "type")->nodeValue;
        $id = XmlUtils::getFirst($xml, "id")->nodeValue;
        if (class_exists($type)) {
            /** @var $model ModelXml */
            $model = new $type("FROM_DB_HACK");
            $rc=new ReflectionClass($type);
            $_id=$rc->getProperty("id");
            $_id->setAccessible(true);
            $_id->setValue($model,$id);
            $_id->setAccessible(false);
            $model->xml=$xml;
            return $model;
        } else {
            return null;
        }
    }

}