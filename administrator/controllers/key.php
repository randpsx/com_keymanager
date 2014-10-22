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

jimport('joomla.application.component.controllerform');

/**
 * Key controller class.
 */
class KeymanagerControllerKey extends JControllerForm
{

    function __construct() {
        $this->view_list = 'keys';
        parent::__construct();
    }

}