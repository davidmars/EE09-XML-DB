<?php
/**
 * Index records in a xml to retrieve it.
 */
class GinetteDbIndex
{
    /**
     * @param GinetteDb $db Well, the database.
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
        /** @noinspection PhpParamsInspection */
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
            $id=$this->db->extractNameXml($f);
            if(is_file($file) && $id){
                $xml=$this->db->loadRecordXml($id);
                $type=$xml->firstChild->nodeName;
                $record=$this->db->recordInstance($id,$type);
                $arr[]=$record;
            }
        }
        return $arr;
    }

    /**
     * Add a record to the index
     * @param GinetteRecord $record
     */
    public function add($record){
        $n=$this->allRecords->createElement($record->getType());
        $n->setAttribute("id",$record->getId());
        $this->allRecords->firstChild->appendChild($n);
        XmlUtils::save($this->allRecords,$this->allRecordsUrl);
    }

    /**
     * searching in the index return a GinetteRecord
     * @param string $id
     * @return GinetteRecord
     */
    public function getRecord($id){
        $xp=new DOMXPath($this->allRecords);
        $results=$xp->query("/Records/*[@id='$id']");
        if($results->length==1){
            $node=$results->item(0);
            $type=$node->nodeName;
            return $this->db->recordInstance($id,$type);
        }else{
            return false;
        }

    }

    public function getList(){

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
