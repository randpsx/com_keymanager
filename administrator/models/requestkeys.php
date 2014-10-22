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
class KeymanagerModelRequestkeys extends JModelList {

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
                'request_id', 'a.request_id',
                'key_id', 'a.key_id',
                'ordering', 'a.ordering',
                'state', 'a.state',
                'created_by', 'a.created_by',
                'pickup_date', 'a.pickup_date',
                'returned_date', 'a.returned_date',
                'lost_date', 'a.lost_date',

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

        
		//Filtering request_id
		$this->setState('filter.request_id', $app->getUserStateFromRequest($this->context.'.filter.request_id', 'filter_request_id', '', 'string'));

		//Filtering key_id
		$this->setState('filter.key_id', $app->getUserStateFromRequest($this->context.'.filter.key_id', 'filter_key_id', '', 'string'));

		//Filtering pickup_date
		$this->setState('filter.pickup_date.from', $app->getUserStateFromRequest($this->context.'.filter.pickup_date.from', 'filter_from_pickup_date', '', 'string'));
		$this->setState('filter.pickup_date.to', $app->getUserStateFromRequest($this->context.'.filter.pickup_date.to', 'filter_to_pickup_date', '', 'string'));

		//Filtering returned_date
		$this->setState('filter.returned_date.from', $app->getUserStateFromRequest($this->context.'.filter.returned_date.from', 'filter_from_returned_date', '', 'string'));
		$this->setState('filter.returned_date.to', $app->getUserStateFromRequest($this->context.'.filter.returned_date.to', 'filter_to_returned_date', '', 'string'));

		//Filtering lost_date
		$this->setState('filter.lost_date.from', $app->getUserStateFromRequest($this->context.'.filter.lost_date.from', 'filter_from_lost_date', '', 'string'));
		$this->setState('filter.lost_date.to', $app->getUserStateFromRequest($this->context.'.filter.lost_date.to', 'filter_to_lost_date', '', 'string'));


        // Load the parameters.
        $params = JComponentHelper::getParams('com_keymanager');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.id', 'asc');
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
        $query->from('`#__keymanager_request_keys` AS a');

        
		// Join over the users for the checked out user
		$query->select("uc.name AS editor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");
		// Join over the foreign key 'request_id'
		$query->select('#__keymanager_requests_1540223.id AS requests_id_1540223');
		$query->join('LEFT', '#__keymanager_requests AS #__keymanager_requests_1540223 ON #__keymanager_requests_1540223.id = a.request_id');
		// Join over the foreign key 'key_id'
		$query->select('#__keymanager_keys_1540216.key_name AS keys_key_name_1540216');
		$query->join('LEFT', '#__keymanager_keys AS #__keymanager_keys_1540216 ON #__keymanager_keys_1540216.id = a.key_id');
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
                $query->where('( a.id LIKE '.$search.'  OR  a.request_id LIKE '.$search.'  OR  a.key_id LIKE '.$search.' )');
            }
        }

        

		//Filtering request_id
		$filter_request_id = $this->state->get("filter.request_id");
		if ($filter_request_id) {
			$query->where("a.request_id = '".$db->escape($filter_request_id)."'");
		}

		//Filtering key_id
		$filter_key_id = $this->state->get("filter.key_id");
		if ($filter_key_id) {
			$query->where("a.key_id = '".$db->escape($filter_key_id)."'");
		}

		//Filtering pickup_date
		$filter_pickup_date_from = $this->state->get("filter.pickup_date.from");
		if ($filter_pickup_date_from) {
			$query->where("a.pickup_date >= '".$db->escape($filter_pickup_date_from)."'");
		}
		$filter_pickup_date_to = $this->state->get("filter.pickup_date.to");
		if ($filter_pickup_date_to) {
			$query->where("a.pickup_date <= '".$db->escape($filter_pickup_date_to)."'");
		}

		//Filtering returned_date
		$filter_returned_date_from = $this->state->get("filter.returned_date.from");
		if ($filter_returned_date_from) {
			$query->where("a.returned_date >= '".$db->escape($filter_returned_date_from)."'");
		}
		$filter_returned_date_to = $this->state->get("filter.returned_date.to");
		if ($filter_returned_date_to) {
			$query->where("a.returned_date <= '".$db->escape($filter_returned_date_to)."'");
		}

		//Filtering lost_date
		$filter_lost_date_from = $this->state->get("filter.lost_date.from");
		if ($filter_lost_date_from) {
			$query->where("a.lost_date >= '".$db->escape($filter_lost_date_from)."'");
		}
		$filter_lost_date_to = $this->state->get("filter.lost_date.to");
		if ($filter_lost_date_to) {
			$query->where("a.lost_date <= '".$db->escape($filter_lost_date_to)."'");
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
        
		foreach ($items as $oneItem) {

			if (isset($oneItem->request_id)) {
				$values = explode(',', $oneItem->request_id);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('id')
							->from('`#__keymanager_requests`')
							->where('id = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->id;
					}
				}

			$oneItem->request_id = !empty($textValue) ? implode(', ', $textValue) : $oneItem->request_id;

			}

			if (isset($oneItem->key_id)) {
				$values = explode(',', $oneItem->key_id);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('key_name')
							->from('`#__keymanager_keys`')
							->where('id = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->key_name;
					}
				}

			$oneItem->key_id = !empty($textValue) ? implode(', ', $textValue) : $oneItem->key_id;

			}
		}
        return $items;
    }

}
