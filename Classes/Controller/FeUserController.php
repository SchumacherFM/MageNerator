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
 * @package magenerator
 * @subpackage controller
 */
class Tx_Magenerator_Controller_FeUserController extends Tx_Magenerator_Controller_AbstractController
{

    /**
     *
     * @var Tx_Magenerator_Domain_UserManagement_Div
     */
    private $umDiv;

    /**
     * Frontend User Groups
     *
     * @var Tx_Extbase_Domain_Repository_FrontendUserGroupRepository
     * @inject
     */
    protected $groupsRepository;

    /**
     * Invites
     *
     * @var Tx_Magenerator_Domain_Repository_InvitesRepository
     * @inject
     */
    protected $invitesRepository;

    /**
     * userExtensionsRepository
     *
     * @var Tx_Magenerator_Domain_Repository_UserExtensionsRepository
     * @inject
     */
    protected $userExtensionsRepository;

    /**
     *  init the class actions
     */
    public function initializeAction() {
        parent::initializeAction();
        $this->umDiv = new Tx_Magenerator_Domain_UserManagement_Div();
    }

    /**
     * @return void
     */
    public function registerAction() {

        $feUser = new Tx_Magenerator_Domain_Model_FeUser();

        $this->forward('userform', NULL, NULL, array(
            'feUser' => $feUser,
            'ftype' => 'create',
        ));
    }

    /**
     * @return void
     */
    public function editAction() {

        $feUser = $this->umDiv->getLoggedInUserObject();

        if (!$feUser || !is_object($feUser)) {
            $this->throwStatus(404, NULL, '<h1>User not found</h1>');
        }

        $this->forward('userform', NULL, NULL, array(
            'feUser' => $feUser,
            'ftype' => 'update',
        ));
    }

    /**
     * shows the form if a user wants to register
     * ftype = form type to display and the post action: register or edit
     *
     * @param Tx_Magenerator_Domain_Model_FeUser $feUser
     * @param string $ftype
     * @return void
     * @dontvalidate $feUser
     * @dontvalidate $ftype
     */
    public function userformAction(Tx_Magenerator_Domain_Model_FeUser $feUser, $ftype) {

        $countries = $this->feUserRepository->findAllCountries();

        $keyPair = $this->_getRSAKeyPair();

        $formTypeAction = ($ftype === 'update') ? 'update' : 'create';
        $isCreate = ($ftype === 'create');

        $userGroups = $this->groupsRepository->findAll( );


        $this->view->assign('userGroups', $userGroups);
        $this->view->assign('keyPair', $keyPair);
        $this->view->assign('feUser', $feUser);
        $this->view->assign('formTypeAction', $formTypeAction);
        $this->view->assign('isCreate', $isCreate);
        $this->view->assign('countries', $countries);
    }

    /**
     *
     * @param Tx_Magenerator_Domain_Model_FeUser $feUser
     * @param string $password2
     * @param string $ftype
     * @param integer $gtc
     * @param string $humanendtime
     * @param string $invite
     * @return void
     */
    public function saveAction(Tx_Magenerator_Domain_Model_FeUser $feUser, $password2, $ftype, $gtc = NULL, $humanendtime = NULL, $invite = NULL) {


        $formTypeAction = ($ftype === 'create') ? 'create' : 'update';

        $inviteModel = false;
        if ($formTypeAction === 'create') {
            $inviteModel = $this->_checkInvite($invite);
        }

        // @todo validate all the userdata and gtc ...
        $feUser->setHumanendtime($humanendtime);

        file_put_contents('/tmp/zzz.txt', var_export( $feUser->getUsergroup()  ,1) );

//        if ($feUser->getUsergroup()->count() == 0) {
//            $groupFree = $this->groupsRepository->findByUid(Tx_Magenerator_Domain_Model_FeUser::GROUP_ID_ACCOUNT_FREE);
//            $feUser->addUsergroup($groupFree);
//        }


        if ($formTypeAction === 'create' || $feUser->getPassword() <> '') {
            $saltedObj = $this->umDiv->getHashedPassword($password2);

            if ($saltedObj->error !== false) {
                $msg = $this->returnMsg('salting', true);
                echo $this->jsonService->encode($msg);
                exit;
            }

            $feUser->setPassword($saltedObj->salted);
        }
        else {
            $feUser->removePasswordProperty();
        }


        // checks all input from post
        $feUser = $this->_checkFeUser($feUser, $ftype, $gtc);


        // @todo check also for endtime
        $userExists = $this->feUserRepository->existsEmailUsername($feUser->getEmail());

        if ($userExists && (int) $userExists <> (int) $feUser->getUid()) {
            $this->returnMsgJson('userExists', true, NULL, null, array($feUser->getEmail()));
        }

        $feUser->setUsername($feUser->getEmail());


        // @todo generate here the key for the activiation email
        // add user to the repo
        if ($formTypeAction === 'create' || (int) $feUser->getUid() == 0) {

            // update the invite:
            if ($inviteModel && $inviteModel instanceof Tx_Magenerator_Domain_Model_Invites) {
                $this->_updateInvite($feUser, $inviteModel);
            }

            // @todo and see sendUserNotificationMailCreate
            $activationLink = '';

            $this->feUserRepository->add($feUser);
        }
        else {
            $this->feUserRepository->update($feUser);
        }

        // write the newly added user to the DB so that the login process can find it!
        $this->persistAll();

        if ($formTypeAction === 'create') {

            $loginOk = $this->umDiv->logUserIn($feUser, $saltedObj->plain);
            $msg = $this->returnMsg('register');
            if (!$loginOk || empty($loginOk)) {
                $msg = $this->returnMsg('registerNoLogin', true);
            }

            $mailService = $this->objectManager->get('Tx_Magenerator_Service_Mail');
            $mailService->sendAdminNotificationMailCreate($feUser);


            // @todo
//            $mailService->sendUserNotificationMailCreate($feUser,$activationLink);
        }
        else {  // update
            // @todo success messages
            $msg = $this->returnMsg('update');
        }



        $flash = $this->flashMessageContainer->getAllMessagesAndFlush();
        if (is_array($flash) && count($flash) > 0) {
            $msg['flashMessageContainer'] = $flash;
        }

        $this->view->assign('json', $this->jsonService->encode($msg));
    }

    /**
     * lists all the extensions of a user
     */
    public function extensionsAction() {

        $user = $this->checkLogin();

        $userext = $user->getSortedUserExt();

        $this->view->assign('userext', $userext);

    }

    /**
     * loads an extesion into the user session
     * @param Tx_Magenerator_Domain_Model_UserExtensions $ext
     */
    public function loadExtensionAction(Tx_Magenerator_Domain_Model_UserExtensions $ext){

        $user = $this->checkLogin();

        // security check if a user is loading its own extension
        // or the extension is marked as public
        if( $ext && ($user->getUid() == $ext->getFeuser() || $ext->getIsPublic() == 1) ){

            $this->sessionService->setAll( $ext->getExtension() );

        }

        $uri = $this->uriBuilder
                ->reset()
                ->setTargetPageUid(243)
                ->setCreateAbsoluteUri(true)
                ->buildFrontendUri();

        $this->redirectToUri($uri);
    }

    /**
     * @maybe this could be moved into the user model
     *
     * @return array
     */
    private function _getRSAKeyPair() {
        $backend = tx_rsaauth_backendfactory::getBackend();
        $keyPair = $backend->createNewKeyPair();
        /* @var $keyPair tx_rsaauth_keypair */

        // Save private key
        $storage = tx_rsaauth_storagefactory::getStorage();
        /* @var $storage tx_rsaauth_abstract_storage */
        $storage->put($keyPair->getPrivateKey());

        $return = array(
            'PublicKeyModulus' => htmlspecialchars($keyPair->getPublicKeyModulus()),
            'Exponent' => sprintf('%x', $keyPair->getExponent()),
        );

        return $return;
    }

    /**
     * checks if all settings for feUser are valid, we do this the annoying way because some disabled JS
     *
     * @param Tx_Magenerator_Domain_Model_FeUser $feUser
     * @param string $ftype
     * @param integer $gtc
     * @return boolean|Tx_Magenerator_Domain_Model_FeUser
     */
    private function _checkFeUser(Tx_Magenerator_Domain_Model_FeUser $feUser, $ftype, $gtc) {

        // only in production environment
        if (stristr(t3lib_div::getHostname(true), '.local') === false && t3lib_div::validEmail($feUser->getEmail())) {
            $this->returnMsgJson('email', true);
        }

        if( $feUser->getUsergroup() != Tx_Magenerator_Domain_Model_FeUser::GROUP_ID_ACCOUNT_FREE ){

            if (trim($feUser->getFirstName()) == '') {
                $this->returnMsgJson('firstname', true);
            }

            if (trim($feUser->getLastName()) == '') {
                $this->returnMsgJson('lastname', true);
            }
        }

        if ($ftype == 'create' && empty($gtc)) {
            $this->returnMsgJson('gtc', true);
        }


        return $feUser;
    }

    /**
     * checks if an invite is valid, if not throws a json error
     * @param string $invite
     * @return Tx_Magenerator_Domain_Model_Invites
     */
    private function _checkInvite($invite) {

        $theInvite = $this->invitesRepository->findOneByInvite($invite);

        if (!$theInvite || !method_exists($theInvite, 'getInvite') || ($theInvite && $theInvite->getEndtime() > 0 )) {
            $this->returnMsgJson('invite', true);
        }

        return $theInvite;
    }

    /**
     * adds the invite ID to the feuser object and gives the registered fe user a few more invites
     * sets the endtime of the invite object to the current timestamp
     *
     * @param Tx_Magenerator_Domain_Model_FeUser $feUser
     * @param Tx_Magenerator_Domain_Model_Invites $inviteModel
     */
    private function _updateInvite(Tx_Magenerator_Domain_Model_FeUser $feUser, Tx_Magenerator_Domain_Model_Invites $inviteModel) {

        $inviteModel->setEndtime(time());

        $this->invitesRepository->update($inviteModel);

        $feUser->setInvite($inviteModel->getInvite());

        $length = (int) $this->settings['invites']['perUser'];
        for ($i = 0; $i < $length; ++$i) {
            $newInvite = new Tx_Magenerator_Domain_Model_Invites();
            $newInvite->setInvite($this->generateInviteId());
            $feUser->addFeInvite($newInvite);
        }
    }

}
