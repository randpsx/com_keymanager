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
			<th><?php echo JText::_('COM_KEYMANAGER_FORM_LBL_REQUEST_ID'); ?></th>
			<td><?php echo $this->item->id; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_KEYMANAGER_FORM_LBL_REQUEST_REQUESTER_USERNAME'); ?></th>
			<td><?php echo $this->item->requester_username; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_KEYMANAGER_FORM_LBL_REQUEST_DEPARTMENT_HEAD_EMAIL'); ?></th>
			<td><?php echo $this->item->department_head_email; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_KEYMANAGER_FORM_LBL_REQUEST_DEPARTMENT_HEAD_TOKEN'); ?></th>
			<td><?php echo $this->item->department_head_token; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_KEYMANAGER_FORM_LBL_REQUEST_DEPARTMENT_HEAD_APPROVED_DATE'); ?></th>
			<td><?php echo $this->item->department_head_approved_date; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_KEYMANAGER_FORM_LBL_REQUEST_VICE_PRESIDENT_EMAIL'); ?></th>
			<td><?php echo $this->item->vice_president_email; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_KEYMANAGER_FORM_LBL_REQUEST_VICE_PRESIDENT_TOKEN'); ?></th>
			<td><?php echo $this->item->vice_president_token; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_KEYMANAGER_FORM_LBL_REQUEST_VICE_PRESIDENT_APPROVED_DATE'); ?></th>
			<td><?php echo $this->item->vice_president_approved_date; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_KEYMANAGER_FORM_LBL_REQUEST_ACCESS_CARD'); ?></th>
			<td><?php echo $this->item->access_card; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_KEYMANAGER_FORM_LBL_REQUEST_ISSUED_DATE'); ?></th>
			<td><?php echo $this->item->issued_date; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_KEYMANAGER_FORM_LBL_REQUEST_CREATED_DATE'); ?></th>
			<td><?php echo $this->item->created_date; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_KEYMANAGER_FORM_LBL_REQUEST_CAN_PICKUP'); ?></th>
			<td><?php echo $this->item->can_pickup; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_KEYMANAGER_FORM_LBL_REQUEST_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_KEYMANAGER_FORM_LBL_REQUEST_CREATED_BY'); ?></th>
			<td><?php echo $this->item->created_by_name; ?></td>
</tr>

        </table>
    </div>
    <?php if($canEdit && $this->item->checked_out == 0): ?>
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_keymanager&task=request.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_KEYMANAGER_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_keymanager')):?>
									<a class="btn" href="<?php echo JRoute::_('index.php?option=com_keymanager&task=request.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_KEYMANAGER_DELETE_ITEM"); ?></a>
								<?php endif; ?>
    <?php
else:
    echo JText::_('COM_KEYMANAGER_ITEM_NOT_LOADED');
endif;
?>
