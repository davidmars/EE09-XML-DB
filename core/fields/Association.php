<?php
/**
 * Created by JetBrains PhpStorm.
 * User: juliette
 * Date: 28/01/13
 * Time: 06:45
 * To change this template use File | Settings | File Templates.
 */
class Association extends NodeField
{
    /**
     * @var ModelXml[] List of children models
     */
    public $children=array();

    public function __construct($node){
        parent::__construct($node);

        /** @var DOMElement $n */
        for ($i=0;$i<$this->node->childNodes->length;$i++) {
            $n=$this->node->childNodes->item($i);
            $type = $n->nodeName;
            if (class_exists($type)) {
                traceCode("association to : " . $type);
                $m = ModelXmlDb::$current->getModelById($n->getAttribute("id"));
                $this->children[] = $m;
            }
        }
    }
    public function getNode(){
        XmlUtils::emptyNode($this->node);
        /** @var ModelXml $m */
        foreach ($this->children as $m) {
            $modelNode=new DOMElement($m->getType());
            $this->node->appendChild($this->node->ownerDocument->importNode($modelNode));
        }
        return parent::getNode();
    }
}
