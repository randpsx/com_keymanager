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
 * Keymanager helper.
 */
class KeymanagerHelper {

    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($vName = '') {
        		JHtmlSidebar::addEntry(
			JText::_('COM_KEYMANAGER_TITLE_REQUESTS'),
			'index.php?option=com_keymanager&view=requests',
			$vName == 'requests'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_KEYMANAGER_TITLE_REQUESTKEYS'),
			'index.php?option=com_keymanager&view=requestkeys',
			$vName == 'requestkeys'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_KEYMANAGER_TITLE_CABINETS'),
			'index.php?option=com_keymanager&view=cabinets',
			$vName == 'cabinets'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_KEYMANAGER_TITLE_HOOKS'),
			'index.php?option=com_keymanager&view=hooks',
			$vName == 'hooks'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_KEYMANAGER_TITLE_BUILDINGS'),
			'index.php?option=com_keymanager&view=buildings',
			$vName == 'buildings'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_KEYMANAGER_TITLE_ROOMS'),
			'index.php?option=com_keymanager&view=rooms',
			$vName == 'rooms'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_KEYMANAGER_TITLE_KEYS'),
			'index.php?option=com_keymanager&view=keys',
			$vName == 'keys'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_KEYMANAGER_TITLE_KEYROOMS'),
			'index.php?option=com_keymanager&view=keyrooms',
			$vName == 'keyrooms'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_KEYMANAGER_TITLE_REPORTSS'),
			'index.php?option=com_keymanager&view=reportss',
			$vName == 'reportss'
		);

    }

    /**
     * Gets a list of the actions that can be performed.
     *
     * @return	JObject
     * @since	1.6
     */
    public static function getActions() {
        $user = JFactory::getUser();
        $result = new JObject;

        $assetName = 'com_keymanager';

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }


}
