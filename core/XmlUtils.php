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

}