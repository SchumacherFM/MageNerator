{namespace mage=Tx_Magenerator_ViewHelpers}<?php
/**
 * MageNerator.net Sample sql install file
 */

$installer = $this;
/** @var $installer {modelResourceSetupName} */

$installer->startSetup();

<f:for each="{sql}" key="modelName" as="sqlConfig">

/**
 * Create table {modelName}
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('{extKey}/{mage:php(type:"lower", value:"{modelName}")}'))
    <f:for each="{sqlConfig}" as="col">
    ->addColumn('{col.name}', {ClassNameVarienDbDdlTable}::{col.type}, '{mage:php(type:"lower", value:"{col.length}")}', array(
        <f:if condition="{col.default}">'default'  => '{col.default}',</f:if>
        'identity'  => {mage:php(type:"boolString", value:"{col.identity}")},
        'unsigned'  => {mage:php(type:"boolString", value:"{col.unsigned}")},
        'nullable'  => {mage:php(type:"boolString", value:"{col.nullable}")},
        'primary'   => {mage:php(type:"boolString", value:"{col.primary}")},
        ), 'Description {col.name}')</f:for>
    ->setComment('Enterprise Reminder Rule');
$installer->getConnection()->createTable($table);

</f:for>

$installer->endSetup();

// The following comments/methods are listed as a helper for you.
// You can remove them once you're finished

    /**
     * Adds column to table.
     *
     * $options contains additional options for columns. Supported values are:
     * - 'unsigned', for number types only. Default: FALSE.
     * - 'precision', for numeric and decimal only. Default: taken from $size, if not set there then 0.
     * - 'scale', for numeric and decimal only. Default: taken from $size, if not set there then 10.
     * - 'default'. Default: not set.
     * - 'nullable'. Default: TRUE.
     * - 'primary', add column to primary index. Default: do not add.
     * - 'primary_position', only for column in primary index. Default: count of primary columns + 1.
     * - 'identity' or 'auto_increment'. Default: FALSE.
     *
     * @param string $name the column name
     * @param string $type the column data type
     * @param string|int|array $size the column length
     * @param array $options array of additional options
     * @param string $comment column description
     * @throws Zend_Db_Exception
     * @return Varien_Db_Ddl_Table
     */
   // Varien_Db_Ddl_Table::addColumn($name, $type, $size = null, $options = array(), $comment = null)


    /**
     * Retrieve 32bit UNIQUE HASH for a Table foreign key
     *
     * @param string $priTableName  the target table name
     * @param string $priColumnName the target table column name
     * @param string $refTableName  the reference table name
     * @param string $refColumnName the reference table column name
     * @return string
     */
    // Mage_Core_Model_Resource_Setup::getFkName($priTableName, $priColumnName, $refTableName, $refColumnName)


    /**
     * Add Foreign Key to table
     *
     * @param string $fkName        the foreign key name
     * @param string $column        the foreign key column name
     * @param string $refTable      the reference table name
     * @param string $refColumn     the reference table column name
     * @param string $onDelete      the action on delete row
     * @param string $onUpdate      the action on update
     * @throws Zend_Db_Exception
     * @return Varien_Db_Ddl_Table
     */
    // Varien_Db_Ddl_Table::addForeignKey($fkName, $column, $refTable, $refColumn, $onDelete = null, $onUpdate = null)

    /* Foreign Key Example
    ->addForeignKey($installer->getFkName('enterprise_reminder/rule', 'salesrule_id', 'salesrule/rule', 'rule_id'),
        'salesrule_id', $installer->getTable('salesrule/rule'), 'rule_id',
        Varien_Db_Ddl_Table::ACTION_SET_NULL, Varien_Db_Ddl_Table::ACTION_CASCADE)
    */

    /**
     * Add index to table
     *
     * @param string $indexName     the index name
     * @param array|string $columns array of columns or column string
     * @param array $options        array of additional options
     * @return Varien_Db_Ddl_Table
     */
    // Varien_Db_Ddl_Table::addIndex($indexName, $fields, $options = array())

    /* index example
    ->addIndex($installer->getIdxName('namespace_extensionname/tablename', array('column_name')),
        array('column_name'))
    */

    /**
     * Retrieve 32bit UNIQUE HASH for a Table index
     *
     * @param string $tableName
     * @param array|string $fields
     * @param string $indexType
     * @return string
     */
    // Mage_Core_Model_Resource_Setup::getIdxName($tableName, $fields, $indexType = '')
