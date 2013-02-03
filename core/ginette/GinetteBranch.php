<?php
/**
 * A branch is part of a tree.
 * A branch has one record.
 * A tree has many branches.
 * A branch has many branches too.
 *
 * @property GinetteBranchArray|GinetteBranch[] $branches
 * @property GinetteBranch $parent
 */
class GinetteBranch{

    public function __construct(GinetteTree $tree){
        $this->tree=$tree;
    }

    /**
     * The getter manage lazy loading to prevent recursive generation of trees.
     * @param $var
     * @throws Exception If you try to set an undefined property
     * @return
     */
    public function __get($var){
        switch ($var){
            case "branches":
                $this->branches=new GinetteBranchArray($this->tree,$this->xml);
                break;
            case "parent":
                $this->parent=toolsGinetteTree::fromNode($this->tree,$this->xml->parentNode);
                break;
            default;
                throw new Exception("Ginette says :
                                 Qu'est ce que tu branles?
                                 There is no $var property in a GinetteTree! ");
        }
        return $this->$var;
    }
    /**
     * @var GinetteTree
     */
    public $tree;
    /**
     * @var GinetteBranch[]
     */
    //public $branches;
    /**
     * @var GinetteRecord
     */
    public $model;

    /**
     * @var DOMElement The xml node related to this branch
     */
    public $xml;

    public function __toString(){
        return "GinetteBranch Record : ".$this->model->getType()." / ".$this->model->getId()."; ".count($this->branches)." branche(s)";
    }
}
