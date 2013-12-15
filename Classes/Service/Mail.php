<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Sebastian Fischer <typo3@evoweb.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Service to handle mail sending
 *
 * @package magenerator
 * @subpackage service
 */
class Tx_Magenerator_Service_Mail implements t3lib_Singleton {
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
	 * Inject object manager
	 *
	 * @param Tx_Extbase_Object_ObjectManagerInterface $objectManager
	 * @return Tx_Magenerator_Services_Mail
	 */
	public function injectObjectManager(Tx_Extbase_Object_ObjectManagerInterface $objectManager) {
		$this->objectManager = $objectManager;
	}

	/**
	 * Inject configuration manager
	 *
	 * @param Tx_Extbase_Configuration_ConfigurationManagerInterface $configurationManager
	 * @return Tx_Magenerator_Services_Mail
	 */
	public function injectConfigurationManager(Tx_Extbase_Configuration_ConfigurationManagerInterface $configurationManager) {
		$this->configurationManager = $configurationManager;
		$this->settings = $this->configurationManager->getConfiguration(Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS);
		$this->frameworkConfiguration = $configurationManager->getConfiguration(Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
	}

	/**
	 * Send an email notification pre activation to the admin
	 *
	 * @param Tx_Magenerator_Domain_Model_FeUser $user
	 * @return integer the number of recipients who were accepted for delivery
	 */
	public function sendAdminNotificationMailCreate(Tx_Magenerator_Domain_Model_FeUser $user) {

		$type = str_replace('send', '', __FUNCTION__);
		$variables = array('user' => $user);

		$mailResult = $this->sendEmail(
			$this->getAdminRecipient(),
			'adminEmail',
			$this->getSubject($user, 'subject' . $type),
			$this->renderFileTemplate(NULL, NULL, $type, $variables)
		);

		return $mailResult;
	}

	/**
	 * Send an email notification post activation to the admin
	 *
	 * @param Tx_Magenerator_Domain_Model_FeUser $user
	 * @return integer the number of recipients who were accepted for delivery
	 */
	public function XXsendAdminNotificationMailPostActivation(Tx_Magenerator_Domain_Model_FeUser $user) {
		$type = str_replace('send', '', __FUNCTION__);
		$variables = array('user' => $user);

		$mailResult = $this->sendEmail(
			$this->getAdminRecipient(),
			'adminEmail',
			$this->getSubject($user, 'subject' . $type),
			$this->renderFileTemplate('FeuserEdit', 'form', $type, $variables)
		);

		return $mailResult;
	}

	/**
	 * Send an email on registration request to activate the user by admin
	 *
	 * @param Tx_Magenerator_Domain_Model_FeUser $user
	 * @return integer the number of recipients who were accepted for delivery
	 */
	public function XXsendAdminNotificationMailPreActivation(Tx_Magenerator_Domain_Model_FeUser $user) {
		$type = str_replace('send', '', __FUNCTION__);
		$variables = array('user' => $user);

		$user->setMailhash($this->getMailHash($user));

		$mailResult = $this->sendEmail(
			$this->getAdminRecipient(),
			'adminEmail',
			$this->getSubject($user, 'subject' . $type),
			$this->renderFileTemplate('FeuserCreate', 'confirm', $type, $variables)
		);

		return $mailResult;
	}

	/**
	 * Send an email notification pre activation to the user
	 *
	 * @param Tx_Magenerator_Domain_Model_FeUser $user
	 * @return integer the number of recipients who were accepted for delivery
	 */
	public function sendUserNotificationMailCreate(Tx_Magenerator_Domain_Model_FeUser $user) {
		$type = str_replace('send', '', __FUNCTION__);
		$variables = array('user' => $user);

		$mailResult = $this->sendEmail(
			$this->getUserRecipient($user),
			'userEmail',
			$this->getSubject($user, 'subject' . $type),
			$this->renderFileTemplate('FeuserCreate', 'form', $type, $variables)
		);

		return $mailResult;
	}

	/**
	 * Send an email notification post activation to the user
	 *
	 * @param Tx_Magenerator_Domain_Model_FeUser $user
	 * @return integer the number of recipients who were accepted for delivery
	 */
	public function XXsendUserNotificationMailPostActivation(Tx_Magenerator_Domain_Model_FeUser $user) {
		$type = str_replace('send', '', __FUNCTION__);
		$variables = array('user' => $user);

		$mailResult = $this->sendEmail(
			$this->getUserRecipient($user),
			'userEmail',
			$this->getSubject($user, 'subject' . $type),
			$this->renderFileTemplate('FeuserEdit', 'form', $type, $variables)
		);

		return $mailResult;
	}

	/**
	 * Send an email on registration request to activate the user by user
	 *
	 * @param Tx_Magenerator_Domain_Model_FeUser $user
	 * @return integer the number of recipients who were accepted for delivery
	 */
	public function XXsendUserNotificationMailPreActivation(Tx_Magenerator_Domain_Model_FeUser $user) {
		$type = str_replace('send', '', __FUNCTION__);
		$variables = array('user' => $user);

		$user->setMailhash($this->getMailHash($user));

		$mailResult = $this->sendEmail(
			$this->getUserRecipient($user),
			'userEmail',
			$this->getSubject($user, 'subject' . $type),
			$this->renderFileTemplate('FeuserCreate', 'confirm', $type, $variables)
		);

		return $mailResult;
	}


	/**
	 * Send an email after edit to activate the user by admin
	 *
	 * @param Tx_Magenerator_Domain_Model_FeUser $user
	 * @return integer the number of recipients who were accepted for delivery
	 */
	public function XXsendAdminActivationMailAfterEdit(Tx_Magenerator_Domain_Model_FeUser $user) {
		$type = str_replace('send', '', __FUNCTION__);
		$variables = array('user' => $user);

		$user->setMailhash($this->getMailHash($user));

		$mailResult = $this->sendEmail(
			$this->getUserRecipient($user),
			'userEmail',
			$this->getSubject($user, 'subject' . $type),
			$this->renderFileTemplate('FeuserCreate', 'confirm', $type, $variables)
		);

		return $mailResult;
	}

	/**
	 * Send an email after edit to activate the user by user
	 *
	 * @param Tx_Magenerator_Domain_Model_FeUser $user
	 * @return integer the number of recipients who were accepted for delivery
	 */
	public function XXsendUserActivationMailAfterEdit(Tx_Magenerator_Domain_Model_FeUser $user) {
		$type = str_replace('send', '', __FUNCTION__);
		$variables = array('user' => $user);

		$user->setMailhash($this->getMailHash($user));

		$mailResult = $this->sendEmail(
			$this->getUserRecipient($user),
			'userEmail',
			$this->getSubject($user, 'subject' . $type),
			$this->renderFileTemplate('FeuserCreate', 'confirm', $type, $variables)
		);

		return $mailResult;
	}

	/**
	 * Send an email after edit to notify the admin
	 *
	 * @param Tx_Magenerator_Domain_Model_FeUser $user
	 * @return integer the number of recipients who were accepted for delivery
	 */
	public function XXsendAdminNotificationMailAfterEdit(Tx_Magenerator_Domain_Model_FeUser $user) {
		$type = str_replace('send', '', __FUNCTION__);
		$variables = array('user' => $user);

		$mailResult = $this->sendEmail(
			$this->getAdminRecipient(),
			'adminEmail',
			$this->getSubject($user, 'subject' . $type),
			$this->renderFileTemplate('FeuserCreate', 'form', $type, $variables)
		);

		return $mailResult;
	}

	/**
	 * Send an email after edit to notify the user
	 *
	 * @param Tx_Magenerator_Domain_Model_FeUser $user
	 * @return integer the number of recipients who were accepted for delivery
	 */
	public function XXsendUserNotificationMailAfterEdit(Tx_Magenerator_Domain_Model_FeUser $user) {
		$type = str_replace('send', '', __FUNCTION__);
		$variables = array('user' => $user);

		$mailResult = $this->sendEmail(
			$this->getAdminRecipient(),
			'adminEmail',
			$this->getSubject($user, 'subject' . $type),
			$this->renderFileTemplate('FeuserCreate', 'form', $type, $variables)
		);

		return $mailResult;
	}

	/**
	 * Send an email notification for the contact form to the admin
	 *
	 * @param array $contact
	 * @return integer the number of recipients who were accepted for delivery
	 */
	public function sendAdminContactMail($contact) {

        $contact = $this->addEnvVars($contact);

		$type = str_replace('send', '', __FUNCTION__); // AdminContactMail ;-)
		$variables = array('contact' => $contact);

		$mailResult = $this->sendEmail(
			$this->getAdminRecipient(),
			'adminEmail',   /* a value defined in the TS */
			$this->getSubject(NULL, 'subject' . $type),
			$this->renderFileTemplate(NULL, NULL, $type, $variables)
		);

		return $mailResult;
	}

    /**
     *
     * @param type $array
     */
    public function addEnvVars($array = array() ){
        $array['IP'] = t3lib_div::getIndpEnv('REMOTE_ADDR');
        $array['Host'] = t3lib_div::getIndpEnv('REMOTE_HOST');
        $array['User-Agent'] = t3lib_div::getIndpEnv('HTTP_USER_AGENT');
        $array['Referer'] = t3lib_div::getIndpEnv('HTTP_REFERER');
        $array['Forwarded'] = t3lib_div::getIndpEnv('HTTP_X_FORWARDED_FOR');
        $array['Date'] = date('Y-m-d H:i:s');
        return $array;
    }

    /**
	 * Get translated version of the subject with replaced username and sitename
	 *
	 * @param Tx_Magenerator_Domain_Model_FeUser $user
	 * @param array $arguments
	 * @return string
	 */
	protected function getSubject($arguments = NULL, $labelIndex) {
		return Tx_Extbase_Utility_Localization::translate(
			$labelIndex,
			'magenerator',
			array($this->settings['sitename'], $arguments )
		);
	}

	/**
	 * Get the mailhash for the activation link based on time, username and email address
	 *
	 * @param Tx_Magenerator_Domain_Model_FeUser $user
	 * @return string
	 */
	protected function getMailHash(Tx_Magenerator_Domain_Model_FeUser $user) {
		return md5($GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'] . $user->getUsername() . time() . $user->getEmail());
	}

	/**
	 * Get admin recipient
	 *
	 * @return string
	 */
	protected function getAdminRecipient() {
		return array(
			trim($this->settings['adminEmail']['toEmail']) => trim($this->settings['adminEmail']['toName'])
		);
	}

	/**
	 * Get user recipient
	 *
	 * @param Tx_Magenerator_Domain_Model_FeUser $user
	 * @return string
	 */
	protected function getUserRecipient(Tx_Magenerator_Domain_Model_FeUser $user) {
		if ($user->getFirstName() || $user->getLastName()) {
			$name = trim($user->getFirstName() . ' ' . $user->getLastName());
		} else {
			$name = trim($user->getUsername());
		}

		return array(
			trim($user->getEmail()) => $name
		);
	}


	/**
	 * Send email
	 *
	 * @param array $recipient
	 * @param string $typeOfEmail
	 * @param string $subject
	 * @param string $bodyHTML
	 * @param string $bodyPlain
	 * @return integer the number of recipients who were accepted for delivery
	 */
	protected function sendEmail(array $recipient, $typeOfEmail, $subject, $bodyHTML, $bodyPlain = '') {

		/** @var $mail t3lib_mail_Message */
		$mail = t3lib_div::makeInstance('t3lib_mail_Message');
		$mail
			->setTo($recipient)
			->setFrom(array($this->settings[$typeOfEmail]['fromEmail'] => $this->settings[$typeOfEmail]['fromName']))
			->setReplyTo(array($this->settings[$typeOfEmail]['replyEmail'] => $this->settings[$typeOfEmail]['replyName']))
			->setSubject($subject);

		if ($bodyHTML !== '') {
			$mail->addPart($bodyHTML, 'text/html');
		}
		if ($bodyPlain !== '') {
			$mail->addPart($bodyPlain, 'text/plain');
		}

		return $mail->send();
	}


	/**
	 * Get template path and filename
	 *
	 * @param string $templateName
	 * @return string
	 */
	protected function getTemplatePathAndFilename($templateName) {
		return $this->getAbsoluteTemplateRootPath() . 'Email/' . $templateName . '.html';
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
			$templateRootPath = t3lib_extMgm::extPath('magenerator') . 'Resources/Private/Templates/';
		}

		$templateRootPath = t3lib_div::getFileAbsFileName($templateRootPath);
		if (t3lib_div::isAllowedAbsPath($templateRootPath)) {
			$result = $templateRootPath . (substr($templateRootPath, -1) !== '/' ? '/' : '');
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
			$layoutRootPath = t3lib_extMgm::extPath('magenerator') . 'Resources/Private/Layout/';
		}

		$layoutRootPath = t3lib_div::getFileAbsFileName($layoutRootPath);
		if (t3lib_div::isAllowedAbsPath($layoutRootPath)) {
			$result = $layoutRootPath . (substr($layoutRootPath, -1) !== '/' ? '/' : '');
		}

		return $result;
	}

	/**
	 * renders the given Template file via fluid rendering engine.
	 *
	 * @param string $controller
	 * @param string $action
	 * @param string $type type of template
	 * @param array $variables array of all variables you want to assgin to the view
	 * @return string of the rendered View.
	 */
	protected function renderFileTemplate($controller = NULL, $action = NULL, $type, array $variables) {
		$view = $this->objectManager->get('Tx_Fluid_View_StandaloneView');
		$view->setTemplatePathAndFilename($this->getTemplatePathAndFilename($type));
		$view->setLayoutRootPath($this->getAbsoluteLayoutRootPath());
		$view->assignMultiple($variables);

//		$request = $view->getRequest();
//		$request->setControllerExtensionName($this->frameworkConfiguration['extensionName']);
//		$request->setPluginName($this->frameworkConfiguration['pluginName']);
//		$request->setControllerName($controller);
//		$request->setControllerActionName($action);

		return $view->render();
	}

}
