<?php
/**
* @author		Girolamo Tomaselli http://bygiro.com - girotomaselli@gmail.com
* @license		GNU General Public License
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class JDomFrameworkJqueryEditorrepeatable extends JDomFrameworkJquery
{	

	var $assetName = 'Editorrepeatable';
	var $editor;
	
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
		
		/* example arguments */
		$this->arg('editor'	, null, $args);

		$editorsSupported = array('tinymce','jce');
		if(!$this->editor OR $this->editor == '' OR !in_array($this->editor,$editorsSupported)){
			$this->editor = 'tinymce';
		}		
	}
	
	function build($editorName = false)
	{	
		if(!$editorName){
			$editorName = $this->editor;
		}
		// Only load once
		if (!empty(static::$loaded[__METHOD__ . $editorName]))
		{
			return;
		}
		$doc = JFactory::getDocument();

		
		$editor = new CkJEditor($editorName);
		$editor->set( 'toolbar', 'Default' );
		$editor->_ckLoadEditor();
		$sc = $editor->getInitializerScript();
		
		// add script
		preg_match_all('/src="([^"]*)"/i',$sc, $matches);
		foreach($matches[1] as $v){
			$doc->addScript($v);
		}
	
		// add stylesheets
		preg_match_all('/href="([^"]*)"/i',$sc, $matches);
		foreach($matches[1] as $v){
			$doc->addStyleSheet($v);
		}
		
		$initializer = '';
		$script = '';
		switch($editorName){
			case 'codemirror':
				// to do
				break;
				
			case 'tinymce':	
				if(strpos($sc,'tinyMCE.init({') !== false){
					$startsAt = strpos($sc, "tinyMCE.init({") + strlen("tinyMCE.init({");
					$caller = 'tinyMCE';
				} else if(strpos($sc,'tinymce.init({') !== false){
					$startsAt = strpos($sc, "tinymce.init({") + strlen("tinymce.init({");
					$caller = 'tinymce';
				} else {
					break;
				}
				
				$endsAt = strpos($sc, "})", $startsAt);
				$result = substr($sc, $startsAt, $endsAt - $startsAt);	
				$script .= 'tinyMCE.settings = {'. $result .'}; ';
				
				$result = str_replace('"textarea.mce_editable"','selectorCss',$result);
				$initializer = 'function(selectorCss){					
				/*	'. $caller .'.init({'. $result .'});	*/		
				}';
				
				
				break;
				
			case 'jce':
				$initializer = 'function(selectorCss){				
					var ids = [];
					jQuery(selectorCss).each(function(){
						ids.push(jQuery(this).attr("id"));

						// add buttons
					});
					
					var customSettings = {
						editor_selector: "editor_jce",
						mode: "exact",
						elements: ids.join(",")
					};
					
					var settings = jQuery.extend({},WFEditor.settings,customSettings);
					WFEditor.init(settings);
					
					// fix for JCE multiple toggle buttons - remove the extra buttons
					jQuery(".form-widget").each(function(){
						jQuery(this)
							.find(".wf_editor_toggle")
							.not(":first-child")
							.remove();
					});
				}';
				break;
		}
	
		$script .= "if(typeof editors_repeatable != 'object') {editors_repeatable = {};}\n";
		
		if($initializer != ''){
			$script .= "editors_repeatable.". $editorName ." = ". $initializer;
		}
		$doc->addScriptDeclaration($script);
		
		static::$loaded[__METHOD__ . $editorName] = true;
	}
	
	function buildCss()
	{
	//	$this->attachCss[] = 'bootstrap.min.css';
	}
	
	function buildJs()
	{
	//	$this->attachCss[] = 'bootstrap.min.css';
	}
}