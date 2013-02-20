<?php
/**
 * manage the Url for the admin records
 */
class C_records extends C_admin
{


    public function __construct()
    {
        parent::__construct();



        //the action
        $action = "";
        if (isset($_GET["action"])) {
            $action = $_GET["action"];
        }

        //the directory to browse
        $recordType = "";
        if (isset($_GET["type"])) {
            $recordType = $_GET["type"];
        }

        //$list=GinetteDir::get($directory);

        switch ($action) {
            case "listRecords":
                $this->listRecords($recordType,$_GET["rangeStart"]);
                break;

            case "popIn":
                $v=new View("recordsManagerPopIn",new VM_records_manager($recordType));
                echo $v->render();
                break;

            default:
                //$v=new View("recordsManager",new VM_files_manager($dir));
                //echo $v->render();
        }




    }

    /**
     *
     * @param string $recordType Name of the record family
     */
    public function listRecords($recordType,$start=0){
        //$records=$this->db->getRecordList();
        $v=new View("records/list-records",new VM_records_selection($recordType,$start,500));
        header('Content-Type: text/html; charset=utf-8');
        echo $v->render();
    }

    /**
     * @param $recordType
     * @return string The url to display ajax content of a directory
     */
    public static function urlListRecords($recordType,$start=0){
        $url=self::$baseUrl."?p=records&action=listRecords&type=$recordType&rangeStart=$start";
        return $url;
    }

    /**
     * @return string The files home url
     */
    public static function urlHome(){
        $url=self::$baseUrl."?p=files&action=home";
        return $url;
    }



}


