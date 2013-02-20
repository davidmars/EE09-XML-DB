<?php
/**
 * This is a way to search records.
 */
class GinetteRecordFinder
{
    /**
     * @var GinetteDb
     */
    private $db;


    /**
     * @param GinetteDb $db
     */
    public function __construct($db)
    {
        $this->db = $db;


    }

    /**
     * @param string $type To search a certain type of record
     * @return \GinetteRecordFinder
     */
    public function type($type)
    {
        $this->type=$type;
        return $this;
    }

    private $sortBy;

    public function sortById(){
        $this->sortBy="id";
        return $this;
    }

    /**
     * @var string the type of record
     */
    private $type = "";

    /**
     * Define the starting range.
     * @param int $startRange in the range result start from the number
     * @return GinetteRecordFinder
     */
    public function rangeStartAt($startRange)
    {
        $this->rangeStart=$startRange;
        return $this;
    }

    /**
     * @var int
     */
    private $rangeStart = 0;
    /**
     * @var int
     */
    private $rangeTotal = 100;

    /**
     * Define the maximum number of record to return
     * @param int $numberToReturn maximum number of results to return in the range
     * @return GinetteRecordFinder
     */
    public function rangeTotal($numberToReturn)
    {
        $this->rangeTotal=$numberToReturn;
        return $this;
    }

    /**
     * @return int The total number of records found without notion of range
     */
    public function length()
    {
        return count($this->getSelectionWithoutRange());
    }

    /**
     * Performs the search
     * @return GinetteRecord[]
     */
    public function doIt()
    {
        $records = $this->getSelectionWithoutRange();



        //range at the end
        if (isset($this->rangeStart) && isset($this->rangeTotal)) {
            $records = array_slice($records, $this->rangeStart, $this->rangeTotal);
        }

        return $records;
    }

    /**
     * @return GinetteRecord[]
     */
    private function getSelectionWithoutRange()
    {
        $xpath = new DOMXpath($this->db->index->allRecords);
        $query = "/Records/";
        //the basic xpath query for the type
        $query .= $this->type;
        $listXml = $xpath->query($query);
        //die("-------------".$listXml->length."-----------------");
        //transform xml to records
        $records = array();

        for ($i=0;$i<$listXml->length;$i++) {
            $n=$listXml->item($i);
            $type = $n->nodeName;
            $id = $n->getAttribute("id");
            $record = $this->db->getRecordInstance($id, $type);
            if ($record) {
                $records[] = $record;
            }
        }

        //where field = or field like field > e
        //TODO::where conditions

        //order by field plus asc, desc etc...
        //TODO::orderBy
        if($this->sortBy){
            $sorter=new GinetteRecordSorter($records);
            switch($this->sortBy){
                case "id":
                    $records=$sorter->sortById();
                    break;
                default:
                    break;
            }
        }


        return $records;
    }

    public function __toString(){
        $str="Search records where type = '".$this->type."'";
        $str.=" \nstarting from the result number".$this->rangeStart;
        $str.=" \nand maximum number results will be ".$this->rangeTotal;
        return $str;
    }
}