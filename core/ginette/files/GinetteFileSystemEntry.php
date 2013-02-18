<?php
/**
 *
 */
class GinetteFileSystemEntry extends Francis
{


    /**
     * @param string $path The path relative to your main php file.
     * @param GinetteDb $db
     */
    public function __construct($path,$db){

        parent::__construct($this->validName($path));
        //removes the database php path
        $this->relativePath=str_replace($db->paths->files."/","",$path);
        //$this->relativePath=utf8_encode($this->relativePath);
        $this->db=$db;
    }

    /**
     * Checks if $path contains broken utf8 characters. So if it the the case the file will be safely renamed.
     * @param string $path The file path
     * @return string The file path (maybe the same, maybe not if the file name has changed)
     */
    private function validName($path){
        $f=new Francis($path);
        $str=iconv("UTF-8", "UTF-8//IGNORE", $f->fileName() );
        if($f->fileName()!=$str){
            $newName=$f->dirName()."/".$str.".".$f->extension();
            rename($path,$newName);
            return $newName;
        }else{
            return $path;
        }
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

    /**
     * Returns the parent GinetteDir relative to this object in the filesystem
     * @return bool|GinetteDir the parent GinetteDir relative to this object in the filesystem
     */
    public function parent(){
        return $this->db->getFileInstance($this->dirName());
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

    public function __toString(){
        return $this->relativePath;
    }


}
