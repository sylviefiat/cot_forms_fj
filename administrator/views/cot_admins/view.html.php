<?php
/**
 * @version     2.0.4
 * @package     com_cot_forms
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Sylvie Fiat <sylvie.fiat@ird.fr>
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Cot_admin.
 */
class Cot_formsViewCot_admins extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors));
		}
        
		Cot_formsHelper::addSubmenu('cot_admins');
        
		$this->addToolbar();
        
        $this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.'/helpers/cot_forms.php';

		$state	= $this->get('State');
		$canDo	= Cot_formsHelper::getActions($state->get('filter.category_id'));

		JToolBarHelper::title(JText::_('COM_COT_FORMS_TITLE_COT_ADMIN'), 'cot_admins.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR.'/views/cot_admin';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
			    JToolBarHelper::addNew('cot_admin.add','JTOOLBAR_NEW');
		    }

		    if ($canDo->get('core.edit') && isset($this->items[0])) {
			    JToolBarHelper::editList('cot_admin.edit','JTOOLBAR_EDIT');
		    }

        }

		if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::custom('cot_admins.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			    JToolBarHelper::custom('cot_admins.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'cot_admins.delete','JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::archiveList('cot_admins.archive','JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
            	JToolBarHelper::custom('cot_admins.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
		}
        
        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
		    if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			    JToolBarHelper::deleteList('', 'cot_admins.delete','JTOOLBAR_EMPTY_TRASH');
			    JToolBarHelper::divider();
		    } else if ($canDo->get('core.edit.state')) {
			    JToolBarHelper::trash('cot_admins.trash','JTOOLBAR_TRASH');
			    JToolBarHelper::divider();
		    }
        }

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_cot_forms');
		}
        
        //Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_cot_forms&view=cot_admins');
        
        $this->extra_sidebar = '';
        
        
	}
    
	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.observer_name' => JText::_('COM_COT_FORMS_COT_ADMINS_OBSERVER_NAME'),
		'a.observer_tel' => JText::_('COM_COT_FORMS_COT_ADMINS_OBSERVER_TEL'),
		'a.observer_email' => JText::_('COM_COT_FORMS_COT_ADMINS_OBSERVER_EMAIL'),
		'a.observation_date' => JText::_('COM_COT_FORMS_COT_ADMINS_OBSERVATION_DATE'),
		'a.observation_location' => JText::_('COM_COT_FORMS_COT_ADMINS_OBSERVATION_LOCATION'),
		'a.observation_localisation' => JText::_('COM_COT_FORMS_COT_ADMINS_OBSERVATION_LOCALISATION'),
		'a.observation_number' => JText::_('COM_COT_FORMS_COT_ADMINS_OBSERVATION_NUMBER'),
		'a.observation_state' => JText::_('COM_COT_FORMS_COT_ADMINS_OBSERVATION_STATE'),		
		'a.admin_validation' => JText::_('COM_COT_FORMS_COT_ADMINS_ADMIN_VALIDATION'),
		);
	}

    
}
