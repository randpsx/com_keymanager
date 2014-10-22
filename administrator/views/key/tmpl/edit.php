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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_keymanager/assets/css/keymanager.css');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function() {

	js('input:hidden.hook_id').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('hook_idhidden')){
			js('#jform_hook_id option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_hook_id").trigger("liszt:updated");
	js('input:hidden.building_id').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('building_idhidden')){
			js('#jform_building_id option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_building_id").trigger("liszt:updated");
    });

    Joomla.submitbutton = function(task)
    {
        if (task == 'key.cancel') {
            Joomla.submitform(task, document.getElementById('key-form'));
        }
        else {

            if (task != 'key.cancel' && document.formvalidator.isValid(document.id('key-form'))) {

                Joomla.submitform(task, document.getElementById('key-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_keymanager&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="key-form" class="form-validate">

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_KEYMANAGER_TITLE_KEY', true)); ?>
        <div class="row-fluid">
            <div class="span10 form-horizontal">
                <fieldset class="adminform">

                    				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('key_name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('key_name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('key_description'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('key_description'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('hook_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('hook_id'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->hook_id as $value):
					if(!is_array($value)):
						echo '<input type="hidden" class="hook_id" name="jform[hook_idhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('state'); ?></div>
			</div>
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php if(empty($this->item->created_by)){ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php }
				else{ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

				<?php } ?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('building_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('building_id'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->building_id as $value):
					if(!is_array($value)):
						echo '<input type="hidden" class="building_id" name="jform[building_idhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('is_master_key'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('is_master_key'); ?></div>
			</div>


                </fieldset>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>



        <?php echo JHtml::_('bootstrap.endTabSet'); ?>

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>