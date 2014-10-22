<?php

/**
 * @version     1.0.1
 * @package     com_keymanager
 * @copyright   Copyright (C) Imperial Valley College 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Omar Ramos <omar.ramos@imperial.edu> - http://www.imperial.edu
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Keymanager records.
 */
class KeymanagerModelRequests extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                                'id', 'a.id',
                'requester_username', 'a.requester_username',
                'department_head_email', 'a.department_head_email',
                'department_head_token', 'a.department_head_token',
                'department_head_approved_date', 'a.department_head_approved_date',
                'vice_president_email', 'a.vice_president_email',
                'vice_president_token', 'a.vice_president_token',
                'vice_president_approved_date', 'a.vice_president_approved_date',
                'access_card', 'a.access_card',
                'issued_date', 'a.issued_date',
                'created_date', 'a.created_date',
                'can_pickup', 'a.can_pickup',
                'ordering', 'a.ordering',
                'state', 'a.state',
                'created_by', 'a.created_by',

            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     */
    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        // Load the filter state.
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);

        
		//Filtering issued_date
		$this->setState('filter.issued_date.from', $app->getUserStateFromRequest($this->context.'.filter.issued_date.from', 'filter_from_issued_date', '', 'string'));
		$this->setState('filter.issued_date.to', $app->getUserStateFromRequest($this->context.'.filter.issued_date.to', 'filter_to_issued_date', '', 'string'));

		//Filtering can_pickup
		$this->setState('filter.can_pickup', $app->getUserStateFromRequest($this->context.'.filter.can_pickup', 'filter_can_pickup', '', 'string'));


        // Load the parameters.
        $params = JComponentHelper::getParams('com_keymanager');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.requester_username', 'asc');
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param	string		$id	A prefix for the store id.
     * @return	string		A store id.
     * @since	1.6
     */
    protected function getStoreId($id = '') {
        // Compile the store id.
        $id.= ':' . $this->getState('filter.search');
        $id.= ':' . $this->getState('filter.state');

        return parent::getStoreId($id);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'DISTINCT a.*'
                )
        );
        $query->from('`#__keymanager_requests` AS a');

        
		// Join over the users for the checked out user
		$query->select("uc.name AS editor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");
		// Join over the user field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

        

		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.state = ' . (int) $published);
		} else if ($published === '') {
			$query->where('(a.state IN (0, 1))');
		}

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('( a.requester_username LIKE '.$search.'  OR  a.department_head_email LIKE '.$search.'  OR  a.vice_president_email LIKE '.$search.'  OR  a.access_card LIKE '.$search.' )');
            }
        }

        

		//Filtering issued_date
		$filter_issued_date_from = $this->state->get("filter.issued_date.from");
		if ($filter_issued_date_from) {
			$query->where("a.issued_date >= '".$db->escape($filter_issued_date_from)."'");
		}
		$filter_issued_date_to = $this->state->get("filter.issued_date.to");
		if ($filter_issued_date_to) {
			$query->where("a.issued_date <= '".$db->escape($filter_issued_date_to)."'");
		}

		//Filtering can_pickup
		$filter_can_pickup = $this->state->get("filter.can_pickup");
		if ($filter_can_pickup != '') {
			$query->where("a.can_pickup = '".$db->escape($filter_can_pickup)."'");
		}


        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }

        return $query;
    }

    public function getItems() {
        $items = parent::getItems();
        
        return $items;
    }

}
