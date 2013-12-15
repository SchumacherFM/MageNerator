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
 * @subpackage viewhelpers
 */

/**
 * calls a php function
 *
 * = Examples =
 *
 * <code>
 * <mage:php type="lower">{text}</f:escape>
 * </code>
 * <output>
 * Text with & " ' < > * replaced by HTML entities (htmlspecialchars applied).
 * </output>
 *
 */
class Tx_Magenerator_ViewHelpers_PhpViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Escapes special characters with their escaped counterparts as needed.
	 *
	 * @param string $value
	 * @param string $type The php function which should be called
	 * @return string the altered string.
	 */
	public function render($value = NULL, $type = 'lower') {

		if ($value === NULL) {
			$value = $this->renderChildren();
		}

		if (!is_string($value)) {
			return $value;
		}

		switch ($type) {
			case 'lower':
				return strtolower($value);
			break;
			case 'upper':
				return strtolower($value);
			break;
			case 'ucfirst':
				return ucfirst($value);
            case 'boolString':
                $value = (int)$value;
                $value = (boolean)$value;
                return $value ? 'true' : 'false';
			default:
				return $value;
			break;
		}
	}
}
