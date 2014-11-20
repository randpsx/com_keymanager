<?php
/**
* @version     1.0.1
* @package     com_keymanager
* @copyright   Copyright (C) Imperial Valley College 2014. All rights reserved.
* @license     GNU General Public License version 2 or later; see LICENSE.txt
* @author      Omar Ramos <omar.ramos@imperial.edu> - http://www.imperial.edu
*/

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
* Keymanager model.
*/
class KeymanagerModelRequest extends JModelAdmin
{
    /**
    * @var		string	The prefix to use with controller messages.
    * @since	1.6
    */
    protected $text_prefix = 'COM_KEYMANAGER';


    /**
    * Returns a reference to the a Table object, always creating it.
    *
    * @param	type	The table type to instantiate
    * @param	string	A prefix for the table class name. Optional.
    * @param	array	Configuration array for model. Optional.
    * @return	JTable	A database object
    * @since	1.6
    */
    public function getTable($type = 'Request', $prefix = 'KeymanagerTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
    * Method to get the record form.
    *
    * @param	array	$data		An optional array of data for the form to interogate.
    * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
    * @return	JForm	A JForm object on success, false on failure
    * @since	1.6
    */
    public function getForm($data = array(), $loadData = true)
    {
        // Initialise variables.
        $app	= JFactory::getApplication();

        // Get the form.
        $form = $this->loadForm('com_keymanager.request', 'request', array('control' => 'jform', 'load_data' => $loadData));


        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
    * Method to get the data that should be injected in the form.
    *
    * @return	mixed	The data for the form.
    * @since	1.6
    */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_keymanager.edit.request.data', array());

        if (empty($data)) {
            $data = $this->getItem();

        }

        return $data;
    }

    /**
    * Method to get a single record.
    *
    * @param	integer	The id of the primary key.
    *
    * @return	mixed	Object on success, false on failure.
    * @since	1.6
    */
    public function getItem($pk = null)
    {
        if ($item = parent::getItem($pk)) {

            if ($item->keys instanceof JObject) {
                $item->keys = $item->keys->getProperties();
            }

            /*$registry = new JRegistry;
            $registry->loadString($item->keys);
            $item->keys = $registry->toArray();*/

        }

        return $item;
    }

    /**
    * Prepare and sanitise the table prior to saving.
    *
    * @since	1.6
    */
    protected function prepareTable($table)
    {
        jimport('joomla.filter.output');

        if (empty($table->id)) {

            // Set ordering to the last item if not set
            if (@$table->ordering === '') {
                $db = JFactory::getDbo();
                $db->setQuery('SELECT MAX(ordering) FROM #__keymanager_requests');
                $max = $db->loadResult();
                $table->ordering = $max+1;
            }

        }
    }

    public function startrequest($pks)
    {
        $db = JFactory::getDbo();

        foreach($pks as $request_id) {
            $query = $db->getQuery(true)
            ->update('#__keymanager_requests')
            ->set('start_request = ' . (int) 1);

            $query->where('id = ' . (int) $request_id);


            $db->setQuery($query);
            $db->execute();

        }

        return true;

    }

    public function getKeysListQuery($key_id) {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select('k.*')
        ->from('#__keymanager_keys as k')
        ->where('k.id = '. (int) $key_id);

        $db->setQuery($query);
        $items = $db->loadObject();

        return $items;
    }

    public function getRoomsListQuery($key_id) {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select('r.*')
        ->from('#__keymanager_key_rooms AS kr')
        ->where('kr.key_id = ' . (int) $key_id)
        ->join('LEFT', '#__keymanager_rooms AS r ON kr.room_id = r.id');


        $db->setQuery($query);
        $items = $db->loadObjectList();

        return $items;
    }


    // Sends an email to the Department Head as well as administrators
    public function departmentheadKeyRequestEmail($pks)
    {

        foreach($pks as $request_id) {
            $params = JComponentHelper::getParams('com_keymanager');
            $table_request = JTable::getInstance('Request','KeymanagerTable');
            $table_request->load($request_id);


            foreach($table_request->keys as $key_id)
            {
                $key_list[$key_id] = $this->getKeysListQuery($key_id);
                /*foreach($key_list as $keys) {
                $key_name[$key_id] = $keys->key_name;
                $key_description[$key_id] = $keys->key_description;
                $master_key[$key_id] = $keys->is_master_key;
                }*/
            }

            $key_list_length = sizeof($key_list);

            foreach($table_request->keys as $key_id)
            {
                $room_list[$key_id] = $this->getRoomsListQuery($key_id);
                /*foreach($room_list as $rooms) {
                $room_name[$key_id] = $rooms->room_name;
                }*/
            }



            $room_list_length = sizeof($room_list);

            $date = JFactory::getDate('now', 'America/Los_Angeles')->toSql(true);

            $app = JFactory::getApplication();
            $from = $params->get('admin_email');
            $fromName = $params->get('from_name');
            $bcc = $params->get(admin_email_2);


            $recipients = array();
            $recipients[0] = $table_request->department_head_email;
            $recipients[1] = $params->get(admin_email);


            $subject = 'Key Request ( Sent on ' . $date. ')';

            $body = '';

            $body .= '<html><head></head><body style="font-family: Calibri,Tahoma,Arial;">';

            $body .= '<p>';
            $body .= 'Hi ' . $table_request->department_head_name . ',';
            $body .= '</p>';
            $body .= '<p>';
            $body .= 'This email is to notify you that <span style="font-weight: bold">' . $table_request->requester_username . '</span> is requesting one or more keys.';
            $body .= '</p>';

            foreach($key_list as $keys => $value) {
                if($value->is_master_key == '1'){
                    $body .= '<p>';
                    $body .= 'The key being requested is: ' . $value->key_name . ' and is a master key.';
                }
                else {
                    $body .='<p>';
                    $body .='The key being requested is: ' .$value->key_name . '.';

                }

                foreach($room_list[$keys] as $room) {

                    $body .= '<br/> The room for this key is: ' . $room->room_name . '.';

                }
                if(!empty($value->key_description)) {
                    $body .= '<br/> The key is for: ' . $value->key_description . '.';
                }
            }


            $body .='<p>';
            $body .= 'To approve and/or disapprove keys click <a href="www.google.com">here</a> .';
            $body .='</p>';



            $body .= '</tbody>';
            $body .= '</table>';

            $body .= '<br />';

            $body .= '<p style="font-weight: bold">';
            $body .= 'This email was sent via Imperial Valley College\'s Key Management System';
            $body .= '</p>';
            $body .= '</body></html>';

            $isHTML = true;

            $mail = JFactory::getMailer();
            if ($app->get('useSMTP', false))
            {
                $mail->useSMTP(true, $app->get('smtpHost'), $app->get('smtpUsername'), $app->get('smtpPassword'), $app->get('smtpSecure'), $app->get('smtpPort', 25));
            }
            return $mail->sendMail($from, $fromName, $recipients, $subject, $body, $isHTML, null, $bcc, null, $from, $fromName);
        }


    }

}

