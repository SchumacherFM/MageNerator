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
class Tx_Magenerator_Domain_Model_Extns extends Tx_Extbase_DomainObject_AbstractValueObject {

	/**
	 * namespace
	 *
	 * @var string
	 */
	protected $namespace;

	/**
	 * url
	 *
	 * @var string
	 */
	protected $url;

	/**
	 * A Namespace has n extensions
	 *
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_Magenerator_Domain_Model_Extname>
	 * @lazy
	 */
	protected $extensions;

	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
		//Do not remove the next line: It would break the functionality
		$this->initStorageObjects();
	}

	/**
	 * Initializes all Tx_Extbase_Persistence_ObjectStorage properties.
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		/**
		 * Do not modify this method!
		 * It will be rewritten on each save in the extension builder
		 * You may modify the constructor of this class instead
		 */
		$this->extensions = new Tx_Extbase_Persistence_ObjectStorage();
	}

	/**
	 * Returns the namespace
	 *
	 * @return string $namespace
	 */
	public function getNamespace() {
		return $this->namespace;
	}

	/**
	 * Sets the namespace
	 *
	 * @param string $namespace
	 * @return void
	 */
	public function setNamespace($namespace) {
		$this->namespace = $namespace;
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
	 * Adds a Extname
	 *
	 * @param Tx_Magenerator_Domain_Model_Extname $extension
	 * @return void
	 */
	public function addExtension(Tx_Magenerator_Domain_Model_Extname $extension) {
		$this->extensions->attach($extension);
	}

	/**
	 * Removes a Extname
	 *
	 * @param Tx_Magenerator_Domain_Model_Extname $extensionToRemove The Extname to be removed
	 * @return void
	 */
	public function removeExtension(Tx_Magenerator_Domain_Model_Extname $extensionToRemove) {
		$this->extensions->detach($extensionToRemove);
	}

	/**
	 * Returns the extensions
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_Magenerator_Domain_Model_Extname> $extensions
	 */
	public function getExtensions() {
		return $this->extensions;
	}

	/**
	 * Sets the extensions
	 *
	 * @param Tx_Extbase_Persistence_ObjectStorage<Tx_Magenerator_Domain_Model_Extname> $extensions
	 * @return void
	 */
	public function setExtensions(Tx_Extbase_Persistence_ObjectStorage $extensions) {
		$this->extensions = $extensions;
	}

}
