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

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';

$doc = JFactory::getDocument();

/* */
$doc->addStyleSheet(JURI::base() . '/modules/mod_keymanager/assets/css/style.css');

/* */
$doc->addScript(JURI::base() . '/modules/mod_keymanager/assets/js/script.css');

require JModuleHelper::getLayoutPath('mod_keymanager', $params->get('content_type', 'blank'));
