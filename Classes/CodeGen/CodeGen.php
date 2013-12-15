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
 * code generator class
 *
 * @package magenerator
 * @subpackage service
 */
class Tx_Magenerator_CodeGen_CodeGen
{
    const CODEGEN_PREFIX_CLASS = 'Tx_Magenerator_CodeGen_';

    /**
     * @var Tx_Extbase_Object_ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var array
     */
    protected $settings;

    /**
     * Helper Class with miscellanous functions
     *
     * @var Tx_Magenerator_Helpers_Magento
     */
//    protected $helpersMage;

    /**
     * @var Tx_Magenerator_Service_SessionStorage
     */
    protected $sessionService;

    /**
     * contains all the config
     *
     * @var array
     */
    protected $extConfig;

    /**
     * the constant from the generator class
     * @var string
     */
    protected $typo3temp;

    /**
     *
     * @var Tx_Magenerator_Domain_Model_FeUser
     */
    protected $feUser;

    /**
     *  inits the generator
     * @param $feUser Tx_Magenerator_Domain_Model_FeUser
     */
    public function init(Tx_Magenerator_Domain_Model_FeUser $feUser) {
        $this->feUser = $feUser;
        $this->objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');

//        $omSettings = $this->objectManager->get('Tx_Magenerator_Service_Settings');
//        $this->settings = $omSettings->getSettings();

        $this->sessionService = $this->objectManager->get('Tx_Magenerator_Service_SessionStorage');
//        $this->helpersMage = $this->objectManager->get('Tx_Magenerator_Helpers_Magento');

        $this->extConfig = $this->sessionService->getAll();

        // @todo more checks
        if( !isset($this->extConfig['magenerator_gcommon']) || !isset($this->extConfig['magenerator_gcommon']['namespace']) ){
            throw new Exception('No config found');
        }


    }

    /**
     * generates the whole extenions
     * @return string the abs path to the tar ball
     */
    public function generate() {

        $tmpUserPath = '';
        $tmpBasePath = '';
        $extNsName = '';

        foreach( $this->extConfig as $className=>$config ){

            $className = ucfirst(t3lib_div::underscoredToLowerCamelCase($className));
            $className = self::CODEGEN_PREFIX_CLASS.$className;

            // gcommon class has always to be executed first !!!
            if( class_exists($className) ){
//echo "Running: $className<br>";
                $classObj = t3lib_div::makeInstance($className);
                $classObj->init( $this->feUser, $this->extConfig );
                $classObj->main();

                if($classObj instanceof Tx_Magenerator_CodeGen_MageneratorGcommon){
                    $tmpUserPath = $classObj->getTempPath('abs');
                    $tmpBasePath = $classObj->getTempPath('abs',false,true);
                    $this->typo3temp = $classObj::TYPO3TEMP;
                    $extNsName = $classObj->getExtensionKey();
                }

            }
        }

//        var_dump($this->extConfig);

/*
        $foo = new Tx_Magenerator_Zend_CodeGenerator_Php_Class();

        $foo->setName('Foo')
                ->setExtendedClass('Mage_Core_Model_Abstract')

                ->setProperties(array(
                    array(
                        'name' => '_bar',
                        'visibility' => 'protected',
                        'defaultValue' => 'baz',
                    ),
                    array(
                        'name' => 'baz',
                        'visibility' => 'public',
                        'defaultValue' => 'bat',
                    ),
                    array(
                        'name' => 'bat',
                        'const' => true,
                        'defaultValue' => 'foobarbazbat',
                    ),
                ))
                ->setMethods(array(
                    // Method passed as array
                    array(
                        'name' => 'setBar',
                        'parameters' => array(
                            array('name' => 'bar'),
                        ),
                        'body' => '$this->_bar = $bar;' . "\n" . 'return $this;',
                        'docblock' => new Tx_Magenerator_Zend_CodeGenerator_Php_Docblock(array(
                            'shortDescription' => 'Set the bar property',
                            'tags' => array(
                                new Tx_Magenerator_Zend_CodeGenerator_Php_Docblock_Tag_Param(array(
                                    'paramName' => 'bar',
                                    'datatype' => 'string'
                                )),
                                new Tx_Magenerator_Zend_CodeGenerator_Php_Docblock_Tag_Return(array(
                                    'datatype' => 'string',
                                )),
                            ),
                        )),
                    ),
                    // Method passed as concrete instance
                    new Tx_Magenerator_Zend_CodeGenerator_Php_Method(array(
                        'name' => 'getBar',
                        'body' => 'return $this->_bar;',
                        'docblock' => new Tx_Magenerator_Zend_CodeGenerator_Php_Docblock(array(
                            'shortDescription' => 'Retrieve the bar property',
                            'tags' => array(
                                new Tx_Magenerator_Zend_CodeGenerator_Php_Docblock_Tag_Return(array(
                                    'datatype' => 'string|null',
                                )),
                            ),
                        )),
                    )),
                ));

        //	echo '<pre>' . $foo->generate() . '</pre>';
*/
        $cleanWorkingDir = !(t3lib_div::getIndpEnv('HTTP_HOST')=='magenerator.local');
        return array(
            'downloadUrl' => $this->getTarball($tmpUserPath,$tmpBasePath,$cleanWorkingDir),
            'extNsName' => $extNsName,
        );
    }

    /**
     * @param string
     * @param string
     * @param boolean $cleanWorkingDir
     * @return string tarball file name
     */
    protected function getTarball($tmpUserPath,$tmpBasePath,$cleanWorkingDir=true){

        if( empty($tmpUserPath) ||
            empty($tmpBasePath) ||
            stristr($tmpUserPath,PATH_site)===false ||
            stristr($tmpBasePath,PATH_site)===false
        ){
                throw new Exception('tmpPaths are empty or have incorrect values');
        }

        $ball = $this->getTarBallName();

        chdir($tmpUserPath);
        $tarCmd = 'tar -czf '.$tmpBasePath.$ball.' *';
        $output = array();
        exec($tarCmd, $output);

        if( $cleanWorkingDir ){
            exec('rm -Rf '.$tmpUserPath);
        }

        return $ball;
    }

    /**
     * gets the name of the tarball
     * @return type
     */
    protected function getTarBallName() {

        $userExtensionNameSpace = $this->extConfig['magenerator_gcommon']['namespace'];
        $extname = $this->extConfig['magenerator_gcommon']['extname'];

        $userExtensionNameSpace = empty($userExtensionNameSpace) ? 'NS-'.time() : $userExtensionNameSpace;
        $extname = empty($extname) ? 'Ext-'.time() : $extname;

        return str_pad($this->feUser->getUid(), 10, '0', STR_PAD_LEFT) . '_' .
                $userExtensionNameSpace . '_' . $extname . '.tar.gz';
    }

    /**
     *
     * @param boolean $addlastDS if true a last directory seperator will be added
     * @return string
     */
    public function getTYPO3TEMP($addlastDS = true){
        return $this->typo3temp . ($addlastDS ? DIRECTORY_SEPARATOR : '');
    }


}
