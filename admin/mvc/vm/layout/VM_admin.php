<?php
/**
 * User: heeek
 * Date: 29/01/13
 * Time: 09:15
 * This Class is used in Views and is configured in controllers.
 * Common stuff to use admin is here
 */
class VM_admin extends ViewVariables
{
    /**
     * @var ModelXmlDb
     */
    public static $db;

    /**
     * @return VM_layout
     */
    public function getLayout(){
        return new VM_layout();
    }
}