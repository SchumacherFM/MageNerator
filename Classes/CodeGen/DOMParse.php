<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Cyrill Schumacher <cyrill@schumacher.fm>
 *
 *  All rights reserved
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

/**
 * xml class, borrowed from Mage_Xml_Parser
 *
 * @package magenerator
 * @subpackage service/codegen
 */
class Tx_Magenerator_CodeGen_DOMParse
{

    protected $_dom = null;
    protected $_currentDom;
    protected $_content = array();

    public function __construct()
    {
        $this->_dom = new DOMDocument;
        $this->_currentDom = $this->_dom;
        return $this;
    }

    public function getDom()
    {
        return $this->_dom;
    }

    protected function _getCurrentDom()
    {
        return $this->_currentDom;
    }

    protected function _setCurrentDom($node)
    {
        $this->_currentDom = $node;
        return $this;
    }

    public function xmlToArray()
    {
        $this->_content = $this->_xmlToArray();
        return $this->_content;
    }

    protected function _xmlToArray($currentNode=false)
    {
        if (!$currentNode) {
            $currentNode = $this->getDom();
        }

        $content = array();
        foreach ($currentNode->childNodes as $node) {

            switch ($node->nodeType) {
                case XML_ELEMENT_NODE:

                    $value = null;
                    if ($node->hasChildNodes()) {
                        $value = $this->_xmlToArray($node);
                    }
                    $attributes = array();
                    if ($node->hasAttributes()) {
                        foreach($node->attributes as $attribute) {
                            $attributes += array($attribute->name=>$attribute->value);
                        }
                        $value = array('_value'=>$value, '_attribute'=>$attributes);
                    }
                    if (isset($content[$node->nodeName])) {
                        if (!isset($content[$node->nodeName][0]) || !is_array($content[$node->nodeName][0])) {
                            $oldValue = $content[$node->nodeName];
                            $content[$node->nodeName] = array();
                            $content[$node->nodeName][] = $oldValue;
                        }
                        $content[$node->nodeName][] = $value;
                    } else {
                        $content[$node->nodeName] = $value;
                    }
                    break;
                case XML_TEXT_NODE:
                    if (trim($node->nodeValue)) {
                        $content = $node->nodeValue;
                    }
                    break;
            }
        }
        return $content;
    }

    /**
     *
     * @param string $file
     * @return \Tx_Magenerator_CodeGen_DOMParse
     */
    public function load($file)
    {
        $this->getDom()->load($file);
        return $this;
    }

    /**
     *
     * @param string $string xml string
     * @return \Tx_Magenerator_CodeGen_DOMParse
     */
    public function loadXML($string)
    {
        $this->getDom()->loadXML($string);
        return $this;
    }

}