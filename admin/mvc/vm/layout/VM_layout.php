<?php
/**
 * User: heeek
 * Date: 29/01/13
 * Time: 09:37
 * This Class is used in Views and is configured in controllers.
 * A page layout in the admin
 */
class VM_layout extends VM_admin
{
    /**
     * Return the list to display in the browser menu.
     * @return VM_record_list
     */
    public function getModelList(){
        $arr=self::$db->getModelList();
        return new VM_record_list($arr);
    }

    /**
     * @param string $treeId
     * @return VM_tree
     */
    public function getTree($treeId="main"){
        $tree=self::$db->getTreeById($treeId);
        return new VM_tree($tree);
    }
}