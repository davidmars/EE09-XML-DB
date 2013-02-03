<?php
/**
 * User: heeek
 * Date: 29/01/13
 * Time: 10:13
 * This Class is used in Views and is configured in controllers.
 * a list of models
 */
class VM_record_list extends ViewVariables
{
    /**
     * @param GinetteRecord[] $models
     */
    public function __construct($models){
        foreach($models as $record){
            $r=new VM_record($record);
            $this->list[]=$r;
            if($record==VM_editModel::$current){
                $r->active=true;
            }
        }
    }

    /**
     * @var VM_record[]
     */
    public $list=array();
}