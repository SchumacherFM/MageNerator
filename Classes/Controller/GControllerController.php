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
 * displays the forms
 *
 * @package magenerator
 * @subpackage controller
 */
class Tx_Magenerator_Controller_GControllerController extends Tx_Magenerator_Controller_AbstractController
{

	const SESSION_SERVICE_KEY_FE = 'gcontrollerfe';
	const SESSION_SERVICE_KEY_BE = 'gcontrollerbe';

	/**
	 *
	 * @param array $config
	 * @return array
	 */
	private function getNav($config = array())
	{

		$navPillsLinkAction = array(
			'fe' => array(
				'type' => 510,
				'noCacheHash' => 1,
				'noCache' => 1,
				'action' => 'listFE',
				'controller' => 'GController',
				'name' => $this->translate('con.fe'),
				'liClass' => '',
				'aClass' => 'ajaxLoader',
			),
			'be' => array(
				'type' => 510,
				'noCacheHash' => 1,
				'noCache' => 1,
				'action' => 'listBE',
				'controller' => 'GController',
				'name' => $this->translate('con.be'),
				'liClass' => '',
				'aClass' => 'ajaxLoader',
			),
		);

		return $this->MergeArrays($navPillsLinkAction, $config);
	}

	/**
	 * action commonProperties
	 *
	 * @param none
	 * @return void
	 */
	public function listFEAction()
	{

		$this->getSessionServiceObjectAndAssign2View(self::SESSION_SERVICE_KEY_FE);

		$navPillsLinkAction = $this->getNav(array('fe' => array('liClass' => 'active')));

		$this->view->assign('navPillsLinkAction', $navPillsLinkAction);
		$this->view->assign('models', $this->getModelsFromSession());
	}

	/**
	 * action commonProperties
	 *
	 * @param none
	 * @return void
	 */
	public function listBEAction()
	{

		$this->getSessionServiceObjectAndAssign2View(self::SESSION_SERVICE_KEY_BE);

		$navPillsLinkAction = $this->getNav(array('be' => array('liClass' => 'active')));

		$this->view->assign('navPillsLinkAction', $navPillsLinkAction);
		$this->view->assign('models', $this->getModelsFromSession());
	}

	/**
	 * action commonPropertiesSave
	 *
	 * @param none
	 * @return void
	 */
	public function saveFEAction()
	{
        $this->checkLogin();

        $args = $this->getArgs();

        $msg = $this->returnMsg('Holla','<pre>' . var_export($this->getArgs(), 1) . '</pre>');

        die(json_encode($msg));
//
        // @todo check here for valid values ... maybe ...
        // because fluid handles select boxes different ... no it does not! where using
        // the value as text field so therefore both must be equal
        $args['blankModels'] = array_combine($args['blankModels'], $args['blankModels']);

        $this->sessionService->storeObject($args, self::SESSION_SERVICE_KEY);

        $this->returnMsgJsonViewAssign('formSaved',false,'alert');

	}

	/**
	 * ajax: checks if the router name hasn't been used before
	 *
	 * @return true of false in json style
	 */
	public function ajaxCheckRouterNameAction()
	{

		$search = $this->request->getArgument('search');

		$extnameRepository = new Tx_Magenerator_Domain_Repository_ExtnameRepository();

		$nameExists = $extnameRepository->existsFrontNames($search);

		$return = array('isError' => false);
		if ($nameExists->count() > 0) {

            $extName = $nameExists->getFirst()->getName();

            $return = $this->returnMsg('CheckRouterName', true, NULL,null,array($extName));

		}
        $this->viewAssignJson($return);
	}

}
