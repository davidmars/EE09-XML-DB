<?php

class VM_records_manager extends VM_admin
{
    /**
     * @var VM_records_selection[] List all the records type
     */
    public $recordsFamilies=array();

    public function __construct($recordType=null){
        foreach(self::$db->definitions as $definition){
            $this->recordsFamilies[]=new VM_records_selection($definition->type);
        }
    }

}