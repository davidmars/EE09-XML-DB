<?php
/**
 * Created by JetBrains PhpStorm.
 * User: juliette
 * Date: 28/01/13
 * Time: 05:24
 * To change this template use File | Settings | File Templates.
 */
class NodeField
{
    /**
     * @var DOMElement
     */
    protected $node;

    /**
     * @param $node DOMElement
     */
    public function __construct($node){
         $this->node=$node;
    }

    /**
     * DOMElement
     */
    public function getNode(){
        return $this->node;
    }

}
