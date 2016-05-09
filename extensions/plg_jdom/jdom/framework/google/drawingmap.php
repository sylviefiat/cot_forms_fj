<?php
/*
* @version		0.3.6
* @package		jForms
* @copyright	G. Tomaselli
* @author		Girolamo Tomaselli - http://bygiro.com - girotomaselli@gmail.com
* @license		GNU GPL v3 or later
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class JDomFrameworkGoogleDrawingmap extends JDomFrameworkGoogle
{	
	var $assetName = 'drawingmap';
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
		$doc->addScript('http://maps.googleapis.com/maps/api/js?sensor=false&libraries=drawing');
		JDom::_('framework.json3');
		
		// fix bootstrap CSS conflict
		$css = '.gm-style img{
			max-width: none !important;
		}';
		$doc->addStyleDeclaration($css);
		
		static::$loaded[__METHOD__] = true;
	}

}