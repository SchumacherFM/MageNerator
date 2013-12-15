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
class Tx_Magenerator_Controller_LoginStatusController
		extends Tx_Magenerator_Controller_AbstractController
{

	/**
	 * action status
	 *
	 * @return void
	 */
	public function statusAction()
	{

		$user = $this->authService->getCurrentFrontendUser2();

		$this->setNoCacheHeaders();
		$this->view->assign('loginUser', $user);
	}

}
