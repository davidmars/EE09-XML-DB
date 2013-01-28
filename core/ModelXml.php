<?php

class ModelXml
{
    /**
     * The database...
     * @var ModelXmlDb
     */
    public static $db;

    /**
     * The xml related to this post
     * @var DOMDocument
     */
    public $xml;


    /**
     * @var string the identifier of this model
     */
    protected  $id;

    /**
     * @var string What kind of model?
     */
    protected $type;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

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
    public $created;

    /**
     * @var DateTime
     */
    public $updated;

    /**
     * @var bool When it is set to false, it means that the values have not been set.
     */
    public $parsed = false;

    /**
     * @param string $id The id of the record. If you create a new model from scratch this id has to be unused.
     *
     */
    public function __construct($id)
    {
        $rc = new ReflectionClass($this);
        $this->type = $rc->getName();
        traceCode("-----------------------------------------------construct a new " . $this->type."-----------");

        if($id!="FROM_DB_HACK"){
           //it is a real new model
          if(self::$db->modelExists($id)){
            throw new Exception("You tried to create a new ".$this->type." with the id ".$id." but this id is already used by an other record.
            Try an other id or delete the $id record. Ciao!");
          }

          //okay...it is a real new model
          $this->id=$id;
          $this->parse();
          $this->save();
        }

        traceCode("-----------------------------------------------construct end " . $this->type."-----------");


    }

    /**
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
     *  Parse the xml to fill the model fields
     */
    private function parse()
    {

        $definition = self::$db->getModelDefinition($this->type);
        if (!$this->xml) {
            //if no xml, clone the structure one
            $this->xml = $definition->xml->cloneNode(true);
            //id should not be overwritten
            XmlUtils::getFirst($this->xml,"id")->nodeValue=$this->id;
        }

        traceCode("----parse-----" . $this->getId());

        foreach ($definition->fields as $field) {

            traceCode("Parse and set set field " . $field->varName." (".$field->type.")");

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
                    $val=new File($node);
                    $this->$fieldName=$val;
                    break;
                case "FileImage":
                    $val=new FileImage($node);
                    $this->$fieldName=$val;
                    break;
                case "Association":
                    $val=new Association($node);
                    $this->$fieldName=$val;
                    break;
                default:
                    if($field->isAModelReference){
                       //relation to ONE model
                       $this->$fieldName=null;
                       for($i=0;$i<$node->childNodes->length;$i++){
                           $n=$node->childNodes->item($i);
                           if($n->nodeType==1){
                            $id=$n->getAttribute("id");
                            if($id && self::$db->modelExists($id)){
                                $val=self::$db->getModelById($id);
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
                                if($id && self::$db->modelExists($id)){
                                    $val=self::$db->getModelById($id);
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

        $definition = self::$db->getModelDefinition($this->type);

        //get a fresh new XML from the structure

        /** @var $saveXml DOMDocument */
        $saveXml = $definition->xml->cloneNode(true);

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

        $saveXml->save(self::$db->getModelXmlUrl($this->id));
        $this->xml = $saveXml;
    }

    /**
     * delete the model from the database
     */
    public function delete(){

        self::$db->deleteModel($this->id);

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


