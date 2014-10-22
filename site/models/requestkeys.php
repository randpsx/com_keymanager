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
class KeymanagerModelRequestkeys extends JModelList
{

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array())
    {
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
     *
     * @since    1.6
     */
    protected function populateState($ordering = null, $direction = null)
    {


        // Initialise variables.
        $app = JFactory::getApplication();

        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
        $this->setState('list.limit', $limit);

        $limitstart = $app->input->getInt('limitstart', 0);
        $this->setState('list.start', $limitstart);

        if ($list = $app->getUserStateFromRequest($this->context . '.list', 'list', array(), 'array')) {
            foreach ($list as $name => $value) {
                // Extra validations
                switch ($name) {
                    case 'fullordering':
                        $orderingParts = explode(' ', $value);

                        if (count($orderingParts) >= 2) {
                            // Latest part will be considered the direction
                            $fullDirection = end($orderingParts);

                            if (in_array(strtoupper($fullDirection), array('ASC', 'DESC', ''))) {
                                $this->setState('list.direction', $fullDirection);
                            }

                            unset($orderingParts[count($orderingParts) - 1]);

                            // The rest will be the ordering
                            $fullOrdering = implode(' ', $orderingParts);

                            if (in_array($fullOrdering, $this->filter_fields)) {
                                $this->setState('list.ordering', $fullOrdering);
                            }
                        } else {
                            $this->setState('list.ordering', $ordering);
                            $this->setState('list.direction', $direction);
                        }
                        break;

                    case 'ordering':
                        if (!in_array($value, $this->filter_fields)) {
                            $value = $ordering;
                        }
                        break;

                    case 'direction':
                        if (!in_array(strtoupper($value), array('ASC', 'DESC', ''))) {
                            $value = $direction;
                        }
                        break;

                    case 'limit':
                        $limit = $value;
                        break;

                    // Just to keep the default case
                    default:
                        $value = $value;
                        break;
                }

                $this->setState('list.' . $name, $value);
            }
        }

        // Receive & set filters
        if ($filters = $app->getUserStateFromRequest($this->context . '.filter', 'filter', array(), 'array')) {
            foreach ($filters as $name => $value) {
                $this->setState('filter.' . $name, $value);
            }
        }

        $this->setState('list.ordering', $app->input->get('filter_order'));
        $this->setState('list.direction', $app->input->get('filter_order_Dir'));
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return    JDatabaseQuery
     * @since    1.6
     */
    protected function getListQuery()
    {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query
            ->select(
                $this->getState(
                    'list.select', 'DISTINCT a.*'
                )
            );

        $query->from('`#__keymanager_request_keys` AS a');

        
    // Join over the users for the checked out user.
    $query->select('uc.name AS editor');
    $query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
    
		// Join over the foreign key 'request_id'
		$query->select('#__keymanager_requests_1540223.id AS requests_id_1540223');
		$query->join('LEFT', '#__keymanager_requests AS #__keymanager_requests_1540223 ON #__keymanager_requests_1540223.id = a.request_id');
		// Join over the foreign key 'key_id'
		$query->select('#__keymanager_keys_1540216.key_name AS keys_key_name_1540216');
		$query->join('LEFT', '#__keymanager_keys AS #__keymanager_keys_1540216 ON #__keymanager_keys_1540216.id = a.key_id');
		// Join over the created by field 'created_by'
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int)substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                
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

    public function getItems()
    {
        $items = parent::getItems();
        foreach($items as $item){
	

			if (isset($item->request_id) && $item->request_id != '') {
				if(is_object($item->request_id)){
					$item->request_id = JArrayHelper::fromObject($item->request_id);
				}
				$values = (is_array($item->request_id)) ? $item->request_id : explode(',',$item->request_id);

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

			$item->request_id = !empty($textValue) ? implode(', ', $textValue) : $item->request_id;

			}

			if (isset($item->key_id) && $item->key_id != '') {
				if(is_object($item->key_id)){
					$item->key_id = JArrayHelper::fromObject($item->key_id);
				}
				$values = (is_array($item->key_id)) ? $item->key_id : explode(',',$item->key_id);

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

			$item->key_id = !empty($textValue) ? implode(', ', $textValue) : $item->key_id;

			}
}
        return $items;
    }
}