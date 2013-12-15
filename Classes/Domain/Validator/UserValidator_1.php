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
class xxTx_Magenerator_Domain_Validator_UserValidator extends Tx_Extbase_Validation_Validator_AbstractValidator
{

    /**
     *
     * @var Tx_Magenerator_Domain_UserManagement_Div
     */
    private $umDiv;

    /**
     * Validation of given Params
     *
     * @param Tx_Magenerator_Domain_Model_FeUser $feUser
     * @return boolean
     */
    public function isValid($feUser)
    {
        /* @var $feUser Tx_Magenerator_Domain_Model_FeUser */


        if( !$this->umDiv ){
            $this->umDiv = new Tx_Magenerator_Domain_UserManagement_Div();
        }

        $gp = t3lib_div::_GP('tx_magenerator_feuser');
        $password2 = $gp['password2'];
        $password = $feUser->getPassword();

        if( substr($password, 0, 4) !== 'rsa:' || substr($password2, 0, 4) !== 'rsa:' ){

            $this->addError('Passwords are not RSA strings', 20120622074156 );

            return false;
        }

        $password = $this->umDiv->getDecryptedRSAPassword($password);
        $password2 = $this->umDiv->getDecryptedRSAPassword($password2);

        if ($password2 == $password )
        {
            $this->addError('Password missmatch', 20120622074163 );
            return false;
        }

        if( !t3lib_div::validEmail( $feUser->getEmail() ) ){
            $this->addError('Email address invalid', 20120622074169 );
            return false;
        }


        // @todo now check here the other stuff ...


        return true;
    }

}
