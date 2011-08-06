<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Rens Admiraal <rens.admiraal@typo3.org>
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
 * @author      Rens Admiraal <rens.admiraal@typo3.org>
 */
class Tx_Jasmine_Services_SpecRunnerWriterService {

	/**
	 * Fluid StandAloneView for rendering the content of the SpecRunner.
	 *
	 * @var Tx_Fluid_View_StandaloneView
	 */
	protected $view;

	/**
	 * Constructor
	 * @return void
	 */
	public function __construct() {
		$this->view = t3lib_div::makeInstance('Tx_Fluid_View_StandaloneView');
	}

	/**
	 * Write a SpecRunner file.
	 *
	 * @param string $extKey The extension key.
	 * @param array $testDefinitionIncludes Array of test definition files to include.
	 * @param array $includes Array of JavaScript files to include before the test definitions.
	 * @param string $type The SpecRunner type (HTML / JUnit / CLI).
	 * @return string The SpecRunner filename.
	 */
	public function writeSpecRunner($extKey, array $testDefinitionIncludes, array $includes, $type) {
		$this->view->setTemplatePathAndFilename(t3lib_extMgm::extPath('jasmine', 'Resources/Private/Standalone/SpecRunner.html'));
		$this->view->assignMultiple(array(
			'extPath' => t3lib_extMgm::siteRelPath('jasmine'),
			'includes' => $includes,
			'specs' => $testDefinitionIncludes
		));

		return $this->writeSpecRunnerToTempdirectory($extKey, $type, $this->view->render());
	}

	/**
	 * Write the SpecRunner file to typo3temp.
	 *
	 * @param string $extKey The extension key.
	 * @param string $type Type of the SpecRunner (HTML / JUnit / CLI).
	 * @param string $content SpecRunner file contents.
	 * @return string The SpecRunner filename.
	 */
	private function writeSpecRunnerToTempdirectory($extKey, $type, $content) {
		try {
			if (!is_dir(PATH_site . 'typo3temp/tx_jasmine/')) {
				t3lib_div::mkdir_deep(PATH_site . 'typo3temp', 'tx_jasmine');
			}

			$filename = 'typo3temp/tx_jasmine/' . $type . '_' . t3lib_div::shortMD5($extKey) . '.html';
			file_put_contents(PATH_site . $filename, $content);
			return $filename;
		} catch (Exception $e) {
			return NULL;
		}
	}

}

?>