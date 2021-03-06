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

jimport('joomla.application.component.controlleradmin');

/**
 * Requests list controller class.
 */
class KeymanagerControllerRequests extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'request', $prefix = 'KeymanagerModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}


	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function saveOrderAjax()
	{
		// Get the input
		$input = JFactory::getApplication()->input;
		$pks = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');

		// Sanitize the input
		JArrayHelper::toInteger($pks);
		JArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		JFactory::getApplication()->close();
	}

     public function startrequest()
        {

                // Get the input
                $input = JFactory::getApplication()->input;
                $pks = $input->post->get('cid', array(), 'array');

                // Sanitize the input
                JArrayHelper::toInteger($pks);

                // Get the model
                $model = $this->getModel();


                $return = $model->startrequest($pks);

                $mail = $model->departmentheadKeyRequestEmail($pks);



                if($mail == true) {
                    $msg = 'Email sent successfully!';
                }
                else if($mail == false) {
                    $msg = 'Failure to send email!';
                    $this->setError($msg);

                }
                // Redirect to the list screen.
                $this->setRedirect(JRoute::_('index.php?option=com_keymanager&view=requests',false), $msg);

        }



}