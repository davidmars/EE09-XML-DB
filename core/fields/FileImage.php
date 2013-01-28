<?php
/**
 * Created by JetBrains PhpStorm.
 * User: juliette
 * Date: 28/01/13
 * Time: 05:13
 * To change this template use File | Settings | File Templates.
 */
class FileImage extends File
{
    /**
     * @var int The image width in pixel
     */
    private $width=0;

    /**
     * @return int The image height in pixel
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return int The image width in pixel
     */
    public function getWidth()
    {
        return $this->width;
    }
    /**
     * @var int The image height in pixel
     */
    private $height=0;
    /**
     * @param $node DOMElement
     */
    public function __construct($node){
        parent::__construct($node);
        $this->height=$this->node->getAttribute("height");
        $this->width=$this->node->getAttribute("width");
    }
    public function getNode(){
        $this->node->setAttribute("width",$this->width);
        $this->node->setAttribute("height",$this->height);
        return parent::getNode();
    }
}
