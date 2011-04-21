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
 * @category    ViewHelpers
 * @package     TYPO3
 * @subpackage  jasmine
 * @author      Rens Admiraal <r.admiraal@drecomm.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
class Tx_Jasmine_ViewHelpers_Be_ConfigurationViewHelper extends Tx_Fluid_ViewHelpers_Be_AbstractBackendViewHelper {

	/**
	 * The extension key.
	 * @var string
	 */
	protected $extensionKey;

	/**
	 * The relative path to the extension folder, relative to TYPO3_mainDir.
	 * @var string
	 */
	protected $baseUrl;

	/**
	 * The relative path to the document root, relative to the TYPO3_mainDir.
	 * @var string
	 */
	protected $backPath;

	/**
	 * @var t3lib_PageRenderer
	 */
	protected $pageRenderer;

	/**
	 * Initialize the ViewHelper.
	 *
	 * @return void
	 */
	public function initialize() {
		$this->extensionKey = strtolower($this->controllerContext->getRequest()->getControllerExtensionName());
		$this->backPath = str_repeat('../', substr_count(TYPO3_mainDir, '/'));
		$this->baseUrl = $this->backPath . t3lib_extMgm::siteRelPath($this->extensionKey);

		$doc = $this->getDocInstance();
		$this->pageRenderer = $doc->getPageRenderer();
	}

	/**
	 * Set the configuration for the pageRenderer.
	 *
	 * @param $extKey string The currently selected extensionKey, if set.
	 * @param $loadHtmlSpecRunner Load the SpecRunner on Ext.Ready yes / no.
	 * @return string
	 */
	public function render($extKey = NULL, $loadHtmlSpecRunner = FALSE) {

			// Enable debugging helpers if we are in development context
		if (!empty($_SERVER['TYPO3_CONTEXT']) && $_SERVER['TYPO3_CONTEXT'] === 'Development') {
			$this->pageRenderer->disableCompressJavascript();
			$this->pageRenderer->enableExtJsDebug();
		}

		if (!empty($extKey) && $loadHtmlSpecRunner === TRUE) {
			$this->pageRenderer->addExtOnReadyCode('TYPO3.Jasmine.App.loadHtmlSpecRunner("' . $extKey . '")');
		}

			// Load ExtDirect code
		$this->pageRenderer->addJsFile($this->backPath . TYPO3_mainDir . 'ajax.php?ajaxID=ExtDirect::getAPI&namespace=TYPO3.Jasmine', NULL, FALSE, TRUE);

			// Load JavaScript files
		$this->pageRenderer->addJsFile($this->baseUrl . 'Resources/Public/JavaScript/Main.js');

			// Load CSS StyleSheets
		$this->pageRenderer->addCssFile($this->baseUrl . 'Resources/Public/Stylesheets/Style.css');
	}

}

?>