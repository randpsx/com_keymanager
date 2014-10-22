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

$safe_htmltags = array(
    'a', 'address', 'em', 'strong', 'b', 'i',
    'big', 'small', 'sub', 'sup', 'cite', 'code',
    'img', 'ul', 'ol', 'li', 'dl', 'lh', 'dt', 'dd',
    'br', 'p', 'table', 'th', 'td', 'tr', 'pre',
    'blockquote', 'nowiki', 'h1', 'h2', 'h3',
    'h4', 'h5', 'h6', 'hr');

/* @var $params Joomla\Registry\Registry */
$filter = JFilterInput::getInstance($safe_htmltags);
echo $filter->clean($params->get('html_content'));
?>