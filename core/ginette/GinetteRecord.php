<?php
/**
 * A Record is for sure what you need at least...at least? NO.
 * At least you need something that is a record but extended.
 *
 */
class GinetteRecord extends GinetteXml implements GinetteXml_interface
{
    /**
     * Return a GinetteFileImage field object that best represents the record.
     * If the record has a FileImage field and this one is not null, it will be returned.
     * If not, a default FileImage field will be returned
     * @throws Exception
     * @return GinetteFileImage The FileImage field
     */
    public function getThumbnail(){
        $definition = $this->db->getRecordDefinition($this->getType());
        if($definition->thumbnail){
            $fieldName=$definition->thumbnail->varName;
            $image=$this->$fieldName;
            if($image && get_class($image)=="GinetteFileImage"){
                return $image;
            }

        }

        $file=$this->db->paths->files."/"."GinetteDefault/default-empty-image.png";
        if(!file_exists($file) || !is_file($file)){
            throw new Exception($file. "doesn't exists");
        }
        $f= $this->db
            ->getFileInstance("GinetteDefault/default-empty-image.png");

        return $f;

    }



    /**
     *  Parse the xml to fill the model fields
     */
    protected function parse()
    {

        $definition = $this->db->getRecordDefinition($this->getType());

        //fields
        foreach ($definition->fields as $field) {
            /** @var string $fieldName name of the property */
            $fieldName = $field->varName;
            $fieldType = $field->type;


            /** @var DOMElement $node  The node in the record */
            $node = XmlUtils::getFirst($this->xml, $fieldName);
            if (!$node) {
                //well the original xml hasn't the node...probably because the model structure has been updated, so we add it.
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
                    //$val=new FileImage($node,$this);
                    $val=$this->db->getFileInstance($node->getAttribute("url"));
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
                            if($id && $this->db->recordExists($id)){
                                $val=$this->db->getRecordById($id);
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
                                if($id && $this->db->recordExists($id)){
                                    $val=$this->db->getRecordById($id);
                                    if($val->getType()==$field->arrayType){
                                        $valArray[]=$val;
                                    }
                                }
                            }
                        }
                        $this->$fieldName=$valArray;

                    }

            }



        }
        parent::parse();
        $this->parsed = true;
    }

    /**
     * record the model into the xml
     */
    public function save()
    {

        //update refresh
        $this->updated->setTimestamp(time());

        $definition = $this->db->getRecordDefinition($this->getType());

        //get a fresh new XML from the structure

        /** @var $saveXml DOMDocument */
        $saveXml = $definition->xml->cloneNode(true);
        /** @var DOMElement $root  */
        $root= $saveXml->firstChild;
        $root->setAttribute("id",$this->getId());
        $root->setAttribute("created",$this->created->getTimestamp());
        $root->setAttribute("updated",$this->updated->getTimestamp());

        foreach ($definition->fields as $field) {

            $fieldName = $field->varName;
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

                case "FileImage":
                    /** @var $obj GinetteFileImage */
                    $obj=$this->$fieldName;
                    $node->setAttribute("url",$obj->relativePath);
                    break;

                case "File":
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
                        /** @var GinetteRecord $m */
                        foreach($arr as $m){
                            if($m->getType()==$field->arrayType){
                                $newNode=$saveXml->createElement($m->getType());
                                $newNode->setAttribute("id",$m->getId());
                                $node->appendChild($newNode);
                            }
                        }

                    }else{
                        $node->nodeValue = $this->$fieldName;
                    }

            }
        }

        $saveXml->save($this->db->getRecordXmlUrl($this->getId()));
        $this->xml = $saveXml;
    }

    /**
     * delete the model from the database
     */
    public function delete(){

        $this->db->deleteRecord($this->getId());

        //remove all references to this one in others models

        //remove this one in indexes

        //---$Type index
        //---$All index

        //remove from cache

        //remove xml file

    }

    public function __toString(){
        return "Model type:".$this->getType()."; id:".$this->getId();
    }

}


