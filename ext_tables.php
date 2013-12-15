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

$plugins = array(
    'MageUsers' => 'MageNerator: Frontend Users Registering',
    'StatusLoginOut' => 'MageNerator: Login Status',
    'GeneratorCommon' => 'MageNerator: Common Properties',
    'GeneratorController' => 'MageNerator: Controllers',
    'GeneratorModels' => 'MageNerator: Models & Resources',
    'GeneratorBlock' => 'MageNerator: Blocks',
    'GeneratorHelper' => 'MageNerator: Helpers',
    'Generator' => 'MageNerator: Generator',
    'GSave' => 'MageNerator: Save',
    'ContactCenter' => 'MageNerator: Contact',
);

foreach ($plugins as $pluginName => $pluginTitle) {
    Tx_Extbase_Utility_Extension::registerPlugin(
            $_EXTKEY, $pluginName, $pluginTitle
            // $pluginIconPathAndFilename
    );
}


t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'MageNerator');

t3lib_extMgm::addLLrefForTCAdescr('tx_magenerator_domain_model_userextensions', 'EXT:magenerator/Resources/Private/Language/locallang_csh_tx_magenerator_domain_model_userextensions.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_magenerator_domain_model_userextensions');
$TCA['tx_magenerator_domain_model_userextensions'] = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:magenerator/Resources/Private/Language/locallang_db.xml:tx_magenerator_domain_model_userextensions',
        'label' => 'extkey',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => TRUE,
        'versioningWS' => 2,
        'versioning_followPages' => TRUE,
        'origUid' => 't3_origuid',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => array(
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ),
        'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/UserExtensions.php',
        'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_magenerator_domain_model_userextensions.gif'
    ),
);

t3lib_div::loadTCA('fe_users');

if (!isset($TCA['fe_users']['ctrl']['type'])) {
    // no type field defined, so we define it here. This will only happen the first time the extension is installed!!
    $TCA['fe_users']['ctrl']['type'] = 'tx_extbase_type';
    $tempColumns = array();
    $tempColumns[$TCA['fe_users']['ctrl']['type']] = array(
        'exclude' => 1,
        'label' => 'LLL:EXT:magenerator/Resources/Private/Language/locallang_db.xml:tx_magenerator_domain_model_feuser.tx_extbase_type',
        'config' => array(
            'type' => 'select',
            'items' => array(
                array('LLL:EXT:magenerator/Resources/Private/Language/locallang_db.xml:tx_magenerator_domain_model_feuser.tx_extbase_type.0', '0'),
            ),
            'size' => 1,
            'maxitems' => 1,
            'default' => 'Tx_Magenerator_FeUser'
        )
    );
    t3lib_extMgm::addTCAcolumns('fe_users', $tempColumns, 1);
}

$TCA['fe_users']['types']['Tx_Magenerator_FeUser']['showitem'] = $TCA['fe_users']['types']['Tx_Extbase_Domain_Model_FrontendUser']['showitem'];
$TCA['fe_users']['columns'][$TCA['fe_users']['ctrl']['type']]['config']['items'][] = array('LLL:EXT:magenerator/Resources/Private/Language/locallang_db.xml:tx_magenerator_domain_model_feuser', 'Tx_Magenerator_FeUser');
t3lib_extMgm::addToAllTCAtypes('fe_users', $TCA['fe_users']['ctrl']['type'], '', 'after:hidden');

$tmp_magenerator_columns = array(
    'credit' => array(
        'exclude' => 0,
        'label' => 'LLL:EXT:magenerator/Resources/Private/Language/locallang_db.xml:tx_magenerator_domain_model_feuser.credit',
        'config' => array(
            'type' => 'input',
            'size' => 10,
            'eval' => 'trim'
        ),
    ),
    'namespace' => array(
        'exclude' => 0,
        'label' => 'LLL:EXT:magenerator/Resources/Private/Language/locallang_db.xml:tx_magenerator_domain_model_feuser.namespace',
        'config' => array(
            'type' => 'input',
            'size' => 30,
            'eval' => 'trim'
        ),
    ),
    'user_ext' => array(
        'exclude' => 0,
        'label' => 'LLL:EXT:magenerator/Resources/Private/Language/locallang_db.xml:tx_magenerator_domain_model_feuser.user_ext',
        'config' => array(
            'type' => 'inline',
            'foreign_table' => 'tx_magenerator_domain_model_userextensions',
            'foreign_field' => 'feuser',
            'maxitems' => 9999,
            'appearance' => array(
                'collapse' => 0,
                'levelLinksPosition' => 'top',
                'showSynchronizationLink' => 1,
                'showPossibleLocalizationRecords' => 1,
                'showAllLocalizationLink' => 1
            ),
        ),
    ),
    'invite' => array(
        'exclude' => 0,
        'label' => 'LLL:EXT:magenerator/Resources/Private/Language/locallang_db.xml:tx_magenerator_domain_model_feuser.invite',
        'config' => array(
            'type' => 'input',
            'size' => 30,
            'eval' => 'trim'
        ),
    ),
    'fe_invites' => array(
        'exclude' => 0,
        'label' => 'LLL:EXT:magenerator/Resources/Private/Language/locallang_db.xml:tx_magenerator_domain_model_feuser.fe_invites',
        'config' => array(
            'type' => 'inline',
            'foreign_table' => 'tx_magenerator_domain_model_invites',
            'foreign_field' => 'feuser',
            'maxitems' => 9999,
            'appearance' => array(
                'collapse' => 0,
                'levelLinksPosition' => 'top',
                'showSynchronizationLink' => 1,
                'showPossibleLocalizationRecords' => 1,
                'showAllLocalizationLink' => 1
            ),
        ),
    ),
);

t3lib_extMgm::addTCAcolumns('fe_users', $tmp_magenerator_columns);

$TCA['fe_users']['columns'][$TCA['fe_users']['ctrl']['type']]['config']['items'][] = array('LLL:EXT:magenerator/Resources/Private/Language/locallang_db.xml:fe_users.tx_extbase_type.Tx_Magenerator_FeUser', 'Tx_Magenerator_FeUser');

$TCA['fe_users']['types']['Tx_Magenerator_FeUser']['showitem'] = $TCA['fe_users']['types']['Tx_Extbase_Domain_Model_FrontendUser']['showitem'];
$TCA['fe_users']['types']['Tx_Magenerator_FeUser']['showitem'] .= ',--div--;LLL:EXT:magenerator/Resources/Private/Language/locallang_db.xml:tx_magenerator_domain_model_feuser,';
$TCA['fe_users']['types']['Tx_Magenerator_FeUser']['showitem'] .= 'namespace, account_type, credit, user_ext, invite, fe_invites';



t3lib_extMgm::addLLrefForTCAdescr('tx_magenerator_domain_model_extns', 'EXT:magenerator/Resources/Private/Language/locallang_csh_tx_magenerator_domain_model_extns.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_magenerator_domain_model_extns');
$TCA['tx_magenerator_domain_model_extns'] = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:magenerator/Resources/Private/Language/locallang_db.xml:tx_magenerator_domain_model_extns',
        'label' => 'namespace',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => TRUE,
        'versioningWS' => 2,
        'versioning_followPages' => TRUE,
        'origUid' => 't3_origuid',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => array(
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ),
        'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Extns.php',
        'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_magenerator_domain_model_extns.gif'
    ),
);


t3lib_extMgm::addLLrefForTCAdescr('tx_magenerator_domain_model_extname', 'EXT:magenerator/Resources/Private/Language/locallang_csh_tx_magenerator_domain_model_extname.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_magenerator_domain_model_extname');
$TCA['tx_magenerator_domain_model_extname'] = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:magenerator/Resources/Private/Language/locallang_db.xml:tx_magenerator_domain_model_extname',
        'label' => 'name',
        'hideTable' => true,
        'tstamp' => 'tstamp',
        'default_sortby' => 'name',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => TRUE,
        'versioningWS' => 2,
        'versioning_followPages' => TRUE,
        'origUid' => 't3_origuid',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => array(
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ),
        'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Extname.php',
        'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_magenerator_domain_model_extname.gif'
    ),
);


t3lib_extMgm::addLLrefForTCAdescr('tx_magenerator_domain_model_invites', 'EXT:magenerator/Resources/Private/Language/locallang_csh_tx_magenerator_domain_model_invites.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_magenerator_domain_model_invites');
$TCA['tx_magenerator_domain_model_invites'] = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:magenerator/Resources/Private/Language/locallang_db.xml:tx_magenerator_domain_model_invites',
        'label' => 'invite',
        'tstamp' => 'tstamp',
        'default_sortby' => 'invite',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => array(
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ),
        'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Invites.php',
        'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_magenerator_domain_model_invites.gif'
    ),
);


t3lib_extMgm::addLLrefForTCAdescr('tx_magenerator_domain_model_contacts', 'EXT:magenerator/Resources/Private/Language/locallang_csh_tx_magenerator_domain_model_contacts.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_magenerator_domain_model_contacts');
$TCA['tx_magenerator_domain_model_contacts'] = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:magenerator/Resources/Private/Language/locallang_db.xml:tx_magenerator_domain_model_contacts',
        'label' => 'cname',
        'tstamp' => 'tstamp',
        'default_sortby' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => TRUE,
        'versioningWS' => 2,
        'versioning_followPages' => TRUE,
        'origUid' => 't3_origuid',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => array(
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ),
        'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Contacts.php',
        'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_magenerator_domain_model_contacts.gif'
    ),
);


t3lib_extMgm::allowTableOnStandardPages('tx_magenerator_domain_model_generatecounter');
$TCA['tx_magenerator_domain_model_generatecounter'] = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:magenerator/Resources/Private/Language/locallang_db.xml:tx_magenerator_domain_model_generatecounter',
        'label' => 'extkey',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'hideTable' => true,
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => array(
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ),
        'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/GenerateCounter.php',
        //@todo the icon file
        'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_magenerator_domain_model_generatecounter.gif'
    ),
);


if (TYPO3_MODE == 'BE') {
// @todo
//	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['Tx_Magenerator_Wizicon'] = t3lib_extMgm::extPath($_EXTKEY) . 'Classes/Wizicon.php';
}


//echo '<pre>';
//var_dump($TCA['pages']['palettes']);
//exit;

t3lib_div::loadTCA('pages');

$TCA['pages']['columns'] += array(
    'tx_magenerator_donotlinkit' => array(
        'label' => 'LLL:EXT:magenerator/Resources/Private/Language/locallang_db.xml:pages.donotlinkit',
        'exclude' => 1,
        'config' => array (
            'type' => 'check',
        ),
    ),
);

// 1 is the Standart page! and 4 is type shortcut
t3lib_extMgm::addToAllTCAtypes('pages', 'tx_magenerator_donotlinkit', '', 'after:nav_hide');
