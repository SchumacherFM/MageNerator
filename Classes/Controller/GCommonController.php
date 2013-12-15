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
class Tx_Magenerator_Controller_GCommonController
		extends Tx_Magenerator_Controller_AbstractController
{

	const SESSION_SERVICE_KEY = 'gcommon';

	/**
	 * extnsRepository
	 *
	 * @var Tx_Magenerator_Domain_Repository_ExtnsRepository
	 */
	protected $extnsRepository;

	/**
	 * injectextnsRepository
	 *
	 * @param Tx_Magenerator_Domain_Repository_ExtnsRepository $extnsRepository
	 * @return void
	 */
	public function injectExtnsRepository(Tx_Magenerator_Domain_Repository_ExtnsRepository $extnsRepository)
	{
		$this->extnsRepository = $extnsRepository;
	}

	/**
	 * action commonProperties
	 *
	 * @param none
	 * @return void
	 */
	public function listAction()
	{

		$this->getSessionServiceObjectAndAssign2View(self::SESSION_SERVICE_KEY);

		$this->view->assign('dependsNs', $this->extnsRepository->findAll());
		$this->view->assign('codepools', $this->helpersMage->getCodepools());
		$this->view->assign('mageVersionAll', $this->helpersMage->getAvailableVersions());
	}

	/**
	 * action commonPropertiesSave
	 *
	 * @param none
	 * @return void
	 */
	public function saveAction()
	{
        $this->checkLogin();


        $args = $this->getArgs();

        // @todo check here for valid values ...
        // because fluid handles select boxes different ...
        $args['selectedSysExt'] = array_combine($args['selectedSysExt'], $args['selectedSysExt']);
        $args['selectedDeactivatedSysExt'] = array_combine(
            $args['selectedDeactivatedSysExt'], $args['selectedDeactivatedSysExt']
        );

        $this->sessionService->storeObject($args, self::SESSION_SERVICE_KEY);

        $this->returnMsgJsonViewAssign('formSaved',false,'alert');

	}

	/**
	 * action commonProperties ajax get extension names
	 *
	 * @param integer uid
	 * @return void
	 */
	public function ajaxExtNamesAction($uid = null)
	{

		$uid = (int) $uid;
		$extensions = array();
		if ($uid > 0) {
			$extns = $this->extnsRepository->findByUid($uid);
			if ($extns) {
				$extname = $extns->getExtensions();
				foreach ($extname as $name) {
					$extensions[$name->getUid()] = $name->getName();
				}
			}
		}
        $this->viewAssignJson($extensions);
	}

}
