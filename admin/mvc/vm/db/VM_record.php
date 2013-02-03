<?php
/**
 * User: heeek
 * Date: 29/01/13
 * Time: 10:15
 * This Class is used in Views and is configured in controllers.
 * a record in the database
 */
class VM_record extends ViewVariables
{
    /**
     * @var GinetteRecord
     */
    public $model;

    public $active=false;

    public $hrefEdit;

    /**
     * @param GinetteRecord $model
     */
    public function __construct($model){
        $this->model=$model;
        $this->hrefEdit=C_editModel::urlEdit($model->getId());
    }

    /**
     * @return string
     */
    public function cssActive(){
        if($this->active){
            return " active ";
        }else{
            return " ";
        }
    }
}