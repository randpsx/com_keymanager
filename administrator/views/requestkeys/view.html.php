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
class KeymanagerViewRequestkeys extends JViewLegacy {

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

        KeymanagerHelper::addSubmenu('requestkeys');

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

        JToolBarHelper::title(JText::_('COM_KEYMANAGER_TITLE_REQUESTKEYS'), 'requestkeys.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/requestkey';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
                JToolBarHelper::addNew('requestkey.add', 'JTOOLBAR_NEW');
            }

            if ($canDo->get('core.edit') && isset($this->items[0])) {
                JToolBarHelper::editList('requestkey.edit', 'JTOOLBAR_EDIT');
            }
        }

        if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::custom('requestkeys.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
                JToolBarHelper::custom('requestkeys.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'requestkeys.delete', 'JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::archiveList('requestkeys.archive', 'JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
                JToolBarHelper::custom('requestkeys.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
        }

        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
            if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
                JToolBarHelper::deleteList('', 'requestkeys.delete', 'JTOOLBAR_EMPTY_TRASH');
                JToolBarHelper::divider();
            } else if ($canDo->get('core.edit.state')) {
                JToolBarHelper::trash('requestkeys.trash', 'JTOOLBAR_TRASH');
                JToolBarHelper::divider();
            }
        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_keymanager');
        }

        //Set sidebar action - New in 3.0
        JHtmlSidebar::setAction('index.php?option=com_keymanager&view=requestkeys');

        $this->extra_sidebar = '';
                //Filter for the field ".request_id;
        jimport('joomla.form.form');
        $options = array();
        JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
        $form = JForm::getInstance('com_keymanager.requestkey', 'requestkey');

        $field = $form->getField('request_id');

        $query = $form->getFieldAttribute('filter_request_id','query');
        $translate = $form->getFieldAttribute('filter_request_id','translate');
        $key = $form->getFieldAttribute('filter_request_id','key_field');
        $value = $form->getFieldAttribute('filter_request_id','value_field');

        // Get the database object.
        $db = JFactory::getDBO();

        // Set the query and get the result list.
        $db->setQuery($query);
        $items = $db->loadObjectlist();

        // Build the field options.
        if (!empty($items))
        {
            foreach ($items as $item)
            {
                if ($translate == true)
                {
                    $options[] = JHtml::_('select.option', $item->$key, JText::_($item->$value));
                }
                else
                {
                    $options[] = JHtml::_('select.option', $item->$key, $item->$value);
                }
            }
        }

        JHtmlSidebar::addFilter(
            'Request ID',
            'filter_request_id',
            JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.request_id')),
            true
        );        //Filter for the field ".key_id;
        jimport('joomla.form.form');
        $options = array();
        JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
        $form = JForm::getInstance('com_keymanager.requestkey', 'requestkey');

        $field = $form->getField('key_id');

        $query = $form->getFieldAttribute('filter_key_id','query');
        $translate = $form->getFieldAttribute('filter_key_id','translate');
        $key = $form->getFieldAttribute('filter_key_id','key_field');
        $value = $form->getFieldAttribute('filter_key_id','value_field');

        // Get the database object.
        $db = JFactory::getDBO();

        // Set the query and get the result list.
        $db->setQuery($query);
        $items = $db->loadObjectlist();

        // Build the field options.
        if (!empty($items))
        {
            foreach ($items as $item)
            {
                if ($translate == true)
                {
                    $options[] = JHtml::_('select.option', $item->$key, JText::_($item->$value));
                }
                else
                {
                    $options[] = JHtml::_('select.option', $item->$key, $item->$value);
                }
            }
        }

        JHtmlSidebar::addFilter(
            'Key ID',
            'filter_key_id',
            JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.key_id')),
            true
        );
		JHtmlSidebar::addFilter(

			JText::_('JOPTION_SELECT_PUBLISHED'),

			'filter_published',

			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)

		);

			//Filter for the field pickup_date
			$this->extra_sidebar .= '<small><label for="filter_from_pickup_date">From Pickup Date</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.pickup_date.from'), 'filter_from_pickup_date', 'filter_from_pickup_date', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange' => 'this.form.submit();'));
			$this->extra_sidebar .= '<small><label for="filter_to_pickup_date">To Pickup Date</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.pickup_date.to'), 'filter_to_pickup_date', 'filter_to_pickup_date', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange'=> 'this.form.submit();'));
			$this->extra_sidebar .= '<hr class="hr-condensed">';

			//Filter for the field returned_date
			$this->extra_sidebar .= '<small><label for="filter_from_returned_date">From Returned Date</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.returned_date.from'), 'filter_from_returned_date', 'filter_from_returned_date', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange' => 'this.form.submit();'));
			$this->extra_sidebar .= '<small><label for="filter_to_returned_date">To Returned Date</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.returned_date.to'), 'filter_to_returned_date', 'filter_to_returned_date', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange'=> 'this.form.submit();'));
			$this->extra_sidebar .= '<hr class="hr-condensed">';

			//Filter for the field lost_date
			$this->extra_sidebar .= '<small><label for="filter_from_lost_date">From Lost Date</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.lost_date.from'), 'filter_from_lost_date', 'filter_from_lost_date', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange' => 'this.form.submit();'));
			$this->extra_sidebar .= '<small><label for="filter_to_lost_date">To Lost Date</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.lost_date.to'), 'filter_to_lost_date', 'filter_to_lost_date', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange'=> 'this.form.submit();'));
			$this->extra_sidebar .= '<hr class="hr-condensed">';

    }

	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.request_id' => JText::_('COM_KEYMANAGER_REQUESTKEYS_REQUEST_ID'),
		'a.key_id' => JText::_('COM_KEYMANAGER_REQUESTKEYS_KEY_ID'),
		'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
		'a.state' => JText::_('JSTATUS'),
		'a.pickup_date' => JText::_('COM_KEYMANAGER_REQUESTKEYS_PICKUP_DATE'),
		'a.returned_date' => JText::_('COM_KEYMANAGER_REQUESTKEYS_RETURNED_DATE'),
		'a.lost_date' => JText::_('COM_KEYMANAGER_REQUESTKEYS_LOST_DATE'),
		);
	}

}
