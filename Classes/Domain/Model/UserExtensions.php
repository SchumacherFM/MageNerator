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
class Tx_Magenerator_Domain_Model_UserExtensions extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * Serialized Array of the config values for one extension
	 *
	 * @var string
	 */
	protected $extension;

	/**
	 * Number of downloads
	 *
	 * @var integer
	 */
	protected $downloads;

	/**
	 * FeUser
	 *
	 * @var integer
	 */
	protected $feuser;

	/**
	 * isPublic
	 *
	 * @var boolean
	 */
	protected $isPublic = FALSE;

	/**
	 * Name of the Extension, build via Namespace_Name
	 *
	 * @var string
	 */
	protected $extkey;

    /**
     *
     * @var integer
     */
    protected $crdate;


    /**
	 * Returns the extension
	 *
	 * @return string $extension
	 */
	public function getExtension() {
		return unserialize($this->extension);
	}

	/**
	 * Sets the extension
	 *
	 * @param string $extension
	 * @return void
	 */
	public function setExtension($extension) {
		$this->extension = serialize($extension);
	}

    /**
     * gets the extension size in kilo bytes
     * @return float
     */
    public function getExtensionSize(){
        return sprintf('%.2f',strlen($this->extension) / 1024 );
    }

    /**
	 * Returns the downloads
	 *
	 * @return integer $downloads
	 */
	public function getDownloads() {
		return $this->downloads;
	}

	/**
	 * Sets the downloads
	 *
	 * @param integer $downloads
	 * @return void
	 */
	public function setDownloads($downloads) {
		$this->downloads = $downloads;
	}

	/**
	 * Returns the isPublic
	 *
	 * @return boolean $isPublic
	 */
	public function getIsPublic() {
		return $this->isPublic;
	}

	/**
	 * Sets the isPublic
	 *
	 * @param boolean $isPublic
	 * @return void
	 */
	public function setIsPublic($isPublic) {
		$this->isPublic = $isPublic;
	}

	/**
	 * Returns the boolean state of isPublic
	 *
	 * @return boolean
	 */
	public function isIsPublic() {
		return $this->getIsPublic();
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
	 * Returns the current date
	 *
	 * @return integer $crdate
	 */
	public function getCrdate() {
		return $this->crdate;
	}

	/**
	 * Returns the current date
	 *
	 * @return string $crdate
	 */
	public function getHumanCrdate() {
		return date('H:i:s<\b\r/>d.m.Y',$this->crdate);
	}

    /**
	 * Returns the feuser
	 *
	 * @return integer $feuser
	 */
	public function getFeuser() {
		return $this->feuser;
	}

}
