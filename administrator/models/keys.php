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
class KeymanagerModelKeys extends JModelList {

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
                'key_name', 'a.key_name',
                'key_description', 'a.key_description',
                'hook_id', 'a.hook_id',
                'ordering', 'a.ordering',
                'state', 'a.state',
                'created_by', 'a.created_by',
                'building_id', 'a.building_id',
                'is_master_key', 'a.is_master_key',

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

        
		//Filtering is_master_key
		$this->setState('filter.is_master_key', $app->getUserStateFromRequest($this->context.'.filter.is_master_key', 'filter_is_master_key', '', 'string'));


        // Load the parameters.
        $params = JComponentHelper::getParams('com_keymanager');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.key_name', 'asc');
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
        $query->from('`#__keymanager_keys` AS a');

        
		// Join over the users for the checked out user
		$query->select("uc.name AS editor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");
		// Join over the foreign key 'hook_id'
		$query->select('#__keymanager_hooks_1540173.hook_number AS hooks_hook_number_1540173');
		$query->join('LEFT', '#__keymanager_hooks AS #__keymanager_hooks_1540173 ON #__keymanager_hooks_1540173.id = a.hook_id');
		// Join over the user field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
		// Join over the foreign key 'building_id'
		$query->select('#__keymanager_buildings_1544399.building_name AS buildings_building_name_1544399');
		$query->join('LEFT', '#__keymanager_buildings AS #__keymanager_buildings_1544399 ON #__keymanager_buildings_1544399.id = a.building_id');

        

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
                $query->where('( a.key_name LIKE '.$search.' )');
            }
        }

        

		//Filtering is_master_key
		$filter_is_master_key = $this->state->get("filter.is_master_key");
		if ($filter_is_master_key != '') {
			$query->where("a.is_master_key = '".$db->escape($filter_is_master_key)."'");
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

			if (isset($oneItem->hook_id)) {
				$values = explode(',', $oneItem->hook_id);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('hook_number')
							->from('`#__keymanager_hooks`')
							->where('id = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->hook_number;
					}
				}

			$oneItem->hook_id = !empty($textValue) ? implode(', ', $textValue) : $oneItem->hook_id;

			}

			if (isset($oneItem->building_id)) {
				$values = explode(',', $oneItem->building_id);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('building_name')
							->from('`#__keymanager_buildings`')
							->where('id = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->building_name;
					}
				}

			$oneItem->building_id = !empty($textValue) ? implode(', ', $textValue) : $oneItem->building_id;

			}
					$oneItem->is_master_key = JText::_('COM_KEYMANAGER_KEYS_IS_MASTER_KEY_OPTION_' . strtoupper($oneItem->is_master_key));
		}
        return $items;
    }

}
