<?php

class C_home extends C_admin
{
    public function __construct(){
        parent::__construct();

        $v=new View("mvc/v/home",new VM_home());
        echo $v->render();
    }



    public static function urlHome($id){
        return "?p=home";
    }
}
  