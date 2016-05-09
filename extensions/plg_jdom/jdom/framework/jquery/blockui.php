<?php
/**
* @author		Girolamo Tomaselli http://bygiro.com - girotomaselli@gmail.com
* @license		GNU General Public License
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class JDomFrameworkJqueryBlockui extends JDomFrameworkJquery
{	

	var $assetName = 'blockui';
	
	var $attachJs = array();
	var $attachCss = array();
	
	protected static $loaded = array();	
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);
		
	}
	
	function build()
	{	
		// Only load once
		if (!empty(static::$loaded[__METHOD__]))
		{
			return;
		}
		$doc = JFactory::getDocument();

		// load language strings
		$this->loadLanguageFiles(true);		
		JText::script("PLG_JDOM_BLOCK_UI_DEFAULT_MESSAGE");
		
		// addresspicker manager files needed
		$this->attachJs[] = 'jquery.blockUI.js';
		$this->attachCss[] = 'blockui.css';
		
		static::$loaded[__METHOD__] = true;
	}
	
	function buildCss()
	{

	}
	
	function buildJs()
	{

	}
}