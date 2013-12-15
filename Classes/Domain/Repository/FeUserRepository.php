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
 * @subpackage repository
 */
class Tx_Magenerator_Domain_Repository_FeUserRepository extends Tx_Extbase_Domain_Repository_FrontendUserRepository {

	/**
	 * All Queries withoud storagePID
	 *
	 * @return Tx_Extbase_Persistence_QueryInterface
	 */
	public function createQuery() {
		$query = parent::createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		return $query;
	}

	// call this in the controller
	// 			$this->feUserRepository->storeConfig($args,'common',$user);

	/**
	 * at the end of all config we'll save this in the user table for ext
	 *
	 * @param array $args
	 * @param string $namespace
	 * @return void
	 */
	public function storeConfig($args,$namespace,Tx_Magenerator_Domain_Model_FeUser $user){

		$extkey = date('Ymd_Hism');
		if( isset($args['namespace']) && isset($args['extname']) && !empty($args['namespace']) && !empty($args['extname']) ){
			$extkey = $args['namespace'].'_'.$args['extname'];
		}

//		$query = $this->createQuery();
//		$constraints = array();
//		$constraints[] = $query->equals('feuser', $userId);
//		$constraints[] = $query->equals('extkey', $extkey );
//		$thisConfig = $query->matching($query->logicalAnd($constraints))->execute()->getFirst();

		$allUserExt = $user->getUserExt();
		file_put_contents('/Users/kiri/Sites/turmhof/site/typo3temp/zzVE.txt', var_export($allUserExt,1) );


		if( !$allUserExt ){
			$thisConfig = new Tx_Magenerator_Domain_Model_UserExtensions();
			$ext = array($namespace=>$args);
			$thisConfig->setExtkey($extkey);
			$thisConfig->setExtension( serialize($ext) );
			$user->addUserExt($thisConfig);
			$this->persistenceManager->persistAll();

			return $thisConfig;
		}
	}

    /**
     * gets all available countries from the table static_countries
     * @return array
     */
    public function findAllCountries(){
        $return = array(''=>'Choose');

		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('cn_iso_2,cn_short_local', 'static_countries', '', '',
                'cn_short_local');
		while ($r = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)) {
			$return[$r['cn_iso_2']] = $r['cn_short_local'];
		}

        return $return;
    }

    /**
     * checks independetly from T3 fields if a username or email adresse exists
     * returns false OR the user ID if a records has been found
     *
     * @param string $emailAdress
     * @return boolean|integer
     */
    public function existsEmailUsername($emailAdress){

        if( !t3lib_div::validEmail($emailAdress) ){
            return false;
        }

		$query = $this->createQuery();
        $query->getQuerySettings()->setReturnRawQueryResult(TRUE);

        $emailAdress =
    	$query->statement('SELECT uid from fe_users where
                username like \''.$emailAdress.'\' or email like \''.$emailAdress.'\'');
        $return = $query->execute();

        if( count($return) == 0 || !isset($return[0]) ){
            return false;
        }

        return $return[0]['uid'];

    }

}
