/**
 * @license Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.removePlugins = 'elementspath, save, print, templates, smiley, about, div, forms, pagebreak, a11yhelp';
	config.enterMode = CKEDITOR.ENTER_P;
	
	config.extraPlugins = 'youtube';
};
