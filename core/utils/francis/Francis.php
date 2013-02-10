<?php
/**
 * User: juliette david
 * Date: 07/02/13
 * Time: 07:17
 * To change this template use File | Settings | File Templates.
 */
class Francis
{
    public $path;

    public function __construct($path){
        $this->path=$path;
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
     * Returns the extension or false if not possible (eg a directory)
     * @return string|bool
     */
    public function extension(){
        if(isset($this->pathInfo['extension'])){
            return $this->pathInfo['extension'];
        }else{
            return false;
        }

    }

    /**
     * @return string The file name without extension
     */
    public function fileName(){
        return $this->pathInfo['filename'];
    }

    public function isDir(){
        return is_dir($this->path);
    }
    public function isFile(){
        return is_file($this->path);
    }
    public function exists(){
        return file_exists($this->path);
    }
    public function size(){
        return filesize($this->path);
    }


}
