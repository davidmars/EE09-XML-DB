<?php

class XmlUtils
{
    /**
     * @param $xml
     * @param string $nodeName
     * @return DOMElement The first node found in the xml
     */
    public static function getFirst($xml, $nodeName)
    {
        $list = $xml->getElementsByTagName($nodeName);
        foreach ($list as $el) {
            return $el;
        }
        return null;
    }

    /**
     * Set a CDATA value in a node
     * @param DOMDocument $xml
     * @param DOMNode $node
     * @param string $value
     */
    public static function cdata($xml, $node, $value)
    {
        $node->removeChild($node->firstChild);
        $newText = $xml->createCDATASection($value);
        $node->appendChild($newText);
    }

    /**
     * @param DOMElement $node Remove all children nodes in the target node.
     */
    public static function emptyNode($node)
    {
        while ($node->hasChildNodes()) {
            $node->removeChild($node->firstChild);
        }
    }

    /**
     * Load an xml and returns it.
     * @param string $url
     * @return DOMDocument
     */
    public static function load($url){
        $x=new DOMDocument();
        $x->preserveWhiteSpace=false;
        $x->load($url);
        return $x;
    }

    /**
     * If $url do not exists create it. In both case return the xml doc Return it.
     * @param $url
     * @return DOMDocument
     */
    public static function loadOrCreate($url,$rootNodeName="data"){
        if(is_file($url)){
            return self::load($url);
        }else{
            $x=new DOMDocument();
            $rootNode=$x->createElement($rootNodeName);
            $x->appendChild($rootNode);
            $x->preserveWhiteSpace=false;
            $x->save($url);
            return $x;
        }

    }

    /**
     * Save an xml doc.
     * @param DOMDocument $xmlDoc
     * @param string $url Where to save it?
     */
    public static function save($xmlDoc,$url){
       $xmlDoc->formatOutput=true;
       $xmlDoc->save($url);
    }

}