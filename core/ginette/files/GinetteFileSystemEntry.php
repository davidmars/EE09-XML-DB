<?php

class GinetteFileSystemEntry extends Francis
{


    /**
     * @param string $path The path relative to your main php file.
     * @param GinetteDb $db
     */
    public function __construct($path,$db){
        parent::__construct($path);
        //removes the database php path
        $this->relativePath=str_replace($db->paths->files."/","",$path);
        $this->db=$db;
    }

    /**
     * @var string Relative path to the database file folder
     */
    public $relativePath;

    /**
     * @var GinetteDb
     */
    public $db;
    /**
     * @var string The path url relative to the database filesystem
     */
    public $pathLocal;


    public function __toString(){
        return $this->relativePath;
    }

    /**
     * Return a GinetteFileSystemEntry (GinetteFile or GinetteDir or GinetteFileImage in fact) from an url.
     *
     * @param string $url The url of the file (or directory) you need.
     * @param GinetteDb $database
     * @return bool|GinetteFileSystemEntry
     */
    public static function getByUrl($url,$database){
        $inst=$database->getFileInstance($database->paths->files."/".$url);
        return $inst;
    }


}
