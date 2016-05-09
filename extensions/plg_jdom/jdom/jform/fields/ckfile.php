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
class JFormFieldCkfile extends JdomClassFormField
{
	/**
	* The form field type.
	*
	* @var string
	*/
	public $type = 'ckfile';

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
				'indirect' => $this->getOption('indirect', null, 'bool'),
				'actions' => explode(',',$this->getOption('actions', null)),
				'preview' => $this->getOption('preview'),
				'root' => $this->getOption('root'),
				'view' => $this->getOption('view'),
				'width' => $this->getOption('width')
			);
		$this->fieldOptions = array_merge($this->fieldOptions,$thisOpts,$this->jdomOptions);
		
		$this->input = JDom::_('html.form.input.file', $this->fieldOptions);

		return parent::getInput();
	}



}