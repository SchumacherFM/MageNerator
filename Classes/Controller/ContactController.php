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
 * generates the code
 *
 * @package magenerator
 * @subpackage controller
 */
class Tx_Magenerator_Controller_ContactController
		extends Tx_Magenerator_Controller_AbstractController
{

    /**
     * Contacts Repository
     *
     * @var Tx_Magenerator_Domain_Repository_ContactsRepository
     * @inject
     */
    protected $contactsRepository;

    /**
     * validates if the contact form has been filled out correctly
     * dies on each failure so we stalk the user if he has disabled JS
     *
     * @param array $contact
     * @return void
     */
    private function checkRequiredFields($contact){

        if( !t3lib_div::validEmail($contact['email']) ){
            $this->returnMsgJson('email', true,'FeUser');
        }

        if( empty($contact['subject']) ){
            $this->returnMsgJson('subject', true);
        }

        if( empty($contact['message']) ){
            $this->returnMsgJson('message', true);
        }

        if( empty($contact['firstName']) ){
            $this->returnMsgJson('firstname', true,'FeUser');
        }

        if( empty($contact['lastName']) ){
            $this->returnMsgJson('lastname', true,'FeUser');
        }

    }


    /**
	 * shows the contact form
	 *
	 * @param none
	 * @return void
	 */
	public function formAction()
	{
        $countries = $this->feUserRepository->findAllCountries();
        $this->view->assign('countries', $countries);

		$user = $this->authService->getCurrentFrontendUser();
        $this->view->assign('feuser', $user);
	}

	/**
	 * sends an email
	 *
	 * @param array $contact data from the contact form
	 * @return void
	 */
	public function sendAction($contact)
	{
        // @todo validate for necessary values
        foreach($contact as $k=>$v){
            $contact[$k] = trim(strip_tags($v));
        }

        $this->checkRequiredFields($contact);

        $contactModel = new Tx_Magenerator_Domain_Model_Contacts();
        $contactModel->setCname( $contact['firstName'].' '.$contact['lastName'] );
        $contactModel->setContact($contact);
        $this->contactsRepository->add($contactModel);

   		$mailService = $this->objectManager->get('Tx_Magenerator_Service_Mail');
        $mailService->sendAdminContactMail($contact);

        $msg = $this->returnMsg('mailsend');
        $json = $this->jsonService->encode($msg);
        $this->view->assign('json', $json);
	}

}
