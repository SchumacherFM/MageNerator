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
 * @subpackage validator
 */
class xTx_Magenerator_Domain_Validator_UserValidator extends Tx_Extbase_Validation_Validator_AbstractValidator
{

    /**
     *
     * @var Tx_Magenerator_Domain_UserManagement_Div
     */
    private $umDiv;

    /**
     * Validation of given Params
     *
     * @param object $feUser
     * @return boolean
     */
    public function isValid($feUser)
    {
        /* @var $feUser Tx_Magenerator_Domain_Model_FeUser */

            $this->addError('Password missmatch', 20120622074163 );
            return false;

    }

}
