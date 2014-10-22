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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_keymanager', JPATH_ADMINISTRATOR);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/components/com_keymanager/assets/js/form.js');


?>
</style>
<script type="text/javascript">
    getScript('//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', function() {
        jQuery(document).ready(function() {
            jQuery('#form-request').submit(function(event) {
                
            });

            
        });
    });

</script>

<div class="request-edit front-end-edit">
    <?php if (!empty($this->item->id)): ?>
        <h1>Edit <?php echo $this->item->id; ?></h1>
    <?php else: ?>
        <h1>Add</h1>
    <?php endif; ?>

    <form id="form-request" action="<?php echo JRoute::_('index.php?option=com_keymanager&task=request.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
        
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('requester_username'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('requester_username'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('department_head_email'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('department_head_email'); ?></div>
	</div>
	<input type="hidden" name="jform[department_head_token]" value="<?php echo $this->item->department_head_token; ?>" />

	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('department_head_approved_date'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('department_head_approved_date'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vice_president_email'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vice_president_email'); ?></div>
	</div>
	<input type="hidden" name="jform[vice_president_token]" value="<?php echo $this->item->vice_president_token; ?>" />

	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('vice_president_approved_date'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('vice_president_approved_date'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('access_card'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('access_card'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('issued_date'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('issued_date'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('created_date'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('created_date'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('can_pickup'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('can_pickup'); ?></div>
	</div>
	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />

	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />

	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

	<?php if(empty($this->item->created_by)): ?>
		<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />
	<?php else: ?>
		<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
	<?php endif; ?>
        <div class="control-group">
            <div class="controls">
                <button type="submit" class="validate btn btn-primary"><?php echo JText::_('JSUBMIT'); ?></button>
                <a class="btn" href="<?php echo JRoute::_('index.php?option=com_keymanager&task=requestform.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>
            </div>
        </div>
        
        <input type="hidden" name="option" value="com_keymanager" />
        <input type="hidden" name="task" value="requestform.save" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
