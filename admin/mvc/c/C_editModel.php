<?php
/**
 * Created by JetBrains PhpStorm.
 * User: heeek
 * Date: 29/01/13
 * Time: 06:46
 * To change this template use File | Settings | File Templates.
 */
class C_editModel extends C_admin
{
    /**
     * @var GinetteRecord
     */
    public $model;

    public function __construct()
    {
        parent::__construct();

        //the model
        $id = "new";
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
        }
        $this->model = $this->db->getModelById($id);

        //the opened branch
        if(isset($_GET["tree"])){
            $tree=$this->db->getTreeById($_GET["tree"]);
            if($tree && $_GET["branch"]){
                $branch=$tree->getBranchById($_GET["branch"]);
                if($branch){
                    VM_tree::$openedBranch=$branch;
                }
            }

        }


        //the action
        $action = "edit";
        if (isset($_GET["action"])) {
            $action = $_GET["action"];
        }

        switch ($action) {
            case "edit";
                return $this->edit();
                break;

            case "save";
                return $this->save();
                break;
        }




    }

    private function edit()
    {
        $vv=new VM_editModel($this->model);
        $v = new View("editModel", $vv);
        echo $v->render();
    }

    private function save()
    {

        $structure=$this->db->getModelDefinition($this->model->getType());
        foreach($structure->fields as $field){
            $var=$field->varName;
            if(isset($_POST[$var])){
                $obj=$this->model->$var;
                switch ($field->type){
                    case "DateTime":
                        /** @var $obj DateTime */
                        $obj->setTimestamp(strtotime($_POST[$var]));
                        break;
                    case "File":
                    case "FileImage":
                        /** @var $obj File */
                        $obj->setUrl($_POST[$var]);
                        break;
                    default:
                        $this->model->$var=$_POST[$var];
                }

            }
        }
        $this->model->save();
        $v = new View("editModel", new VM_editModel($this->model));
        echo $v->render();
    }

    /**
     * @param string $id The record to edit
     * @param GinetteBranch $branch
     * @return string
     */
    public static function urlEdit($id,$branch=null)
    {
        $url="?p=editModel&id=$id";
        if($branch){
            $url.="&tree=".$branch->tree->getId();
            $url.="&branch=".$branch->localId();
        }
        return $url;
    }

    public static function urlSave($id)
    {
        return "?p=editModel&action=save&id=$id";
    }
}


