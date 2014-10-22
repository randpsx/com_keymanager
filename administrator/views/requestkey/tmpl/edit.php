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
JHtml::_('formbehavior.chosen', 'select', null, array('search_contains' => true));
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_keymanager/assets/css/keymanager.css');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function() {

	js('input:hidden.request_id').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('request_idhidden')){
			js('#jform_request_id option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_request_id").trigger("liszt:updated");
	js('input:hidden.key_id').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('key_idhidden')){
			js('#jform_key_id option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_key_id").trigger("liszt:updated");
    });

    Joomla.submitbutton = function(task)
    {
        if (task == 'requestkey.cancel') {
            Joomla.submitform(task, document.getElementById('requestkey-form'));
        }
        else {

            if (task != 'requestkey.cancel' && document.formvalidator.isValid(document.id('requestkey-form'))) {

                Joomla.submitform(task, document.getElementById('requestkey-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_keymanager&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="requestkey-form" class="form-validate">

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_KEYMANAGER_TITLE_REQUESTKEY', true)); ?>
        <div class="row-fluid">
            <div class="span10 form-horizontal">
                <fieldset class="adminform">

                    				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('request_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('request_id'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->request_id as $value):
					if(!is_array($value)):
						echo '<input type="hidden" class="request_id" name="jform[request_idhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('key_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('key_id'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->key_id as $value):
					if(!is_array($value)):
						echo '<input type="hidden" class="key_id" name="jform[key_idhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php if(empty($this->item->created_by)){ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php }
				else{ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

				<?php } ?>			<div class="control-group">
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


                </fieldset>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>



        <?php echo JHtml::_('bootstrap.endTabSet'); ?>

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>