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
class Tx_Magenerator_Domain_Model_Extname extends Tx_Extbase_DomainObject_AbstractValueObject {

	/**
	 * name
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * url
	 *
	 * @var string
	 */
	protected $url;

	/**
	 * extkey
	 *
	 * @var string
	 */
	protected $extkey;

	/**
	 * frontName
	 *
	 * @var string
	 */
	protected $frontName;

	/**
	 * Returns the name
	 *
	 * @return string $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets the name
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Returns the url
	 *
	 * @return string $url
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * Sets the url
	 *
	 * @param string $url
	 * @return void
	 */
	public function setUrl($url) {
		$this->url = $url;
	}

	/**
	 * Returns the extkey
	 *
	 * @return string $extkey
	 */
	public function getExtkey() {
		return $this->extkey;
	}

	/**
	 * Sets the extkey
	 *
	 * @param string $extkey
	 * @return void
	 */
	public function setExtkey($extkey) {
		$this->extkey = $extkey;
	}

	/**
	 * Returns the frontName
	 *
	 * @return string $frontName
	 */
	public function getFrontName() {
		return $this->frontName;
	}

	/**
	 * Sets the frontName
	 *
	 * @param string $frontName
	 * @return void
	 */
	public function setfFrontName($frontName) {
		$this->frontName = $frontName;
	}

}
