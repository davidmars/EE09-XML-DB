<?php

class M_field
{
    /**
     * @var string The variable name
     */
    public $varName;
    /**
     * @var string Define the type of field
     */
    public $type;
    /**
     * @var mixed The default value of this field
     */
    public $defaultValue;
    /**
     * @var DOMElement The xml node
     */
    public $node;
    /**
     * @var string The field description
     */
    public $description;
    /**
     * @var bool Will be true if the field is a reference to a model
     */
    public $isAModelReference=false;
    /**
     * @var bool Will be true if the field is an array of a certain model
     */
    public $isArray=false;
    /**
     * @var string Determine the type of child in the array
     */
    public $arrayType="";
    /**
     * @var bool Is the field editable or not
     */
    public $editable=true;
    /**
     * @var string It is for the admin UI. It identify what kind of editor to use with the field (input text, textarea, uploader etc...)
     */
    public $editor="";

}