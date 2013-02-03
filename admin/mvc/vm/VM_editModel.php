<?php
/**
 * User: heeek
 * Date: 29/01/13
 * Time: 09:11
 * This Class is used in Views and is configured in controllers.
 * The view Model to edit a model
 */
class VM_editModel extends VM_admin
{
    /**
     * @var GinetteRecord The currently edited record
     */
    public static $current;
    /**
     * @var GinetteRecord
     */
    public $model;
    /**
     * @var M_fieldManager
     */
    public $definition;
    /**
     * @param GinetteRecord $model
     */
    public function __construct($model){
        self::$current=$model;
        $this->model=$model;
        $this->definition=VM_admin::$db->getModelDefinition($this->model->getType());
        $this->formElements=array();
        foreach($this->definition->fields as $field){
            $el=new VM_form_element($field,$this->model);
            $this->formElements[]=$el;
        }
    }

    /**
     * @var VM_form_element[] List of element to create the form.
     */
    public $formElements;
}