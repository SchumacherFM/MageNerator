{namespace mage=Tx_Magenerator_ViewHelpers}
<?php
/**
 * MageNerator.net Sample sql install file
 */

$installer = $this;
/* @var $installer Enterprise_Reminder_Model_Mysql4_Setup */

$installer->run("

CREATE TABLE `{$this->getTable('enterprise_reminder/rule')}` (
    `rule_id` int(10) unsigned NOT NULL auto_increment,
    `name` varchar(255) NOT NULL default '',
    `description` text NOT NULL,
  ...
    `active_from` datetime default NULL,
    `active_to` datetime default NULL,
    PRIMARY KEY  (`rule_id`),
    KEY `IDX_EE_REMINDER_SALESRULE` (`salesrule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");
