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
     * @var bool True if this branch is the root branch of the tree
     */
    public $isTrunk=false;


    /**
     * @return string An identifier of the branch in the tree.
     * This identifier in fact is the xpath identifier of the node.
     */
    public function localId(){
        $id=$this->xml->getAttribute("branchId");
        if(!$id){
            $id=$this->tree->db->settings->getUid();
            $this->xml->setAttribute("branchId",$id);
            $this->tree->save();
        }
        return $id;
    }

    /**
     * Returns true if this branch is a child of the specified branch
     * @param GinetteBranch $branch
     * @return bool True if this branch is a child of the specified branch
     */
    public function isChildOf($branch){
        if(preg_match("#^".preg_quote($branch->xml->getNodePath())."#",$this->xml->getNodePath())){
            return true;
        }else{
            return false;
        }
    }

    /**
     * return number of children recursively.
     */
    public function countAllChildren(){
        return $this->xml->getElementsByTagName("*")->length;
    }

    /**
     * return all children branches recursively
     * @return GinetteBranch[]
     */
    public function allChildren(){
        $ret=array();
        foreach( $this->xml->getElementsByTagName("*") as $node){
            $ret[]=toolsGinetteTree::fromNode($this->tree,$node);
        }
        return $ret;
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
