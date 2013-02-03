<?php
/**
 * A field manager represents a fields set.
 * Its job is not to stock data but just to describe the fields.
 */
class M_fieldManager
{
    /**
     * @param DOMDocument $xml
     */
    public function __construct($xml)
    {
        $this->xml = $xml;

        $this->description=$this->xml->documentElement->getAttribute("description");
        $this->type=$this->xml->documentElement->nodeName;

        for ($i = 0; $i < $xml->firstChild->childNodes->length; $i++) {
            /** @var DOMElement $modelNode  */
            $modelNode = $xml->firstChild->childNodes->item($i);
            if ($modelNode->nodeType != XML_TEXT_NODE) {
                $this->fields[] = $this->nodeToField($modelNode);
            }
        }
    }

    /**
     * @var M_field The field to use to display a thumbnail
     */
    public $thumbnail;
    /**
     * @var string The model name
     */
    public $type;
    /**
     * @var string the model description
     */
    public $description;
    /**
     * @var DOMDocument The original xml structure
     */
    public $xml;

    /**
     * @var M_field[] The field list
     */
    public $fields;

    /**
     * From a node, return a field
     * @param DOMElement $node
     * @return \M_field
     */
    private function nodeToField($node)
    {
        $type = $node->getAttribute("type");
        if (!$type) {
            $type = "String";
        }
        $f = new M_field();
        $f->node = $node;
        $f->defaultValue = $node->nodeValue;
        $f->description=$node->getAttribute("description");
        $f->varName = $node->nodeName;
        $f->type = $type;

        //define the thumbnail field
        if(!$this->thumbnail && $type=="FileImage"){
            $this->thumbnail=$f;
        }
        if($node->getAttribute("editable")=="false"){
            $f->editable=false;
        }
        $f->editor=$node->getAttribute("editor");

        if(class_exists($f->type) && in_array("GinetteRecord",class_parents($f->type))){
            //a single model reference
            $f->isAModelReference=true;
        }else if(preg_match("#^(.*)".preg_quote("[]")."$#",$f->type,$extr)){
            //a multiple model reference (typed array)
            $arrayType=$extr[1];
            if(class_exists($arrayType) && in_array("GinetteRecord",class_parents($arrayType))){
                $f->isArray=true;
                $f->arrayType=$arrayType;
            }
        }
        return $f;


    }


}





