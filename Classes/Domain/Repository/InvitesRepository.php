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
class Tx_Magenerator_Domain_Repository_InvitesRepository extends Tx_Extbase_Persistence_Repository {

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
 

}
