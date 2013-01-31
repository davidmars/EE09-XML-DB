<?php

class ModelXml
{
    /**
     * The database where is recorded this object
     * @var ModelXmlDb
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
    protected $created;

    /**
     * @var DateTime
     */
    protected $updated;

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
     * @param $db ModelXmlDb
     * @throws Exception
     */
    public function __construct($id,$db)
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
            $this->xml->firstChild->setAttribute("id",$this->id);
            $this->xml->firstChild->setAttribute("created",time());
            $this->xml->firstChild->setAttribute("updated",time());

            $this->parse();
            $this->save();


        }

        traceCode("-----------------------------------------------construct end " . $this->type."-----------");


    }

    /**
     * Return a FileImage field object that best represents the record.
     * If the record has a FileImage field and this one is not null, it will be returned.
     * If not, a default FileImage field will be returned
     * @return FileImage The FileImage field
     */
    public function getThumbnail(){
        $definition = $this->db->getModelDefinition($this->type);
        if($definition->thumbnail){
            $fieldName=$definition->thumbnail->varName;
            return $this->$fieldName;
        }else{
            $f= new FileImage(null,$this);
            $f->setUrl("config/default-thumbnail.jpg");
            return $f;
        }
    }

    /**
     * The magic getter should be called once. When this call occurs, the xml is parsed.
     * @param $field
     * @return mixed
     */
    public function __get($field)
    {
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

    /**
     *  Parse the xml to fill the model fields
     */
    private function parse()
    {

        $definition = $this->db->getModelDefinition($this->type);


        traceCode("----parse-----" . $this->getId());

        //meta
        $this->created=new DateTime("@".$this->xml->firstChild->getAttribute("created"));
        $this->updated=new DateTime("@".$this->xml->firstChild->getAttribute("updated"));

        //fields
        foreach ($definition->fields as $field) {

            traceCode("Parse and set field " . $field->varName." (".$field->type.")");

            /** @var string $fieldName name of the property */
            $fieldName = $field->varName;
            $fieldType = $field->type;


            /** @var DOMElement $node  The node in the record */
            $node = XmlUtils::getFirst($this->xml, $fieldName);
            if (!$node) {
                //well the original xml hasn't the node...probably because the model structure has been updated, so we add it.
                traceError("node <i>" . $fieldName . "</i> not found in original xml");
                $node = $field->node->cloneNode(true);
                $node = $this->xml->importNode($node);
                $this->xml->firstChild->appendChild($node);
            }
            switch ($fieldType) {
                case "String":
                    $this->$fieldName=$node->nodeValue;
                    break;

                case "DateTime":
                    $val="now";
                    if(is_numeric($node->nodeValue)){
                        $val="@".$node->nodeValue;
                    }
                    $this->$fieldName=new DateTime($val);
                    break;

                //NodeFields
                case "File":
                    $val=new File($node,$this);
                    $this->$fieldName=$val;
                    break;
                case "FileImage":
                    $val=new FileImage($node,$this);
                    $this->$fieldName=$val;
                    break;
                case "Association":
                    $val=new Association($node,$this);
                    $this->$fieldName=$val;
                    break;
                default:
                    if($field->isAModelReference){
                       //relation to ONE model
                       $this->$fieldName=null;
                       for($i=0;$i<$node->childNodes->length;$i++){
                           /** @var $n DOMElement */
                           $n=$node->childNodes->item($i);
                           if($n->nodeType==1){
                            $id=$n->getAttribute("id");
                            if($id && $this->db->modelExists($id)){
                                $val=$this->db->getModelById($id);
                                $this->$fieldName=$val;
                                break;
                            }
                           }
                       }
                    }elseif($field->isArray){
                        //relation to MANY models
                        $valArray=array();
                        for($i=0;$i<$node->childNodes->length;$i++){
                            $n=$node->childNodes->item($i);
                            if($n->nodeType==1){
                                $id=$n->getAttribute("id");
                                if($id && $this->db->modelExists($id)){
                                    $val=$this->db->getModelById($id);
                                    if($val->type==$field->arrayType){
                                        $valArray[]=$val;
                                    }
                                }
                            }
                        }
                        $this->$fieldName=$valArray;

                    }

            }



        }
        traceCode("----end parse-----" . $this->getId());
        $this->parsed = true;
    }

    /**
     * record the model into the xml
     */
    public function save()
    {
        traceCode("--------save " .$this->id."------------");

        //update refresh
        $this->updated->setTimestamp(time());


        $definition = $this->db->getModelDefinition($this->type);

        //get a fresh new XML from the structure

        /** @var $saveXml DOMDocument */
        $saveXml = $definition->xml->cloneNode(true);
        $saveXml->firstChild->setAttribute("id",$this->getId());
        $saveXml->firstChild->setAttribute("created",$this->created->getTimestamp());
        $saveXml->firstChild->setAttribute("updated",$this->updated->getTimestamp());

        foreach ($definition->fields as $field) {

            $fieldName = $field->varName;
            traceCode("save field " . $fieldName);
            $node = XmlUtils::getFirst($saveXml, $fieldName);
            switch ($field->type) {
                case "String":
                    XmlUtils::cdata($saveXml, $node, $this->$fieldName);
                    break;

                case "DateTime":
                    /** @var $obj DateTime */
                    $obj=$this->$fieldName;
                    $node->nodeValue = $obj->getTimestamp();
                    break;

                case "File":
                case "FileImage":
                case "Association":
                    /** @var $obj NodeField */
                    $obj=$this->$fieldName;
                    $newNode=$saveXml->importNode($obj->getNode());
                    $node->parentNode->replaceChild($newNode,$node);
                    break;

                default:
                    if($field->isAModelReference){

                        //relation to ONE model of a certain type
                        $newNode=$saveXml->createElement($field->type);
                        if($this->$fieldName){
                            $newNode->setAttribute("id",$this->$fieldName->id);
                        }
                        XmlUtils::emptyNode($node);
                        $node->appendChild($newNode);
                    }else if($field->isArray){
                        //relation to MANY models of a certain type
                        XmlUtils::emptyNode($node);
                        $arr=$this->$fieldName;
                        /** @var ModelXml $m */
                        foreach($arr as $m){
                            if($m->getType()==$field->arrayType){
                                $newNode=$saveXml->createElement($m->getType());
                                $newNode->setAttribute("id",$m->getId());
                                $node->appendChild($newNode);
                            }
                        }

                    }else{
                        traceError("unknow field type for " . $this->$fieldName);
                        $node->nodeValue = $this->$fieldName;
                    }

            }
        }

        $saveXml->save($this->db->getModelXmlUrl($this->id));
        $this->xml = $saveXml;
    }

    /**
     * delete the model from the database
     */
    public function delete(){

        $this->db->deleteModel($this->id);

        //remove all references to this one in others models

        //remove this one in indexes

        //---$Type index
        //---$All index

        //remove from cache

        //remove xml file

    }

    /**
     * @return DOMElement a reference node <ModelName id='model-id'></ModelName>
     */
    private  function getNodeAssoc()
    {
        $dom = new DOMDocument();
        $n = $dom->createElement($this->type);
        $n->setAttribute("id", $this->id);
        //$n->setAttributeNode($attrId);
        return $n;
    }

}


