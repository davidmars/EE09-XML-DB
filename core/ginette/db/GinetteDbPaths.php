<?php
/**
 * Reference all Ginette database directories.
 */
class GinetteDbPaths
{
    /**
     * Will automatically set all paths necessary to the database.
     * @param string $rootPath database root path
     * @param string $phpRoot The root directory of the Ginette framework.
     * Useful for stuff not directly in relation with THIS database but with all Ginette Database.
     */
    public function __construct($rootPath,$phpRoot){

        //database paths
        $this->root=$rootPath;
        $this->definitions=$rootPath."/definitions";
        $this->data=$rootPath."/data";
        $this->indexes=$this->data."/indexes";
        $this->records=$this->data."/records";
        $this->trees=$this->data."/trees";

        //framework path
        $this->xmlTemplates=$phpRoot."/xmlTemplates";
    }

    /**
     * @var string XML templates directory. Here are stored xml that will be used to generate new xml such as Trees, indexes, fields nodes etc...
     */
    public $xmlTemplates;

    /**
     * @var string Root directory for a database
     */
    public $root;
    /**
     * @var string Directory where are stored GinetteRecords definitions
     */
    public $definitions;
    /**
     * @var string Root directory where are stored indexes, records and trees
     */
    private $data;
    /**
     * @var string Directory where are stored records indexes
     */
    public $indexes;
    /**
     * @var string Directory where are stored xml records
     */
    public $records;
    /**
     * @var string Directory where are stored trees xml
     */
    public $trees;
}
