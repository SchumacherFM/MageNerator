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
 * Email service
 *
 * Contains quick-use emailing functions.
 *
 * @package magenerator
 * @subpackage service
 */
class Tx_Magenerator_Service_Email implements t3lib_Singleton {

	/**
	 * Send an email. Supports any to-string-convertible parameter types
	 *
	 * @param mixed $subject
	 * @param mixed $body
	 * @param mixed $recipientEmail
	 * @param mixed $recipientName
	 * @param mixed $fromEmail
	 * @param mixed $fromName
	 * @return integer the number of recipients who were accepted for delivery
	 * @api
	 */
	public function mail($subject, $body, $recipientEmail, $recipientName=NULL, $fromEmail=NULL, $fromName=NULL) {
		$mail = new t3lib_mail_Message();
		if ($recipientName == NULL) {
			$recipientName = $recipientEmail;
		}
		if ($fromEmail) {
			if ($fromName == NULL) {
				$fromName = $fromEmail;
			}
			$mail->setFrom(array($fromEmail => $fromName));
		}
		$mail->setTo(array($recipientEmail => $recipientName));
		$mail->setSubject($subject);
		$mail->setBody($body);
		return $mail->send();
	}

	/**
	 * Get a mailer (SwiftMailer) object instance
	 *
	 * @return t3lib_mail_Message;
	 * @api
	 */
	public function getMailer() {
		$mail = new t3lib_mail_Message();
		return $mail;
	}

}
