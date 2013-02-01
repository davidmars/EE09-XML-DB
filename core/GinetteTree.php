<?php
/**
 *
 */
class GinetteTree extends GinetteXml implements GinetteXml_interface
{
    /**
     * @param string $id
     * @param GinetteDb $db
     */
    public function __construct($id,GinetteDb $db){
        $this->db=$db;
        $rc = new ReflectionClass($this);
        $this->type = $rc->getName();

        if($id!="FROM_DB_HACK"){
            traceCode("------construct a new " . $this->type."-----------");
            //it is a real new model
            if($this->db->treeExists($id)){
                throw new Exception("You tried to create a new ".$this->type." with the id ".$id." but this id is already used by an other tree.
            Try an other id or delete the $id record. Ciao!");
            }

            //okay...it is a real new tree

            //set the id
            $this->id=$id;

            //set the xml from the structure
            $this->xml = new DOMDocument();
            $this->xml->loadXML(GinetteDb::getTemplatesPath()."/GinetteTree.xml");

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
     *  Parse the xml to fill the Tree properties
     */
    protected function parse()
    {
        parent::parse();
        traceCode("----parse tree-----" . $this->getId());
        traceCode($this->xml->firstChild->getAttribute("created"));

        //populate branches
        $this->branches=self::getBranches($this->xml->firstChild,$this);

    }

    /**
     * @param DOMElement $xml
     * @param $tree
     * @return
     */
    public static function getBranches($xml,$tree){
        $ret=array();
        for($i=0;$i<$xml->childNodes->length;$i++){
            $n=$xml->childNodes->item($i);
            trace("will try add a branch to tree...".$n->nodeType);
            if($n->nodeType=="1"){
                trace("...good");
                //TODO::manage lazy constructor
                $branch=GinetteBranch::fromNode($tree,$n,$tree->db);
                //$branch=new GinetteBranch($tree);
                if($branch){
                    trace("YES! add a branch to tree!!!");
                    $ret[]=$branch;
                }
            }
        }
        return $ret;
    }

    /**
     * Save the Tree in its xml
     */
    public function save(){

    }

    /**
     * @var GinetteBranch[]
     */
    public $branches;





}

/**
 * A branch is part of a tree.
 * A tree has many branches.
 * A branch has many branches too.
 * A branch has many leaves.
 * Leaves are records.
 */
class GinetteBranch{


    public function __construct(GinetteTree $tree){
        $this->tree=$tree;
    }

    /**
     * @param DOMElement $node
     */
    public static function fromNode(GinetteTree $tree,DOMElement $node,GinetteDb $db){
        $model=$db->getModelById($node->getAttribute("id"));
        if(!$model){
            return null;
        }

        //okay...
        $branch=new GinetteBranch($tree);
        $branch->model=$model;
        $branch->xml=$node;

        //populate branches
        $branch->branches=GinetteTree::getBranches($node,$tree);
        return $branch;
    }

    /**
     * @var GinetteTree
     */
    public $tree;
    /**
     * @var GinetteBranch[]
     */
    public $branches;
    /**
     * @var ModelXml
     */
    public $model;

    /**
     * @var DOMElement
     */
    public $xml;

    public function __toString(){
        return "GinetteBranch model:".$this->model."; ".count($this->branches)." branche(s)";
    }


}
