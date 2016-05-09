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


class JDomHtmlFormInputAddresspicker extends JDomHtmlFormInput
{
	var $size;
	var $targets_prefix;
	var $mode;
	var $onOpenModal;
	var $onCloseModal;
	var $onSuccess;

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@domID		: HTML id (DOM)  default=dataKey
	 *
	 * 	@size		: Input Size
	 * 
	 */
	function __construct($args)
	{
		parent::__construct($args);

		$this->arg('size'		, 6, $args, '32');	
		$this->arg('targets_prefix'	, null, $args, '');		
		$this->arg('mode'			, null, $args, 'modal');	
		$this->arg('onOpenModal'	, null, $args, 'false');		
		$this->arg('onCloseModal'	, null, $args, 'false');		
		$this->arg('onSuccess'		, null, $args, 'false');		
	}
	
	function build()
	{
		static $jsLoaded;
		$doc = JFactory::getDocument();

		JDom::_('framework.jquery.addresspicker');
		
		$inputId = $this->getInputId();

		$script = '
		jQuery(document).ready(function(){	
			jQuery( "#'. $inputId .'" ).addressPickerByGiro(
			{
				mode: "'. $this->mode .'",
				targets_prefix: "'. $this->targets_prefix .'",
				onOpenModal: '. $this->onOpenModal .',
				onCloseModal: '. $this->onCloseModal .',
				onSuccess: '. $this->onSuccess .',
				text: {
					genericerror: "'. JText::_("JDOM_ADDRESSPICKER_GENERIC_ERROR") .'",
					cancel: "'. JText::_("JDOM_ADDRESSPICKER_CANCEL") .'",
					ok: "'. JText::_("JDOM_ADDRESSPICKER_OK") .'",
					edit: "'. JText::_("JDOM_ADDRESSPICKER_EDIT") .'",
					search: "'. JText::_("JDOM_ADDRESSPICKER_SEARCH") .'",
					you_are_here:"'. JText::_("JDOM_ADDRESSPICKER_YOU_ARE_HERE") .'",
					noresults: "'. JText::_("JDOM_ADDRESSPICKER_NORESULTS") .'",
					toofar: "'. JText::_("JDOM_ADDRESSPICKER_TOOFAR") .'",
					set_your_address: "'. JText::_("JDOM_ADDRESSPICKER_SET_YOUR_ADDRESS") .'",
					set_your_address_info: "'. JText::_("JDOM_ADDRESSPICKER_SET_YOUR_ADDRESS_INFO") .'"
				}
			});
		});';
		
		$doc->addScriptDeclaration($script);
		$html =	'<input type="text" id="<%DOM_ID%>" name="<%INPUT_NAME%>"<%STYLE%><%CLASS%><%SELECTORS%>'
			.	' value="<%VALUE%>"'
			.	' size="' . $this->size . '"'
			.	'/>' .LN
			.	'<%VALIDOR_ICON%>'.LN
			.	'<%MESSAGE%>';
			
		return $html;
	}

}