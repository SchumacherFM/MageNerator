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
 * @subpackage service/codegen
 */
class Tx_Magenerator_CodeGen_MageneratorGmodel extends Tx_Magenerator_CodeGen_MageneratorAbstract
{
    /**
     * for internal communication between methods if they need specific values...
     * @var array
     */
//    private $communicatorObject = array();

    /**
     * DocBlock Generator
     * @var Tx_Magenerator_CodeGen_DocBlock
     */
    protected $docblock;

    /**
     * sets the internal communicator obj
     * @param string $k
     * @param mixed $v
     */
//    private function setCO($k,$v){
//        $this->communicatorObject[$k] = $v;
//    }

    /**
     * gets an mixed value from the internal communicator obj
     * @param strings $k
     * @return mixed or false on failure
     */
//    private function getCO($k){
//        return isset($this->communicatorObject[$k]) ? $this->communicatorObject[$k] : false;
//    }

    /**
     * main method for executing this class
     */
    public function main() {
        $this->docblock = t3lib_div::makeInstance('Tx_Magenerator_CodeGen_DocBlock');
        $this->docblock->init($this->getFeUser(), $this->extConfig);
        $this->appCodeModel();
    }

    /**
     * gets for different magento version the name of the resource folder
     * @return string
     */
    private function getResourceFolder($toLower=false){
        $resource = 'Resource';
        if ($this->versionCompare('1_5')) {
            $resource = 'Mysql4';
        }
        return $toLower ? strtolower($resource) : $resource;
    }


    /**
     * creates all necessary code for a model
     */
    public function appCodeModel() {

        $model = $this->getExtConfigValue('magenerator_gmodel');
        $extKey = $this->getExtensionKey();

        if (!is_array($model) || count($model) == 0) {
            return false;
        }

        $modelExtKey = strtolower($extKey);
        $modelName = $extKey . '_Model';



        $xmlModel = $this->getMultiDimArray('config/global/models/' . $modelExtKey . '/class', $modelName);

        $xmlConfigFile = $this->tempPath . $this->getEtcXmlFilename('config');
        $success = $this->xmlInjectValue($xmlConfigFile, $xmlModel);

        // create resource xml entry
        if( isset($model['sql']) ){
            $modelResourceXmlName = $this->getExtensionKey(true).'_'.$this->getResourceFolder(true);

            $xmlModelRes = $this->getMultiDimArray('config/global/models/' . $modelExtKey . '/resourceModel', $modelResourceXmlName);
            $xmlModelResClass = $this->getMultiDimArray('config/global/models/' . $modelResourceXmlName . '/class',
                $modelName.'_'.$this->getResourceFolder() );
            $xmlModelRes = array_merge_recursive($xmlModelRes,$xmlModelResClass);

            foreach($model['sql'] as $sModelName=>$sModelCfg){
                $sModelName = strtolower($sModelName);
                $xmlModelResEntities = $this->getMultiDimArray('config/global/models/' . $modelResourceXmlName .
                        '/entities/'.$sModelName.'/table', strtolower($extKey.'_'.$sModelName) );
                $xmlModelRes = array_merge_recursive($xmlModelRes,$xmlModelResEntities);
            }

            $xmlModelResSetup = $this->getMultiDimArray('config/global/resources/' . $this->getExtname(true).'_setup' .
                    '/setup/module', $extKey );
            $xmlModelRes = array_merge_recursive($xmlModelRes,$xmlModelResSetup);

            $xmlModelResSetupClass = $this->getMultiDimArray('config/global/resources/' . $this->getExtname(true).'_setup' .
                    '/setup/class', $modelName.'_'.$this->getResourceFolder().'_Setup' );
            $xmlModelRes = array_merge_recursive($xmlModelRes,$xmlModelResSetupClass);

//        var_dump($xmlModelRes); exit;


            $success = $this->xmlInjectValue($xmlConfigFile, $xmlModelRes);

        } //endif

        // creating the files
        $this->generateModel('Blank');

        $this->generateModel('Resource');
    }

    /*     * ****************************************************************************************************** */

    /**
     * generates a blank model for mage > 1.6
     * @see appCodeModel()
     * @return false if nothnig will be generated
     */
    protected function generateModel($type) {
        $model = $this->getExtConfigValue('magenerator_gmodel');
//        $extKey = $this->getExtensionKey();

        $modelType = $type == 'Blank' ? 'blankModels' : 'sql';
        if( !isset($model[$modelType]) ){
            return false;
        }

        foreach ($model[$modelType] as $name => $model) {

            $mthType = 'generateModel' . $type . 'Code';
            $codeClassObj = $this->$mthType($name, $model);
//            echo '<pre>' . htmlspecialchars($code->generate()) . '</pre>';

            if (is_array($codeClassObj)) {

                foreach ($codeClassObj as $codeModelName => $codeObject) {

                    $filename = $this->getFilenameFromClassName($this->getFolderStructure('codePool'), $codeObject->getName());
                    $this->writeFilePHP($filename, $codeObject->generate());
                } // endforeach
            }
            else {
                $filename = $this->getFilenameFromClassName($this->getFolderStructure('codePool'), $codeClassObj->getName());
                $this->writeFilePHP($filename, $codeClassObj->generate());
            }
        } // endforeach

        if( $type === 'Resource' ){
            $this->generateModelSetup();
        }
        return true;
    }

    /**
     * generates a blank model code
     * @param string $key
     * @param string $modelName
     * @return Zend\Code\Generator\ClassGenerator
     */
    protected function generateModelBlankCode($key, $modelName) {

        $modelNameTpl = $this->getExtensionKey() . '_Model_';

        $model = new Zend\Code\Generator\ClassGenerator();

        $docBlockClass = $this->docblock->getDocBlockClass(array('subpackage' => 'Model'));

        $model->setName($modelNameTpl . $modelName)
                ->setExtendedClass('Mage_Core_Model_Abstract')
                ->setDocblock($docBlockClass);


        $property = new Zend\Code\Generator\PropertyGenerator();
        $property->setName('stdProperty');
        $property->setVisibility('protected');

        $docBlockProperty = $this->docblock->getDocBlockProperty();
        $property->setDocBlock($docBlockProperty);
        $model->addPropertyFromGenerator($property);

        $tags = array(
            array(
                'name'=>'param',
                'description'=>'string $parameter1 This a sample parameter',
            ),
        );
        $docBlockMethod = $this->docblock->getDocBlockMethod($tags);
        $method = new \Zend\Code\Generator\MethodGenerator();
        $method->setName('stdMethod');
        $parameter = new \Zend\Code\Generator\ParameterGenerator();
        $parameter->setName('parameter1');
        $parameter->setType('string');
        $parameter->setPosition(1);
        $method->setParameter($parameter);
        $method->setDocBlock($docBlockMethod);
        $method->setBody('// here happens black magic');
        $model->addMethodFromGenerator($method);

        return $model;
    }

    /**
     * generates a blank resource model
     * @see appCodeModel()
     * @param string $modelName
     * @param array $sql
     * @return Zend\Code\Generator\ClassGenerator
     */
    protected function generateModelResourceCode($modelName, $sql = array()) {

//        var_dump($modelName);
//        var_dump($sql);
//        exit;

        $extKeyResourceNameTpl = $this->getExtensionKey() . '_Model_' . $this->getResourceFolder() . '_';
        $modelNameTpl = $this->getExtensionKey() . '_Model_';
        $extName = $this->getExtname(true);



        // find primary column name ...
        $sqlIdName = 'id_column_is_undefined';
        foreach($sql as $cols){
            if( (int)$cols['primary'] === 1 ){
                $sqlIdName = $cols['name'];
                break;
            }
        }

        $modelContainer = array();

        /* <Model> */
        $model = new Zend\Code\Generator\ClassGenerator();
        $docBlockClass = $this->docblock->getDocBlockClass(array('subpackage' => 'model'));
        $model->setName($modelNameTpl . $modelName)
                ->setExtendedClass('Mage_Core_Model_Abstract')
                ->setDocblock($docBlockClass);
        $modelContainer[$model->getName()] = $model;

        $docBlockMethod = $this->docblock->getDocBlockMethod(array(),'Initialize resource model');
        $method = new \Zend\Code\Generator\MethodGenerator();
        $method->setName('_construct');
        $method->setVisibility('protected');
        $method->setDocBlock($docBlockMethod);
        $method->setBody('$this->_init(\'' . $extName . '/' . strtolower($modelName) . '\');');
        $model->addMethodFromGenerator($method);
        /* </Model> */


        /* <Resource Model> */
        $modelResource = new Zend\Code\Generator\ClassGenerator();
        $docBlockClass = $this->docblock->getDocBlockClass(array('subpackage' => $this->getResourceFolder() ));
        $modelResource->setName($extKeyResourceNameTpl . $modelName)
                ->setExtendedClass('Mage_Core_Model_Resource_Db_Abstract')
                ->setDocblock($docBlockClass);

        $docBlockMethod = $this->docblock->getDocBlockMethod(array(),'Initialize connection and define main table');
        $method = new \Zend\Code\Generator\MethodGenerator();
        $method->setName('_construct');
        $method->setVisibility('protected');
        $method->setDocBlock($docBlockMethod);
        $method->setBody('$this->_init(\'' . $extName . '/' . strtolower($modelName) . '\',\''.$sqlIdName.'\');');
        $modelResource->addMethodFromGenerator($method);
        $modelContainer[$modelResource->getName()] = $modelResource;
        /* </Resource Model> */


        /* <Resource Model Collection> */
        $modelResource = new Zend\Code\Generator\ClassGenerator();
        $docBlockClass = $this->docblock->getDocBlockClass(array('subpackage' => $this->getResourceFolder(true).'_collection'));
        $modelResource->setName($extKeyResourceNameTpl . $modelName.'_Collection')
                ->setExtendedClass('Mage_Core_Model_Resource_Db_Collection_Abstract')
                ->setDocblock($docBlockClass);

        $docBlockMethod = $this->docblock->getDocBlockMethod(array(),'Initialize resource model for collection');
        $method = new \Zend\Code\Generator\MethodGenerator();
        $method->setName('_construct');
        $method->setVisibility('protected');
        $method->setDocBlock($docBlockMethod);
        $method->setBody('$this->_init(\'' . $extName . '/' . strtolower($modelName) . '\');');
        $modelResource->addMethodFromGenerator($method);
        $modelContainer[$modelResource->getName()] = $modelResource;
        /* </Resource Model Collection> */

        return $modelContainer;
    }

    protected function generateModelSetup(){

        $model = $this->getExtConfigValue('magenerator_gmodel');
        if( !isset($model['sql']) ){
            return false;
        }

        $extKeyResourceNameTpl = $this->getExtensionKey() . '_Model_' . $this->getResourceFolder() . '_';

        /*<Resource Setup>*/
        $modelSetup = new Zend\Code\Generator\ClassGenerator();
        $docBlockClass = $this->docblock->getDocBlockClass(array('subpackage' => $this->getResourceFolder(true).'_setup'));
        $modelSetup->setName($extKeyResourceNameTpl . 'Setup')
                ->setExtendedClass('Mage_Core_Model_Resource_Setup')
                ->setDocblock($docBlockClass);

        $filename = $this->getFilenameFromClassName($this->getFolderStructure('codePool'), $modelSetup->getName());
        $this->writeFilePHP($filename, $modelSetup->generate());
        /*</Resource Setup>*/

        $sqlPath = $this->getFolderStructure('sql');
        $version = $this->getExtConfigValue('magenerator_gcommon/version');
        $sqlFilename = $sqlPath.DIRECTORY_SEPARATOR.'install-'.$version.'.php';

        if( $this->versionCompare('1_5') ){
            $sqlFilename = $sqlPath.DIRECTORY_SEPARATOR.'mysql4-install-'.$version.'.php';
        }


        $phptVersion = $this->versionCompare('1_5') ? '1_5' : '1_6'; /* also for 2_0 */

//        var_dump($model['sql']);

        $assign = array(
            'modelResourceSetupName'=>$modelSetup->getName(),
            'extKey'=>$this->getExtensionKey(true),
            'sql'=>$model['sql'],
            'ClassNameVarienDbDdlTable'=> Tx_Magenerator_Helpers_Magento::CLASS_VARIEN_DB_DDL_TABLE,
        );

        $phpt = $this->templateRenderer->renderFileTemplate('sql/install-'.$phptVersion, $assign );

//        echo '<pre>'.htmlspecialchars($phpt).'</pre>';

        $this->writeFile($this->tempPath . $sqlFilename, $phpt);

//        var_dump($model['sql']);


    }

}