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
 * code template renderer via fluid
 *
 * @package magenerator
 * @subpackage service/codegen
 */
class Tx_Magenerator_CodeGen_TemplateRenderer implements t3lib_Singleton {

    const TEMPLATE_FOLDER = 'CodeGen';
    const TEMPLATE_SUFFIX = '.phpt';
    const EXTENSION_NAME = 'magenerator';

	/**
	 * Object manager
	 *
	 * @var Tx_Extbase_Object_ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * Configuration manager
	 *
	 * @var Tx_Extbase_Configuration_ConfigurationManagerInterface
	 */
	protected $configurationManager;

	/**
	 * Settings of the create controller
	 *
	 * @var array
	 */
	protected $settings = array();

	/**
	 * Framework configurations
	 *
	 * @var array
	 */
	protected $frameworkConfiguration = array();

    /**
     *
     * @param Tx_Extbase_Object_ObjectManagerInterface $objectManager
     * @param Tx_Extbase_Configuration_ConfigurationManagerInterface $configurationManager
     */
    public function __construct(Tx_Extbase_Object_ObjectManagerInterface $objectManager = NULL, Tx_Extbase_Configuration_ConfigurationManagerInterface $configurationManager = NULL) {
        $this->objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
        /* @var $this->objectManager Tx_Extbase_Object_ObjectManager */

		$this->configurationManager = t3lib_div::makeInstance('Tx_Extbase_Configuration_ConfigurationManager');
        /* @var $this->configurationManager Tx_Extbase_Configuration_ConfigurationManager */
		$this->settings = $this->configurationManager->getConfiguration(Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS);
		$this->frameworkConfiguration = $this->configurationManager->getConfiguration(Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);

    }

	/**
	 * Get template path and filename
	 *
	 * @param string $templateName
	 * @return string
	 */
	protected function getTemplatePathAndFilename($templateName) {
		return $this->getAbsoluteTemplateRootPath() . self::TEMPLATE_FOLDER . DIRECTORY_SEPARATOR .
               $templateName . self::TEMPLATE_SUFFIX;
	}

	/**
	 * Get absolute template root path
	 *
	 * @return string
	 */
	protected function getAbsoluteTemplateRootPath() {
		$result = '';
		$templateRootPath = trim($this->settings['templateRootPath']);

		if ($templateRootPath === '') {
			$templateRootPath = trim($this->frameworkConfiguration['view']['templateRootPath']);
		}

		if ($templateRootPath === '') {
			$templateRootPath = t3lib_extMgm::extPath( self::EXTENSION_NAME ) . 'Resources/Private/Templates/';
		}

		$templateRootPath = t3lib_div::getFileAbsFileName($templateRootPath);
		if (t3lib_div::isAllowedAbsPath($templateRootPath)) {
			$result = $templateRootPath . (substr($templateRootPath, -1) !== DIRECTORY_SEPARATOR ? DIRECTORY_SEPARATOR : '');
		}

		return $result;
	}

	/**
	 * Get absolute layout root path
	 *
	 * @return string
	 */
	protected function getAbsoluteLayoutRootPath() {
		$result = '';
		$layoutRootPath = trim($this->settings['layoutRootPath']);

		if ($layoutRootPath === '') {
			$layoutRootPath = trim($this->frameworkConfiguration['view']['layoutRootPath']);
		}

		if ($layoutRootPath === '') {
			$layoutRootPath = t3lib_extMgm::extPath( self::EXTENSION_NAME ) . 'Resources/Private/Layout/';
		}

		$layoutRootPath = t3lib_div::getFileAbsFileName($layoutRootPath);
		if (t3lib_div::isAllowedAbsPath($layoutRootPath)) {
			$result = $layoutRootPath . (substr($layoutRootPath, -1) !== DIRECTORY_SEPARATOR ? DIRECTORY_SEPARATOR : '');
		}

		return $result;
	}

	/**
	 * renders the given Template file via fluid rendering engine.
	 *
	 * @param string $type type of template
	 * @param array $variables array of all variables you want to assgin to the view
	 * @return string of the rendered View.
	 */
	public function renderFileTemplate($type, array $variables) {
		$view = $this->objectManager->get('Tx_Fluid_View_StandaloneView');
        /* @var $view Tx_Fluid_View_StandaloneView */
		$view->setTemplatePathAndFilename($this->getTemplatePathAndFilename($type));
		$view->setLayoutRootPath($this->getAbsoluteLayoutRootPath());
		$view->assignMultiple($variables);
		return $view->render();
	}

}
