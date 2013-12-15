<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_magenerator_domain_model_invites'] = array(
	'ctrl' => $TCA['tx_magenerator_domain_model_invites']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden, feuser, invite,endtime',
	),
	'types' => array(
		'1' => array('showitem' => 'hidden,feuser,invite,starttime, endtime'),
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
		'invite' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:magenerator/Resources/Private/Language/locallang_db.xml:tx_magenerator_domain_model_invites.invite',
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
