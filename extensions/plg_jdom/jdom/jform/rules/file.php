<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.2   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		2.5
* @package		Cook Self Service
* @subpackage	JDom
* @license		GNU General Public License
* @author		Jocelyn HUARD
*
* @added by		Girolamo Tomaselli - http://bygiro.com
* @version		0.0.1
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// no direct access
defined('_JEXEC') or die('Restricted access');



/**
* Form validator rule for Jdom.
*
* @package	Jdom
* @subpackage	Form
*/
class JFormRuleFile extends JdomClassFormRule
{
	/**
	* Indicates that this class contains special methods (ie: get()).
	*
	* @var boolean
	*/
	public $extended = true;

	/**
	* Unique name for this rule.
	*
	* @var string
	*/
	protected $handler = 'file';

	/**
	* Use this function to customize your own javascript rule.
	* $this->regex must be null if you want to customize here.
	*
	* @access	public
	* @param	JXMLElement	$fieldNode	The JXMLElement object representing the <field /> tag for the form field object.
	*
	* @return	string	A JSON string representing the javascript rules validation.
	*/
	public function getJsonRule($fieldNode)
	{
		static $instance;
		
		if($instance){
			$this->handler .= '_'. $instance;
		}
		$instance++;
		
		$this->regex = '';
		$allowedExtensionsText = '*.*';
		if (isset($fieldNode['allowedExtensions']))
		{
			$allowedExtensions = (string)$fieldNode['allowedExtensions'];

			$allowedExtensionsText = str_replace("|", ", ", $allowedExtensions);
			$this->setRegex($fieldNode);
		}


		$values = array(
			"#regex" => 'new RegExp("' . $this->regex . '", \'i\')'
		);

		if (isset($fieldNode['msg-incorrect']))
			$values["alertText"] = LI_PREFIX . JText::_($fieldNode['msg-incorrect']);
		else
			$values["alertText"] = LI_PREFIX . JText::sprintf('PLG_JDOM_ERROR_ALLOWED_FILES', $allowedExtensionsText);

		$json = JdomHtmlValidator::jsonFromArray($values);
		return "{" . LN . $json . LN . "}";
	}

	
	public function setRegex($fieldNode)
	{
		//Remove eventual '*.'
		$allowedExtensions = str_replace(array(",","*."," "), array("|",""), (string)$fieldNode['allowedExtensions']);

		$this->regex = '\.(' . $allowedExtensions . ')$';
	}

}