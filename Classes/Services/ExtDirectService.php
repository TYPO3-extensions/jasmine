<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Rens Admiraal <r.admiraal@drecomm.nl>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * ExtDirect service class.
 *
 * @category    Services
 * @package     TYPO3
 * @subpackage  jasmine
 * @author      Rens Admiraal <r.admiraal@drecomm.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
class Tx_Jasmine_Services_ExtDirectService {

	/**
	 * Service class to find tests and their includes.
	 *
	 * @var Tx_Jasmine_Services_TestFinderService
	 */
	protected $testFinderService;

	/**
	 * Service class to write the SpecRunner files.
	 *
	 * @var Tx_Jasmine_Services_SpecRunnerWriterService
	 */
	protected $specRunnerWriter;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {
		$this->testFinderService = t3lib_div::makeInstance('Tx_Jasmine_Services_TestFinderService');
		$this->specRunnerWriter = t3lib_div::makeInstance('Tx_Jasmine_Services_SpecRunnerWriterService');
	}

	/**
	 * Render the HTML SpecRunner file for given extKey (or all extensions if extKey == '-').
	 *
	 * @param string $extKey The extension key.
	 * @return array Response status array.
	 */
	public function getHtmlSpecRunner($extKey) {
		$testDefinitions = $this->testFinderService->findTestDefinitions();
		if ($extKey !== '-') {
			$testDefinitions = array($testDefinitions[$extKey]);
		}

		$includes = $this->testFinderService->findIncludesInTestDefinitions($testDefinitions);
		$testDefinitionIncludes = array();
		foreach ($testDefinitions as $extensionKey => $testDefinitions) {
			foreach ($testDefinitions as $index => $testDefinition) {
				$testDefinitionIncludes[] = str_replace(PATH_site, NULL, $testDefinition);
			}
		}

		$filename = $this->specRunnerWriter->writeSpecRunner($extKey, $testDefinitionIncludes, $includes, 'html');
		if ($filename) {
			return array('status' => 'success', 'file' => $filename);
		} else {
			return array('status' => 'failed');
		}
	}

}

?>