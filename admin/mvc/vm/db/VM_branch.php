<?php

class VM_branch
{
    /**
     * @var int
     */
    public $numberOfChildren;
    /**
     * @var GinetteBranch
     */
    public $branch;
    /**
     * @var bool
     */
    public $isOpen=false;
    /**
     * @var identifier of the branch
     */
    public $branchId;
    /**
     * @var GinetteRecord
     */
    public $record;

    public $isActive=false;

    /**
     * @param GinetteBranch $branch
     */
    public function __construct($branch){
        $this->branch=$branch;
        $this->branchId=$this->branch->localId();
        $this->record=$this->branch->model;

        if($this->record==VM_editModel::$current){
            $this->isActive=true;
        }
        if(VM_tree::$openedBranch && VM_tree::$openedBranch->isChildOf($this->branch)){
            $this->isOpen=true;
        }
        $this->numberOfChildren = $this->branch->countAllChildren();


    }
    /**
     * @return VM_branch[]
     */
    public function branches(){
        $ret=array();
        foreach($this->branch->branches as $br){
            $ret[]=new VM_branch($br);
        }
        return $ret;
    }


    /**
     * @return string The good attribute to use in template to display the branch opened or not
     */
    public function attrOpen(){
        if($this->isOpen){
            return " checked='checked' ";
        }else{
            return " ";
        }
    }

    /**
     * @return string "active" or " " according if the record is the currently edited record or not.
     */
    public function cssActive(){
        if($this->isActive){
            return " active ";
        }else{
            return " ";
        }
    }
}
