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
* @addon		Fly bootstrap modal
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


class JDomHtmlFlyBootstrapTabs extends JDomHtmlFlyBootstrap
{
	var $domClass = '';
	var $domId = '';
	var $side = 'top';
	var $tabs = array();

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);
		
		$this->arg('tabs'		, null, $args);
		$this->arg('domId'		, null, $args);
		$this->arg('domClass'	, null, $args);
		$this->arg('side'		, null, $args, 'top');
	}

	function build()
	{
		static $ids;		
		
		if(!is_array($ids)){
			$ids = array();
		}

		$this->domClass .= ' nav nav-tabs jdom_tabs';
		
				
		// create menu tabs
		$lis = array();
		foreach($this->tabs as $k => &$tab){
			$tab->active = '';
			if($k==0){
				$tab->active = ' active';
			}
			
			$c = '';
			$name = $this->safeAlias($tab->name);
			do{
				$tab->link = $name . $c;
				$c++;
			}while(in_array($tab->link,$ids));
			
			$ids[] = $tab->link;
			
			$lis[] = '
			<li class="'. $tab->active .'">
				<a href="#'. $tab->link .'">'. $tab->name .'</a>
			</li>';
		}
		
		// create tabs content
		$contents = array();
		unset($tab);
		foreach($this->tabs as $k => $tab){
			$contents[] = '
				<div id="'. $tab->link .'" class="tab-pane'. $tab->active .'">
					'. $tab->content .'
				</div>';
		}
		
		$html = '';
		if($this->side != 'top'){
			$html .= '<div class="tabbable tabs-'. $this->side .'">';
		}
		
		$html .= '<ul <%CLASS%>>'. implode('',$lis) .'</ul>';
		$html .= '<div class="tab-content">'. implode('',$contents) .'</div>';
		
		if($this->side != 'top'){
			$html .= '</div>';
		}
		
		return $html;
	}

	function buildJS()
	{
		static $loaded;
		
		if(isset($loaded)){
			return;
		}
		
		$script = "
	jQuery('body').on('click','.jdom_tabs a',function (e) {
	  e.preventDefault();
	  jQuery(this).tab('show');
	});";
		$this->addScriptInline($script, true);
		
		$loaded = true;
	}
}