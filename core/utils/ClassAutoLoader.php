<?php
/**
 * A class to autoload php class files
 */
class ClassAutoLoader
{
    public function __construct(){
        $this->init();
    }
    private function init(){
        spl_autoload_register(array($this, 'load'));
    }
    public function load($class_name) {
        $class_name = trim($class_name);
        foreach ($this->paths as $p){
            $f=$p."/".$class_name.".php";
            if(is_file($f) && file_exists($f)){
                require_once($f);
                return;
            }
        }
    }

    /**
     * @var array list of directories where to search for auto includes.
     */
    public $paths=array();

    /**
     * Add a directory where to search for auto-include php files
     * @param string $path A directory url
     * @param bool $recursive If set to true, will add all subdirectories.
     */
    public function addPath($path,$recursive=false){
        $this->paths[]=$path;
        if($recursive){
            $subs=$this->getRecursiveFolders($path);
            foreach($subs as $sub){
                $this->paths[]=$sub;
            }
        }
    }

    /**
     * @param $folder
     * @return array
     */
    private function getRecursiveFolders($folder){
        $folders=array();

        foreach(scandir($folder) as $entry){
            $abs=$folder."/".$entry;
            if($entry != "." && $entry != ".." && is_dir($abs)){
                $folders[]=$abs;
                $folders=array_merge($folders,$this->getRecursiveFolders($abs));
            }
        }
        return $folders;
    }

}



