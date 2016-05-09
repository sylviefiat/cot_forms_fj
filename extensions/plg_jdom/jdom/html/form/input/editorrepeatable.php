<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <     JDom Class - Cook Self Service library    |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		2.5
* @package		Cook Self Service
* @subpackage	JDom
* @license		GNU General Public License
* @author		Jocelyn HUARD
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class JDomHtmlFormInputEditorrepeatable extends JDomHtmlFormInput
{
	var $level = 4;			//Namespace position
	var $last = true;		//This class is last call


	var $cols;
	var $rows;
	var $width;
	var $height;
	var $editor;

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@domID		: HTML id (DOM)  default=dataKey
	 *
	 *
	 * 	@cols		: Textarea width (in caracters)
	 * 	@rows		: Textarea height (in caracters)
	 * 	@width		: Textarea width (in px)
	 * 	@height		: Textarea height (in px)
	 * 	@editor		: Editor name (for example, 'tinymce'). If null then the current editor will be returned
	 * 	@domClass	: CSS class
	 * 	@selectors	: raw selectors (Array) ie: javascript events
	 */
	function __construct($args)
	{

		parent::__construct($args);

		$this->arg('cols'		, null, $args, '32');
		$this->arg('rows'		, null, $args, '4');
		$this->arg('width'		, null, $args, '100%');
		$this->arg('height'		, null, $args, $this->rows * 20);
		$this->arg('editor'		, null, $args);
		$this->arg('buttons'	, null, $args, false);
		$this->arg('domClass'	, null, $args);
		$this->arg('selectors'	, null, $args);
		
		$editorsSupported = array('tinymce','jce');
		if(!$this->editor OR $this->editor == '' OR !in_array($this->editor,$editorsSupported)){
			$this->editor = 'tinymce';
		}

		if(is_string($this->buttons) AND strpos($this->buttons,',') !== false){
			$this->buttons = explode(',',$this->buttons);
		} else if($this->buttons !== false){
			$this->buttons = true;
		}		
	}

	function build()
	{
		JDom::_('framework.jquery.editorrepeatable',array(
								'editor'=>$this->editor
								)
		);
		
		$html = '';

		$html =	'<textarea id="<%DOM_ID%>" data-editor="'. $this->editor .'" name="<%INPUT_NAME%>"<%STYLE%><%CLASS%><%SELECTORS%>'
			.	' cols="' . $this->cols . '"'
			.	' rows="' . $this->rows . '"'
			.	'>'
			.	'<%VALUE%>'
			.	'</textarea>' .LN
			.	'<%VALIDOR_ICON%>'.LN
			.	'<%MESSAGE%>';		

		switch($this->editor){
			case 'tinymce':
				// toggle button
				if($this->buttons !== false){
					$html .= $this->getButtons();
				}

				$html .= '
<div class="toggle-editor btn-toolbar pull-right clearfix">
	<div class="btn-group">
		<a class="btn" href="#"
			onclick="tinyMCE.execCommand(\'mceToggleEditor\', false, \''. $this->domId .'\');return false;"
			title="'. JText::_('PLG_TINY_BUTTON_TOGGLE_EDITOR') .'"
		>
			<i class="icon-eye"></i>'. JText::_('PLG_TINY_BUTTON_TOGGLE_EDITOR') .'
		</a>
	</div>
</div>				
				';			
				break;
				
			case 'jce':
				// toggle button
				if($this->buttons !== false){
					$html .= $this->getButtons();
				}
				$toggle = '<a href="#"
			onclick="tinyMCE.execCommand(\'mceToggleEditor\', false, \''. $this->domId .'\');return false;"
		>[Toggle Editor]</a><br />';
				$html = $toggle . $html;
				break;
		}
		
		return $html;
	}

	function getHtmlTags($html, $tag='*', $by_attr = '', $attr_value = ''){
		$result = array();
		$xml = simplexml_load_string($html);

		if($by_attr != '' AND $attr_value != ''){
			$query = sprintf('//%s[@%s="%s"]', $tag, $by_attr, $attr_value);
		} else if($by_attr != ''){
			$query = sprintf('//%s[@%s]', $tag, $by_attr);
		} else {
			$query = '//'. $tag;
		}	

		$arr = $xml->xpath($query);
		foreach ($arr as $x) {
		  $result[] = $x->asXML();
		}
		
		return $result;
	}

	function getButtons(){
		$result = '';
		$editor = new CkJEditor($this->editor);
		$output = $editor->getDisplay($this->getInputName(), $this->dataValue, $this->width, $this->height, $this->cols, $this->rows, $this->buttons, $this->domId);
		$matches = $this->getHtmlTags($output,'div','id','editor-xtd-buttons');
		$result = $matches[0];
		
		return $result;
	}
}