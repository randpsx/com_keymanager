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
            jQuery('#form-requestkey').submit(function(event) {
                
            });

            
			jQuery('input:hidden.request_id').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('request_idhidden')){
					jQuery('#jform_request_id option[value="' + jQuery(this).val() + '"]').attr('selected',true);
				}
			});
					jQuery("#jform_request_id").trigger("liszt:updated");
			jQuery('input:hidden.key_id').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('key_idhidden')){
					jQuery('#jform_key_id option[value="' + jQuery(this).val() + '"]').attr('selected',true);
				}
			});
					jQuery("#jform_key_id").trigger("liszt:updated");
        });
    });

</script>

<div class="requestkey-edit front-end-edit">
    <?php if (!empty($this->item->id)): ?>
        <h1>Edit <?php echo $this->item->id; ?></h1>
    <?php else: ?>
        <h1>Add</h1>
    <?php endif; ?>

    <form id="form-requestkey" action="<?php echo JRoute::_('index.php?option=com_keymanager&task=requestkey.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
        
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('request_id'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('request_id'); ?></div>
	</div>
	<?php foreach((array)$this->item->request_id as $value): ?>
		<?php if(!is_array($value)): ?>
			<input type="hidden" class="request_id" name="jform[request_idhidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />';
		<?php endif; ?>
	<?php endforeach; ?>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('key_id'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('key_id'); ?></div>
	</div>
	<?php foreach((array)$this->item->key_id as $value): ?>
		<?php if(!is_array($value)): ?>
			<input type="hidden" class="key_id" name="jform[key_idhidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />';
		<?php endif; ?>
	<?php endforeach; ?>
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
		<div class="control-label"><?php echo $this->form->getLabel('pickup_date'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('pickup_date'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('returned_date'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('returned_date'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('lost_date'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('lost_date'); ?></div>
	</div>
        <div class="control-group">
            <div class="controls">
                <button type="submit" class="validate btn btn-primary"><?php echo JText::_('JSUBMIT'); ?></button>
                <a class="btn" href="<?php echo JRoute::_('index.php?option=com_keymanager&task=requestkeyform.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>
            </div>
        </div>
        
        <input type="hidden" name="option" value="com_keymanager" />
        <input type="hidden" name="task" value="requestkeyform.save" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
