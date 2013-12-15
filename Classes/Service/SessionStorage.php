<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Cyrill Schumacher <cyrill@schumacher.fm>
 *
 *  All rights reserved
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * @package magenerator
 * @subpackage service
 */
class Tx_Magenerator_Service_SessionStorage implements t3lib_Singleton {


	const SESSIONNAMESPACE = 'magenerator_';

	/**
	 * Returns the all objects stored in the user's session
	 * @return Object the stored object
	 */
	public function getAll() {
        // this is a kind of bad because sesData is just a property with no protected or private config
		$sessionData = $this->getFrontendUser()->sesData;
        foreach($sessionData as $k=>$v){
            if( stristr($k,self::SESSIONNAMESPACE)===false ){
                unset($sessionData[$k]);
            }else{
                $sessionData[$k] = unserialize($v);
            }
        }
		return $sessionData;
	}

	/**
	 * Returns the all objects stored in the user's session
     * @param array $array
	 * @return Object the stored object
	 */
	public function setAll($array) {
        // this is a kind of bad because sesData is just a property with no protected or private config

        foreach($array as $k=>$v){
            if( stristr($k,self::SESSIONNAMESPACE)!==false ){
                $k = str_replace(self::SESSIONNAMESPACE, '', $k);
                $this->storeObject($v, $k);
            }
        }

	}

	/**
	 * Returns the object stored in the user's session
	 * @param string $key
	 * @return Object the stored object
	 */
	public function get($key) {
		$sessionData = $this->getFrontendUser()->getKey('ses', self::SESSIONNAMESPACE.$key);
		return $sessionData;
	}

	/**
	 * checks if object is stored in the user's session
	 * @param string $key
	 * @return boolean
	 */
	public function has($key) {
		$sessionData = $this->getFrontendUser()->getKey('ses', self::SESSIONNAMESPACE.$key);
		if ($sessionData == '') {
			return false;
		}
		return true;
	}

	/**
	 * Writes something to storage
	 * @param string $key
	 * @param string $value
	 * @return boolean always true
	 */
	public function set($key,$value) {
		$this->getFrontendUser()->setKey('ses', self::SESSIONNAMESPACE.$key, $value);
		$this->getFrontendUser()->storeSessionData();
        return true;
	}

	/**
	 * Writes a object to the session if the key is empty it used the classname
	 * @param object $object
	 * @param string $key
	 * @return	void
	 */
	public function storeObject($object,$key=null) {
		if (is_null($key)) {
			$key = get_class($object);
		}
		return $this->set($key,serialize($object));
	}

	/**
	 * Writes something to storage
	 * @param string $key
	 * @return	object
	 */
	public function getObject($key) {
        $get = $this->get($key);
        $get = unserialize($get);
		return $get;
	}

	/**
	 * Cleans up the session: removes the stored object from the PHP session
	 * @param string $key
	 * @return	void
	 */
	public function clean($key) {
		$this->getFrontendUser()->setKey('ses', self::SESSIONNAMESPACE.$key, NULL);
		$this->getFrontendUser()->storeSessionData();
	}

	/**
	 * Gets a frontend user which is taken from the global registry or as fallback from TSFE->fe_user.
	 *
	 * @return	ux_tslib_feUserAuth	The current extended frontend user object or false
	 */
	protected function getFrontendUser() {
		if ($GLOBALS ['TSFE']->fe_user) {
			return $GLOBALS ['TSFE']->fe_user;
		}
		return false;
	}
}
