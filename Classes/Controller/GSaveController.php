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
class Tx_Magenerator_Controller_GSaveController
		extends Tx_Magenerator_Controller_AbstractController
{

	/**
	 * action commonProperties
	 *
	 * @param none
	 * @return void
	 */
	public function listAction()
	{

	}

	/**
	 * action commonPropertiesSave
	 *
	 * @param none
	 * @return void
	 */
	public function saveAction()
	{
        $user = $this->checkLogin();

        $args = $this->getArgs();

        $isPublic = (isset($args['is_public']) && (int)$args['is_public'] === 1 ) ? true : false;

        $ext = $this->sessionService->getAll();

        $extKey = '';
        if( isset($ext['magenerator_gcommon']) && isset($ext['magenerator_gcommon']['extname']) ){
            $extKey = trim($ext['magenerator_gcommon']['namespace'].'_'.$ext['magenerator_gcommon']['extname']);
        }

        if( $extKey === '_' || substr($extKey,-1,1) === '_' ){
            $this->returnMsgJson('extkeymissing', true);
        }

        $storedExt = t3lib_div::makeInstance('Tx_Magenerator_Domain_Model_UserExtensions');
        $storedExt->setExtension($ext);
        $storedExt->setIsPublic($isPublic);
        $storedExt->setExtKey($extKey);
        $user->addUserExt($storedExt);

        $this->feUserRepository->update($user);

        $this->returnMsgJsonViewAssign('formSaved',false,'alert');

	}

}
