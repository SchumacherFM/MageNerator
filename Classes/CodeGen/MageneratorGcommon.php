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
 * this class handles all the common things for an extension
 *
 * @package magenerator
 * @subpackage service/codegen
 */
class Tx_Magenerator_CodeGen_MageneratorGcommon extends Tx_Magenerator_CodeGen_MageneratorAbstract
{

    /**
     * main method for executing this class
     */
    public function main(){
        $this->folderStructure();
        $this->appEtcModulesExtXml();
        $this->appCodeEtcConfigXml();
    }

    /**
     * generates the Modul config file in app/etc/modules/....
     */
    protected function appEtcModulesExtXml() {
        $codePool = $this->getCodepool();

        $extKey = $this->getExtensionKey();

        $xmlFile = array('app', 'etc', 'modules');

        t3lib_div::mkdir_deep($this->tempPath . $this->implodePath($xmlFile));

        $xmlFile[] = $extKey . '.xml';

        $active = $this->getExtConfigValue('magenerator_gcommon/active', 0, true);
        $active = $active == 1 ? 'true' : 'false';

        $mDom = new Tx_Magenerator_CodeGen_DOMGen();
        // @todo user with a paid account will get a nice XML output

        $content = array(
            'config' => array(
                'modules' => array(
                    $extKey => array(
                        'active' => $active,
                        'codePool' => $codePool,
                    ),
                ),
                'global' => array(),
            ),
        );


        $dependsCustomExt = $this->getExtConfigValue('magenerator_gcommon/dependsCustomExt');
        $selectedSysExt = $this->getExtConfigValue('magenerator_gcommon/selectedSysExt');

        if (!empty($dependsCustomExt) || !empty($selectedSysExt)) {


            $content['config']['modules'][$extKey]['depends'] = array();

            $dependsCustomExt = preg_split('~\s+~', $dependsCustomExt, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($dependsCustomExt as $custExt) {
                $content['config']['modules'][$extKey]['depends'][$custExt] = NULL;
            }

            foreach ($selectedSysExt as $sysExt) {
                $content['config']['modules'][$extKey]['depends'][$sysExt] = NULL;
            }
        } // endif


        $deactivateCustomExt = $this->getExtConfigValue('magenerator_gcommon/deactivateCustomExt');
        $selectedDeactivatedSysExt = $this->getExtConfigValue('magenerator_gcommon/selectedDeactivatedSysExt');

        if (!empty($deactivateCustomExt) || !empty($selectedDeactivatedSysExt)) {

            $deactivateCustomExt = preg_split('~\s+~', $deactivateCustomExt, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($deactivateCustomExt as $custExt) {
                $content['config']['modules'][$custExt]['active'] = 'false';
            }

            foreach ($selectedDeactivatedSysExt as $sysExt) {
                $content['config']['modules'][$sysExt]['active'] = 'false';
            }
        } // endif

        $mDom->arrayToXml($content);
        $mDom->save($this->tempPath . $this->implodePath($xmlFile));
    }

    /**
     * creates the basic config.xml file can be later modified by other methods
     * @return void
     */
    protected function appCodeEtcConfigXml() {

        $extKey = $this->getExtensionKey();
        $version = $this->getExtConfigValue('magenerator_gcommon/version');

        $xmlconfig = $this->getEtcXmlFilename('config');

        $mDom = new Tx_Magenerator_CodeGen_DOMGen();

        $content = array(
            'config' => array(
                'modules' => array(
                    $extKey => array(
                        'version' => $version
                    ),
                ),
            ),
        );

        $mDom->arrayToXml($content);
        $mDom->save($this->tempPath . $xmlconfig);
    }

}
