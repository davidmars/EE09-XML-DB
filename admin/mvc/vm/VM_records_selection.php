<?php
/**
 * User: heeek
 * Date: 19/02/13
 * Time: 08:59
 * This Class is used in Views and is configured in controllers.
 * represents a list of records according selection parameters
 */
class VM_records_selection extends VM_admin
{
    /**
     * @var string type of records we need
     */
    public $type;

    /**
     * @param string $type
     */
    public function __construct($type,$rangeStart=0,$rangeLength=100){
        $this->type=$type;
        $this->rangeStart=$rangeStart;
        $this->rangeLength=$rangeLength;
        $this->records=$this->getList();
    }

    /**
     * @var GinetteRecord[]
     */
    public $records;
    /**
     * @return GinetteRecord[] The records you probably need
     */
    private function getList(){

        $search=self::$db->find($this->type)->sortById()->rangeStartAt($this->rangeStart)->rangeTotal($this->rangeLength);
        $records=$search->doIt();
        return $records;

    }

    /**
     * @return bool|string Return the next range of records url
     */
    public function nextUrl(){
        if($this->rangeStart<$this->count()){
            return C_records::urlListRecords($this->type,$this->rangeStart+$this->rangeLength,$this->rangeLength);
        }else{
            return false;
        }
    }
    /**
     * @return int The number of records in this selection
     */
    public function count(){
        return self::$db->find($this->type)->length();
    }
}

