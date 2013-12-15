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
 * @subpackage extconf
 */
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}


Tx_Extbase_Utility_Extension::configurePlugin(
        $_EXTKEY, 'StatusLoginOut', array(
    'LoginStatus' => 'status',
        ),
        // non-cacheable actions
        array(
    'LoginStatus' => 'status',
        )
);

Tx_Extbase_Utility_Extension::configurePlugin(
        $_EXTKEY, 'MageUsers', array(
    'FeUser' => 'register,edit,userform,save,extensions,loadExtension',
    'UserExtensions' => 'top',
        ),
        // non-cacheable actions
        array(
    'FeUser' => 'register,edit,userform,save,extensions,loadExtension',
    'UserExtensions' => '',
        )
);

Tx_Extbase_Utility_Extension::configurePlugin(
        $_EXTKEY, 'GeneratorCommon', array(
    'GCommon' => 'list,ajaxExtNames,save',
        ),
        // non-cacheable actions
        array(
    'GCommon' => 'list,save',
        )
);


Tx_Extbase_Utility_Extension::configurePlugin(
        $_EXTKEY, 'GeneratorController', array(
    'GController' => 'listFE,listBE,saveFE,saveBE,ajaxCheckRouterName',
        ),
        // non-cacheable actions
        array(
    'GController' => 'listFE,listBE,saveFE,saveBE',
        )
);



Tx_Extbase_Utility_Extension::configurePlugin(
        $_EXTKEY, 'GeneratorModels', array(
    'GModels' => 'list,save',
        ),
        // non-cacheable actions
        array(
    'GModels' => 'list,save',
        )
);


Tx_Extbase_Utility_Extension::configurePlugin(
        $_EXTKEY, 'GeneratorBlock', array(
    'GBlock' => 'list,save',
        ),
        // non-cacheable actions
        array(
    'GBlock' => 'list,save',
        )
);

Tx_Extbase_Utility_Extension::configurePlugin(
        $_EXTKEY, 'GeneratorHelper', array(
    'GHelper' => 'list,save',
        ),
        // non-cacheable actions
        array(
    'GHelper' => 'list,save',
        )
);


Tx_Extbase_Utility_Extension::configurePlugin(
        $_EXTKEY, 'Generator', array(
    'Generate' => 'list,generate',
        ),
        // non-cacheable actions
        array(
    'Generate' => 'list,generate',
        )
);

Tx_Extbase_Utility_Extension::configurePlugin(
        $_EXTKEY, 'GSave', array(
    'GSave' => 'list,save',
        ),
        // non-cacheable actions
        array(
    'GSave' => 'list,save',
        )
);

Tx_Extbase_Utility_Extension::configurePlugin(
        $_EXTKEY, 'ContactCenter', array(
    'Contact' => 'form,send',
        ),
        // non-cacheable actions
        array(
    'Contact' => 'form,send',
        )
);

// handle serialized data
// on display in the backend
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getMainFieldsClass'][]  =  'EXT:magenerator/Classes/Hooks/Tceform.php:&Tx_Magenerator_Hooks_Tceform';

// on save entry
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][]  =  'EXT:magenerator/Classes/Hooks/Tcemain.php:&Tx_Magenerator_Hooks_Tcemain';

