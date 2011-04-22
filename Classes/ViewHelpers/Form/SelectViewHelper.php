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
 */
class Tx_Jasmine_ViewHelpers_Form_SelectViewHelper extends Tx_Fluid_ViewHelpers_Form_SelectViewHelper {

	/**
	 * Render the option tags.
	 *
	 * @return array an associative array of options, key will be the value of the option tag.
	 */
	protected function getOptions() {
		if (!is_array($this->arguments['options']) && !($this->arguments['options'] instanceof Traversable)) {
			return array();
		}
		$options = array();

		$optionsArgument = $this->arguments['options'];

		foreach ($optionsArgument as $key => $value) {
			if ($this->arguments->hasArgument('optionValueField')) {
				$key = $value[$this->arguments['optionValueField']];
			}

			if ($this->arguments->hasArgument('optionLabelField')) {
				$value = array(
					'icon' => !empty($value['icon']) ? $value['icon'] : NULL,
					'label' => $value[$this->arguments['optionLabelField']]
				);
			}

			$options[$key] = $value;
		}

		return $options;
	}

	/**
	 * Render one option tag.
	 *
	 * @param string $value value attribute of the option tag (will be escaped).
	 * @param array $label content of the option tag (will be escaped).
	 * @param boolean $isSelected specifies wheter or not to add selected attribute.
	 * @return string the rendered option tag.
	 */
	protected function renderOptionTag($value, $label, $isSelected) {
		$background = 'background: white;';
		if (!empty($label['icon'])) {
			$background = "background: white no-repeat url('" . $label['icon'] . "') 3px 50%; ";
		}

		$output = '<option value="' . htmlspecialchars($value) . '"';
		if ($isSelected) {
			$output .= ' selected="selected"';
		}
		$output .= ' style="' . $background . 'padding: 1px 1px 1px 24px;">' . htmlspecialchars($label['label']) . '</option>';

		return $output;
	}

}

?>