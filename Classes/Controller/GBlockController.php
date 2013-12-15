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
class Tx_Magenerator_Controller_GBlockController extends Tx_Magenerator_Controller_AbstractController
{

    const SESSION_SERVICE_KEY = 'gblock';

    /**
     * action commonProperties
     *
     * @param none
     * @return void
     */
    public function listAction() {

        $this->getSessionServiceObjectAndAssign2View(self::SESSION_SERVICE_KEY);

    }

    /**
     * action commonPropertiesSave
     *
     * @param none
     * @return void
     */
    public function saveAction() {
 
    }

}
