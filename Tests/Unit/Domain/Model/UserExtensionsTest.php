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
 *  the Free Software Foundation; either version 2 of the License, or
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
 * Test case for class Tx_Magenerator_Domain_Model_UserExtensions.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @package TYPO3
 * @subpackage MageNerator
 *
 * @author Cyrill Schumacher <cyrill@schumacher.fm>
 */
class Tx_Magenerator_Domain_Model_UserExtensionsTest extends Tx_Extbase_Tests_Unit_BaseTestCase {
	/**
	 * @var Tx_Magenerator_Domain_Model_UserExtensions
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new Tx_Magenerator_Domain_Model_UserExtensions();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function getExtensionReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setExtensionForStringSetsExtension() {
		$this->fixture->setExtension('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getExtension()
		);
	}

	/**
	 * @test
	 */
	public function getDownloadsReturnsInitialValueForInteger() {
		$this->assertSame(
			0,
			$this->fixture->getDownloads()
		);
	}

	/**
	 * @test
	 */
	public function setDownloadsForIntegerSetsDownloads() {
		$this->fixture->setDownloads(12);

		$this->assertSame(
			12,
			$this->fixture->getDownloads()
		);
	}

	/**
	 * @test
	 */
	public function getIsPublicReturnsInitialValueForBoolean() {
		$this->assertSame(
			TRUE,
			$this->fixture->getIsPublic()
		);
	}

	/**
	 * @test
	 */
	public function setIsPublicForBooleanSetsIsPublic() {
		$this->fixture->setIsPublic(TRUE);

		$this->assertSame(
			TRUE,
			$this->fixture->getIsPublic()
		);
	}

	/**
	 * @test
	 */
	public function getZipFilenameReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setZipFilenameForStringSetsZipFilename() {
		$this->fixture->setZipFilename('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getZipFilename()
		);
	}

}
?>