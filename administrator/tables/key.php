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

/**
 * key Table class
 */
class KeymanagerTablekey extends JTable {
    public $rooms = array();
    /**
     * Constructor
     *
     * @param JDatabase A database connector object
     */
    public function __construct(&$db) {
        parent::__construct('#__keymanager_keys', 'id', $db);
    }

    /**
     * Overloaded bind function to pre-process the params.
     *
     * @param	array		Named array
     * @return	null|string	null is operation was satisfactory, otherwise returns an error
     * @see		JTable:bind
     * @since	1.5
     */
    public function bind($array, $ignore = '') {
        if(!empty($array['rooms'])) {
            $this->rooms = $array['rooms'];
        }


		//Support for multiple or not foreign key field: hook_id
			if(isset($array['hook_id'])){
				if(is_array($array['hook_id'])){
					$array['hook_id'] = implode(',',$array['hook_id']);
				}
				else if(strrpos($array['hook_id'], ',') != false){
					$array['hook_id'] = explode(',',$array['hook_id']);
				}
				else if(empty($array['hook_id'])) {
					$array['hook_id'] = '';
				}
			}
		$input = JFactory::getApplication()->input;
		$task = $input->getString('task', '');
		if(($task == 'save' || $task == 'apply') && (!JFactory::getUser()->authorise('core.edit.state','com_keymanager') && $array['state'] == 1)){
			$array['state'] = 0;
		}
		if($array['id'] == 0){
			$array['created_by'] = JFactory::getUser()->id;
		}

		//Support for multiple or not foreign key field: building_id
			if(isset($array['building_id'])){
				if(is_array($array['building_id'])){
					$array['building_id'] = implode(',',$array['building_id']);
				}
				else if(strrpos($array['building_id'], ',') != false){
					$array['building_id'] = explode(',',$array['building_id']);
				}
				else if(empty($array['building_id'])) {
					$array['building_id'] = '';
				}
			}

        if (isset($array['params']) && is_array($array['params'])) {
            $registry = new JRegistry();
            $registry->loadArray($array['params']);
            $array['params'] = (string) $registry;
        }

        if (isset($array['metadata']) && is_array($array['metadata'])) {
            $registry = new JRegistry();
            $registry->loadArray($array['metadata']);
            $array['metadata'] = (string) $registry;
        }
        if (!JFactory::getUser()->authorise('core.admin', 'com_keymanager.key.' . $array['id'])) {
            $actions = JFactory::getACL()->getActions('com_keymanager', 'key');
            $default_actions = JFactory::getACL()->getAssetRules('com_keymanager.key.' . $array['id'])->getData();
            $array_jaccess = array();
            foreach ($actions as $action) {
                $array_jaccess[$action->name] = $default_actions[$action->name];
            }
            $array['rules'] = $this->JAccessRulestoArray($array_jaccess);
        }
        //Bind the rules for ACL where supported.
        if (isset($array['rules']) && is_array($array['rules'])) {
            $this->setRules($array['rules']);
        }

        if (isset($array['building_id']) && $array['building_id'] != 0) {
            $array['is_master_key'] = 1;

        }
        else {
            $array['is_master_key'] = 0;
        }

        return parent::bind($array, $ignore);
    }

    /**
     * This function convert an array of JAccessRule objects into an rules array.
     * @param type $jaccessrules an arrao of JAccessRule objects.
     */
    private function JAccessRulestoArray($jaccessrules) {
        $rules = array();
        foreach ($jaccessrules as $action => $jaccess) {
            $actions = array();
            foreach ($jaccess->getData() as $group => $allow) {
                $actions[$group] = ((bool) $allow);
            }
            $rules[$action] = $actions;
        }
        return $rules;
    }

    /**
     * Overloaded check function
     */
    public function check() {

        //If there is an ordering column and this is a new row then get the next ordering value
        if (property_exists($this, 'ordering') && $this->id == 0) {
            $this->ordering = self::getNextOrder();
        }

        return parent::check();
    }

    public function load($keys = Null, $reset = true) {
        $result = parent::load($keys, $reset);

        if($result) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->select('room_id')
                ->from($db->quoteName('#__keymanager_key_rooms'))
                ->where($db->quoteName('key_id') . ' = ' . (int) $this->id);
            $db->setQuery($query);
            $this->rooms = $db->loadColumn();
        }
        return $result;
    }

    public function store($updateNulls = false) {
        $result = parent::store();
        $initial_rooms = array();
        $rooms_diff_add = array();
        $rooms_diff_delete = array();

        if($result) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->select('room_id')
                ->from($db->quoteName('#__keymanager_key_rooms'))
                ->where($db->quoteName('key_id') . ' = ' . (int) $this->id);
            $db->setQuery($query);

            $initial_rooms = $db->loadColumn();
            $rooms_diff_delete = array_diff($initial_rooms, $this->rooms);
            $rooms_diff_add = array_diff($this->rooms, $initial_rooms);

            $this->rooms;
            foreach($rooms_diff_add as $room_id) {
                $table = JTable::getInstance('Keyroom','KeymanagerTable');
                $data = array();
                $data['key_id'] = $this->id;
                $data['room_id'] = $room_id;
                $data['state'] = $this->state;
                $success = $table->save($data);
            }

            foreach($rooms_diff_delete as $room_id) {
                $query = $db->getQuery(true)
                    ->delete($db->quoteName('#__keymanager_key_rooms'))
                    ->where($db->quoteName('key_id'). ' = ' . (int) $this->id)
                    ->where($db->quoteName('room_id') . ' = ' . (int) $room_id);
                $db->setQuery($query);
                $result = $db->execute();
            }
        }
        return $result;
    }

    /**
     * Method to set the publishing state for a row or list of rows in the database
     * table.  The method respects checked out rows by other users and will attempt
     * to checkin rows that it can after adjustments are made.
     *
     * @param    mixed    An optional array of primary key values to update.  If not
     *                    set the instance property value is used.
     * @param    integer The publishing state. eg. [0 = unpublished, 1 = published]
     * @param    integer The user id of the user performing the operation.
     * @return    boolean    True on success.
     * @since    1.0.4
     */
    public function publish($pks = null, $state = 1, $userId = 0) {
        // Initialise variables.
        $k = $this->_tbl_key;

        // Sanitize input.
        JArrayHelper::toInteger($pks);
        $userId = (int) $userId;
        $state = (int) $state;

        // If there are no primary keys set check to see if the instance key is set.
        if (empty($pks)) {
            if ($this->$k) {
                $pks = array($this->$k);
            }
            // Nothing to set publishing state on, return false.
            else {
                $this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
                return false;
            }
        }

        // Build the WHERE clause for the primary keys.
        $where = $k . '=' . implode(' OR ' . $k . '=', $pks);

        // Determine if there is checkin support for the table.
        if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time')) {
            $checkin = '';
        } else {
            $checkin = ' AND (checked_out = 0 OR checked_out = ' . (int) $userId . ')';
        }

        // Update the publishing state for rows with the given primary keys.
        $this->_db->setQuery(
                'UPDATE `' . $this->_tbl . '`' .
                ' SET `state` = ' . (int) $state .
                ' WHERE (' . $where . ')' .
                $checkin
        );
        $this->_db->query();

        // Check for a database error.
        if ($this->_db->getErrorNum()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        // If checkin is supported and all rows were adjusted, check them in.
        if ($checkin && (count($pks) == $this->_db->getAffectedRows())) {
            // Checkin each row.
            foreach ($pks as $pk) {
                $this->checkin($pk);
            }
        }

        // If the JTable instance value is in the list of primary keys that were set, set the instance.
        if (in_array($this->$k, $pks)) {
            $this->state = $state;
        }

        $this->setError('');
        return true;
    }

    /**
     * Define a namespaced asset name for inclusion in the #__assets table
     * @return string The asset name
     *
     * @see JTable::_getAssetName
     */
    protected function _getAssetName() {
        $k = $this->_tbl_key;
        return 'com_keymanager.key.' . (int) $this->$k;
    }

    /**
     * Returns the parent asset's id. If you have a tree structure, retrieve the parent's id using the external key field
     *
     * @see JTable::_getAssetParentId
     */
    protected function _getAssetParentId(JTable $table = null, $id = null) {
        // We will retrieve the parent-asset from the Asset-table
        $assetParent = JTable::getInstance('Asset');
        // Default: if no asset-parent can be found we take the global asset
        $assetParentId = $assetParent->getRootId();
        // The item has the component as asset-parent
        $assetParent->loadByName('com_keymanager');
        // Return the found asset-parent-id
        if ($assetParent->id) {
            $assetParentId = $assetParent->id;
        }
        return $assetParentId;
    }

    public function delete($pk = null) {
        $this->load($pk);
        $result = parent::delete($pk);
        if ($result) {


        }
        return $result;
    }

}
