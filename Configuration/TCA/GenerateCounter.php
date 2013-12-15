<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_magenerator_domain_model_generatecounter'] = array(
	'ctrl' => $TCA['tx_magenerator_domain_model_generatecounter']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'extkey,feuser',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, hidden;;1, extkey, feuser'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => array(
				'type' => 'check',
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
			'label' => 'LLL:EXT:magenerator/Resources/Private/Language/locallang_db.xml:tx_magenerator_domain_model_generatecounter.extkey',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
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
