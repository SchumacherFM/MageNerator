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
 *
 * just for displaying the generated extensions
 * and for loading
 *
 * @package magenerator
 * @subpackage controller
 */
class Tx_Magenerator_Controller_UserExtensionsController
		extends Tx_Magenerator_Controller_AbstractController
{
    /**
     * @var Tx_Magenerator_Domain_Repository_UserExtensionsRepository
     * @inject
     */
    protected $userExtensionsRepository;

	/**
	 * action top
	 *
	 * @return void
	 */
	public function topAction()
	{
		$userExtensionss = $this->userExtensionsRepository->findTop();

		$this->view->assign('top', $userExtensionss);
	}

}
