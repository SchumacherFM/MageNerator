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
 * generates the code
 *
 * @package magenerator
 * @subpackage controller
 */
class Tx_Magenerator_Controller_GenerateController extends Tx_Magenerator_Controller_AbstractController
{

    /**
     *
     * @var Tx_Magenerator_Domain_Repository_GenerateCounterRepository
     * @inject
     */
    protected $generateCounterRepository;

    /**
     * action commonProperties
     *
     * @param none
     * @return void
     */
    public function listAction() {

		$this->getSessionServiceObjectAndAssign2View(Tx_Magenerator_Controller_GCommonController::SESSION_SERVICE_KEY);

    }

    /**
     * action commonPropertiesSave
     *
     * @param none
     * @return void
     */
    public function generateAction() {
        $user = $this->checkLogin();

        $generator = t3lib_div::makeInstance('Tx_Magenerator_CodeGen_CodeGen');
        /* @var $generator Tx_Magenerator_Service_CodeGen */
        $generator->init($user);
        $extGen = $generator->generate();

        if (empty($extGen['downloadUrl'])) {
            throw new Exception('No download file has been created! :-(');
        }

        $counter = t3lib_div::makeInstance('Tx_Magenerator_Domain_Model_GenerateCounter');
        /* @var $counter Tx_Magenerator_Domain_Model_GenerateCounter */

        $counter->setExtkey($extGen['extNsName']);
        $counter->setFeuser($user->getUid());
        $this->generateCounterRepository->add($counter);


        // this feuser session save values for this controller
        $this->view->assign('downloadURL', DIRECTORY_SEPARATOR . $generator->getTYPO3TEMP() . $extGen['downloadUrl']);
        $this->view->assign('httpHost', t3lib_div::getIndpEnv('HTTP_HOST') );
    }

}
