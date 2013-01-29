<?php
/**
 * Created by JetBrains PhpStorm.
 * User: heeek
 * Date: 29/01/13
 * Time: 06:52
 * To change this template use File | Settings | File Templates.
 */
class C_admin
{
    /**
     * @var ModelXmlDb $db
     */
    public $db;

    public function __construct(){
        //open the database
        $this->db=new ModelXmlDb("../myDatabase1");
        VM_admin::$db=$this->db;
    }
}
