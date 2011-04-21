<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ExtDirect']['TYPO3.Jasmine.ExtDirect'] =
	t3lib_extMgm::extPath('jasmine') . 'Classes/Services/ExtDirectService.php:Tx_Jasmine_Services_ExtDirectService';

?>