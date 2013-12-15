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
class Tx_Magenerator_Controller_GModelsController
		extends Tx_Magenerator_Controller_AbstractController
{

	const SESSION_SERVICE_KEY = 'gmodel';

	/**
	 * action commonProperties
	 *
	 * @param none
	 * @return void
	 */
	public function listAction()
	{

		$this->getSessionServiceObjectAndAssign2View(self::SESSION_SERVICE_KEY);

		$this->view->assign('sqlColumnTypes', $this->helpersMage->getSqlColumnTypes());
		$this->view->assign('modelsColumnCount', $this->helpersMage->getModelsColumnCount());
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

//		$msg['header'] = 'Holla';
//		$msg['text'] = '<pre>'.var_export( $this->getArgs(), 1).'</pre>';
//		die(json_encode($msg));


        // @todo check here for valid values ... maybe ...
        // because fluid handles select boxes different ... no it does not! where using
        // the value as text field so therefore both must be equal
        $args['blankModels'] = array_combine($args['blankModels'], $args['blankModels']);

        $this->sessionService->storeObject($args, self::SESSION_SERVICE_KEY);


        $this->returnMsgJsonViewAssign('formSaved',false,'alert');



	}

}
