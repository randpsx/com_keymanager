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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$canCreate = $user->authorise('core.create', 'com_keymanager');
$canEdit = $user->authorise('core.edit', 'com_keymanager');
$canCheckin = $user->authorise('core.manage', 'com_keymanager');
$canChange = $user->authorise('core.edit.state', 'com_keymanager');
$canDelete = $user->authorise('core.delete', 'com_keymanager');
?>

<form action="<?php echo JRoute::_('index.php?option=com_keymanager&view=requests'); ?>" method="post" name="adminForm" id="adminForm">

    <?php echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
    <table class="table table-striped" id = "requestList" >
        <thead >
            <tr >
                <?php if (isset($this->items[0]->state)): ?>
        <th width="1%" class="nowrap center">
            <?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
        </th>
    <?php endif; ?>

    				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_KEYMANAGER_REQUESTS_REQUESTER_USERNAME', 'a.requester_username', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_KEYMANAGER_REQUESTS_DEPARTMENT_HEAD_EMAIL', 'a.department_head_email', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_KEYMANAGER_REQUESTS_DEPARTMENT_HEAD_TOKEN', 'a.department_head_token', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_KEYMANAGER_REQUESTS_DEPARTMENT_HEAD_APPROVED_DATE', 'a.department_head_approved_date', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_KEYMANAGER_REQUESTS_VICE_PRESIDENT_EMAIL', 'a.vice_president_email', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_KEYMANAGER_REQUESTS_VICE_PRESIDENT_TOKEN', 'a.vice_president_token', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_KEYMANAGER_REQUESTS_VICE_PRESIDENT_APPROVED_DATE', 'a.vice_president_approved_date', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_KEYMANAGER_REQUESTS_ACCESS_CARD', 'a.access_card', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_KEYMANAGER_REQUESTS_ISSUED_DATE', 'a.issued_date', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_KEYMANAGER_REQUESTS_CREATED_DATE', 'a.created_date', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_KEYMANAGER_REQUESTS_CAN_PICKUP', 'a.can_pickup', $listDirn, $listOrder); ?>
				</th>


    <?php if (isset($this->items[0]->id)): ?>
        <th width="1%" class="nowrap center hidden-phone">
            <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
        </th>
    <?php endif; ?>

    				<?php if ($canEdit || $canDelete): ?>
					<th class="center">
				<?php echo JText::_('COM_KEYMANAGER_REQUESTS_ACTIONS'); ?>
				</th>
				<?php endif; ?>

    </tr>
    </thead>
    <tfoot>
    <tr>
        <td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
            <?php echo $this->pagination->getListFooter(); ?>
        </td>
    </tr>
    </tfoot>
    <tbody>
    <?php foreach ($this->items as $i => $item) : ?>
        <?php $canEdit = $user->authorise('core.edit', 'com_keymanager'); ?>

        				<?php if (!$canEdit && $user->authorise('core.edit.own', 'com_keymanager')): ?>
					<?php $canEdit = JFactory::getUser()->id == $item->created_by; ?>
				<?php endif; ?>

        <tr class="row<?php echo $i % 2; ?>">

            <?php if (isset($this->items[0]->state)): ?>
                <?php $class = ($canEdit || $canChange) ? 'active' : 'disabled'; ?>
                <td class="center">
                    <a class="btn btn-micro <?php echo $class; ?>"
                       href="<?php echo ($canEdit || $canChange) ? JRoute::_('index.php?option=com_keymanager&task=requestform.publish&id=' . $item->id . '&state=' . (($item->state + 1) % 2), false, 2) : '#'; ?>">
                        <?php if ($item->state == 1): ?>
                            <i class="icon-publish"></i>
                        <?php else: ?>
                            <i class="icon-unpublish"></i>
                        <?php endif; ?>
                    </a>
                </td>
            <?php endif; ?>

            				<td>
				<?php if (isset($item->checked_out) && $item->checked_out) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'requests.', $canCheckin); ?>
				<?php endif; ?>
				<a href="<?php echo JRoute::_('index.php?option=com_keymanager&view=request&id='.(int) $item->id); ?>">
				<?php echo $this->escape($item->requester_username); ?></a>
				</td>
				<td>

					<?php echo $item->department_head_email; ?>
				</td>
				<td>

					<?php echo $item->department_head_token; ?>
				</td>
				<td>

					<?php echo $item->department_head_approved_date; ?>
				</td>
				<td>

					<?php echo $item->vice_president_email; ?>
				</td>
				<td>

					<?php echo $item->vice_president_token; ?>
				</td>
				<td>

					<?php echo $item->vice_president_approved_date; ?>
				</td>
				<td>

					<?php echo $item->access_card; ?>
				</td>
				<td>

					<?php echo $item->issued_date; ?>
				</td>
				<td>

					<?php echo $item->created_date; ?>
				</td>
				<td>

					<?php echo $item->can_pickup; ?>
				</td>


            <?php if (isset($this->items[0]->id)): ?>
                <td class="center hidden-phone">
                    <?php echo (int)$item->id; ?>
                </td>
            <?php endif; ?>

            				<?php if ($canEdit || $canDelete): ?>
					<td class="center">
						<?php if ($canEdit): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_keymanager&task=requestform.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i></a>
						<?php endif; ?>
						<?php if ($canDelete): ?>
							<button data-item-id="<?php echo $item->id; ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i></button>
						<?php endif; ?>
					</td>
				<?php endif; ?>

        </tr>
    <?php endforeach; ?>
    </tbody>
    </table>

    <?php if ($canCreate): ?>
        <a href="<?php echo JRoute::_('index.php?option=com_keymanager&task=requestform.edit&id=0', false, 2); ?>"
           class="btn btn-success btn-small"><i
                class="icon-plus"></i> <?php echo JText::_('COM_KEYMANAGER_ADD_ITEM'); ?></a>
    <?php endif; ?>

    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
    <?php echo JHtml::_('form.token'); ?>
</form>

<script type="text/javascript">

    jQuery(document).ready(function () {
        jQuery('.delete-button').click(deleteItem);
    });

    function deleteItem() {
        var item_id = jQuery(this).attr('data-item-id');
        if (confirm("<?php echo JText::_('COM_KEYMANAGER_DELETE_MESSAGE'); ?>")) {
            window.location.href = '<?php echo JRoute::_('index.php?option=com_keymanager&task=requestform.remove&id=', false, 2) ?>' + item_id;
        }
    }
</script>


