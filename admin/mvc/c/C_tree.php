<?php
/**
 * Created by JetBrains PhpStorm.
 * User: heeek
 * Date: 29/01/13
 * Time: 06:46
 * To change this template use File | Settings | File Templates.
 */
class C_tree extends C_admin
{
    /**
     * @var GinetteTree
     */
    public $tree;

    public function __construct()
    {
        parent::__construct();

        //the tree
        $tree = "";
        if (isset($_GET["tree"])) {
            $tree = $_GET["tree"];
        }
        $this->tree = $this->db->getTreeById($tree);

        //the action
        $action = "";
        if (isset($_GET["action"])) {
            $action = $_GET["action"];
        }

        switch ($action) {
            case "moveBranch";
                return $this->moveBranch();
                break;

        }




    }

    private function moveBranch()
    {
        $branch=$this->tree->getBranchById($_GET["branchId"]);
        if(!$branch){
            die("pas de branch");
        }
        if($_GET["targetBranchId"]=="treeRoot"){
            $to=$this->tree;
        }else{
            $to=$this->tree->getBranchById($_GET["targetBranchId"]);
        }

        if(!$to){
            die("pas de branch container");
        }
        $position=$_GET["position"];
        $to->branches->offsetSet($position,$branch);
        $this->tree->save();
        $vv=new VM_branch($to);
        $v = new View("model-preview/branch", $vv);
        echo $v->render();

    /*
        p=moveBranch
        branchId
        targetBranchId
        position
        tree
     */
    }

}


