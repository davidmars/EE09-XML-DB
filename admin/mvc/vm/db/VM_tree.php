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
        $this->trunk=new VM_branch($this->tree->trunk);
    }

    /**
     * @var VM_branch The main branch of the tree
     */
    public $trunk;



    /**
     * @var GinetteBranch The currently branch that is open
     */
    public static $openedBranch;

}
