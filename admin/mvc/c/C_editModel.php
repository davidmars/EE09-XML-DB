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
    public function __construct(){
        parent::__construct();

        $id="new";
        if(isset($_REQUEST["id"])){
            $id=$_REQUEST["id"];
        }
        $model=$this->db->getModelById($id);

        $v=new View("mvc/v/editModel",new VM_editModel($model));
        echo $v->render();
    }



    public static function urlEdit($id){
        return "?p=editModel&id=$id";
    }
}

