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
        
	js('input:hidden.key_id').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('key_idhidden')){
			js('#jform_key_id option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_key_id").trigger("liszt:updated");
	js('input:hidden.room_id').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('room_idhidden')){
			js('#jform_room_id option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_room_id").trigger("liszt:updated");
    });

    Joomla.submitbutton = function(task)
    {
        if (task == 'reports.cancel') {
            Joomla.submitform(task, document.getElementById('reports-form'));
        }
        else {
            
            if (task != 'reports.cancel' && document.formvalidator.isValid(document.id('reports-form'))) {
                
                Joomla.submitform(task, document.getElementById('reports-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_keymanager&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="reports-form" class="form-validate">

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_KEYMANAGER_TITLE_REPORTS', true)); ?>
        <div class="row-fluid">
            <div class="span10 form-horizontal">
                <fieldset class="adminform">

                    

                </fieldset>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        
        

        <?php echo JHtml::_('bootstrap.endTabSet'); ?>

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>