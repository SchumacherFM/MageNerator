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
 * Interface for magento code generation
 *
 * @package magenerator
 * @subpackage interfaces
 */
interface Tx_Magenerator_Interfaces_MageCodeGen
{

    /**
     * The extension config entered by the user in the frontend
     * @param array $extConfig
     */
    public function setExtConfig($extConfig);

    /**
     * The extension config entered by the user in the frontend
     * @param Tx_Magenerator_Domain_Model_FeUser $feUser
     */
    public function setFeUser($feUser);

   /**
     * creates the folder structure based on the extConfig = extension config
     *
     * @return mixed
     */
    public function folderStructure();

    public function appEtcModulesExtXml();

    public function appCodeEtcConfigXml();

    public function appCodeEtcAdminhtmlXml();

    public function appCodeEtcSystemXml();

    public function appCodeEtcWidgetXml();

    public function appCodeModel();

    public function appCodeModelResource();

    public function appCodeControllers();

    public function appCodeControllersAdminhtml();

    public function finalize();
}

