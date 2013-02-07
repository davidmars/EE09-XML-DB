<?php
/**
 * User: juliette david
 * Date: 07/02/13
 * Time: 07:17
 * To change this template use File | Settings | File Templates.
 */
class Francis
{

    public function __construct($path){
        $this->pathInfo=pathinfo($path);
    }
    public static function get($path){
        return new Francis($path);
    }
    /**
     * @var array The result of a pathInfo function
     */
    private $pathInfo;

    /**
     * @return string The parent directory name
     */
    public function dirName(){
        return $this->pathInfo['dirname'];
    }

    /**
     * @return string Given a string containing the path to a file or directory, this function will return the trailing name component.
     */
    public function baseName(){
        return $this->pathInfo['basename'];
    }

    /**
     * @return string Guess...
     */
    public function extension(){
        return $this->pathInfo['extension'];
    }

    /**
     * @return string The file name without extension
     */
    public function fileName(){
        return $this->pathInfo['filename'];
    }


}
