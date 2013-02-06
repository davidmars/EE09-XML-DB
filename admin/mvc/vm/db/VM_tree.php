<?php
/**
 * User: juliette david
 * Date: 05/02/13
 * Time: 08:31
 * To change this template use File | Settings | File Templates.
 */
class VM_tree extends ViewVariables
{
    /**
     * @var GinetteTree
     */
    public $tree;

    /**
     * @param $tree GinetteTree
     */
    public function __construct($tree){
        $this->tree = $tree;
    }

    /**
     * @return VM_branch[]
     */
    public function branches(){
        $ret=array();
        foreach($this->tree->branches as $br){
            $ret[]=new VM_branch($br);
        }
        return $ret;
    }

    /**
     * @var GinetteBranch The currently branch that is open
     */
    public static $openedBranch;

}
