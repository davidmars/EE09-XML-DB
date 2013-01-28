<?php
/**
 * A file field is a reference to a real file.
 */
class File extends NodeField
{
    /**
     * @var string The file Url
     */
    protected $url;
    /**
     * @var int The file size
     */
    protected $fileSize;
    /**
     * @var string The file mime
     */
    protected $mime;
    /**
     * @param string $url Use this function to set the file.
     */
    public function setUrl($url)
    {
        $this->url = $url;
        $r=file_exists($this->url);
        if($r){
            $this->fileSize=filesize($this->url);
        }
    }
    /**
     * @return string The url of the file
     */
    public function getUrl()
    {
        return $this->url;
    }
    /**
     * @return int The file size of the file. This value is set when you set the file url.
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * @return string The mime of the file. This value is set when you set the file url.
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * @return bool True if the file exists, false if not...
     */
    public function exists(){
        $r=file_exists($this->url);
        return $r;
    }

    public function __toString(){
        return $this->url;
    }

    /**
     * @param $node DOMElement
     */
    public function __construct($node){
        parent::__construct($node);
        $this->fileSize=$this->node->getAttribute("fileSize");
        $this->url=$this->node->getAttribute("url");
        $this->mime=$this->node->getAttribute("mime");
    }
    public function getNode(){
        $this->node->setAttribute("url",$this->url);
        $this->node->setAttribute("mime",$this->mime);
        $this->node->setAttribute("fileSize",$this->fileSize);
        return parent::getNode();
    }



}

