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
 * xml class, borrowed from Mage_Xml_Generator
 *
 * @package magenerator
 * @subpackage service/codegen
 */
class Tx_Magenerator_CodeGen_DOMGen
{

    protected $_dom = null;
    protected $_currentDom;

    /**
     *
     * @return DOMDocument
     */
    public function __construct($formatOutput = true) {
        $this->_dom = new DOMDocument('1.0');

        // @todo user with a paid account will get a nice XML output
        $this->_dom->formatOutput = (boolean)$formatOutput;

        $this->_currentDom = $this->_dom;
        return $this;
    }

    /**
     * gets the dom obj
     * @return DOMDocument
     */
    public function getDom() {
        return $this->_dom;
    }

    /**
     * gets the current dom
     * @return DOMDocument
     */
    protected function _getCurrentDom() {
        return $this->_currentDom;
    }

    /**
     *
     * @param DOMDocument $node
     * @return DOMDocument
     */
    protected function _setCurrentDom($node) {
        $this->_currentDom = $node;
        return $this;
    }

    /**
     * @param array $content
     */
    public function arrayToXml($content) {
        $parentNode = $this->_getCurrentDom();
        if(!$content || !count($content)) {
            return $this;
        }
        foreach ($content as $_key=>$_item) {
            try{
                $node = $this->getDom()->createElement($_key);
            } catch (DOMException $e) {
              //  echo $e->getMessage();
                echo 'Error: '.get_class($this) . '<br>';
                var_dump($_key);
                var_dump($_item);
                exit;
            }
            $parentNode->appendChild($node);
            if (is_array($_item) && isset($_item['_attribute'])) {
                if (is_array($_item['_value'])) {
                    if (isset($_item['_value'][0])) {
                        foreach($_item['_value'] as $_k=>$_v) {
                            $this->_setCurrentDom($node)->arrayToXml($_v);
                        }
                    } else {
                        $this->_setCurrentDom($node)->arrayToXml($_item['_value']);
                    }
                } else {
                    $child = $this->getDom()->createTextNode($_item['_value']);
                    $node->appendChild($child);
                }
                foreach($_item['_attribute'] as $_attributeKey=>$_attributeValue) {
                    $node->setAttribute($_attributeKey, $_attributeValue);
                }
            } elseif (is_string($_item)) {
                $text = $this->getDom()->createTextNode($_item);
                $node->appendChild($text);
            } elseif (is_array($_item) && !isset($_item[0])) {
                $this->_setCurrentDom($node)->arrayToXml($_item);
            } elseif (is_array($_item) && isset($_item[0])) {
                foreach($_item as $k=>$v) {
                    $this->_setCurrentDom($node)->arrayToXml($v);
                }
            }
        }
        return $this;
    }

    /**
     * converts to a xml string
     * @return string
     */
    public function __toString() {
        return $this->getDom()->saveXML();
    }

    /**
     * saves the dom as file
     * @param string $file
     * @return integer Returns the number of bytes written or FALSE if an error occurred.
     */
    public function save($file) {
        return $this->getDom()->save($file);
    }

    /**
     *
     * @param string $name
     * @param string $value
     * @return DOMNode
     */
    public function createElement($name,$value = NULL){
        $node = $this->getDom()->createElement($name,$value);
        $node = $this->getDom()->appendChild($node);
        return $node;
    }

    /**
     *
     * @param string $name
     * @param string $value
     * @return DOMNode
     */
    public function createTextNode($name,$value = NULL){
        $node = $this->getDom()->createTextNode($name,$value);
        $node = $this->getDom()->appendChild($node);
        return $node;
    }

}