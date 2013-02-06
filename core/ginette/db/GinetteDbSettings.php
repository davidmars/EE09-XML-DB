<?php
/**
 * User: juliette david
 * Date: 05/02/13
 * Time: 18:23
 * To change this template use File | Settings | File Templates.
 */
class GinetteDbSettings
{
    /**
     * @param GinetteDb $db
     */
    public function __construct($db){
        $this->db=$db;
        $xml=XmlUtils::load($db->paths->settings);
        $this->node=$xml->firstChild;
        $this->uid=$this->node->getAttribute("uid");
    }

    /**
     * @var GinetteDb
     */
    private $db;
    /**
     * @var DOMElement
     */
    private $node;
    /**
     * @var int The current uid
     */
    private $uid;

    public function getUid(){
        $this->uid++;
        $this->node->setAttribute("uid",$this->uid);
        XmlUtils::save($this->node->ownerDocument,$this->db->paths->settings);
        return $this->uid;
    }
}
