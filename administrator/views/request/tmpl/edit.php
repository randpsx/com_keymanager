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

    });

    Joomla.submitbutton = function(task)
    {
        if (task == 'request.cancel') {
            Joomla.submitform(task, document.getElementById('request-form'));
        }
        else {

            if (task != 'request.cancel' && document.formvalidator.isValid(document.id('request-form'))) {

                Joomla.submitform(task, document.getElementById('request-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_keymanager&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="request-form" class="form-validate">

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_KEYMANAGER_TITLE_REQUEST', true)); ?>
        <div class="row-fluid">
            <div class="span10 form-horizontal">
                <fieldset class="adminform">

                    				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('requester_username'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('requester_username'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('department_head_email'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('department_head_email'); ?></div>
			</div>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('department_head_name'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('department_head_name'); ?></div>
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
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('vice_president_name'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('vice_president_name'); ?></div>
            </div>
				<input type="hidden" name="jform[vice_president_token]" value="<?php echo $this->item->vice_president_token; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('vice_president_approved_date'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('vice_president_approved_date'); ?></div>
			</div>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('keys'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('keys'); ?></div>
            </div>

            <?php
                foreach((array)$this->item->keys as $value):
                    if(!is_array($value)):
                        echo '<input type="hidden" class="keys" name="jform[keyshidden]['.$value.']" value="'.$value.'" />';
                    endif;
                endforeach;
            ?>

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
                <div class="control-label"><?php echo $this->form->getLabel('start_request'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('start_request'); ?></div>
            </div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('can_pickup'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('can_pickup'); ?></div>
			</div>
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php if(empty($this->item->created_by)){ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php }
				else{ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

				<?php } ?>

                </fieldset>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>



        <?php echo JHtml::_('bootstrap.endTabSet'); ?>

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>

