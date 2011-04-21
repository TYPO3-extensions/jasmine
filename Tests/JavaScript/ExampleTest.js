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
 * Definition of the 'Test' test script
 * @include EXT:jasmine/Resources/Public/JavaScript/TestLibraries/ExampleTest.js
 */
describe("Test", function() {

	beforeEach(function() {
	});

	it("Test 1", function() {
		expect(0).toBe(1);
	});

	it("Test 2", function() {
		expect(1).toBe(1);
	});

});