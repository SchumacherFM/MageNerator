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
 * this class provides some basic setter and getter
 *
 * @package magenerator
 * @subpackage service/codegen
 */
class Tx_Magenerator_CodeGen_DocBlock extends Tx_Magenerator_CodeGen_MageneratorAbstractGlobal
{

    /**
     * inits all the necessary properties for a class
     * @param Tx_Magenerator_Domain_Model_FeUser $feUser
     * @param array $extConfig
     */
    public function init(Tx_Magenerator_Domain_Model_FeUser $feUser, $extConfig) {

        $this->setExtConfig($extConfig);
        $this->setFeUser($feUser);
    }

    /**
     * generates the docBlock
     * @param array $tags only supported is subpackage as key
     * @return \Zend\Code\Generator\DocBlockGenerator
     */
    public function getDocBlockClass($tags = array()) {

        $docblock = new Zend\Code\Generator\DocBlockGenerator();

        $docblock->setShortDescription('Sample generated class');
        $docblock->setLongDescription('This is a class generated with http://magenerator.net');
        $docblock->setTags(array(
            array(
                'name' => 'author',
                'description' => $this->feUser->getFirstName() . ' ' . $this->feUser->getLastName() . ' <' . $this->feUser->getEmail() . '>',
            ),
            array(
                'name' => 'version',
                'description' => '$Rev:$',
            ),
            array(
                'name' => 'license',
                'description' => 'http://opensource.org/licenses/osl-3.0.php  Open Software License',
            ),
            array(
                'name' => 'category',
                'description' => $this->getUserExtensionNameSpace(),
            ),
            array(
                'name' => 'package',
                'description' => $this->getExtname(),
            ),
            array(
                'name' => 'subpackage',
                'description' => isset($tags['subpackage']) ? $tags['subpackage'] : '',
            ),
        ));
        return $docblock;
    }

    /**
     * generates the docBlock
     * @param array $tags only supported is nothing
     * @return \Zend\Code\Generator\DocBlockGenerator
     */
    public function getDocBlockProperty($tags) {

        $docblock = new Zend\Code\Generator\DocBlockGenerator();

        $docblock->setShortDescription('Sample generated property');
        $docblock->setTags(array(
            array(
                'name' => 'var',
                'description' => 'mixed',
            ),
        ));
        return $docblock;
    }

    /**
     * generates the docBlock
     * @param array $tags two dim
     * @return \Zend\Code\Generator\DocBlockGenerator
     */
    public function getDocBlockMethod($tags=array(),$shortDescription='') {

        /*
        array(
            array(
                'name' => 'param',
                'description' => 'mixed',
            ),
        )
         */

        $docblock = new Zend\Code\Generator\DocBlockGenerator();
        $shortDescription = empty($shortDescription) ? 'Sample generated method' : $shortDescription;
        $docblock->setShortDescription($shortDescription);
        $docblock->setTags($tags);
        return $docblock;
    }

}
