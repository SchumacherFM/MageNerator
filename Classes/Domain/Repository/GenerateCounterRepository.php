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
class Tx_Magenerator_Domain_Repository_GenerateCounterRepository extends Tx_Extbase_Persistence_Repository
{

    protected $defaultOrderings = array(
        'feuser' => Tx_Extbase_Persistence_QueryInterface::ORDER_DESCENDING,
        'extkey' => Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING,
        'crdate' => Tx_Extbase_Persistence_QueryInterface::ORDER_DESCENDING,
    );

}
