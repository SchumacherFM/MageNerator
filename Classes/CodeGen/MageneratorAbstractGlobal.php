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
abstract class Tx_Magenerator_CodeGen_MageneratorAbstractGlobal
{

    const TYPO3TEMP = 'typo3temp/mage';

    /**
     *
     * @var Tx_Magenerator_CodeGen_TemplateRenderer
     */
    protected $templateRenderer;

    /**
     * the version which the user would like to have created
     * version is either: 1_5, 1_6 or 2_0
     * @var string
     */
    protected $version;

    /**
     * the main config as created by the user in the frontend
     * @var array
     */
    protected $extConfig;

    /**
     *
     * @var Tx_Magenerator_Domain_Model_FeUser
     */
    protected $feUser;

    /**
     * constructor, initialized the template renderer
     */
    public function __construct() {
        $this->templateRenderer = t3lib_div::makeInstance('Tx_Magenerator_CodeGen_TemplateRenderer');
        /* @var $this->templateRenderer Tx_Magenerator_CodeGen_TemplateRenderer */
    }

   /**
     * The extension config entered by the user in the frontend
     * @param array $extConfig
     */
    protected function setExtConfig($extConfig) {
        $this->extConfig = $extConfig;
        $this->version = $this->extConfig['magenerator_gcommon']['mageversion'];
    }

    /**
     * The extension config entered by the user in the frontend
     * @param Tx_Magenerator_Domain_Model_FeUser $feUser
     */
    protected function setFeUser($feUser) {
        $this->feUser = $feUser;
    }

    /**
     * The extension config entered by the user in the frontend
     * @return Tx_Magenerator_Domain_Model_FeUser $feUser
     */
    protected function getFeUser() {
        return $this->feUser;
    }

    /**
     * compares a version of magento 1_5 or 1_6 or 2_0
     * @param string $version
     * @return boolean
     */
    protected function versionCompare($version){
        return ( $this->version === $version );
    }

    /**
     * gets the name of the user
     * @return string
     */
    protected function getUserExtensionNameSpace() {
        $ns = $this->feUser->getNamespace();
        $extNs = $this->extConfig['magenerator_gcommon']['namespace'];

        $rns = empty($extNs) ? $ns : $extNs;
        $rns = $this->cleanForFileSystem($rns);
        return empty($rns) ? 'NameSpace' : $rns;
    }

    /**
     * gets the codepool
     * @return string
     */
    protected function getCodepool() {
        return $this->getExtConfigValue('magenerator_gcommon/codepool', 'local', true);
    }

    /**
     * gets the extension name
     * @param boolean $toLower
     * @return string
     */
    protected function getExtname($toLower=false) {
        $return = $this->getExtConfigValue('magenerator_gcommon/extname', 'ExtensionName', true);
        if( $toLower ){
            $return = strtolower($return);
        }
        return $return;
    }

    /**
     * gets the extension key with underscore
     * @param boolean $lower if it should be lowercase, default is ucfirst
     * @return string
     */
    public function getExtensionKey($lower = false) {
        $key = $this->getUserExtensionNameSpace() . '_' . $this->getExtname();
        if ($lower) {
            $key = strtolower($key);
        }
        return $key;
    }

    /**
     * gets values from the main config array and can also clean vlaues
     * @param array $arrayKeyPath
     * @param mixed $defaultValue
     * @param boolean $clean
     * @return mixed
     */
    protected function getExtConfigValue($arrayKeyPath, $defaultValue = NULL, $clean = false) {

        $path = t3lib_div::trimExplode('/', $arrayKeyPath);

        if (count($path) == 0) {
            die('ArrayKeyPath is empty');
        }

        $value = $this->getExtConfigWay($this->extConfig, $path);

        if ($clean === true) {
            $value = $this->cleanForFileSystem($value);
        }

        if ($defaultValue !== NULL && ( (is_string($value) && empty($value)) || (is_array($value) && count($value) == 0) )) {
            $value = $defaultValue;
        }

        return $value;
    }

    /**
     * recursive subfunction for getExtConfigValue
     * @param array $extConfigPart part of the extConfig Array to walk thru
     * @param array $path keys of the extConfig Array to return
     * @return string|array
     */
    private function getExtConfigWay($extConfigPart, $path) {


        if (isset($extConfigPart[$path[0]]) && is_array($extConfigPart[$path[0]]) && isset($path[1])) {
            // maybe buggy due to $path[1]
            $return = $this->getExtConfigWay($extConfigPart[$path[0]], array_slice($path, 1));
        }
        else {
            $return = $extConfigPart[$path[0]];
        }

        return $return;
    }

    /**
     * cleans a string so that it can be used as directory or filename
     * removes all invalid chars
     * @param string $string
     * @return string
     */
    protected function cleanForFileSystem($string) {
        return preg_replace('~[^a-z0-9\-_=\.,;@#]+~i', '', $string);
    }

    /**
     *
     * @param array $pathArray
     * @return string the path
     */
    protected function implodePath($pathArray) {
        return implode(DIRECTORY_SEPARATOR, $pathArray);
    }

}
