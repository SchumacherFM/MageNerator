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
 * @package magenerator
 * @subpackage controller
 */
abstract class Tx_Magenerator_Controller_AbstractController extends Tx_Extbase_MVC_Controller_ActionController
{

    /**
     * used for addError Method and in checkFeUser
     * @var array
     */
    protected $myErrors = array();

    /**
     * Helper Class with miscellanous functions
     *
     * @var Tx_Magenerator_Helpers_Magento
     * @inject
     */
    protected $helpersMage;

    /**
     * general action init method
     *
     * return nothing
     */
    public function initializeAction() {
        parent::initializeAction();
    }

    /**
     * @var Tx_Magenerator_Service_SessionStorage
     * @inject
     */
    protected $sessionService;

    /**
     * @var Tx_Magenerator_Service_Auth
     * @inject
     */
    protected $authService;

    /**
     * @var Tx_Magenerator_Service_Json
     * @inject
     */
    protected $jsonService;

    /**
     * feUserRepository
     *
     * @var Tx_Magenerator_Domain_Repository_FeUserRepository
     * @inject
     */
    protected $feUserRepository;

    /**
     * internal storage for feuser
     *
     * @var Tx_Magenerator_Domain_Model_FeUser
     */
    private $feUser = false;

    /**
     * singleton method for getting the feuser
     * @return Tx_Magenerator_Domain_Model_FeUser
     */
    protected function getFeUser() {

        if (!$this->feUser || !is_object($this->feUser) || empty($this->feUser)) {

            $this->feUser = $this->authService->getCurrentFrontendUser();

            if (!$this->feUser || !is_object($this->feUser) || empty($this->feUser)) {
                $this->feUser = t3lib_div::makeInstance('Tx_Magenerator_Domain_Model_FeUser');
            }
        }

        return $this->feUser;
    }

    /**
     * @var Tx_Fed_Service_Email
     * @inject
     */
//	protected $emailService;

    /**
     * translate service
     *
     * @param string $key
     * @param array $arguments optional
     * @return translated string
     */
    protected function translate($key, $arguments = NULL) {
        $extensionName = $this->request->getControllerExtensionName();
        return Tx_Extbase_Utility_Localization::translate($key, $extensionName, $arguments);
    }

    /**
     * gets all the posted arguments, exits if nothing has been posted
     * @return array
     */
    protected function getArgs() {
        $args = $this->request->getArguments();
        unset($args['controller']);
        unset($args['action']);

        foreach ($args as $k => $v) {
            $v = NULL;
            if (strstr($k, 'uns_') !== false) {
                unset($args[$k]);
            }
        }

        // GModel removements
        if (isset($args['sql']) && isset($args['sql']['###tablex###'])) {
            unset($args['sql']['###tablex###']);
        }

        if (count($args) == 0) {
            $this->returnMsgJson('noDataPosted', true, 'alert');
        }

        return $args;
    }

    /**
     * sets the no cache headers
     * @param none
     * @return Tx_Extbase_MVC_Web_Response
     */
    protected function setNoCacheHeaders() {
        $this->response->setHeader('Cache-Control', 'no-cache, must-revalidate');
        $this->response->setHeader('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
        $this->response->setHeader('Pragma', 'no-cache');
        return $this->response;
    }

    /**
     * checks if user is loggedin, if not exists and returns json message
     *
     * @return Tx_Magenerator_Domain_Model_FeUser
     */
    protected function checkLogin() {

        $user = $this->getFeUser();
        $msg = array();

        if (!$user || !is_object($user) || $user->getUid()===NULL || (int)$user->getUid() === 0 ) {
            $this->returnMsgJson('loggedout', true, 'alert');
        }

        return $user;
    }

    /**
     * encodes an array into json format and assigns it to the view
     * sets also the header for app/json
     *
     * @param array $array
     * @return void
     */
    protected function viewAssignJson($array) {
        $this->response->setHeader('Content-Type', 'application/json');
        $this->view->assign('json', $this->jsonService->encode($array));
    }

    /**
     * creates a return msg, sends json header and assign the json object to the view
     * @param string $key           name of the key OR will become text
     * @param boolean $isError      if error msg then true
     * @param string $prefix        optional locallang prefix or will become header if empty then controllername will be used
     * @param array $argsHeader     optional additional args used in conjunction with %s
     * @param array $argsText       optional additional args used in conjunction with %s
     * @return array
     */
    protected function returnMsgJsonViewAssign($key, $isError = false, $prefix = NULL, $argsHeader = NULL, $argsText = NULL) {
        $msg = $this->returnMsg($key, $isError, $prefix, $argsHeader, $argsText);
        $this->setNoCacheHeaders();
        $this->response->setHeader('Content-Type', 'application/json');
        $this->view->assign('json', $this->jsonService->encode($msg));
    }

    /**
     * outputs an error as a json string and exits the script
     * @param string $key           name of the key OR will become text
     * @param boolean $isError      if error msg then true
     * @param string $prefix        optional locallang prefix or will become header if empty then controllername will be used
     * @param array $argsHeader     optional additional args used in conjunction with %s
     * @param array $argsText       optional additional args used in conjunction with %s
     * @return array
     */
    protected function returnMsgJson($key, $isError = false, $prefix = NULL, $argsHeader = NULL, $argsText = NULL) {
        $msg = $this->returnMsg($key, $isError, $prefix, $argsHeader, $argsText);
        echo $this->jsonService->encode($msg);
        exit;
    }

    /**
     *  returns an array formatted for json output to display notifications to a user
     * @param string $key   name of the key OR will become text
     * @param boolean $isError      if error msg then true
     * @param string $prefix        optional locallang prefix or will become header if empty then controllername will be used
     * @param array $argsHeader     optional additional args used in conjunction with %s
     * @param array $argsText       optional additional args used in conjunction with %s
     * @return array
     */
    protected function returnMsg($key, $isError = false, $prefix = NULL, $argsHeader = NULL, $argsText = NULL) {

        if (empty($prefix)) {
            $prefix = $this->request->getControllerName();
        }

        $eKey = $isError === false ? 'success' : 'error';
        $header = implode('.', array(
            $prefix, $eKey, $key, 'head'
                ));
        $text = implode('.', array(
            $prefix, $eKey, $key, 'text'
                ));

        // feuser.error.salting.head
        // feuser.error.salting.text

        $header2 = $this->translate($header, $argsHeader);
        $text2 = $this->translate($text, $argsText);
        if (empty($header2)) {
            $header2 = $prefix;
        }
        if (empty($text2)) {
            $text2 = $key;
        }

        $return = array();
        $return['isError'] = (boolean) $isError;
        $return['header'] = $header2;
        $return['text'] = $text2;
        return $return;
    }

    /**
     * gets the stored values from a FE session and assigns the keys as variables
     * to the flud template.
     * @param string $sessionServiceKey
     * @return nothing
     */
    protected function getSessionServiceObjectAndAssign2View($sessionServiceKey) {
        if ($this->authService->assertFrontendUserLoggedIn()) {
            $args = $this->sessionService->getObject($sessionServiceKey);

            if (!$args || !is_array($args) ) {
                $args = array('namespace' => $this->getFeUser()->getNamespace());
            }
            $this->view->assignMultiple($args);
        }
    }

    /**
     * gets the stored values from a FE session and assigns the keys as variables
     * to the flud template.
     * @param string $sessionServiceKey
     * @return nothing
     */
    protected function getModelsFromSession() {
        $models = array(
            '' => $this->translate('con.select.noModel'),
        );

        if ($this->authService->assertFrontendUserLoggedIn()) {
            $args = $this->sessionService->getObject('gmodel');


            if (isset($args['blankModels'])) {
                foreach ($args['blankModels'] as $bmk => $bmv) {
                    $models[$bmk] = $bmv;
                }
            }

            if (isset($args['sql'])) {

                foreach ($args['sql'] as $modelName => $cols) {
                    $models[$modelName] = $modelName;
                }
            }
        }
        return $models;
    }

    /**
     *
     * @param array $arrayFirst
     * @param array $arraySecond
     * @return array
     */
    protected function MergeArrays($arrayFirst, $arraySecond) {
        foreach ($arraySecond as $key => $val) {
            if (array_key_exists($key, $arrayFirst) && is_array($val)) {
                $arrayFirst[$key] = $this->MergeArrays($arrayFirst[$key], $arraySecond[$key]);
            }
            else {
                $arrayFirst[$key] = $val;
            }
        }

        return $arrayFirst;
    }

    /**
     * Persist all data that was not stored by now
     *
     * @return void
     */
    protected function persistAll() {
        $this->objectManager->get('Tx_Extbase_Persistence_Manager')->persistAll();
    }

    /**
     * generates a random invite ID from TS settings config
     * @return string
     */
    protected function generateInviteId() {

        $length = (int) $this->settings['invites']['length'];
        $length = $length == 0 ? 10 : $length;
        $characters = $this->settings['invites']['generateFromChars'];
        $characters = empty($characters) ? '0123456789abcdefghijklmnopqrstuvwxyz' : $characters;
        $string = '';
        for ($p = 0; $p < $length; $p++) {
            $string .= $characters[mt_rand(0, strlen($characters))];
        }
        return $string;
    }

}
