<?php
/**
 * Created by JetBrains PhpStorm.
 * User: heeek
 * Date: 02/02/13
 * Time: 07:07
 * To change this template use File | Settings | File Templates.
 */
class GinetteBranchArray extends ArrayObject
{
    /**
     * @var DOMElement
     */
    public $xml;
    /**
     * @var GinetteTree
     */
    public $tree;

    /**
     * @param GinetteTree $tree
     * @param DOMElement $xml The xml node that represents this list
     */
    public function __construct($tree,$xml){
        $this->tree=$tree;
        $this->xml=$xml;
        $this->remap();
    }

    /**
     * reset the array from the xml
     */
    private function remap(){
        $array = array();
        for($i=0;$i<$this->xml->childNodes->length;$i++){
            $n=$this->xml->childNodes->item($i);
            if($n->nodeType=="1"){
                // @var DOMElement $n
                $branch=toolsGinetteTree::fromNode($this->tree,$n);
                if($branch){
                    $array[]=$branch;
                    //$ret[]=$branch;
                }else{
                    $comment=$this->xml->ownerDocument->createComment($this->xml->ownerDocument->saveXML($n));
                    $this->xml->appendChild($comment);
                    $this->xml->removeChild($n); //remove the node

                }
            }
        }
        $this->exchangeArray($array);
    }

    /**
     * @return int Number of items
     */
    public function length(){
        return $this->count();
    }

    /**
     * Test if index is valid and if index is in range of the array
     * @param int $index
     * @return bool
     */
    private function indexIsGood($index){
        if(is_int($index)){
            if($index<0){
                return false;
            }else if($index>$this->xml->childNodes->length){
                return false;
            }
            return true;
        }else{
            return false;
        }
    }


    /**
     * Removes the last GinetteBranch and returns that GinetteBranch.
     * @return GinetteBranch The removed branch
     */
    public function pop(){
        $ret=$this->offsetGet($this->count()-1);
        $this->xml->removeChild($this->xml->lastChild);
        $this->remap();
        return $ret;
    }

    /**
     * Add a value at the end of the array
     * @param GinetteRecord $value The record you want to add to the list
     * @return GinetteBranch The new branch
     */
    public function push($value){
        $branch=$this->tree->newBranch($value);
        $this->xml->appendChild($branch->xml);
        $this->remap();
        return $branch;
    }

    /**
     * Add a value at the end of the array
     * @param GinetteRecord $value The record you want to add to the list
     * @return GinetteBranch The new branch
     */
    public function append($value){
        return $this->push($value);
    }
    /**
     * Removes the first GinetteBranch and returns that GinetteBranch.
     * @return GinetteBranch The removed branch
     */
    public function shift(){
        $ret=$this->offsetGet(0);
        $this->xml->removeChild($this->xml->firstChild);
        $this->remap();
        return $ret;
    }


    /**
     * Add a value at the beginning of the array
     * @param GinetteRecord $value The record you want to add to the list
     * @return GinetteBranch The new branch
     */
    public function prepend($value) {
        $branch=$this->tree->newBranch($value);
        $this->xml->insertBefore($branch->xml,$this->xml->firstChild);
        $this->remap();
        return $branch;
    }

    /**
     * @param int $index
     * @param GinetteBranch $value
     * @return bool|void
     */
    public function offsetSet($index, $value) {
        if(!$this->indexIsGood($index)){
            throw new Exception("Ginette says :
            Petit coquin va! Tu sais pas compter.
            Invalid index ($index). GinetteBranchArray only accepts integers in range.
            ");
        }
        if($index>=$this->xml->childNodes->length-1){
            $this->xml->appendChild($value->xml);
        }else{
            $this->xml->insertBefore($this->xml->childNodes->item($index+1));
        }
        $this->remap();
    }

    /**
     * @param int $index
     */
    public function offsetUnset($index){
        if(!$this->indexIsGood($index)){
            throw new Exception("Ginette says :
            Petit coquin va! Tu sais pas compter.
            Invalid index ($index). GinetteBranchArray only accepts integers in range.
            ");
        }
        $this->xml->removeChild($this->xml->childNodes->item($index));
        $this->remap();
    }
}
