<?php

class ModelXml extends GinetteXml implements GinetteXml_interface
{


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
     *  Parse the xml to fill the model fields
     */
    protected function parse()
    {
        parent::parse();
        traceCode("----parse model-----" . $this->getId());
        $definition = $this->db->getModelDefinition($this->type);

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

    public function __toString(){
        return "Model type:".$this->getType()."; id:".$this->getId();
    }

}


