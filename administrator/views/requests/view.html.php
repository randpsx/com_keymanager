<?php

/**
 * @version     1.0.1
 * @package     com_keymanager
 * @copyright   Copyright (C) Imperial Valley College 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Omar Ramos <omar.ramos@imperial.edu> - http://www.imperial.edu
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Keymanager.
 */
class KeymanagerViewRequests extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }

        KeymanagerHelper::addSubmenu('requests');

        $this->addToolbar();

        $this->sidebar = JHtmlSidebar::render();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
    protected function addToolbar() {
        require_once JPATH_COMPONENT . '/helpers/keymanager.php';

        $state = $this->get('State');
        $canDo = KeymanagerHelper::getActions($state->get('filter.category_id'));

        JToolBarHelper::title(JText::_('COM_KEYMANAGER_TITLE_REQUESTS'), 'requests.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/request';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
                JToolBarHelper::addNew('request.add', 'JTOOLBAR_NEW');
            }

            if ($canDo->get('core.edit') && isset($this->items[0])) {
                JToolBarHelper::editList('request.edit', 'JTOOLBAR_EDIT');
            }

        }

        if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::custom('requests.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
                JToolBarHelper::custom('requests.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'requests.delete', 'JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::archiveList('requests.archive', 'JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
                JToolBarHelper::custom('requests.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
        }

        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
            if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
                JToolBarHelper::deleteList('', 'requests.delete', 'JTOOLBAR_EMPTY_TRASH');
                JToolBarHelper::divider();
            } else if ($canDo->get('core.edit.state')) {
                JToolBarHelper::trash('requests.trash', 'JTOOLBAR_TRASH');
                JToolBarHelper::divider();
            }
        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_keymanager');
        }


        if ($canDo->get('core.admin') && isset($this->items[0])) {
            JToolbarHelper::custom('requests.startrequest', 'generic.png','', 'Start Request', true);
        }


        //Set sidebar action - New in 3.0
        JHtmlSidebar::setAction('index.php?option=com_keymanager&view=requests');

        $this->extra_sidebar = '';

			//Filter for the field issued_date
			$this->extra_sidebar .= '<small><label for="filter_from_issued_date">From Issued Date</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.issued_date.from'), 'filter_from_issued_date', 'filter_from_issued_date', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange' => 'this.form.submit();'));
			$this->extra_sidebar .= '<small><label for="filter_to_issued_date">To Issued Date</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.issued_date.to'), 'filter_to_issued_date', 'filter_to_issued_date', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange'=> 'this.form.submit();'));
			$this->extra_sidebar .= '<hr class="hr-condensed">';

		//Filter for the field can_pickup
		$select_label = JText::sprintf('COM_KEYMANAGER_FILTER_SELECT_LABEL', 'Can Pickup');
		$options = array();
		$options[0] = new stdClass();
		$options[0]->value = "1";
		$options[0]->text = "Yes";
		$options[1] = new stdClass();
		$options[1]->value = "0";
		$options[1]->text = "No";
		JHtmlSidebar::addFilter(
			$select_label,
			'filter_can_pickup',
			JHtml::_('select.options', $options , "value", "text", $this->state->get('filter.can_pickup'), true)
		);

		JHtmlSidebar::addFilter(

			JText::_('JOPTION_SELECT_PUBLISHED'),

			'filter_published',

			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)

		);

    }

	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.requester_username' => JText::_('COM_KEYMANAGER_REQUESTS_REQUESTER_USERNAME'),
        'a.keys' => JText::_('COM_KEYMANAGER_REQUESTS_KEY_NAME'),
		'a.department_head_email' => JText::_('COM_KEYMANAGER_REQUESTS_DEPARTMENT_HEAD_EMAIL'),
		'a.department_head_token' => JText::_('COM_KEYMANAGER_REQUESTS_DEPARTMENT_HEAD_TOKEN'),
		'a.department_head_approved_date' => JText::_('COM_KEYMANAGER_REQUESTS_DEPARTMENT_HEAD_APPROVED_DATE'),
		'a.vice_president_email' => JText::_('COM_KEYMANAGER_REQUESTS_VICE_PRESIDENT_EMAIL'),
		'a.vice_president_token' => JText::_('COM_KEYMANAGER_REQUESTS_VICE_PRESIDENT_TOKEN'),
		'a.vice_president_approved_date' => JText::_('COM_KEYMANAGER_REQUESTS_VICE_PRESIDENT_APPROVED_DATE'),
		'a.access_card' => JText::_('COM_KEYMANAGER_REQUESTS_ACCESS_CARD'),
		'a.issued_date' => JText::_('COM_KEYMANAGER_REQUESTS_ISSUED_DATE'),
		'a.created_date' => JText::_('COM_KEYMANAGER_REQUESTS_CREATED_DATE'),
		'a.can_pickup' => JText::_('COM_KEYMANAGER_REQUESTS_CAN_PICKUP'),
		'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
		'a.state' => JText::_('JSTATUS'),
        'a.start_request' => JText::_('COM_KEYMANAGER_FORM_LBL_REQUEST_START_REQUEST')
		);
	}

}
