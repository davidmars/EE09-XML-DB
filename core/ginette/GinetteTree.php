<?php
/**
 * A tree is tree dimensional list of records.
 * Records are not directly stocked in it, you need to get it via a branch.
 */
class GinetteTree extends GinetteXml implements GinetteXml_interface
{


    /**
     * Return a new branch that you will be able to add somewhere in the tree.
     * @param GinetteRecord $record
     * @return GinetteBranch
     */
    public function newBranch(GinetteRecord $record){
        $b=new GinetteBranch($this);
        $b->model=$record;
        $b->xml=$this->xml->createElement($record->getType());
        $b->xml->setAttribute("id",$record->getId());
        return $b;
    }
    /**
     *  Parse the xml to fill the Tree properties
     */
    protected function parse()
    {
        parent::parse();
        //populate branches
        /** @noinspection PhpParamsInspection */
        $this->branches=new GinetteBranchArray($this,$this->xml->firstChild);
    }



    /**
     * Save the Tree.
     */
    public function save(){
        $this->xml->formatOutput=true;
        $this->xml->save($this->db->getTreeXmlUrl($this->getId()));
    }
    /**
     * Delete the Tree.
     */
    public function delete(){
        die("tree delete not implemented yet.");
    }

    /**
     * @var GinetteBranchArray|GinetteBranch[]
     */
    public $branches;


    public function __toString(){
        return "GinetteTree id:".$this->getType();
    }

}


