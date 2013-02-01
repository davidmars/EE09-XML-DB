<?php

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
    public $xml;

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
    public function __construct($id,GinetteDb $db)
    {
        $this->db=$db;
        $rc = new ReflectionClass($this);
        $this->type = $rc->getName();
        traceCode("-----------------------------------------------construct a new " . $this->type."-----------");

        if($id!="FROM_DB_HACK"){
            //it is a real new model
            if($this->db->modelExists($id)){
                throw new Exception("You tried to create a new ".$this->type." with the id ".$id." but this id is already used by an other record.
            Try an other id or delete the $id record. Ciao!");
            }

            //okay...it is a real new model

            //set the id
            $this->id=$id;

            //set the xml from the structure
            $definition=$db->getModelDefinition($this->type);
            $this->xml = $definition->xml->cloneNode(true);
            /** @var $root DOMElement */
            $root=$this->xml->firstChild;
            $root->setAttribute("id",$this->id);
            $root->setAttribute("created",time());
            $root->setAttribute("updated",time());

            $this->parse();
            $this->save();


        }

        traceCode("-----------------------------------------------construct end " . $this->type."-----------");


    }

    /**
     * The magic getter should be called once. When this call occurs, the xml is parsed.
     * @param $field
     * @return mixed
     */
    public function __get($field)
    {
        trace("magic ____________________get");
        if (!$this->parsed) {
            $this->parse();
        }
        return $this->$field;
    }
    /**
     * @param $field
     * @return mixed
     */
    public function __set($field,$val)
    {
        if (!$this->parsed) {
            $this->parse();
        }
        $this->$field=$val;
    }

    protected function parse(){
        $this->parsed=true;
        $this->created=DateAndTime::fromString($this->xml->firstChild->getAttribute("created"));
        $this->updated=DateAndTime::fromString($this->xml->firstChild->getAttribute("updated"));
    }

}
