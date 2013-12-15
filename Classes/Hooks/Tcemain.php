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
 * hook for TCEmain
 *
 * @package magenerator
 * @subpackage hooks
 */
class Tx_Magenerator_Hooks_Tcemain
{

    /**
     * gets an array with the original columsn of a table
     * @param string $table
     * @return array
     */
    private function getContactTcaTableColumns($table) {
        t3lib_div::loadTCA($table);

        $columns = array();
        foreach ($GLOBALS['TCA'][$table]['columns'] as $col => $config) {
            $columns[$col] = $col;
        }
        return $columns;
    }

    /**
     * main hook method which will be called in TCEmain
     * @param array $incomingFieldArray
     * @param string $table
     * @param integer $id
     * @param t3lib_TCEmain $that
     */
    public function processDatamap_preProcessFieldArray(&$incomingFieldArray, $table, $id, t3lib_TCEmain $that) {

        if (method_exists($this, $table)) {
            $this->$table($incomingFieldArray, $table, $id, $that);
        }
    }

    /**
     * method will be called via line 47 ;-)
     * @param array $incomingFieldArray
     * @param string $table
     * @param integer $id
     * @param t3lib_TCEmain $that
     */
    private function tx_magenerator_domain_model_contacts(&$incomingFieldArray, $table, $id, t3lib_TCEmain $that) {
        $orgCols = $this->getContactTcaTableColumns($table);

        $newSerialize = array();
        foreach ($incomingFieldArray as $col => $val) {
            if (!isset($orgCols[$col])) {
                $newSerialize[$col] = $val;
                unset($incomingFieldArray[$col]);
            }
        }

        $incomingFieldArray['contact'] = serialize($newSerialize);
    }

    /*
      public function processDatamap_beforeStart(t3lib_TCEmain $that){}
      public function processDatamap_postProcessFieldArray($status, $table, $id, &$fieldArray, t3lib_TCEmain $that){}
      public function processDatamap_afterDatabaseOperations($status, $table, $id, $fieldArray,t3lib_TCEmain $that){}
      public function processDatamap_afterAllOperations(t3lib_TCEmain $that){}
     */

}
