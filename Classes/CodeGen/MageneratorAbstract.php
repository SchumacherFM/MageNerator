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
 * this class provides some basic setter and getter
 *
 * @package magenerator
 * @subpackage service/codegen
 */
abstract class Tx_Magenerator_CodeGen_MageneratorAbstract extends Tx_Magenerator_CodeGen_MageneratorAbstractGlobal
{

    /**
     * contains the whole folder structure
     * @var array
     */
    protected $folderStructure = array();

    /**
     * absoute internal temp path where to save all the data
     * @var string
     */
    protected $tempPath;

    /**
     * inits all the necessary properties for a class
     * @param Tx_Magenerator_Domain_Model_FeUser $feUser
     * @param array $extConfig
     */
    public function init(Tx_Magenerator_Domain_Model_FeUser $feUser, $extConfig) {

        $this->setExtConfig($extConfig);
        $this->setFeUser($feUser);

        $this->tempPath = $this->getTempPath('abs', true);
        $this->initFolderStructure();
    }

    /**
     * main method for executing a class
     * @param none
     * @return void
     */
    abstract public function main();

    /**
     * sets the internal folderStructure Array
     * @return array the folders
     */
    protected function initFolderStructure() {


        $codePool = $this->getCodepool();
        $ns = $this->getUserExtensionNameSpace();
        $extName = $this->getExtname();

        $appPrefix = array('app', 'code', $codePool, $ns, $extName);

        // user must provide here the design path ...
        // $designFePrefix = array('app','design',  strtolower($ns),  strtolower($extName) );

        $this->folderStructure = array(
            'codePool' => array('app', 'code', $codePool),
            'base' => $appPrefix,
            'etc' => array_merge($appPrefix, array('etc')),
        );

        if (isset($this->extConfig['magenerator_gmodel']) && is_array($this->extConfig['magenerator_gmodel'])) {
            $this->folderStructure['model'] = array_merge($appPrefix, array('Model'));

            // if theres a model with a entity then create sql folder and subfolders
            if( isset($this->extConfig['magenerator_gmodel']['sql']) ){
                $this->folderStructure['sql'] = array_merge($appPrefix, array('sql',$this->getExtensionKey(true).'_sql'));
            }

        }

        // and so on ...
//            'block'=> array_merge($appPrefix, array('Block') ),
//            'controllers'=> array_merge($appPrefix, array('controllers') ),
//            'helper'=> array_merge($appPrefix, array('Helper') ),
//            'resource'=> array_merge($appPrefix, array('Model') ),
    }

    /**
     * returns the folder
     * @param string $type one of the arrays keys
     * @param boolean $implode default true
     * @return array|string
     */
    protected function getFolderStructure($type, $implode = true) {
        $path = $this->folderStructure[$type];
        if ($implode) {
            $path = $this->implodePath($path);
        }
        return $path;
    }

    /**
     * creates the folder structure
     * @return void
     */
    protected function folderStructure() {

        foreach ($this->folderStructure as $folder) {
            t3lib_div::mkdir_deep($this->tempPath . $this->implodePath($folder));
        }
    }

    /**
     *
     * @param string $type could be abs,rel or url
     * @param boolean $createOnHdd
     * @param boolean $removeUserDir
     * @return string
     */
    public function getTempPath($type = 'abs', $createOnHdd = false, $removeUserDir = false) {

        if( t3lib_div::getIndpEnv('HTTP_HOST') === 'magenerator.local' ){
            $userDir = $this->feUser->getUid();
        }else{
            $userDir = sha1($this->feUser->getUid() . $this->feUser->getEmail()); /* @todo maybe more values for sha */
        }

        switch ($type) {
            case 'abs':
                $path = array(substr(PATH_site, 0, -1), self::TYPO3TEMP, $userDir, '');
                break;
            case 'rel':
                $path = array('', self::TYPO3TEMP, $userDir, '');
                break;
            default:
                $path = array('http://' . t3lib_div::getHostname(true), self::TYPO3TEMP, $userDir, '');
        }

        $path = implode(DIRECTORY_SEPARATOR, $path);

        if ($createOnHdd && $type === 'abs') {
            t3lib_div::mkdir_deep($path);
        }

        if ($removeUserDir) {
            $path = str_replace($userDir . DIRECTORY_SEPARATOR, '', $path);
        }

        return $path;
    }

    /**
     * gets a relativ file path to a etc xml file
     * @param string $type can be config,adminhtml,system or widget
     * @return string the relative filepaht
     */
    protected function getEtcXmlFilename($type) {

        $etcPath = $this->implodePath($this->folderStructure['etc']);
        $absFilePath = '';
        switch ($type) {
            case 'config':
                $absFilePath = $etcPath . DIRECTORY_SEPARATOR . 'config.xml';
                break;
            case 'adminhtml':
                $absFilePath = $etcPath . DIRECTORY_SEPARATOR . 'adminhtml.xml';
                break;
            case 'system':
                $absFilePath = $etcPath . DIRECTORY_SEPARATOR . 'system.xml';
                break;
            case 'widget':
                $absFilePath = $etcPath . DIRECTORY_SEPARATOR . 'widget.xml';
                break;
        }
        return $absFilePath;
    }

    /**
     * wrapper for t3lib_div::writeFile
     *
     * @param string $targetFile the path and filename of the targetFile (relative to extension dir)
     * @param string $fileContents
     */
    protected function writeFile($targetFile, $fileContents) {

        if (empty($fileContents)) {
            t3lib_div::devLog('No file content! File ' . $targetFile . ' had no content', 'extension_builder', 0, $this->settings);
        }
        $success = t3lib_div::writeFile($targetFile, $fileContents);
        if (!$success) {
            throw new Exception('File ' . $targetFile . ' could not be created!');
        }
    }

    /**
     * writes a php file
     *
     * @param string $targetFile the path and filename of the targetFile (relative to extension dir)
     * @param string $fileContents
     */
    protected function writeFilePHP($targetFile, $fileContents) {

        $this->writeFile($targetFile, '<?php ' . PHP_EOL . $fileContents);
    }

    /**
     * injects a multidim array into a xml file and saves that file
     * @param string $xmlFile
     * @param array $array the multidim array to merge into it
     * @return boolean
     */
    protected function xmlInjectValue($xmlFile, $array) {

        $xml = new Tx_Magenerator_CodeGen_DOMParse();
        $xml->load($xmlFile);
        $xmlArray = $xml->xmlToArray();

        $xmlArray = array_merge_recursive($xmlArray, $array);
        // if here is a integer in a key then DomGen won't work

        $domGen = new Tx_Magenerator_CodeGen_DOMGen();
        $domGen->arrayToXml($xmlArray);

        $writtenBytes = $domGen->save($xmlFile);

        if (!$writtenBytes) {
            throw new Exception('File ' . $xmlFile . ' could not be read!');
        }
        return $writtenBytes;
    }

    /**
     *
     * @param string $path /a/b/c/d
     * @param mixed $value the last value to assign to
     * @param string $seperator cannot be empty, default is always slash
     * @return array
     */
    protected function getMultiDimArray($path, $value, $seperator = '/') {
        // 'config/global/models/'.$model_extkey.'/class'
        $seperator = empty($seperator) ? '/' : $seperator;
        $ep = t3lib_div::trimExplode($seperator, $path, true);

        if (count($ep) > 0) {
            $tmpIndex = array_shift($ep);
            return array($tmpIndex => $this->getMultiDimArray(implode('/', $ep), $value, $seperator));
        }

        return $value;
    }

    /**
     * converts a class name to a valid absolute filename, adds the temp path automatically
     * checks if file exsists, if not creates it and also the necessary folders
     * @param string $pathPrefix
     * @param string $className
     * @return string
     */
    protected function getFilenameFromClassName($pathPrefix = '', $className) {
        $className = $this->cleanForFileSystem($className);
        $classFile = str_replace(' ', DIRECTORY_SEPARATOR, ucwords(str_replace('_', ' ', $className)));

        if (substr($pathPrefix, -1, 1) !== '/') {
            $pathPrefix = $this->tempPath . $pathPrefix . DIRECTORY_SEPARATOR;
        }
        $classFile = $pathPrefix . $classFile . '.php';
        $classDir = dirname( $classFile);

        if( !is_dir($classDir) ){
            t3lib_div::mkdir_deep($classDir);
        }
        if( !touch($classFile) ) {
            throw new Exception('Cant create file: '.$classFile);
        }

        return $classFile;
    }

}
