<?php

$extensionClassesPath = t3lib_extMgm::extPath('jasmine') . 'Classes/';
return array(
	'tx_jasmine_services_specrunnerwriterservice' => $extensionClassesPath . 'Services/SpecRunnerWriterService.php',
	'tx_jasmine_services_testfinderservice' => $extensionClassesPath . 'Services/TestFinderService.php',
);