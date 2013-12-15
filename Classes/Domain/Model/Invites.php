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
 * @package magenerator
 * @subpackage model
 */
class Tx_Magenerator_Domain_Model_Invites extends Tx_Extbase_DomainObject_AbstractValueObject {

	/**
	 * endtime
	 *
	 * @var integer
	 */
	protected $endtime;

    /**
	 * invite
	 *
	 * @var string
	 */
	protected $invite;

	/**
	 * Returns the invite
	 *
	 * @return string $invite
	 */
	public function getInvite() {
		return $this->invite;
	}

	/**
	 * Sets the invite
	 *
	 * @param string $invite
	 * @return void
	 */
	public function setInvite($invite) {
		$this->invite = $invite;
	}

	/**
	 * Returns the endtime
	 *
	 * @return integer $endtime
	 */
	public function getEndtime() {
		return $this->endtime;
	}

	/**
	 * Sets the endtime
	 *
	 * @param integer $endtime
	 * @return void
	 */
	public function setEndtime($endtime) {
		$this->endtime = $endtime;
	}

}
