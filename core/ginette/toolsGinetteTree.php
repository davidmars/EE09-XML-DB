<?php
/**
 * Some utilities to play with GinetteTree
 */
class toolsGinetteTree
{
    /**
     * From a XML node return the relative GinetteBranch object.
     * @param GinetteTree $tree The tree where is the branch
     * @param DOMElement $node The xml node that represents this branch
     * @throws Exception If no related model is found
     * @return \GinetteBranch|bool
     */
    public static function fromNode(GinetteTree $tree,DOMElement $node){
        $idRecord=$node->getAttribute("id");
        $typeRecord=$node->nodeName;

        $isTrunk=($node->nodeName=="GinetteTree") ? true : false;

        if($isTrunk || $tree->db->recordExists($idRecord)){
            $branch=new GinetteBranch($tree);
            if(!$isTrunk){
                $model=$tree->db->getRecordInstance($idRecord,$typeRecord);
                $branch->model=$model;
            }else{
                $branch->isTrunk=true;
            }
            //okay...
            $branch->xml=$node;
            return $branch;
        }else{
            die("invalid ".$idRecord);
            //return false;
        }
        /*
        if(!$model){
            throw new Exception("Ginette says :
            Va te rouler!
            Impossible to create this branch,
            there is no model '$idModel'");
        }
        */



    }
}
