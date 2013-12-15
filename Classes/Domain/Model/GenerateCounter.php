<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Cyrill Schumacher <cyrill@schumacher.fm>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 *
 * @package magenerator
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Tx_Magenerator_Domain_Model_GenerateCounter extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * FeUser
	 *
	 * @var integer
	 */
	protected $feuser;

	/**
	 * Name of the Extension, build via Namespace_Name
	 *
	 * @var string
	 */
	protected $extkey;

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
	 * Returns the feuser
	 *
	 * @return integer $feuser
	 */
	public function getFeuser() {
		return $this->feuser;
	}

    /**
	 * Returns the feuser
	 *
	 * @param integer $feuser
     * @return void
	 */
	public function setFeuser($feuser) {
		$this->feuser = $feuser;
	}

}
