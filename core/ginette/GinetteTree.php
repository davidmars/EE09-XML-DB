<?php
/**
 * A tree is tree dimensional list of records.
 * Records are not directly stocked in it, you need to get it via a branch.
 */
class GinetteTree extends GinetteXml implements GinetteXml_interface
{
    /**
     * @param string $id
     * @param GinetteDb $db
     * @throws Exception
     */
    public function __construct($id,GinetteDb $db){

        $this->db=$db;
        $rc = new ReflectionClass($this);
        $this->type = $rc->getName();

        if($id!="FROM_DB_HACK"){
            traceCode("------construct a new " . $this->type."-----------");
            //it is a real new model
            if($this->db->treeExists($id)){
                throw new Exception("Ginette say to you :
                Espèce d'abruti! You tried to create a new ".$this->type." with the id '$id' but this id is already used by an other tree.
                Try an other id for this tree or delete the '$id' one. Adieux!");
            }

            //okay...it is a real new tree

            //set the id
            $this->id=$id;

            //set the xml from the structure
            $this->xml=XmlUtils::load($db->paths->xmlTemplates."/GinetteTree.xml");

            /** @var $root DOMElement */
            $root=$this->xml->firstChild;
            $root->setAttribute("id",$this->id);
            $root->setAttribute("created",time());
            $root->setAttribute("updated",time());

            $this->parse();
            $this->save();

        }else{
            traceCode("------construct a new " . $this->type." from an xml-----------");
            //$this->parse();
        }
    }

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
        $this->branches=new GinetteBranchArray($this,$this->xml->firstChild);
    }



    /**
     * Save the Tree.
     */
    public function save(){
        $this->xml->formatOutput=true;
        $this->xml->save($this->db->getTreeXmlUrl($this->id));
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
        return "GinetteTree id:".$this->id;
    }

}


