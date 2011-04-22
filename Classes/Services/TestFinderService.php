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
 *
 *
 * @category    Services
 * @package     TYPO3
 * @subpackage  jasmine
 * @author      Rens Admiraal <r.admiraal@drecomm.nl>
 */
class Tx_Jasmine_Services_TestFinderService {

	/**
	 * A multidimensional array with the following structure:
	 * extKey1
	 * 	- absolutePathToTest1
	 *  - absolutePathToTest2
	 * extKey2
	 *  - absolutePathToTest1
	 *
	 * Only extension keys with existing test files are included.
	 *
	 * @var array
	 */
	protected $testDefinitionList = array();

	/**
	 * Find the list of extension keys containing test files.
	 *
	 * @return array
	 */
	public function findExtensionsWithTests() {
		if (empty($this->testDefinitionList)) {
			$this->findTestDefinitions();
		}

		$extensionList = array(array(
			'extensionKey' => '-',
			'icon' => '',
			'name' => Tx_Extbase_Utility_Localization::translate('all_extensions', 'jasmine')
		));
		$extensionKeys = array_keys($this->testDefinitionList);
		foreach ($extensionKeys as $index => $extensionKey) {
			$path = preg_replace('#(' . PATH_typo3conf . 'ext/|' . PATH_typo3 . 'sysext/|/$)#', NULL, t3lib_extMgm::extPath($extensionKey));
			$nameParts = explode('_', $path);
			$nameParts = array_map('ucfirst', $nameParts);

			$extensionList[$extensionKey] = array(
				'extensionKey' => $extensionKey,
				'icon' => '/' . t3lib_extMgm::siteRelPath($extensionKey) . 'ext_icon.gif',
				'name' => implode(' ', $nameParts)
			);
		}

		return $extensionList;
	}

	/**
	 * Find all extensions with tests, and the actual test definition files.
	 *
	 * @return array
	 */
	public function findTestDefinitions() {
		$enabledExtensionList = t3lib_extMgm::getEnabledExtensionList();
		$enabledExtensionList = explode(',', $enabledExtensionList);

		foreach ($enabledExtensionList as $index => $extensionKey) {
			$testDirectory = t3lib_extMgm::extPath($extensionKey, 'Tests/JavaScript/');
			if (is_dir($testDirectory)) {
				$this->testDefinitionList[$extensionKey] = self::findTestDefinitionsFromDirectory($testDirectory);
			}
		}

			// Remove entries without test definitions
		foreach ($this->testDefinitionList as $index => $value) {
			if (empty($value)) {
				unset($this->testDefinitionList[$index]);
			}
		}

		return $this->testDefinitionList;
	}

	/**
	 * Recursively find all test definitions files in a directory.
	 *
	 * Test definition files have to end with Test.js, hidden folders (starting with a .) are ignored.
	 *
	 * @static
	 * @param string $directory
	 * @return array
	 */
	public static function findTestDefinitionsFromDirectory($directory) {
		$testFiles = array();
		$files = t3lib_div::getAllFilesAndFoldersInPath(array(), $directory, 'js', FALSE, 99, '\..*');
		foreach ($files as $index => $file) {
			if (substr($file, -7) === 'Test.js') {
				$testFiles[] = t3lib_div::getFileAbsFileName($file);
			}
		}

		return $testFiles;
	}

	/**
	 * Find includes in the test definitions.
	 *
	 * @param array $testDefinitions
	 * @return array
	 */
	public function findIncludesInTestDefinitions(array $testDefinitions) {
		$includes = array();

		foreach ($testDefinitions as $extensionKey => $testDefinitions) {
			foreach ($testDefinitions as $index => $testDefinition) {
				$this->findIncludesInTestDefinitionFile($testDefinition, $includes);
			}
		}
		$includes = array_unique($includes);
		return $includes;
	}

	/**
	 * Find includes in file.
	 *
	 * @param string $file Filename to find includes for.
	 * @param array $includes Array to which the includes found should be appended.
	 * @return void
	 */
	protected function findIncludesInTestDefinitionFile($file, array &$includes) {
		if (is_file($file)) {
			preg_match_all("/\/\*\*(.*?)\*\//s", file_get_contents($file), $docBlocks);
			foreach ($docBlocks[1] as $docBlockIndex => $docBlock) {
				preg_match_all("/@include ([^ \r\t\n]*)/", $docBlock, $blockIncludes);
				if (!empty($blockIncludes[1])) {
					foreach ($blockIncludes[1] as $index => $filename) {
						$filename = str_replace(PATH_site, NULL, t3lib_div::getFileAbsFileName($filename));
						if (!in_array($filename, $includes)) {
							$includes[] = $filename;
						}
					}
				}
			}
		}
	}

}

?>