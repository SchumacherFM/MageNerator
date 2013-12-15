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
 * extends the select viewhelper with the tag onchange
 *
 * @package magenerator
 * @subpackage viewhelper
 */
class Tx_Magenerator_ViewHelpers_Form_TextareaViewHelper extends Tx_Fluid_ViewHelpers_Form_TextareaViewHelper {


	/**
	 * Initialize the arguments.
	 *
	 * @return void
	 * @api
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerTagAttribute('onkeyup', 'string', 'keyup event', false);
		$this->registerTagAttribute('minlength', 'integer', 'min length',false);
	}

}
