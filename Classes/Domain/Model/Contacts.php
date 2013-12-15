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
class Tx_Magenerator_Domain_Model_Contacts extends Tx_Extbase_DomainObject_AbstractValueObject {

    /**
	 * cname contact name
	 *
	 * @var string
	 */
	protected $cname;

    /**
	 * serialized contact data
	 *
	 * @var array
	 */
	protected $contact;

	/**
	 * Returns the cname
	 *
	 * @return string $cname
	 */
	public function getCname() {
		return $this->cname;
	}

	/**
	 * Sets the cname
	 *
	 * @param string $cname
	 * @return void
	 */
	public function setCname($cname) {
		$this->cname = $cname;
	}

	/**
	 * Returns the contact
	 *
	 * @return array $contact
	 */
	public function getContact() {
		return unserialize($this->contact);
	}

	/**
	 * Sets the contact
	 *
	 * @param array $contact
	 * @return void
	 */
	public function setContact($contact) {
		$this->contact = serialize($contact);
	}

}
