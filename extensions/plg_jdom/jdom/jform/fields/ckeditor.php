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
* Form field for Jdom.
*
* @package	Jdom
* @subpackage	Form
*/
class JFormFieldCkeditor extends JdomClassFormField
{
	/**
	* The form field type.
	*
	* @var string
	*/
	public $type = 'ckeditor';

	/**
	* Method to get the field input markup.
	*
	* @access	public
	*
	* @return	string	The field input markup.
	*
	* @since	11.1
	*/
	public function getInput()
	{
		$this->setCommonProperties();
		$thisOpts = array(
				'height' => $this->getOption('height'),
				'editor' => $this->getOption('editor'),
				'buttons' => $this->getOption('buttons'),
				'rows' => $this->getOption('rows'),
				'width' => $this->getOption('width')
			);
		$this->fieldOptions = array_merge($this->fieldOptions,$thisOpts,$this->jdomOptions);
		
		$editorType = 'editor';
		if($this->getOption('repeatable') != '' OR (isset($this->fieldOptions['repeatable']) AND $this->fieldOptions['repeatable'])){
			$editorType = 'editorrepeatable';
		}
		
		$this->input = JDom::_('html.form.input.'. $editorType, $this->fieldOptions);

		
		return parent::getInput();
	}



}