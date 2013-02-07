<?php
/**
 * A tree is tree dimensional list of records.
 * Records are not directly stocked in it, you need to get it via a branch.
 *
 * @property GinetteBranch $trunk
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

        //populate branches
        $this->trunk=toolsGinetteTree::fromNode($this,$this->xml->firstChild);
        //because of magic setter parent parse at the end !
        parent::parse();
    }

    /**
     * @param string $branchId Id of the branch
     * @return bool|GinetteBranch|null
     */
    public function getBranchById($branchId){
        $xp=new DOMXPath($this->xml);
        $nodes=$xp->query("//*[@branchId='".$branchId."']");
        if($nodes->length == 1){
            $branch=toolsGinetteTree::fromNode($this,$nodes->item(0));
            return $branch;
        }
        return false;
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




    public function __toString(){
        return "GinetteTree id:".$this->getType();
    }

}


