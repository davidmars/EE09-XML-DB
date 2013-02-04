<?php
/**
 * @property DOMDocument $xml The xml storage related to this record
 * @property DateTime $created The creation date of this record.
 * @property DateTime $updated^The last modification date of this record
 */
class GinetteXml
{
    /**
     * The database where is recorded this object
     * @var GinetteDb
     */
    public $db;

    /**
     * The xml storage related to this record
     * @var DOMDocument
     */
    //public $xml;

    /**
     * @var string The identifier of this model, it is also the xml storage file name
     */
    protected  $id;
    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @var string What kind of model?
     */
    protected $type;
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @var DateTime
     */
    //protected $created;

    /**
     * @var DateTime
     */
    //protected $updated;

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @var bool When it is set to false, it means that the values have not been set.
     */
    public $parsed = false;

    /**
     * @param string $id The id of the record. If you create a new model from scratch this id has to be unused.
     * @param $db GinetteDb
     * @throws Exception
     */
    public function __construct($id,GinetteDb $db=null)
    {



        //set the db
        if($db===null){
           $db=GinetteDb::current();
        }
        $this->db=$db;

        //set the type
        $this->type=get_class($this);
        //set the id
        $this->id=$id;

        //$back=debug_backtrace();
        //traceCode("__construct $id $type ".$back[0]["file"]." line ".$back[0]["line"]);

    }

    /**
     * The magic getter should be called once. When this call occurs, the xml is parsed.
     * @param $field
     * @throws Exception
     * @return mixed
     */
    public function __get($field)
    {
        traceCode("__get $field in ".$this->id);
        if($field=="xml"){
            $this->xml=$this->db->loadRecordXml($this->id);
        }else if(!$this->parsed) {
            $this->parse();
        }else{
            throw new Exception("Ginette say :
            Allons bon...
            There is no field $field in a ".$this->type);
        }
        return $this->$field;
    }

    /**
     * @param $field
     * @param $val
     * @throws Exception
     * @return mixed
     */
    public function __set($field,$val)
    {
        if($field=="xml"){
            $this->xml=$val;
        }else if (!$this->parsed) {
            $this->parse();
            //$this->$field=$val;
        }else{
            throw new Exception("Ginette say : Mais nooon...
            You tried to set an undefined field.
            There is no field $field in a ".$this->type);
        }

    }

    protected function parse(){

        /** @var DOMElement $root  */
        $root=$this->xml->firstChild;
        $this->created=DateAndTime::fromString($root->getAttribute("created"));
        $this->updated=DateAndTime::fromString($root->getAttribute("updated"));
        $this->parsed=true;
    }

}
