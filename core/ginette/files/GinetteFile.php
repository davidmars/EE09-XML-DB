<?php
/**
 *
 */
class GinetteFile extends GinetteFileSystemEntry
{
    /**
     * @return GinetteFileMeta Here you can get extra information for the file
     */
    public function meta(){
        $id=str_replace("/","-",$this->relativePath);
        $m=$this->db->getRecordInstance($id,"GinetteFileMeta",true);
        return $m;
    }

    public function __toString(){
        return "File ".parent::__toString();
    }
}
