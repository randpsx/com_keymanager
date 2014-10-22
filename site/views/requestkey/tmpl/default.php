<?php
/**
 * @version     1.0.1
 * @package     com_keymanager
 * @copyright   Copyright (C) Imperial Valley College 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Omar Ramos <omar.ramos@imperial.edu> - http://www.imperial.edu
 */
// no direct access
defined('_JEXEC') or die;

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_keymanager', JPATH_ADMINISTRATOR);
$canEdit = JFactory::getUser()->authorise('core.edit', 'com_keymanager');
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_keymanager')) {
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<?php if ($this->item) : ?>

    <div class="item_fields">
        <table class="table">
            <tr>
			<th><?php echo JText::_('COM_KEYMANAGER_FORM_LBL_REQUESTKEY_ID'); ?></th>
			<td><?php echo $this->item->id; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_KEYMANAGER_FORM_LBL_REQUESTKEY_REQUEST_ID'); ?></th>
			<td><?php echo $this->item->request_id; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_KEYMANAGER_FORM_LBL_REQUESTKEY_KEY_ID'); ?></th>
			<td><?php echo $this->item->key_id; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_KEYMANAGER_FORM_LBL_REQUESTKEY_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_KEYMANAGER_FORM_LBL_REQUESTKEY_CREATED_BY'); ?></th>
			<td><?php echo $this->item->created_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_KEYMANAGER_FORM_LBL_REQUESTKEY_PICKUP_DATE'); ?></th>
			<td><?php echo $this->item->pickup_date; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_KEYMANAGER_FORM_LBL_REQUESTKEY_RETURNED_DATE'); ?></th>
			<td><?php echo $this->item->returned_date; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_KEYMANAGER_FORM_LBL_REQUESTKEY_LOST_DATE'); ?></th>
			<td><?php echo $this->item->lost_date; ?></td>
</tr>

        </table>
    </div>
    <?php if($canEdit && $this->item->checked_out == 0): ?>
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_keymanager&task=requestkey.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_KEYMANAGER_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_keymanager')):?>
									<a class="btn" href="<?php echo JRoute::_('index.php?option=com_keymanager&task=requestkey.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_KEYMANAGER_DELETE_ITEM"); ?></a>
								<?php endif; ?>
    <?php
else:
    echo JText::_('COM_KEYMANAGER_ITEM_NOT_LOADED');
endif;
?>
