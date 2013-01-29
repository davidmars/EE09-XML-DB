<?php
/**
 * User: heeek
 * Date: 29/01/13
 * Time: 11:28
 * This Class is used in Views and is configured in controllers.
 * Something to diplay in a form
 */
class VM_form_element extends ViewVariables
{
    /**
     * @var string
     */
    public $cssSpan;
    /**
     * @var string
     */
    public $title;
    /**
     * @var mixed|string
     */
    public $value;
    /**
     * @var string
     */
    public $template="mvc/v/form/default";
    /**
     * @var bool
     */
    public $editable=true;
    /**
     * @var M_field
     */
    public $field;

    /**
     * @return string attribute to use on the input to know if it has to be disabled or not.
     */
    public function attrDisabled(){
        if(!$this->editable){
            return " disabled ";
        }else{
            return "";
        }
    }

    /**
     * @param M_field $field
     * @param ModelXml $model
     */
    public function __construct($field,$model){
        $this->field=$field;
        $this->cssSpan="span4";
        $this->title=$field->varName;
        $this->value="not defined";
        $this->varName=$varName=$field->varName;
        $this->description=$field->description;
        $this->editable=$field->editable;


        switch($field->type){
            case "String":
                $this->value=$model->$varName;
                switch($field->editor){
                    case "text-area":
                        $this->cssSpan="span8";
                        $this->template="mvc/v/form/input-textarea";
                        break;

                    default:
                        $this->template="mvc/v/form/input-text";
                        break;
                }
                break;

            case "FileImage":
                $this->value=$model->$varName;
                $this->template="mvc/v/form/input-image";
                break;

            case "File":
                $this->value=$model->$varName;
                $this->template="mvc/v/form/input-file";
                break;

            case "DateTime":
                $this->value=$model->$varName;
                $this->template="mvc/v/form/input-date";

            default;

                if($field->isAModelReference){
                    $this->value=$model->$varName;
                    $this->template="mvc/v/form/assoc/model-assoc";
                }
                //$this->

        }
    }
}