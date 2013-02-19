<?php
/**
 * manage the Url for the admin filesystem (GinetteDir, GinetteFile, GinetteFileImage...)
 */
class C_files extends C_admin
{


    public function __construct()
    {
        parent::__construct();



        //the action
        $action = "";
        if (isset($_GET["action"])) {
            $action = $_GET["action"];
        }

        //the directory to browse
        $directory = "";
        if (isset($_GET["$directory"])) {
            $directory = $_GET["$directory"];
        }
        $dir=GinetteDir::get($directory);

        switch ($action) {
            case "dirContent":
                $this->dirContent($_GET["folderPath"]);
                break;

            case "popIn":
                $v=new View("filesManagerPopIn",new VM_files_manager($dir));
                echo $v->render();
                break;

            default:
                $v=new View("filesManager",new VM_files_manager($dir));
                echo $v->render();
        }




    }

    /**
     *
     * @param $folderPath
     */
    public function dirContent($folderPath){
        /** @var $dir GinetteDir */
        $dir=$this->db->getFileInstance(urldecode($folderPath));
        $v=new View("files/dir-content",new VM_file_dir($dir));
        header('Content-Type: text/html; charset=utf-8');
        echo $v->render();
    }

    /**
     * @param $folderPath
     * @return string The url to display ajax content of a directory
     */
    public static function urlDirContent($folderPath){
        $url=self::$baseUrl."?p=files&action=dirContent&folderPath=$folderPath";
        return $url;
    }

    /**
     * @return string The files home url
     */
    public static function urlHome(){
        $url=self::$baseUrl."?p=files&action=home";
        return $url;
    }



}


