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
        $this->type=XmlUtils::getFirst($this->xml,"type")->nodeValue;

        for ($i = 0; $i < $xml->firstChild->childNodes->length; $i++) {

            $modelNode = $xml->firstChild->childNodes->item($i);
            if ($modelNode->nodeType != XML_TEXT_NODE) {
                $this->fields[] = $this->nodeToField($modelNode);
            }
        }
    }

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

        if(class_exists($f->type) && in_array("ModelXml",class_parents($f->type))){
            //a single model reference
            $f->isAModelReference=true;
        }else if(preg_match("#^(.*)".preg_quote("[]")."$#",$f->type,$extr)){
            //a multiple model reference (typed array)
            $arrayType=$extr[1];
            if(class_exists($arrayType) && in_array("ModelXml",class_parents($arrayType))){
                $f->isArray=true;
                $f->arrayType=$arrayType;
            }
        }
        return $f;


    }


}

class M_field
{
    /**
     * @var string The variable name
     */
    public $varName;
    /**
     * @var string Define the type of field
     */
    public $type;
    /**
     * @var The default value of this field
     */
    public $defaultValue;
    /**
     * @var DOMElement The xml node
     */
    public $node;
    /**
     * @var string The field description
     */
    public $description;
    /**
     * @var bool Will be true if the field is a reference to a model
     */
    public $isAModelReference=false;
    /**
     * @var bool Will be true if the field is an array of a certain model
     */
    public $isArray=false;
    /**
     * @var string Determine the type of child in the array
     */
    public $arrayType="";
}


