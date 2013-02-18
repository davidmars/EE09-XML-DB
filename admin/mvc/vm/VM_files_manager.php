<?php

class VM_files_manager extends VM_admin
{
    /**
     * @var VM_file_dir
     */
    public $rootDir;

    public function __construct(){
        $rootDir=self::$db->fileRoot;
        $this->rootDir=new VM_file_dir($rootDir);
    }

}