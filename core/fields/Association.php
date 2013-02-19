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
     * @var GinetteRecord[] List of children models
     */
    public $children=array();

    public function __construct($node,$model){
        parent::__construct($node,$model);

        /** @var DOMElement $n */
        for ($i=0;$i<$this->node->childNodes->length;$i++) {
            $n=$this->node->childNodes->item($i);
            $type = $n->nodeName;
            if (class_exists($type)) {
                $m = $this->model->db->getRecordById($n->getAttribute("id"));
                $this->children[] = $m;
            }
        }
    }
    public function getNode(){
        XmlUtils::emptyNode($this->node);
        /** @var GinetteRecord $m */
        foreach ($this->children as $m) {
            $modelNode=new DOMElement($m->getType());
            $this->node->appendChild($this->node->ownerDocument->importNode($modelNode));
        }
        return parent::getNode();
    }
}
