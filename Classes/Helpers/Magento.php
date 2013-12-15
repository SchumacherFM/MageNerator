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
 * general helper class for magento
 *
 * @package magenerator
 * @subpackage helpers
 */
class Tx_Magenerator_Helpers_Magento implements t3lib_Singleton
{
    
    const CLASS_VARIEN_DB_DDL_TABLE = 'Varien_Db_Ddl_Table';

	/**
	 * @var Tx_Extbase_Object_ObjectManagerInterface
	 */
	protected $objectManager;


    /**
     * @var array Settings
     */
    protected $settings;

    /**
     * constructor
     */
    public function __construct() {
		$this->objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
        $omSettings = $this->objectManager->get('Tx_Magenerator_Service_Settings');
        $this->settings = $omSettings->getSettings();
    }

    /**
     * gets the available version and checks if they are actived
     *
     * @return array
     */
    public function getAvailableVersions() {
        // todo add bitte waehlen sie ...
        $select = array(''=> Tx_Extbase_Utility_Localization::translate('helper.pleaseChoose', 'magenerator') );
        $return = $this->settings['mageversion'];
        return array_merge($select,$return);
    }

    /**
     * gets the available version and checks if they are actived
     *
     * @return array
     */
    public function getCodepools() {

        return array(
            'local' => 'local',
            'community' => 'community',
            'core' => 'core',
        );
    }

    /**
     * gets the available version and checks if they are actived
     *
     * @return array
     */
    public function getSqlColumnTypes() {

        return array(
            'TYPE_TEXT' => 'text',
            'TYPE_BOOLEAN' => 'boolean',
            'TYPE_SMALLINT' => 'smallint',
            'TYPE_INTEGER' => 'integer',
            'TYPE_BIGINT' => 'bigint',
            'TYPE_FLOAT' => 'float',
            'TYPE_NUMERIC' => 'numeric',
            'TYPE_DECIMAL' => 'decimal',
            'TYPE_DATE' => 'date',
            'TYPE_TIMESTAMP' => 'timestamp',
            'TYPE_DATETIME' => 'datetime',
            'TYPE_BLOB' => 'blob',
            'TYPE_VARBINARY' => 'varbinary',
        );
    }

    /**
     * generates a array with 40 entries and a frist entry which says: create no table
     * @return array
     */
    public function getModelsColumnCount() {
        $a = range(0, 40);
        $a[0] = Tx_Extbase_Utility_Localization::translate('mo.createNoTable', 'magenerator');
        return $a;
    }

}
