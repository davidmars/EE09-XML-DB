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
     * @var ModelXml
     */
    public $model;

    public function __construct()
    {
        parent::__construct();

        $id = "new";
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
        }
        $this->model = $this->db->getModelById($id);

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
        $v = new View("editModel", new VM_editModel($this->model));
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


    public static function urlEdit($id)
    {
        return "?p=editModel&id=$id";
    }

    public static function urlSave($id)
    {
        return "?p=editModel&action=save&id=$id";
    }
}


