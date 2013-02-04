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
            $id=$this->db->extractNameXml($f);
            if(is_file($file) && $id){
                $r=$this->db->getModelById($id);
                $xml=$this->db->loadRecordXml($id);
                $type=$xml->firstChild->nodeName;
                $arr[]=new $type($id,$this->db);


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
