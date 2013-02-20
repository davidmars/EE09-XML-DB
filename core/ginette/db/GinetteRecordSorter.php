<?php
/**
 * utility class to sort GinetteRecords according to some predefined functions
 */
class GinetteRecordSorter
{
    /**
     * @param GinetteRecord[] $list
     */
    public function __construct($recordsList){
        $this->records=$recordsList;
    }

    /**
     * @var GinetteRecord[]
     */
    public $records;

    /**
     * @return GinetteRecord[]
     */
    public function sortById(){
        //die("------------".count($this->records));
        uasort($this->records,array($this,"cmpById"));
        return $this->records;
    }

    /**
     * @param GinetteRecord $a
     * @param GinetteRecord $b
     */
    private function cmpById($a, $b)
    {
        //die(gettype($a));
        return strnatcasecmp($a->getId(), $b->getId());
        /*
        if($a->getId()>$b->getId()){
            return 1;
        }elseif($a->getId()<$b->getId()){
            return -1;
        }else{
            return 0;
        }
        */
    }



}