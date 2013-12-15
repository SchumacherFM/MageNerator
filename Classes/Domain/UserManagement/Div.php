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
 * @subpackage UserManagement
 */
class Tx_Magenerator_Domain_UserManagement_Div
{
	/**
	 * An instance of the salted hashing method.
	 * This member is set in the getSaltingInstance() function.
	 *
	 * @var	tx_saltedpasswords_abstract_salts
	 */
	protected $objInstanceSaltedPW = NULL;


    /**
     * An RSA backend.
     *
     * @var	tx_rsaauth_abstract_backend
     */
    private $backend = NULL;

    /**
     *
     * @param Tx_Magenerator_Domain_Model_FeUser $feUser
     * @param string $plainTextPw
     * @see	t3lib_userAuth::checkAuthentication()
     * @return void
     */
    public function logUserIn(Tx_Magenerator_Domain_Model_FeUser $feUser,$plainTextPw) {
        // @todo now log the user in with fe_login
        $feUserAuth = $GLOBALS['TSFE']->fe_user;
        /* @var $feUserAuth tslib_feUserAuth */

        $check = FALSE;
        $loginData = array(
            'username' => $feUser->getEmail(),
            'uident_text' => $plainTextPw, // must be plaintext!
            'status' => 'login',
        );


        $feUserAuth->checkPid = ''; //do not use a particular pid
        $info = $feUserAuth->getAuthInfoArray();
        $user = $feUserAuth->fetchUserRecord($info['db_user'], $loginData['username']);

        if (isset($user) && $user != '') {
            $authBase = new tx_saltedpasswords_sv1();

            $ok = $authBase->compareUident($user, $loginData);

            if ($ok) {
                //login successfull
                $feUserAuth->createUserSession($user);
                $check = TRUE;
            }
            else {
                //login failed
                $check = FALSE;
            }
        }
        else {
            $check = FALSE;
        }
        return $check;
    }

    /**
     * gets the salted password from a rsa string
     *
     * @param string $rsaPassword
     * @return object The array contains the salted and the plain PW
     */
    public function getHashedPassword($rsaPassword){
        $saltedPassword = false;
        $return = new stdClass();
        $return->error = true;

        if( substr($rsaPassword, 0, 4) !== 'rsa:' ){
            return $return;
        }

        $password = $this->getDecryptedRSAPassword($rsaPassword);

        if( empty($password) ){
            return $return;
        }

        if( !$this->objInstanceSaltedPW ){
            $this->objInstanceSaltedPW = tx_saltedpasswords_salts_factory::getSaltingInstance($user['password']);
        }

        $saltedPassword = $this->objInstanceSaltedPW->getHashedPassword($password);

        $return->salted = $saltedPassword;
        $return->plain = $password;
        $return->error = false;

        return $return;
    }

    /**
     *
     * @param RSA:string $password
     * @return boolean|decrypted password
     */
    public function getDecryptedRSAPassword($password)
    {
        if( !$this->backend || !is_object($this->backend) ){
    		$this->backend = tx_rsaauth_backendfactory::getBackend();
        }

        $storage = tx_rsaauth_storagefactory::getStorage();
        /* @var $storage tx_rsaauth_abstract_storage */

        // Decrypt the password

        $key = $storage->get();
        if ($key != NULL && substr($password, 0, 4) === 'rsa:')
        {
            // Decode password and store it in loginData
            $decryptedPassword = $this->backend->decrypt($key, substr($password, 4));
            if ($decryptedPassword != NULL)
            {
                return $decryptedPassword;
            }
            else
            {
                if ($this->pObj->writeDevLog)
                {
                    t3lib_div::devLog('Process login data: Failed to RSA decrypt password', 'Tx_Magenerator_Domain_UserManagement_Div');
                }
            }
            // Remove the key
            $storage->put(NULL);
            return false;
        }
        else
        {
            if ($this->pObj->writeDevLog)
            {
                t3lib_div::devLog('Process login data: passwordTransmissionStrategy has been set to "rsa" but no rsa encrypted password has been found.', 'Tx_Magenerator_Domain_UserManagement_Div');
            }
            return false;
        }
    }

    /**
     * Returns a fe user domain object for a currently logged in user
     * or NULL if no user is logged in.
     *
     * @return Tx_Extbase_Domain_Model_FrontendUser  FE user object
     */
    public static function getLoggedInUserObject()
    {
        $feUserUid = $GLOBALS['TSFE']->fe_user->user['uid'];
        if ($feUserUid > 0)
        {
            $feUserRepository = t3lib_div::makeInstance('Tx_Magenerator_Domain_Repository_FeUserRepository');
            /* @var $feUserRepository Tx_Magenerator_Domain_Repository_FeUserRepository */
            return $feUserRepository->findByUid(intval($feUserUid));
        }
        else
        {
            return NULL;
        }
    }

    /**
     * Returns groups of currently logged in frontend user or null if no fe user is logged in.
     *
     * @return Tx_Extbase_Persistence_ObjectStorage     Object storage with fe user groups for currently logged in user
     */
    public static function getLoggedInUserGroups()
    {
        $feUserObject = self::getLoggedInUserObject(); /* @var $feUserObject Tx_Extbase_Domain_Model_FrontendUser */
        if (!is_null($feUserObject))
        {
            return $feUserObject->getUsergroups();
        }
        else
        {
            return NULL;
        }
    }

    /**
     * Returns true, if currently logged in user is in given fe user group
     *
     * @param int   $groupId   The group UID for in which we want to know wheter the user belongs to
     * @return bool     True, if currently logged in fe user belongs to given group
     */
    public static function isLoggedInUserInGroup($groupId)
    {
        $loggedInFeUserGroups = self::getLoggedInUserGroups();
        if (!is_null($loggedInFeUserGroups))
        {
            foreach ($loggedInFeUserGroups as $feUserGroup)
            { /* @var $feUserGroup Tx_Extbase_Domain_Model_FrontendUserGroup */
                if ($feUserGroup->getUid() == $groupId)
                {
                    return TRUE;
                }
            }
        }
        return false;
    }

    /**
     * Returns true, if the currently logged in user is in one of the
     * given fe groups
     *
     * @param array $feUserGroupUids
     * @return unknown
     */
    public static function isLoggedInUserInGroups($feUserGroupUids)
    {
        foreach ($feUserGroupUids as $feUserGroupUid)
        {
            if (self::isLoggedInUserInGroup($feUserGroupUid))
                return TRUE;
        }
        return false;
    }

}
