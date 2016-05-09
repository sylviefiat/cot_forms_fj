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
* Form field for Jextrafields.
*
* @package	Jextrafields
* @subpackage	Form
*/
class JFormFieldCktags extends JdomClassFormField
{
	/**
	* The form field type.
	*
	* @var string
	*/
	public $type = 'cktags';

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
				'mintags' => $this->getOption('mintags'),
				'maxtags' => $this->getOption('maxtags')
			);
			
		$this->fieldOptions = array_merge($this->fieldOptions,$thisOpts, $this->jdomOptions);
		
		$this->input = JDom::_('html.form.input.tags', $this->fieldOptions);

		return parent::getInput();
	}

	public function getLabel()
	{
		$extraLabel = array();
		$min = $this->getOption('mintags');
		$max = $this->getOption('maxtags');
		
		if($min > 0){
			$extraLabel[] = 'min: '. $min ;
		}

		if($max > 0){
			$extraLabel[] = 'max: '. $max ;
		}
		
		$this->__set('labelclass','jtags');
		$label = parent::getLabel();
		if(!empty($extraLabel)){
			$label = $label . '<br /><span class="jtags_info">('. implode(' - ', $extraLabel) .')</span>';
		}
		
		return $label;

	}

	public function getOutput($tmplEngine = null)
	{
		$html = '';
		if(!isset($this->value)){
			return $html;
		}
		
		$value = explode(',',$this->value);
		
		$html = array();
		foreach($value as $v){
			$html[] = JDom::_('html.label', array(
					'content' => trim($v),
					'color' => 'info'
				));			
		}

		return implode(' ',$html);
	}
}
