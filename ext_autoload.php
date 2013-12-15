<?php
// This class contains information about classes to be needed for the code generation

$extensionZendPath = t3lib_extMgm::extPath('magenerator') . 'Classes/Zend/';

return array(
    'zend\code\generator\classgenerator' => $extensionZendPath . 'Code/Generator/ClassGenerator.php',
    'zend\code\generator\abstractgenerator' => $extensionZendPath . 'Code/Generator/AbstractGenerator.php',
    'zend\code\generator\generatorinterface'=> $extensionZendPath . 'Code/Generator/GeneratorInterface.php',
    'zend\code\generator\docblockgenerator'=> $extensionZendPath . 'Code/Generator/DocBlockGenerator.php',
    'zend\code\generator\docblock\tag'=> $extensionZendPath . 'Code/Generator/DocBlock/Tag.php',
    'zend\code\generator\docblock\tag\licensetag'=> $extensionZendPath . 'Code/Generator/DocBlock/Tag/LicenseTag.php',
    'zend\code\generator\docblock\tag\paramtag'=> $extensionZendPath . 'Code/Generator/DocBlock/Tag/ParamTag.php',
    'zend\code\generator\docblock\tag\returntag'=> $extensionZendPath . 'Code/Generator/DocBlock/Tag/ReturnTag.php',
    'zend\code\generator\propertygenerator' => $extensionZendPath . 'Code/Generator/PropertyGenerator.php',
    'zend\code\generator\propertyvaluegenerator' => $extensionZendPath . 'Code/Generator/PropertyValueGenerator.php',
    'zend\code\generator\abstractmembergenerator' => $extensionZendPath . 'Code/Generator/AbstractMemberGenerator.php',
    'zend\code\generator\valuegenerator' => $extensionZendPath . 'Code/Generator/ValueGenerator.php',
    'zend\code\generator\methodgenerator' => $extensionZendPath . 'Code/Generator/MethodGenerator.php',
    'zend\code\generator\parametergenerator' => $extensionZendPath . 'Code/Generator/ParameterGenerator.php',

);