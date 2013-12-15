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
 * @subpackage repository
 */
class Tx_Magenerator_Domain_Repository_UserExtensionsRepository extends Tx_Extbase_Persistence_Repository
{

    protected $defaultOrderings = array(
        'feuser' => Tx_Extbase_Persistence_QueryInterface::ORDER_DESCENDING,
        'extkey' => Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING,
        'crdate' => Tx_Extbase_Persistence_QueryInterface::ORDER_DESCENDING,
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
     * returns the
     * @return Tx_Magenerator_Domain_Model_UserExtensions
     */
    public function findTop() {

        $timeAgo = time() - (3600*24*92); // last three month

        $query = $this->createQuery();
        $query->getQuerySettings()->setReturnRawQueryResult(true);
        $query->statement('select crc32(feuser) feuser,count(*) as amount from tx_magenerator_domain_model_userextensions
            where deleted=0 and hidden=0 and crdate > '.$timeAgo.'
            group by feuser order by amount desc limit 0,10');
        return $query->execute();

    }


}
