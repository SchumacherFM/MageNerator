<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Cyrill Schumacher <cyrill@schumacher.fm>
 *
 *  All rights reserved
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Interface to be implemented by every frontenduser model that should be used with this registration
 *
 * @package magenerator
 * @subpackage interfaces
 */
interface Tx_Magenerator_Interfaces_FrontendUser {

	/**
	 * Returns the credit
	 *
	 * @return float $credit
	 */
	public function getCredit();

	/**
	 * Sets the credit
	 *
	 * @param float $credit
	 * @return void
	 */
	public function setCredit($credit);

	/**
	 * Returns the endtime
	 *
	 * @return integer $endtime
	 */
	public function getEndtime();

	/**
	 * Sets the endtime
	 *
	 * @param integer $endtime
	 * @return void
	 */
	public function setEndtime($endtime);

	/**
	 * Returns the endtime human readable
	 *
	 * @return string $endtime
	 */
	public function getHumanendtime();

	/**
	 * Returns the endtime human readable
	 *
	 * @return string $endtime
	 */
	public function setHumanendtime($endtime);

	/**
	 * Returns the namespace
	 *
	 * @return string $namespace
	 */
	public function getNamespace();

	/**
	 * Sets the namespace
	 *
	 * @param string $namespace
	 * @return void
	 */
	public function setNamespace($namespace);

	/**
	 * Adds a UserExtensions
	 *
	 * @param Tx_Magenerator_Domain_Model_UserExtensions $userExt
	 * @return void
	 */
	public function addUserExt(Tx_Magenerator_Domain_Model_UserExtensions $userExt);

	/**
	 * Removes a UserExtensions
	 *
	 * @param Tx_Magenerator_Domain_Model_UserExtensions $userExtToRemove The UserExtensions to be removed
	 * @return void
	 */
	public function removeUserExt(Tx_Magenerator_Domain_Model_UserExtensions $userExtToRemove);

	/**
	 * Returns the userExt
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_Magenerator_Domain_Model_UserExtensions> $userExt
	 */
	public function getUserExt();

	/**
	 * Sets the userExt
	 *
	 * @param Tx_Extbase_Persistence_ObjectStorage<Tx_Magenerator_Domain_Model_UserExtensions> $userExt
	 * @return void
	 */
	public function setUserExt(Tx_Extbase_Persistence_ObjectStorage $userExt);

    /**
     * removes the password property to disable updating of an empty string
     *
     * @return void
     */
    public function removePasswordProperty();

	/**
	 * Adds a Invites
	 *
	 * @param Tx_Magenerator_Domain_Model_Invites $feInvite
	 * @return void
	 */
	public function addFeInvite(Tx_Magenerator_Domain_Model_Invites $feInvite);

	/**
	 * Removes a Invites
	 *
	 * @param Tx_Magenerator_Domain_Model_Invites $feInviteToRemove The Invites to be removed
	 * @return void
	 */
	public function removeFeInvite(Tx_Magenerator_Domain_Model_Invites $feInviteToRemove);

	/**
	 * Returns the feInvites
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_Magenerator_Domain_Model_Invites> $feInvites
	 */
	public function getFeInvites();

	/**
	 * Sets the feInvites
	 *
	 * @param Tx_Extbase_Persistence_ObjectStorage<Tx_Magenerator_Domain_Model_Invites> $feInvites
	 * @return void
	 */
	public function setFeInvites(Tx_Extbase_Persistence_ObjectStorage $feInvites);

	/**
	 * Returns the invite
	 *
	 * @return string $invite
	 */
	public function getInvite();

	/**
	 * Sets the invite
	 *
	 * @param string $invite
	 * @return void
	 */
	public function setInvite($invite);


}

