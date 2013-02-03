<?php
/**
 * Index records in a xml to retrieve it.
 */
class GinetteDbIndex
{
    /**
     * @param GinetteDb $db
     * @param string $indexesPath where are xml indexes files?
     */
    public function __construct($db){
        $this->db=$db;
        $this->allRecordsUrl=$this->db->paths->indexes."/allRecords.xml";
        $this->allRecords=XmlUtils::loadOrCreate($this->allRecordsUrl,"Records");
    }

    /**
     * Reset the all records index
     */
    public function indexAllRecords(){
        XmlUtils::emptyNode($this->allRecords->firstChild);
        foreach ($this->modelsFromFileSystem() as $m){
            $n=$this->allRecords->createElement($m->getType());
            $n->setAttribute("id",$m->getId());
            $this->allRecords->firstChild->appendChild($n);
        }
        XmlUtils::save($this->allRecords,$this->allRecordsUrl);
    }
    /**
     * Return list of models from the file system
     * @return GinetteRecord[]
     */
    private function modelsFromFileSystem(){
        $arr=array();
        $dir=$this->db->paths->records."/";
        foreach(scandir($dir) as $f){
            $file=$dir.$f;
            $modelName=$this->db->extractNameXml($f);
            if(is_file($file) && $modelName){
                $arr[]=$this->db->getModelById($modelName);
            }
        }
        return $arr;
    }

    /**
     * @var GinetteDb
     */
    public $db;
    /**
     * @var DOMDocument
     */
    public $allRecords;
    /**
     * @var string
     */
    public $allRecordsUrl;


}
