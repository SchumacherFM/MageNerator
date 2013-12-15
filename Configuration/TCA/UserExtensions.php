<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_magenerator_domain_model_userextensions'] = array(
	'ctrl' => $TCA['tx_magenerator_domain_model_userextensions']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, feuser,extension, downloads, is_public',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1,
            extension, downloads, is_public,feuser,
            --div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,starttime, endtime'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_magenerator_domain_model_userextensions',
				'foreign_table_where' => 'AND tx_magenerator_domain_model_userextensions.pid=###CURRENT_PID### AND tx_magenerator_domain_model_userextensions.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),
		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'crdate' => array(
            'exclude' => 1,
            'label' => 'Create Date',
            'config' => Array (
                'type' => 'none',
                'format' => 'date',
                'eval' => 'date',

            ),
		),
		'extkey' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:magenerator/Resources/Private/Language/locallang_db.xml:tx_magenerator_domain_model_userextensions.extkey',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'extension' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:magenerator/Resources/Private/Language/locallang_db.xml:tx_magenerator_domain_model_userextensions.extension',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			),
		),
		'downloads' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:magenerator/Resources/Private/Language/locallang_db.xml:tx_magenerator_domain_model_userextensions.downloads',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			),
		),
		'is_public' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:magenerator/Resources/Private/Language/locallang_db.xml:tx_magenerator_domain_model_userextensions.is_public',
			'config' => array(
				'type' => 'check',
				'default' => 0
			),
		),
		'feuser' => array(
			'exclude' => 0,
			'label' => 'Frontend User',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'fe_users',
                'size'=>1,
				'maxitems'      => 1,
                'items'=>array(
                  array('No One',0),
                ),
			),
		),
	),
);
