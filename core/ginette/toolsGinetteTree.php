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
     * @return \GinetteBranch|null
     */
    public static function fromNode(GinetteTree $tree,DOMElement $node){
        $idRecord=$node->getAttribute("id");
        $typeRecord=$node->nodeName;
        if($tree->db->modelExists($idRecord)){
            $model=$tree->db->recordInstance($idRecord,$typeRecord);
            //okay...
            $branch=new GinetteBranch($tree);
            $branch->model=$model;
            $branch->xml=$node;
            return $branch;
        }else{

            return false;
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
