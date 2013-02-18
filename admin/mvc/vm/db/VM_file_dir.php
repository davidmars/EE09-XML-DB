<?php
/**
 * Created by JetBrains PhpStorm.
 * User: heeek
 * Date: 11/02/13
 * Time: 11:24
 * To change this template use File | Settings | File Templates.
 */
class VM_file_dir
{
    /**
     * @var GinetteDir
     */
    public $dir;

    /**
     * @param GinetteDir $dir
     */
    public function __construct(GinetteDir $dir){
        $this->dir=$dir;
    }

    /**
     * @return VM_file_dir[]
     */
    public function childrenDir(){
        $arr=array();
        foreach($this->dir->childrenDir() as $dir){
            $arr[]=new VM_file_dir($dir);

        }
        return $arr;
    }
}
