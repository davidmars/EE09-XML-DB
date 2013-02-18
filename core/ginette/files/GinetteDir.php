<?php
/**
 *
 */
class GinetteDir extends GinetteFileSystemEntry
{
    /**
     * @return GinetteDirMeta Here you can get extra information for the folder
     */
    public function meta(){
        $id=str_replace("/","-",$this->relativePath);
        $m=$this->db->getRecordInstance($id,"GinetteDirMeta",true);
        return $m;
    }
    /**
     * @return array GinetteFileSystemEntry[] Returns all children elements of this folder (sub folders, files, images)
     */
    public function children(){

        $this->files=array();
        $this->filesExceptImages=array();
        $this->images=array();
        $this->dirs=array();

        foreach(glob($this->path."/*") as $entry){
            if($entry != "." && $entry != ".." && file_exists($entry)){
                $instance=$this->db->getFileInstance($entry);
                switch (get_class($instance)){
                    case "GinetteDir":
                        $this->dirs[]=$instance;
                        break;
                    case "GinetteFileImage":

                        $this->images[]=$instance;
                        $this->files[]=$instance;
                        break;
                    case "GinetteFile":
                        $this->files[]=$instance;
                        $this->filesExceptImages[]=$instance;
                        break;
                }
            }
        }
        return array_merge($this->dirs,$this->files);
    }

    /**
     * Returns all files in this directory (images too)
     * @return GinetteFile[] Returns all sub files
     */
    public function childrenFiles(){
        if(!$this->files){
            $this->children();
        }
        return $this->files;
    }

    /**
     * Returns all files in this directory, except the images
     * @return GinetteFile[]
     */
    public function childrenFilesExceptImages(){
        if(!$this->filesExceptImages){
            $this->children();
        }
        return $this->filesExceptImages;
    }

    /**
     * Return all Images files in this directory
     * @param bool $recursive If set to true will return all children recursively
     * @return GinetteFileImage[] Returns all images files
     */
    public function childrenImages($recursive=false){

        if(!$this->images){
            $this->children();
        }

        if($recursive){
            $arr=$this->images;
            foreach($this->childrenDir() as $dir){
                $arr=array_merge($arr,$dir->childrenImages(true));
            }
            return $arr;
        }

        return $this->images;
    }
    /**
     * Return all sub directories of this directory
     * @return GinetteDir[]
     */
    public function childrenDir(){
        if(!$this->dirs){
            $this->children();
        }
        return $this->dirs;
    }

    /**
     * @var GinetteFile[]
     */
    private $files;
    /**
     * @var GinetteFile[]
     */
    private $filesExceptImages;
    /**
     * @var GinetteFileImage[]
     */
    private $images;
    /**
     * @var GinetteDir[]
     */
    private $dirs;

    public function __toString(){
        return "Directory ".parent::__toString();
    }
}
