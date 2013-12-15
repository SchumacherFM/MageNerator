<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Cyrill Schumacher <cyrill@schumacher.fm>
 *
 *  All rights reserved
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * xml class
 *
 * @package magenerator
 * @subpackage service/codegen
 */
class Tx_Magenerator_CodeGen_Magexml extends SimpleXMLElement
{
    const XML_HEADER = '<?xml version="1.0"?>';

    /**
     * Enter description here...
     *
     * @return boolean
     */
    public function hasChildren()
    {
        if (!$this->children()) {
            return false;
        }

        // simplexml bug: @attributes is in children() but invisible in foreach
        foreach ($this->children() as $k=>$child) {
            return true;
        }
        return false;
    }

    /**
     * Returns attribute value by attribute name
     *
     * @return string
     */
    public function getAttribute($name){
        $attrs = $this->attributes();
        return isset($attrs[$name]) ? (string)$attrs[$name] : null;
    }

    /**
     * Returns the node and children as an array
     *
     * @return array|string
     */
    public function asArray()
    {
        return $this->_asArray();
    }

    /**
     * asArray() analog, but without attributes
     * @return array|string
     */
    public function asCanonicalArray()
    {
        return $this->_asArray(true);
    }

    /**
     * Returns the node and children as an array
     *
     * @param bool $isCanonical - whether to ignore attributes
     * @return array|string
     */
    protected function _asArray($isCanonical = false)
    {
        $result = array();
        if (!$isCanonical) {
            // add attributes
            foreach ($this->attributes() as $attributeName => $attribute) {
                if ($attribute) {
                    $result['@'][$attributeName] = (string)$attribute;
                }
            }
        }
        // add children values
        if ($this->hasChildren()) {
            foreach ($this->children() as $childName => $child) {
                $result[$childName] = $child->_asArray($isCanonical);
            }
        } else {
            if (empty($result)) {
                // return as string, if nothing was found
                $result = (string) $this;
            } else {
                // value has zero key element
                $result[0] = (string) $this;
            }
        }
        return $result;
    }

    /**
     * Makes nicely formatted XML from the node
     *
     * @param string $filename
     * @param int|boolean $level if false
     * @return string
     */
    public function asNiceXml($filename='', $level=0)
    {
        if (is_numeric($level)) {
            $pad = str_pad('', $level*3, ' ', STR_PAD_LEFT);
            $nl = "\n";
        } else {
            $pad = '';
            $nl = '';
        }

        $out = $pad.'<'.$this->getName();

        if ($attributes = $this->attributes()) {
            foreach ($attributes as $key=>$value) {
                $out .= ' '.$key.'="'.str_replace('"', '\"', (string)$value).'"';
            }
        }

        if ($this->hasChildren()) {
            $out .= '>'.$nl;
            foreach ($this->children() as $child) {
                $out .= $child->asNiceXml('', is_numeric($level) ? $level+1 : true);
            }
            $out .= $pad.'</'.$this->getName().'>'.$nl;
        } else {
            $value = (string)$this;
            if (strlen($value)) {
                $out .= '>'.$this->xmlentities($value).'</'.$this->getName().'>'.$nl;
            } else {
                $out .= '/>'.$nl;
            }
        }

        if ((0===$level || false===$level) && !empty($filename)) {

            $success = t3lib_div::writeFile($filename, self::XML_HEADER. PHP_EOL .$out);
            if (!$success) {
                throw new Exception('File ' . $filename . ' could not be created!');
            }

        }

        return $out;
    }

    /**
     * @param int $level
     * @return string
     */
    public function innerXml($level=0)
    {
        $out = '';
        foreach ($this->children() as $child) {
            $out .= $child->asNiceXml($level);
        }
        return $out;
    }

    /**
     * Converts meaningful xml characters to xml entities
     *
     * @param  string
     * @return string
     */
    public function xmlentities($value = null)
    {
        if (is_null($value)) {
            $value = $this;
        }
        $value = (string)$value;

        $value = str_replace(array('&', '"', "'", '<', '>'), array('&amp;', '&quot;', '&apos;', '&lt;', '&gt;'), $value);

        return $value;
    }

    /**
     * Appends $source to current node
     *
     * @param Varien_Simplexml_Element $source
     * @return Varien_Simplexml_Element
     */
    public function appendChild($source)
    {
        if ($source->children()) {
            /**
             * @see http://bugs.php.net/bug.php?id=41867 , fixed in 5.2.4
             */
            if (version_compare(phpversion(), '5.2.4', '<')===true) {
                $name = $source->children()->getName();
            }
            else {
                $name = $source->getName();
            }
            $child = $this->addChild($name);
        } else {
            $child = $this->addChild($source->getName(), $this->xmlentities($source));
        }
        $child->setParent($this);

        $attributes = $source->attributes();
        foreach ($attributes as $key=>$value) {
            $child->addAttribute($key, $this->xmlentities($value));
        }

        foreach ($source->children() as $sourceChild) {
            $child->appendChild($sourceChild);
        }
        return $this;
    }

    /**
     * Extends current node with xml from $source
     *
     * If $overwrite is false will merge only missing nodes
     * Otherwise will overwrite existing nodes
     *
     * @param Varien_Simplexml_Element $source
     * @param boolean $overwrite
     * @return Varien_Simplexml_Element
     */
    public function extend($source, $overwrite=false)
    {
        if (!$source instanceof Varien_Simplexml_Element) {
            return $this;
        }

        foreach ($source->children() as $child) {
            $this->extendChild($child, $overwrite);
        }

        return $this;
    }

    /**
     * Extends one node
     *
     * @param Varien_Simplexml_Element $source
     * @param boolean $overwrite
     * @return Varien_Simplexml_Element
     */
    public function extendChild($source, $overwrite=false)
    {
        // this will be our new target node
        $targetChild = null;

        // name of the source node
        $sourceName = $source->getName();

        // here we have children of our source node
        $sourceChildren = $source->children();

        if (!$source->hasChildren()) {
            // handle string node
            if (isset($this->$sourceName)) {
                // if target already has children return without regard
                if ($this->$sourceName->children()) {
                    return $this;
                }
                if ($overwrite) {
                    unset($this->$sourceName);
                } else {
                    return $this;
                }
            }

            $targetChild = $this->addChild($sourceName, $source->xmlentities());
            $targetChild->setParent($this);
            foreach ($source->attributes() as $key=>$value) {
                $targetChild->addAttribute($key, $this->xmlentities($value));
            }
            return $this;
        }

        if (isset($this->$sourceName)) {
            $targetChild = $this->$sourceName;
        }

        if (is_null($targetChild)) {
            // if child target is not found create new and descend
            $targetChild = $this->addChild($sourceName);
            $targetChild->setParent($this);
            foreach ($source->attributes() as $key=>$value) {
                $targetChild->addAttribute($key, $this->xmlentities($value));
            }
        }

        // finally add our source node children to resulting new target node
        foreach ($sourceChildren as $childKey=>$childNode) {
            $targetChild->extendChild($childNode, $overwrite);
        }

        return $this;
    }

    public function setNode($path, $value, $overwrite=true)
    {
        $arr1 = explode('/', $path);
        $arr = array();
        foreach ($arr1 as $v) {
            if (!empty($v)) $arr[] = $v;
        }
        $last = sizeof($arr)-1;
        $node = $this;
        foreach ($arr as $i=>$nodeName) {
            if ($last===$i) {
                /*
                if (isset($xml->$nodeName)) {
                    if ($overwrite) {
                        unset($xml->$nodeName);
                    } else {
                        continue;
                    }
                }
                $xml->addChild($nodeName, $xml->xmlentities($value));
                */
                if (!isset($node->$nodeName) || $overwrite) {
                    // http://bugs.php.net/bug.php?id=36795
                    // comment on [8 Feb 8:09pm UTC]
                    if (isset($node->$nodeName) && (version_compare(phpversion(), '5.2.6', '<')===true)) {
                        $node->$nodeName = $node->xmlentities($value);
                    } else {
                        $node->$nodeName = $value;
                    }
                }
            } else {
                if (!isset($node->$nodeName)) {
                    $node = $node->addChild($nodeName);
                } else {
                    $node = $node->$nodeName;
                }
            }

        }
        return $this;
    }

    /**
     * Would keep reference to parent node
     *
     * If SimpleXMLElement would support complicated attributes
     *
     * @todo make use of spl_object_hash to keep global array of simplexml elements
     *       to emulate complicated attributes
     * @var Varien_Simplexml_Element
     */
    protected $_parent = null;

    /**
     * For future use
     *
     * @param Varien_Simplexml_Element $element
     */
    public function setParent($element)
    {
        #$this->_parent = $element;
    }

    /**
     * Returns parent node for the element
     *
     * Currently using xpath
     *
     * @return Varien_Simplexml_Element
     */
    public function getParent()
    {
        if (!empty($this->_parent)) {
            $parent = $this->_parent;
        } else {
            $arr = $this->xpath('..');
            $parent = $arr[0];
        }
        return $parent;
    }

}
