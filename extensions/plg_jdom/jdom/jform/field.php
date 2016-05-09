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

// JDOM html validator
JLoader::register('JdomHtmlValidator', JPATH_SITE . DS . 'libraries' . DS . 'jdom' . DS . 'jform' . DS . 'html' . DS . 'validator.php');

/**
* Form field for Jdom.
*
* @package	Jdom
* @subpackage	Form
*/
class JdomClassFormField extends JFormField
{
	protected $extension = null;
	
	/**
	* The literal input HTML.
	*
	* @var string
	*/
	protected $input = null;

	/**
	* Cache for ACL actions
	*
	* @var object
	*/
	protected static $acl = null;

	/**
	* The literal label HTML.
	*
	* @var string
	*/
	protected $label = null;
	
	/**
	* The literal label HTML MARKUP.
	*
	* @var string
	*/
	public $markup = null;

	/**
	* The form field dynamic options (JDom legacy).
	*
	* @var array
	*/
	public $jdomOptions = array();
	
	/**
	* The form field common options.
	*
	* @var array
	*/
	public $fieldOptions = array();


	public function __construct($form = null){
		parent::__construct($form);
	}
	
	
	/**
	* Method to get extended properties of the field.
	*
	* @access	public
	* @param	string	$name	Name of the property.
	*
	* @return	mixed	Field property value.
	*/
	public function __get($name)
	{
		switch ($name)
		{
			case 'format':
			case 'position':
			case 'align':
			case 'responsive':
				return $this->element[$name];
				break;
		}
		return parent::__get($name);
	}

	/**
	 * Method to set certain otherwise inaccessible properties of the form field object.
	 *
	 * @param   string  $name   The property name for which to the the value.
	 * @param   mixed   $value  The value of the property.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function __set($name, $value)
	{
		static $version;		
		if(!isset($version)){
			$version = new JVersion();
		}
		
		switch($name){
			case 'value':
			case 'default':
				$this->$name = $value;
				return;
				break;
		}
	
		// Joomla! 1.6 - 1.7 - 2.5
		if (version_compare($version->RELEASE, '3.2', '<'))
		{	
			$this->__ckset($name, $value);
		} else {
			parent::__set($name, $value);
		}
	}		
	/**
	 * Method to set certain otherwise inaccessible properties of the form field object.
	 *
	 * @param   string  $name   The property name for which to the the value.
	 * @param   mixed   $value  The value of the property.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function __ckset($name, $value)
	{
		switch ($name)
		{
			case 'class':
				// Removes spaces from left & right and extra spaces from middle
				$value = preg_replace('/\s+/', ' ', trim((string) $value));

			case 'description':
			case 'hint':
			case 'value':
			case 'labelclass':
			case 'onchange':
			case 'onclick':
			case 'validate':
			case 'pattern':
			case 'group':
			case 'default':
				$this->$name = (string) $value;
				break;

			case 'id':
				$this->id = $this->getId((string) $value, $this->fieldname);
				break;

			case 'fieldname':
				$this->fieldname = $this->getFieldName((string) $value);
				break;

			case 'name':
				$this->fieldname = $this->getFieldName((string) $value);
				$this->name = $this->getName($this->fieldname);
				break;

			case 'multiple':
				// Allow for field classes to force the multiple values option.
				$value = (string) $value;
				$value = $value === '' && isset($this->forceMultiple) ? (string) $this->forceMultiple : $value;

			case 'required':
			case 'disabled':
			case 'readonly':
			case 'autofocus':
			case 'hidden':
				$value = (string) $value;
				$this->$name = ($value === 'true' || $value === $name || $value === '1');
				break;

			case 'autocomplete':
				$value = (string) $value;
				$value = ($value == 'on' || $value == '') ? 'on' : $value;
				$this->$name = ($value === 'false' || $value === 'off' || $value === '0') ? false : $value;
				break;

			case 'spellcheck':
			case 'translateLabel':
			case 'translateDescription':
			case 'translateHint':
				$value = (string) $value;
				$this->$name = !($value === 'false' || $value === 'off' || $value === '0');
				break;

			case 'translate_label':
				$value = (string) $value;
				$this->translateLabel = $this->translateLabel && !($value === 'false' || $value === 'off' || $value === '0');
				break;

			case 'translate_description':
				$value = (string) $value;
				$this->translateDescription = $this->translateDescription && !($value === 'false' || $value === 'off' || $value === '0');
				break;

			case 'size':
				$this->$name = (int) $value;
				break;

			default:
				if (property_exists(__CLASS__, $name))
				{
					JLog::add("Cannot access protected / private property $name of " . __CLASS__);
				}
				else
				{
					$this->$name = $value;
				}
		}
	}		
	
	/**
	* Method to check the authorisations.
	*
	* @access	public
	*
	* @return	boolean	True if user is allowed to see the field.
	*/
	public function canView()
	{
		if (!isset($this->element['access']))
			return true;

		$access = (string)$this->element['access'];

		$acl = $this->getActions();
		foreach(explode(",", $access) as $action)
			if ($acl->get($action))
				return true;

		return false;
	}

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
		if (!$this->canView())
			return;

		
		static $loadedJsCss;
		if(!$loadedJsCss){
			$doc = JFactory::getDocument();
			$base = JURI::root(true) .'/libraries/jdom/jform/assets/css/';			
			$doc->addStyleSheet($base .'jform.css');
			
			$loadedJsCss = true;
		}
				
			
		//Loads the front javascript and HTML validator (inspirated by JDom.)
		//Hidden messages strings are created in order to fill the javascript message alert.
		//When an error occurs, the messages appears under each field.
		//On loading, the informations messages for each field are shown when values are empties.
		//Each validation occurs on the 'change' event, and merged together in alert box on form submit.
		//If the field is required without validation rule, the helper is called only for the required message implementation.
		
		$input = $this->input;		
		return $input;
	}
	
	
	/**
	* Method to read an option parameter.
	*
	* @access	protected
	* @param	string	$name	The parameter name.
	* @param	string	$default	Default value.
	* @param	string	$type	Var type for this parameter.
	*
	* @return	mixed	Parameter value.
	*/
	public function getOption($name, $default = null, $type = null)
	{
		if (!isset($this->element[$name]))
			return $default;

		$elemValue = $this->element[$name];

		switch($type)
		{
			case 'bool':
				$elemValue = (string)$elemValue;
				if (($elemValue == 'true') || ($elemValue == '1'))
					return true;

				if (($elemValue == 'false') || ($elemValue == '0'))
					return false;

				if (!empty($elemValue))
					return true;

				return false;
				break;

			case 'int':
				return (int)$elemValue;
				break;

			default:
				return (string)$elemValue;
				break;
		}
	}

	protected function setCommonProperties(){
		if(!isset($this->value) AND isset($this->default)) {
			$this->__set('value',$this->default);
		}

		if($this->getOption('autocomplete') != ''){
			$this->__set('autocomplete',$this->getOption('autocomplete'));
		}

		// load JS validators and clean the input classes
		$class = JdomHtmlValidator::loadValidators($this->element, $this->id);
	
		$this->fieldOptions = array(
			'alias' => $this->getOption('alias'),
			'autocomplete' => $this->getOption('autocomplete'),
			'cols' => $this->getOption('cols'),			
			'containerClass' => $this->getOption('containerClass'),			
			'dataKey' => $this->getOption('name'),
			'dataValue' => $this->value,
			'description' => $this->getOption('description'),			
			'disabled' => $this->getOption('disabled'),			
			'domClass' => $class,
			'formControl' => $this->formControl,
			'formGroup' => $this->group,
			'hidden' => $this->getOption('hidden'),			
			'labelKey' => $this->getOption('labelKey'),
			'labelclass' => $this->getOption('labelclass'),			
			'list' => $this->getOptions(),
			'listKey' => $this->getOption('listKey'),
			'nullLabel' => $this->getOption('nullLabel'),
			'placeholder' => $this->getOption('placeholder'),
			'readonly' => $this->getOption('readonly'),			
			'responsive' => $this->getOption('responsive'),
			'rows' => $this->getOption('rows'),
			'size' => $this->getOption('size'),
			'submitEventName' => ($this->getOption('submit') == 'true'?'onclick':null),
			'viewType' => $this->getOption('viewType')
		);
	}
	
	public function getAllLabels()
	{
		static $labels;
		$values = $options = array();
		$type = strtolower($this->getOption('type'));
		
		if(!isset($labels)){
			$labels = array();
		}
		
		$hash = md5($type . $this->id . $this->name);
		
		if(isset($labels[$hash])){
			return $labels[$hash];
		}
		
		switch($type){
			case 'author':
			case 'cachehandler':
			case 'checkboxes':
			case 'ckcheckboxes':
			case 'ckcombo':
			case 'ckcontentlanguage':
			case 'ckusergrouplist':
			case 'cklist':
			case 'ckradio':
			case 'ckrules':
			case 'combo':
			case 'contentlanguage':
			case 'contenttype':
			case 'databaseconnection':
			case 'headertag':
			case 'imagelist':
			case 'language':
			case 'list':
			case 'menu':
			case 'moduletag':
			case 'plugins':
			case 'predefinedlist':
			case 'radio':
			case 'registrationdaterange':
			case 'rules':
			case 'sessionhandler':
			case 'sql':
			case 'status':
			case 'useractive':
			case 'userstate':
				$options = $this->getOptions();				
			break;
			
			case 'chromestyle':
			case 'templatestyle':
			case 'groupedlist':
			case 'menuitem':
			case 'timezone':
				$groups = (array) $this->getGroups();
				
				foreach($groups as $styles){				
					foreach($styles as $st){
						$values[$st->value] = $st->text; 
					}					
				}	
				break;
			
			case 'ckcheckbox':
			case 'checkbox':
				if((string)$this->element['value'] <= 1){
					$values[0] = JText::_('JNO');
					$values[1] = JText::_('JYES');
					
					// fix for $field->value not recognized
					$this->__set('value',(int)$this->value);
				} else {
					$options = $this->getOptions();
				}
				break;
				
			case 'accesslevel':
			case 'ckaccesslevel':
			case 'usergroup':
			case 'usergrouplist':
				$options = JHtml::_('access.assetgroups');
				break;
				
			case 'ckstate':
				$values = array(
					0 => JText::_('JUNPUBLISHED'),
					1 => JText::_('JPUBLISHED'),
					2 => JText::_('JARCHIVED'),
					-2 => JText::_('JTRASHED')
				);
				break;
				
			case 'ckdirection':
				$values = array(
					'asc' => JText::_('JGLOBAL_ORDER_ASCENDING'),
					'desc' => JText::_('JGLOBAL_ORDER_DESCENDING')
				);
				break;
		}
		
		foreach($options as $opt){
			$opt = (object)$opt;
			$listKey = ($this->getOption('listKey') != '') ? $this->getOption('listKey') : 'value';
			$labelKey = ($this->getOption('labelKey') != '') ? $this->getOption('labelKey') : 'text';
			
			if(isset($opt->$listKey)){
				$values[$opt->$listKey] = JText::_($opt->$labelKey);
			}
		}

		$labels[$hash] = $values;
		
		return $values;
	}
	
	public function getOutput($tmplEngine = null)
	{	
		$html = '';
		if(!isset($this->value)){
			return $html;
		}
				
		$this->setCommonProperties();
		$values = $this->getAllLabels();
		$type = strtolower($this->getOption('type'));
		$fieldName = $this->getOption('name');

		switch($type){
			case 'ckcolor':
			case 'color':
				$val = $this->value;
				if($type == 'ckcolor'){
					$val = '#'. $val;
				}
				$html .= JDom::_('html.fly.color', array(
							'width' => 12,
							'height' => 12,
							'dataValue' => $val
							)
						);

				$html .= ' <span class="fly-color-value">'. $this->value .'</span>';
				break;
				
			case 'ckordering':
				$html .= JDom::_('html.grid.ordering', array(
					'dataValue' => $this->value,
					'enabled' => true
				));
				break;
			case 'ckmedia':
			case 'ckfile':
				$preview = ($this->getOption('preview') == 'true') ? true : false;				
				
				$thisOpts = array(
						'height' => $this->getOption('height'),
						'indirect' => $this->getOption('indirect'),
						'actions' => explode(',',$this->getOption('actions', null)),
						'preview' => $preview,
						'root' => $this->getOption('root'),
						'attrs' => $this->getOption('attrs'),
						'fullRoot' => true,
						'frame' => false,
						'view' => $this->getOption('view'),
						'width' => $this->getOption('width')
					);
				$this->fieldOptions = array_merge($this->fieldOptions,$thisOpts,$this->jdomOptions);
		
				$preview = false;
				if ($this->fieldOptions['preview'] == 'true'){
					$preview = true;
				}

				switch($tmplEngine){
					case 'doT':
					default:
						if($tmplEngine == 'doT'){
							$fname ='{{ var tmp_val = it.'. $fieldName .';
								if(typeof tmp_val == "string"){
									tmp_val = tmp_val.split(/[\\/]/).pop();
								}}
								{{ } }}
								{{=tmp_val || ""}}';
						} else {
							$fname = basename($this->value);
						}					
					
						if(isset($this->fieldOptions['selectors'])){
							if (!is_array($this->fieldOptions['selectors'])){
								$this->fieldOptions['selectors'] .= ' title="'. $fname .'"';
							} else {
								$this->fieldOptions['selectors']['title'] = $fname;
							}
						}
				
						if($preview){			
							$html .= '<p style="width: 100%;">'. JDom::_("html.fly.file.image", $this->fieldOptions) .'</p>';
						} else {
							$html .= $fname;
						}
						
					break;
				}
				break;
				
			default:
				if(count($values)){					
					switch($tmplEngine){
						case 'doT':
							$values = str_replace("'","\'",$this->escapeJsonString(json_encode($values)));
							$html .= 
								'{{ var i,value,tmp_val,vals = JSON.parse(\''. $values .'\');
										tmp_val = [];
										if(typeof it.'. $fieldName .' != null){
											tmp_val = it.'. $fieldName .';
										}}
										{{ } }}
											{{ switch(typeof tmp_val) {
												case "boolean":
													value = 0;
													if(tmp_val){
														value = 1; }}
													{{ }
													value = vals[value];											
													break;
												
												case "object":
													var tmp_vals=[];
													
													if(!(tmp_val instanceof Array)){
														tmp_val = [];
													}}													
													{{ } 
													var values_length = tmp_val.length;
														
													for(i=0;i<values_length;i++){
														tmp_vals.push(vals[tmp_val[i]]);												
													}}{{ }
													value = tmp_vals.join(", ");
													
													break;

												default:
													value = vals[tmp_val];
													break;												
														
												}}
											{{ } }}
									{{=value || ""}}
									';
							break;
							
						default:
							if(is_string($this->value) OR is_numeric($this->value)){
								if(isset($values[$this->value])){
									$html .= $values[$this->value];
								} else {
									$html .= $this->value;
								}
							} else if(is_array($this->value) OR is_object($this->value)) {
								$html .= $this->outputArray($this->value);
							}
							break;
					}				
				} else {	
					if(is_string($this->value)){
						$html .= $this->value;
					} else {
						$html .= $this->outputArray($this->value);
					}
				}
			break;
		}

		return $html;
	}

	public function outputArray($list){
		$output = '<ol>';

		$list = (array)$list;
		foreach($list as $k => $val){
			$li = '<li>';
			if(!is_numeric($k)){
				$li .= $k .': ';
			}
			
			if(is_string($val)){
				$allLabels = $this->getAllLabels();
				if(isset($allLabels[$val])){
					$val = $allLabels[$val];
				}
			} else if(is_object($val) OR is_array($val)){
				$val = $this->outputArray($val);
			} else {
				continue;
			}
			
			$li .= $val .'</li>';
			$output .= $li;
		}
		
		$output .= '</ol>';
		return $output;
	}	

	protected function getOptions(){
		if (isset($this->jdomOptions['list'])){
			return $this->jdomOptions['list'];
		}		
		
		$options = array();
		//Get the options from XML
		foreach ($this->element->children() as $option)
		{
			$opt = new stdClass();
			foreach($option->attributes() as $attr => $value)
				$opt->$attr = (string)$value;
	
			$opt->text = JText::_(trim((string) $option));
			$options[] = $opt;
		}
		
		return $options;
	}
	
	/**
	* Method to get the field label markup.
	*
	* @access	public
	*
	* @return	string	The field label markup.
	*
	* @since	11.1
	*/
	public function getLabel()
	{
		if (!$this->canView())
			return;
		
		if($this->markup == null){
			$this->markup == 'label';
		}
	
		$this->label = JDom::_('html.form.label', array_merge(array(
				'dataKey' => $this->getOption('name'),
				'alias' => $this->getOption('alias'),
				'required' => $this->getOption('required'),
				'formGroup' => $this->group,
				'formControl' => $this->formControl,
				'domClass' => $this->labelclass,
				'label' => $this->getOption('label'),
				'description' => $this->getOption('description'),
				'markup' => $this->markup
			), $this->jdomOptions));

		return $this->label;
	}	

	public function getXML(){				
		return $this->element;
	}
		
	public function escapeJsonString($value) {
		# list from www.json.org: (\b backspace, \f formfeed)    
		$escapers =     array("\\",     "/",   "\"",  "\n",  "\r",  "\t", "\x08", "\x0c");
		$replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t",  "\\f",  "\\b");
		$result = str_replace($escapers, $replacements, $value);
		return $result;
	}

	/**
	* Gets a list of the actions that can be performed.
	*
	* @access	public static
	* @param	integer	$itemId	The item ID.
	*
	* @return	JObject	An ACL object containing authorizations
	*
	* @since	1.6
	*/
	public function getActions($itemId = 0)
	{
		if (isset(self::$acl))
			return self::$acl;

		if(!isset($this->extension) OR $this->extension == ''){		
			$parts = explode(".", $this->form->getName());
			$this->extension = $parts[0];
		}
		
		$user	= JFactory::getUser();
		$result	= new JObject;

		$actions = array(
			'core.admin',
			'core.manage',
			'core.create',
			'core.edit',
			'core.edit.state',
			'core.edit.own',
			'core.delete',
			'core.delete.own',
			'core.view.own',
			'core.view.list',
			'core.view.item',
		);

		foreach ($actions as $action)
			$result->set($action, $user->authorise($action, $this->extension));

		self::$acl = $result;

		return $result;
	}
	
	function getLanguages(){
		static $languages;
		
		if(isset($language)){
			return $languages;
		}
		$db = JFactory::getDBO();
		
		$sql = "SELECT *, LOWER(REPLACE(lang_code,'-','')) as lang_tag  FROM #__languages WHERE published = 1 ORDER BY ordering";
		$db->setQuery(  $sql );
		$languages = $db->loadObjectList();

		foreach($languages as &$lang){
			$lang->postfix = $lang->lang_tag;
			if($lang->lang_tag != ''){
				$lang->postfix = '_'. $lang->lang_tag;
			}
			
			$lang->img_url = '';
			if($lang->lang_code != ''){
				$lang->img_url = JURI::root() .'media/mod_languages/images/'. $lang->image .'.gif';
			}
		}
		
		return $languages;
	}

	function createMultilanguageField(){		
		$input = '';
		$layout = strtolower($this->getOption('multilanguageLayout'));

		$name = $this->getOption('name');
		$alias = $this->getOption('alias');
		
		$languages = $this->getLanguages();
		
		$fake_lang = new stdClass;
		$fake_lang->postfix = $fake_lang->img_url = $fake_lang->lang_code = $fake_lang->lang_tag = '';
		$fake_lang->title = JText::_("JDEFAULT");
		array_unshift($languages,$fake_lang);

		$isTheOriginalFieldRequired = $this->__get('required');
		// create content tabs
		$content = array();
		foreach($languages as $lang){
			$title = $lang->title;
			if($lang->img_url != ''){
				$title .= ' <img src="'. $lang->img_url .'" />';
			}

			// joomla class
			$class = $this->__get('class');
			preg_match_all('/validate\[(.*?)\]/s', $class, $matches);

			$OriginalvalidateClass = '';
			if(isset($matches[1][0])){
				$OriginalvalidateClass = trim(preg_replace('/\s+/', '', $matches[1][0]),',');
			}
			
			// jdom class
			if(isset($this->jdomOptions['domClass'])){
				$jdomClass = $this->jdomOptions['domClass'];
				preg_match_all('/validate\[(.*?)\]/s', $this->jdomOptions['domClass'], $jdomMatches);				
			}
			
			$OriginalJdomValidateClass = '';
			if(isset($jdomMatches[1][0])){
				$OriginalJdomValidateClass = trim(preg_replace('/\s+/', '', $jdomMatches[1][0]),',');
			}
			
			$ml_fname = $name;
			$ml_alias = $alias;			
			if($lang->postfix != ''){
				$ml_fname .= $lang->postfix;
				$ml_alias .= $lang->postfix;
				
				$this->required = false;
				
				// TO DO: remove class: validate[required]
				$validateClass = trim(str_replace('required','',$OriginalvalidateClass), ',');
				$jdomValidateClass = trim(str_replace('required','',$OriginalJdomValidateClass), ',');
				
			} else {
				$this->required = $isTheOriginalFieldRequired;
				
				$validateClass = $OriginalvalidateClass;
				$jdomValidateClass = $OriginalJdomValidateClass;
			}

			$newClass = '';
			if($validateClass != ''){
				$newClass = 'validate['. $validateClass .']';
			}
			
			$newJdomClass = '';
			if($jdomValidateClass != ''){
				$newJdomClass = 'validate['. $jdomValidateClass .']';
			}

			if(isset($matches[0][0])){
				$class = str_replace($matches[0][0],$newClass,$class);
			}
			
			if(isset($jdomMatches[0][0])){
				$jdomClass = str_replace($jdomMatches[0][0],$newJdomClass,$jdomClass);
			}
			
			$this->__set('class',$class);

			if(!isset($this->jdomOptions['domClass'])){				
				$this->jdomOptions['domClass'] = $class;
			} else {
				$this->jdomOptions['domClass'] = $jdomClass;
			}
			
			
			$this->setInputFieldname($ml_fname, $ml_alias);
	
			switch($layout){
				case 'tabs':
					$tb = array(
						'name' => $title,
						'content' => $this->getInput()
					);
				
					$content[$lang->lang_code] = (object)$tb;
					break;
					
				default:
					$html = '<div class="control-group form-vertical">';

					$html .= '<div class="control-label ml_label">' 
							. $title
							. '</div>';

					$html .= '<div class="controls ml_input">'
							. $this->getInput()
							. '</div>';

					$html .= '</div>';
		
					$content[$lang->lang_code] = $html;
					break;
			}
			
		}
		
		switch($layout){
			case 'tabs':
				$input = JDom::_('html.fly.bootstrap.tabs', array(
						'side' => 'left',
						'domId' => $this->id .'_tabs',
						'tabs' => array_values($content),
						'domClass' => 'ml_tabs'
					));					
				break;
				
			default:
				$input = '<div class="ml_group_fields">'. implode('',$content) .'</div>';
				break;
		}
		
		return $input;
	}
	

	public function getInputI($suffix = '')
	{	
		$multilanguage = ($this->getOption('multilanguage') != '') ? true : false ;
		if(isset($this->jdomOptions['multilanguage']) AND $this->jdomOptions['multilanguage']){
			$multilanguage = $this->jdomOptions['multilanguage'];
		}
		
		if($multilanguage){
			$input = $this->createMultilanguageField();
		} else {
			$name = $this->getOption('name');
			$alias = $this->getOption('alias');	
			if($alias != ''){
				$alias .= $suffix;
			}
		
			$this->setInputFieldname($name . $suffix, $alias);
		
			if(isset($this->jdomOptions['required'])){
				$required = $this->jdomOptions['required'];
				
				// joomla class
				preg_match_all('/validate\[(.*?)\]/s', $this->__get('class'), $matches);
				$validateClass = '';
				if(isset($matches[1][0])){
					$validateClass = trim(preg_replace('/\s+/', '', $matches[1][0]),',');
				}
			
		
				// jdom class
				$jdomValidateClass = '';
				if(isset($this->jdomOptions['domClass'])){
					preg_match_all('/validate\[(.*?)\]/s', $this->jdomOptions['domClass'], $jdomMatches);				
					$jdomValidateClass = trim(preg_replace('/\s+/', '', $jdomMatches[1][0]),',');
				}

				if($required){
					$this->__set('required',true);
					
					//add class: validate[required] (if required)					
					$validateClass = trim($validateClass . ',required', ',');
					$jdomValidateClass = trim($jdomValidateClass . ',required', ',');
				} else {
					$this->__set('required',false);
					
					//remove class: validate[required]
					$validateClass = trim(str_replace('required','',$validateClass), ',');
					$jdomValidateClass = trim(str_replace('required','',$jdomValidateClass), ',');
				}
				
				$newClass = '';
				if($validateClass != ''){
					$newClass = 'validate['. $validateClass .']';
				}
				
				$newJdomClass = '';
				if($jdomValidateClass != ''){
					$newJdomClass = 'validate['. $jdomValidateClass .']';
				}

				$class = $this->__get('class');
				if(isset($matches[0][0])){				
					$class = str_replace($matches[0][0],$newClass,$class);
				}
				
				if(isset($jdomMatches)){
					$jdomClass = str_replace($jdomMatches[0][0],$newJdomClass,$this->jdomOptions['domClass']);
				}
				
				$this->__set('class',$class);

				if(!isset($this->jdomOptions['domClass'])){				
					$this->jdomOptions['domClass'] = $class;
				} else {
					$this->jdomOptions['domClass'] = $jdomClass;
				}				
			}
	
			$input = $this->getInput();
		}
		
		return $input;
	}

	public function setInputFieldname($newFname = null, $newAlias = null){
		if(empty($newFname) AND empty($newAlias)){
			return;
		}
		
		if(empty($newFname)){
			$newFname = $this->getOption('name');
		}
		
		if(empty($newAlias)){
			$newAlias = $this->getOption('alias');
		}

		$jdomOptions = $this->jdomOptions;
		$dataObject = null;
		if(isset($jdomOptions['dataObject'])){
			$dataObject = $jdomOptions['dataObject'];
		}		
		
		$group = $this->group;
		if(isset($jdomOptions['formGroup'])){
			$group = $jdomOptions['formGroup'];
		}
		
		$extraOptions = array_merge($jdomOptions,array(
				'dataKey' => $newFname,
				'alias' => $newAlias
		));
		
		$value = null;
		if(is_object($dataObject) AND $newFname != '' AND isset($dataObject->$newFname)){
			$extraOptions['dataValue'] = $value = $dataObject->$newFname;
		}		
		
		// set jdomOptions
		$this->jdomOptions = $extraOptions;
		
		// set id and name
		$this->id = $this->getInputId('',$group,null,$newFname,$newAlias);
		$this->name = $this->getInputName('',$group,null,$newFname);
		
		// set value
		if(isset($extraOptions['dataValue'])){
			$this->__set('value', $extraOptions['dataValue']);
		}
	}
	
	function getInputName($suffix = null, $formGroup = null, $formControl = null, $dataKey = null)
	{
		if(!$formGroup){
			$formGroup = $this->group;
		}
		
		if(!$formControl){
			$formControl = $this->formControl;
		}
		
		if(!$dataKey){
			$dataKey = $this->fieldname;
		}
		
		$name =  $dataKey;		
		if($suffix){
			$name =  $dataKey .'-'. $suffix;
		}
		
		if($formControl != null OR $formGroup != null){
			$name = '[' . $name . ']';
			if ($formGroup != null){
				$group = preg_replace("/\.(?!([^\{\{]+)?\}\})/i",'||',$formGroup);
				$name = '[' . implode('][', explode('||', $group)) . ']'. $name;		
			}
			
			if ($formControl != null){
				$name = $formControl . $name;
			}
		}
		
		return $name;
	}

	function getInputId($suffix = null, $formGroup = null, $formControl = null, $dataKey = null, $alias = null)
	{
		if(!$formGroup){
			$formGroup = $this->group;
		}
		
		if(!$formControl){
			$formControl = $this->formControl;
		}
		
		if(!$dataKey){
			$dataKey = $this->fieldname;
		}	
			
		$id = $dataKey;
		if(isset($alias) AND $alias != ''){
			$id = $alias;
		}
		
		if ($formGroup != null)
			$id = preg_replace("/\.(?!([^\{\{]+)?\}\})/i",'_',$formGroup) .'_'. $id;
		
		if ($formControl != null)
			$id = $formControl . '_' . $id;
		
		if ($suffix)
			$id .= '-'. $suffix;
		
		return $id;
	}	
	
}