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
 * hook for TCEform
 *
 * @package magenerator
 * @subpackage hooks
 */
class Tx_Magenerator_Hooks_Tceform
{

    /**
     * removes a field from the TCA
     * @param string $table
     * @param string $field
     */
    private function removeFieldFromTca($table, $field) {
        unset($GLOBALS['TCA'][$table]['columns'][$field]);
        $showItem = t3lib_div::trimExplode(',', $GLOBALS['TCA'][$table]['types'][1]['showitem'], 1);

        foreach ($showItem as $k => $v) {
            if ($v === $field) {
                unset($showItem[$k]);
            }
        }
        $GLOBALS['TCA'][$table]['types'][1]['showitem'] = implode(',', $showItem);
    }

    /**
     * adds a virtual field to the TCA
     *
     * @param string $table
     * @param string $field
     * @param array $config
     */
    private function addFieldToTca($table, $field, $config = array()) {

        $GLOBALS['TCA'][$table]['columns'][$field] = array(
            'exclude' => 0,
            'label' => ucfirst($field),
            'config' =>
            array(
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim',
            )
        );

        $GLOBALS['TCA'][$table]['columns'][$field]['config'] = array_merge(
                $GLOBALS['TCA'][$table]['columns'][$field]['config'], $config
        );

        // add here also somewhere in which tab you would like to have the fields
        $GLOBALS['TCA'][$table]['types'][1]['showitem'] .= ',' . $field;
    }

    /**
     * adds a new tab
     *
     * @param string $table
     * @param strings $tabName
     */
    private function addTabToTca($table, $tabName) {
        $GLOBALS['TCA'][$table]['types'][1]['showitem'] .= ",\n--div--;" . $tabName;
    }

    /**
     * main hook method which is called in tceforms
     * @param string $table
     * @param array $row
     * @param t3lib_tceforms $that
     */
    public function getMainFields_preProcess($table, &$row, t3lib_tceforms $that) {

        if (method_exists($this, $table)) {
            $this->$table($table, $row, $that);
        }
    }

    /**
     * hook function for specific table, called dynamically
     * @param string $table
     * @param array $row
     * @param t3lib_tceforms $that
     */
    private function tx_magenerator_domain_model_contacts($table, &$row, t3lib_tceforms $that) {

        $this->removeFieldFromTca($table, 'contact');
        $contact = unserialize($row['contact']);

        foreach ($contact as $field => $value) {

            $config = array();
            if (strlen($value) > 50) {
                $config = array(
                    'type' => 'text',
                    'cols' => '50',
                    'rows' => '10',
                    'eval' => 'trim'
                );
            }

            $this->addFieldToTca($table, $field, $config);
        }

        $row = array_merge($row, $contact);
    }

    /**
     * hook function for specific table, called dynamically
     * @param string $table
     * @param array $row
     * @param t3lib_tceforms $that
     */
    private function tx_magenerator_domain_model_userextensions($table, &$row, t3lib_tceforms $that) {
        $tabLLPrefix = 'LLL:EXT:magenerator/Resources/Private/Language/locallang_db.xml:userextensions.tab.';

        $this->removeFieldFromTca($table, 'extension');
        $extension = unserialize($row['extension']);

        foreach ($extension as $moName => $modData) {

            // add tab here ...
            $this->addTabToTca($table, $tabLLPrefix . $moName);

            foreach ($modData as $field => $value) {

                $config = array();
                if (is_array($value)) {
                    $config = array(
                        'type' => 'text',
                        'cols' => '50',
                        'rows' => '10',
                        'eval' => 'trim'
                    );
                    $modData[$field] = var_export($value, 1);
                }

                $this->addFieldToTca($table, $field, $config);
            } // endfor

            $row = array_merge($row, $modData);
        }
    }

    // public function XXgetMainFields_postProcess($table, $row, t3lib_tceforms $that) {}

    /**
     *
     * @see t3lib_tceforms::getSingleField
     * @param string $table
     * @param string $field
     * @param array $row
     * @param string $altName
     * @param boolean $palette
     * @param string $extra
     * @param string $pal
     * @param integer $sender
     *
      public function XXgetSingleField_preProcess($table, $field, &$row, &$altName, $palette, $extra, $pal, $sender) {
      if ($table == 'tx_magenerator_domain_model_contacts' && $field == 'contact') {

      if (!empty($row['contact']) && strstr($row['contact'], ':') !== false && strstr($row['contact'], '{') !== false) {
      $contact = unserialize($row['contact']);
      $string = array();

      foreach ($contact as $k => $v) {

      $string[] = "$k\t=>\t$v";
      }

      $row['contact'] = implode("\n", $string);
      }
      }
      }

      public function XXgetSingleField_beforeRender($table, $field, &$row, &$PA) {
      if ($table == 'tx_magenerator_domain_model_contacts' && $field == 'contact') {

      if (!empty($row['contact']) && strstr($row['contact'], ':') !== false) {
      $row['contact'] = var_export(unserialize($row['contact']), 1);
      }
      var_dump($PA);
      exit;
      }
      }

      public function XXgetSingleField_postProcess(&$table, $field, &$row, &$out, $PA, $sender) {

      }
     */

}
