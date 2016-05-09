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
* @addon		form fieldset
* @author		Girolamo Tomaselli - http://bygiro.com
* @version		0.0.1
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class JDomHtmlFormFieldset extends JDomHtmlForm
{
	var $listName = 'itemsList';
//	var $markup = 'div';
	var $enumList;
	var $dataObject;
	var $jdomOptions = array();
	var $formGroup = null;
	var $formControl = null;

	var $attachJs = array();
	var $attachCss = array();
	
	protected $form;
	protected $fieldset;
	protected $fieldsToRender;
	protected $fieldsetName;
	protected $actions = array();
	protected $tmplEngine = null;
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *
	 * 	@listName	: default 'items'
	 * 	@markup		: default 'div'
	 * 	@dataObject	: object to override field value
	 * 	@formControl: Override the fieldset formControl
	 * 	@formGroup	: Override the fieldset formGroup
	 * 	@jformFields: array of jform fields 
	 * 	@fieldsToRender: array of fields  to render
	 * 	@actions	: default array()
	 * 	@tmplEngine	: JS Template Engine, default null
	 * 	@domClass	: CSS class
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);

		$this->arg('listName'		, null, $args, 'itemsList');
	//	$this->arg('markup'			, null, $args, 'div');
		$this->arg('enumList'		, null, $args);
		$this->arg('dataObject'		, null, $args, array());
		$this->arg('formGroup'		, null, $args);
		$this->arg('formControl'	, null, $args, 'jform');
		$this->arg('fieldset'		, null, $args);
		$this->arg('multilanguage'	, null, $args, false);
		$this->arg('form'			, null, $args);
		$this->arg('fieldsToRender'	, null, $args, array());
		$this->arg('jdomOptions'	, null, $args, array());
		$this->arg('actions'		, null, $args, array());
		$this->arg('tmplEngine'		, null, $args, null);
		$this->arg('domClass'		, null, $args);
		$this->arg('fieldsetName'	, null, $args);
		
		if($this->form instanceof JForm AND isset($this->fieldsetName)){			
			if(!$this->fieldset){
				$this->fieldset = $this->form->getFieldset($this->fieldsetName);
			}
			
			$fieldSets = $this->form->getFieldsets();
			$fset = $fieldSets[$this->fieldsetName];
			$multilanguage = ($fset->multilanguage != '') ? true : false;
		}
		
		if(isset($multilanguage)){
			$this->multilanguage = $multilanguage;
		}
	}


	function build()
	{	
		$output = '';
		if($this->multilanguage){
			$languages = $this->getLanguages();
			
			$fake_lang = (object)array(
				'postfix' => '',
				'lang_code' => '',
				'img_url' => '',
				'lang_tag' => '',			
			);
			$fake_lang->title = JText::_("JDEFAULT");
			array_unshift($languages,$fake_lang);
			
			$ml_forms = array();
			foreach($languages as $lang){
				$title = $lang->title;
				if($lang->img_url != ''){
					$title .= ' <img src="'. $lang->img_url .'" />';
				}
			
				$opts = array();
				$opts['multilanguage'] = false;
				
				if($lang->lang_tag != ''){
					$opts['required'] = false;
				}

				$opts = array_merge($this->jdomOptions, $opts);
				
				$tb = array(
					'name' => $title,
					'content' => $this->renderFieldset($lang->postfix, $opts)
				);
			
				$ml_forms[] = (object)$tb;
			}
			
			$output = JDom::_('html.fly.bootstrap.tabs', array(
					'side' => 'top',
					'tabs' => $ml_forms,
					'domClass' => 'ml_fset_tabs'
				));			
		} else {
			$output = $this->renderFieldset('', $this->jdomOptions);
		}
		
		return $output;
	}

	function renderFieldset($postfix = '', $jdomOptions = array()){
		$item = $this->dataObject;	
		$cels = array();		
		foreach($this->fieldset as $field){
			if(count($this->fieldsToRender) > 0 AND !in_array($field->fieldname,$this->fieldsToRender)){
				continue;
			}
			$html = '';
			
			if(!$this->formGroup){
				$this->formGroup = $field->group;
			}

			$fieldName = $field->fieldname;
			if(isset($item->$fieldName)){
				try{
					$field->value = $item->$fieldName;
				} catch (Exception $e){
					$error = $e->getMessage();
				}
				
				// following code, not working when there are more than 1 field with same name and same group
				if(isset($error) AND $this->form instanceof JForm){
					try{
						$this->form->setValue($fieldName,$field->group,$item->$fieldName);
						$field = $this->form->getField($fieldName,$field->group,$item->$fieldName);				
					} catch (Exception $e){
						$error = $e->getMessage();
					}
				}
			}

			if($item AND isset($item->$fieldName)){
				$field->jdomOptions = array_merge($field->jdomOptions, array(
						'dataValue' => $item->$fieldName,
							));
			}

			if(count($this->enumList) AND isset($this->enumList[$fieldName])){
				$field->jdomOptions = array_merge($field->jdomOptions, array(
						'list' => $this->enumList[$fieldName],
							));
			}
			
			$field->jdomOptions = array_merge($field->jdomOptions, array(
					'dataObject' => $item,
					'formControl' => $this->formControl,
					'formGroup' => $this->formGroup
						));

			if(count($jdomOptions)){
				$field->jdomOptions = array_merge($field->jdomOptions, $jdomOptions);
			}
			
			//Check ACL
		    if ((method_exists($field, 'canView')) && !$field->canView()){
		    	continue;
			}

			if ($field->hidden)
			{
				$html .= $field->getInputI($postfix);
				continue;
			}
			unset($error);
			
			$containerClass = $classes = $canView = $canEdit = '';
			if(($this->form instanceof JForm)){
				$containerClass = $this->form->getFieldAttribute($field->fieldname,'containerClass',null,$field->group);
				$classes = $this->form->getFieldAttribute($field->fieldname,'class',null,$field->group);
				$canView = $this->form->getFieldAttribute($field->fieldname,'canView',null,$field->group);
				$canEdit = $this->form->getFieldAttribute($field->fieldname,'canEdit',null,$field->group);
			}

			// check ACL
			if($canView AND class_exists('ByGiroHelper')){
				$canView = ByGiroHelper::canAccess($canView);
			} else {
				$canView = true;
			}
			
			if(isset($this->jdomOptions['canEdit'])){
				$canEdit = $this->jdomOptions['canEdit'];
			} else if($canEdit AND class_exists('ByGiroHelper')){
				$canEdit = ByGiroHelper::canAccess($canEdit);
			} else {
				$canEdit = true;
			}

			if(!$canView){
				continue;
			}
			
			if(!$canEdit){
				try{
					$field->disabled = 1;
				} catch (Exception $e){
					$error = $e->getMessage();
				}
				
				$selectors = array();
				if(isset($field->jdomOptions['selectors'])){
					$selectors = $field->jdomOptions['selectors'];
				}
				if(is_array($selectors)){
					$selectors['disabled'] = 'disabled';
				} else {
					$selectors .= ' disabled="disabled"';
				}
				
				
				$field->jdomOptions = array_merge($field->jdomOptions, array(
					'selectors' => $selectors
						));
			}

			if($field->type == 'ckfieldset'){
				$field->jdomOptions = array_merge($field->jdomOptions, array(
						'formGroup' => trim($this->formGroup .'.'. $field->fieldname)
							));
			}

			$selectors = (($field->type == 'Editor' OR $field->type == 'Rules') ? ' style="clear: both; margin: 0;"' : '');

			if(isset($field->jdomOptions['containerClass'])){
				$containerClass = $field->jdomOptions['containerClass'];
			}
			
			if(isset($field->inputSelector)){
				$selectors .= ' '. $field->inputSelector;
			}
			
			$labelSelector = '';
			if(isset($field->labelSelector)){
				$labelSelector = $field->labelSelector;
			}
			
			if($field->type == 'ckspacer' OR $field->type == 'Spacer'){
				$html .= '<div class="control-group field-' . $field->id . $field->responsive .' '. $classes .' '. $containerClass .'">';
				$html .= $field->getLabel();
				$html .= '</div>';			
			} else {
				$html .= '<div class="control-group field-' . $field->id . $field->responsive . ' '. $containerClass .'">';

				$html .= '<div class="control-label">' 
						. $field->getLabel()
						. '</div>';

				$html .= '<div class="controls"' . $selectors . '>'
						. $field->getInputI($postfix)
						. '</div>';

				$html .= '</div>';
			}
		
			$cels[$field->fieldname] = $html;
		}
		
		// sort based on fieldsToRender
		if(count($this->fieldsToRender) > 0){
			$newOrder = array();
			foreach($this->fieldsToRender as $fi){
				if(isset($cels[$fi])){
					$newOrder[$fi] = $cels[$fi];
				}
			}
			$cels = $newOrder;
		}
	
		if(count($this->actions) > 0){
			$buttons = '<div class="actions_cln control-group btn-toolbar"><div class="btn-group">';
			foreach($this->actions as $act){
				$buttons .= JDom::_('html.fly.bootstrap.button', array(
						'domClass' => $act->domClass,
						'extra' => $act->extra,
						'icon' => $act->icon,
						'label' => $act->label
					));
			}
			$buttons .= '</div></div>';
			$cels[] = $buttons;
		}
		
		return implode('',$cels);	
	}
}