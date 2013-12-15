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
class Tx_Magenerator_Domain_Repository_ExtnameRepository extends Tx_Extbase_Persistence_Repository {

    protected $defaultOrderings = array(
         'name' => Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING,
    );

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

	/**
	 *
	 * @return object  The not matching object if found, otherwise NULL
	 */
	public function existsFrontNames($search){

		$query = $this->createQuery();
		return $query->matching( $query->like('front_name', $search) )->execute();

	}

}
