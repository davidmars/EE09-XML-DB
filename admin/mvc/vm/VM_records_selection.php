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
    public function __construct($type){
        $this->type=$type;
    }

    private $_recordsList;
    /**
     * @return GinetteRecord[] The records you probably need
     */
    public function getList($number=null){
        if($this->_recordsList){
            return $this->_recordsList;
        }else{
            $all=self::$db->getRecordList($this->type);
            if($number){
                for($i=0;$i<$number;$i++){
                    $this->_recordsList[]=$all[$i];
                }
            }else{
                $this->_recordsList=self::$db->getRecordList($this->type);
            }

        }
        return $this->_recordsList;
    }

    /**
     * @return int The number of records in this selection
     */
    public function count(){
        return count($this->getList());
    }
}