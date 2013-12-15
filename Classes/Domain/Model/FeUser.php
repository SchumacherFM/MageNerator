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
class Tx_Magenerator_Domain_Model_FeUser extends Tx_Extbase_Domain_Model_FrontendUser implements Tx_Magenerator_Interfaces_FrontendUser {

	const GROUP_ID_ACCOUNT_FREE = 2;

    /**
     *
     * @see http://forge.typo3.org/issues/39562
     * @param none
     * @return void
     */
    public function __sleep() {
//        return $this->_getProperties();
    }

	/**
	 * @var integer
	 */
	protected $usergroup;

	/**
	 * @var string
	 */
	protected $password;

	/**
	 * namespace
	 *
	 * @var float
	 */
	protected $credit;

	/**
	 * endtime
	 *
	 * @var integer
	 */
	protected $endtime;

	/**
	 * namespace
	 *
	 * @var string
	 */
	protected $namespace;

	/**
	 * invite
	 *
	 * @var string
	 */
	protected $invite;

	/**
	 * All ext of a user
	 *
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_Magenerator_Domain_Model_UserExtensions>
	 * @lazy
	 */
	protected $userExt;

	/**
	 * feInvites
	 *
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_Magenerator_Domain_Model_Invites>
     * @lazy
	 */
	protected $feInvites;

	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		//Do not remove the next line: It would break the functionality
		$this->initStorageObjects();
	}

	/**
	 * Initializes all Tx_Extbase_Persistence_ObjectStorage properties.
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		$this->userExt = new Tx_Extbase_Persistence_ObjectStorage();
		$this->feInvites = new Tx_Extbase_Persistence_ObjectStorage();

	}

	/**
	 * Returns the uid as crc32 value
	 *
	 * @return string
	 */
	public function getUidCrc32() {
		return crc32($this->uid);
	}

	/**
	 * Returns the credit
	 *
	 * @return float $credit
	 */
	public function getCredit() {
		return $this->credit;
	}

	/**
	 * Sets the credit
	 *
	 * @param float $credit
	 * @return void
	 */
	public function setCredit($credit) {
		$this->credit = $credit;
	}

	/**
	 * Returns the endtime
	 *
	 * @return integer $endtime
	 */
	public function getEndtime() {
		return $this->endtime;
	}

	/**
	 * Sets the endtime
	 *
	 * @param integer $endtime
	 * @return void
	 */
	public function setEndtime($endtime) {
		$this->endtime = $endtime;
	}

	/**
	 * Returns the endtime human readable
	 *
	 * @return string $endtime
	 */
	public function getHumanendtime() {
        if( $this->getEndtime() > 0 ){
            $endtime = date('d.m.Y',$this->getEndtime() );
        }else{
            $endtime = '';
        }
		return $endtime;
	}

	/**
	 * Returns the endtime human readable
	 *
	 * @return string $endtime
	 */
	public function setHumanendtime($endtime) {
        $endtime = trim( (string)$endtime);

        $matches = array();
        preg_match_all('~([0-9]+)~', $endtime,$matches,PREG_SET_ORDER);

        if( empty($endtime) || count($matches) <> 3 ){
            $this->setEndtime(0);
            return true;
        }

            $part0 = (int)$matches[0][1];
            $part1 = (int)$matches[1][1];
            $part2 = (int)$matches[2][1];
            $year = date('Y');
            $day2 = $month2 = $year2 = 0;

        // us date
        if( $part0 >= 1 && $part0 <= 12 &&
            $part1 >= 1 && $part1 <= 31 &&
            $part2 >= $year
        ){
            $month2 = $part0;
            $day2 = $part1;
            $year2 = $part2;
        }

        // uk date
        if( $part0 >= 1 && $part0 <= 31 &&
            $part1 >= 1 && $part1 <= 12 &&
            $part2 >= $year
        ){
            $month2 = $part1;
            $day2 = $part0;
            $year2 = $part2;
        }

        // de date 2012.12.31
        if( $part2 >= 1 && $part2 <= 31 &&
            $part1 >= 1 && $part1 <= 12 &&
            $part0 >= $year
        ){
            $month2 = $part1;
            $day2 = $part2;
            $year2 = $part0;
        }

        if( $month2 == 0 || $day2 == 0 || $year2 == 0 ){
            return false;
        }

        $time = mktime(23,59,59, $month2, $day2, $year2);

        $this->setEndtime($time);
        return true;

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
	 * Adds a UserExtensions
	 *
	 * @param Tx_Magenerator_Domain_Model_UserExtensions $userExt
	 * @return void
	 */
	public function addUserExt(Tx_Magenerator_Domain_Model_UserExtensions $userExt) {
		$this->userExt->attach($userExt);
	}

	/**
	 * Removes a UserExtensions
	 *
	 * @param Tx_Magenerator_Domain_Model_UserExtensions $userExtToRemove The UserExtensions to be removed
	 * @return void
	 */
	public function removeUserExt(Tx_Magenerator_Domain_Model_UserExtensions $userExtToRemove) {
		$this->userExt->detach($userExtToRemove);
	}

	/**
	 * Returns the userExt
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_Magenerator_Domain_Model_UserExtensions> $userExt
	 */
	public function getUserExt() {
		return $this->userExt;
	}

	/**
	 * Returns the userExt sorted by crdate desc
	 *
	 * @return array $userExt
	 */
	public function getSortedUserExt() {

        $newSorted = array();
        $ue = $this->getUserExt();
        foreach($ue as $k=>$ext){
            $key = $ext->getCrdate(). '_' . str_pad($ext->getUid(), 10, '0',STR_PAD_LEFT);
            $newSorted[$key] = $ext;
        }
        krsort($newSorted);
		return $newSorted;
	}

	/**
	 * Sets the userExt
	 *
	 * @param Tx_Extbase_Persistence_ObjectStorage<Tx_Magenerator_Domain_Model_UserExtensions> $userExt
	 * @return void
	 */
	public function setUserExt(Tx_Extbase_Persistence_ObjectStorage $userExt) {
		$this->userExt = $userExt;
	}

    /**
     * removes the password property to disable updating of an empty string
     *
     * @return void
     */
    public function removePasswordProperty(){
        unset($this->password);
    }

	/**
	 * Adds a Invites
	 *
	 * @param Tx_Magenerator_Domain_Model_Invites $feInvite
	 * @return void
	 */
	public function addFeInvite(Tx_Magenerator_Domain_Model_Invites $feInvite) {
		$this->feInvites->attach($feInvite);
	}

	/**
	 * Removes a Invites
	 *
	 * @param Tx_Magenerator_Domain_Model_Invites $feInviteToRemove The Invites to be removed
	 * @return void
	 */
	public function removeFeInvite(Tx_Magenerator_Domain_Model_Invites $feInviteToRemove) {
		$this->feInvites->detach($feInviteToRemove);
	}

	/**
	 * Returns the feInvites
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_Magenerator_Domain_Model_Invites> $feInvites
	 */
	public function getFeInvites() {
		return $this->feInvites;
	}

	/**
	 * Sets the feInvites
	 *
	 * @param Tx_Extbase_Persistence_ObjectStorage<Tx_Magenerator_Domain_Model_Invites> $feInvites
	 * @return void
	 */
	public function setFeInvites(Tx_Extbase_Persistence_ObjectStorage $feInvites) {
		$this->feInvites = $feInvites;
	}

	/**
	 * Returns the invite
	 *
	 * @return string $invite
	 */
	public function getInvite() {
		return $this->invite;
	}

	/**
	 * Sets the invite
	 *
	 * @param string $invite
	 * @return void
	 */
	public function setInvite($invite) {
		$this->invite = $invite;
	}


	/**
	 * @return integer
	 */
	public function getUsergroup() {
		return $this->usergroup;
	}

	/**
	 * @param integer $usergroup
	 * @return void
	 */
	public function setUsergroup($usergroup) {
		$this->usergroup = $usergroup;
	}


}
