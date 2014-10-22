<?php

/**
 * @version     1.0.1
 * @package     com_keymanager
 * @subpackage  mod_keymanager
 * @copyright   Copyright (C) Imperial Valley College 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Omar Ramos <omar.ramos@imperial.edu> - http://www.imperial.edu
 */
defined('_JEXEC') or die;

/**
 * Helper for mod_keymanager
 *
 * @package     com_keymanager
 * @subpackage  mod_keymanager
 */
class ModKeymanagerHelper {

    /**
     * Retrieve component items
     * @param Joomla\Registry\Registry  &$params  module parameters
     * @return array Array with all the elements
     */
    public static function getList(&$params) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        /* @var $params Joomla\Registry\Registry */
        $query
                ->select('*')
                ->from($params->get('table'))
                ->where('state = 1');

        $db->setQuery($query, $params->get('offset'), $params->get('limit'));
        $rows = $db->loadObjectList();
        return $rows;
    }

    /**
     * Retrieve component items
     * @param Joomla\Registry\Registry  &$params  module parameters
     * @return mixed stdClass object if the item was found, null otherwise
     */
    public static function getItem(&$params) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        /* @var $params Joomla\Registry\Registry */
        $query
                ->select('*')
                ->from($params->get('item_table'))
                ->where('id = ' . intval($params->get('item_id')));

        $db->setQuery($query);
        $element = $db->loadObject();
        return $element;
    }

    /**
     * 
     * @param Joomla\Registry\Registry $params
     * @param string $field
     */
    public static function renderElement($table_name, $field_name, $field_value) {
        $result = '';
        
        switch ($table_name) {
            
		case '#__keymanager_requests':
		switch($field_name){
		case 'id':
		$result = $field_value;
		break;
		case 'requester_username':
		$result = $field_value;
		break;
		case 'department_head_email':
		$result = $field_value;
		break;
		case 'department_head_token':
		$result = $field_value;
		break;
		case 'department_head_approved_date':
		$result = $field_value;
		break;
		case 'vice_president_email':
		$result = $field_value;
		break;
		case 'vice_president_token':
		$result = $field_value;
		break;
		case 'vice_president_approved_date':
		$result = $field_value;
		break;
		case 'access_card':
		$result = $field_value;
		break;
		case 'issued_date':
		$result = $field_value;
		break;
		case 'created_date':
		$result = $field_value;
		break;
		case 'can_pickup':
		$result = $field_value;
		break;
		case 'created_by':
		$user = JFactory::getUser($field_value);
		$result = $user->name;
		break;
		}
		break;
		case '#__keymanager_request_keys':
		switch($field_name){
		case 'id':
		$result = $field_value;
		break;
		case 'request_id':
		$result = self::loadValueFromExternalTable('#__keymanager_requests', 'id', 'id', $field_value);
		break;
		case 'key_id':
		$result = self::loadValueFromExternalTable('#__keymanager_keys', 'id', 'key_name', $field_value);
		break;
		case 'created_by':
		$user = JFactory::getUser($field_value);
		$result = $user->name;
		break;
		case 'pickup_date':
		$result = $field_value;
		break;
		case 'returned_date':
		$result = $field_value;
		break;
		case 'lost_date':
		$result = $field_value;
		break;
		}
		break;
		case '#__keymanager_cabinets':
		switch($field_name){
		case 'id':
		$result = $field_value;
		break;
		case 'cabinet_name':
		$result = $field_value;
		break;
		case 'cabinet_description':
		$result = $field_value;
		break;
		case 'created_by':
		$user = JFactory::getUser($field_value);
		$result = $user->name;
		break;
		}
		break;
		case '#__keymanager_hooks':
		switch($field_name){
		case 'id':
		$result = $field_value;
		break;
		case 'hook_number':
		$result = $field_value;
		break;
		case 'cabinet_id':
		$result = self::loadValueFromExternalTable('#__keymanager_cabinets', 'id', 'cabinet_name', $field_value);
		break;
		case 'created_by':
		$user = JFactory::getUser($field_value);
		$result = $user->name;
		break;
		case 'hook_created_date':
		$result = $field_value;
		break;
		}
		break;
		case '#__keymanager_buildings':
		switch($field_name){
		case 'id':
		$result = $field_value;
		break;
		case 'building_name':
		$result = $field_value;
		break;
		case 'building_description':
		$result = $field_value;
		break;
		case 'created_by':
		$user = JFactory::getUser($field_value);
		$result = $user->name;
		break;
		}
		break;
		case '#__keymanager_rooms':
		switch($field_name){
		case 'id':
		$result = $field_value;
		break;
		case 'room_name':
		$result = $field_value;
		break;
		case 'room_description':
		$result = $field_value;
		break;
		case 'building_id':
		$result = self::loadValueFromExternalTable('#__keymanager_buildings', 'id', 'building_name', $field_value);
		break;
		case 'created_by':
		$user = JFactory::getUser($field_value);
		$result = $user->name;
		break;
		}
		break;
		case '#__keymanager_keys':
		switch($field_name){
		case 'id':
		$result = $field_value;
		break;
		case 'key_name':
		$result = $field_value;
		break;
		case 'key_description':
		$result = $field_value;
		break;
		case 'hook_id':
		$result = self::loadValueFromExternalTable('#__keymanager_hooks', 'id', 'hook_number', $field_value);
		break;
		case 'created_by':
		$user = JFactory::getUser($field_value);
		$result = $user->name;
		break;
		case 'building_id':
		$result = self::loadValueFromExternalTable('#__keymanager_buildings', 'id', 'building_name', $field_value);
		break;
		case 'is_master_key':
		$result = $field_value;
		break;
		}
		break;
		case '#__keymanager_key_rooms':
		switch($field_name){
		case 'id':
		$result = $field_value;
		break;
		case 'key_id':
		$result = self::loadValueFromExternalTable('#__keymanager_keys', 'id', 'key_name', $field_value);
		break;
		case 'room_id':
		$result = self::loadValueFromExternalTable('#__keymanager_rooms', 'id', 'room_name', $field_value);
		break;
		case 'created_by':
		$user = JFactory::getUser($field_value);
		$result = $user->name;
		break;
		}
		break;
        }
        return $result;
    }

    /**
     * Returns the translatable name of the element
     * @param Joomla\Registry\Registry $params
     * @param string $field Field name
     * @return string Translatable name.
     */
    public static function renderTranslatableHeader(&$params, $field) {
        return JText::_('MOD_KEYMANAGER_HEADER_FIELD_' . str_replace('#__', '', strtoupper($params->get('table'))) . '_' . strtoupper($field));
    }

    /**
     * Checks if an element should appear in the table/item view
     * @param string $field name of the field
     * @return boolean True if it should appear, false otherwise
     */
    public static function shouldAppear($field) {
        $noHeaderFields = array('checked_out_time', 'checked_out', 'ordering', 'state');
        return !in_array($field, $noHeaderFields);
    }

    

    /**
     * Method to get a value from a external table
     * @param string $source_table Source table name
     * @param string $key_field Source key field 
     * @param string $value_field Source value field
     * @param mixed  $key_value Value for the key field
     * @return mixed The value in the external table or null if it wasn't found
     */
    private static function loadValueFromExternalTable($source_table, $key_field, $value_field, $key_value) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
                ->select($value_field)
                ->from($source_table)
                ->where($key_field . ' = ' . $db->quote($key_value));


        $db->setQuery($query);
        return $db->loadResult();
    }
}
