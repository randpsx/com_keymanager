<?php
/**
 * @version     1.0.1
 * @package     com_keymanager
 * @subpackage  mod_keymanager
 * @copyright   Copyright (C) Imperial Valley College 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Omar Ramos <omar.ramos@imperial.edu> - http://www.imperial.edu
 */
defined('_JEXEC') or die;
$element = ModKeymanagerHelper::getItem($params);
?>

<?php if (!empty($element)) : ?>
    <div>
        <?php $fields = get_object_vars($element); ?>
        <?php foreach ($fields as $field_name => $field_value): ?>
            <?php if (ModKeymanagerHelper::shouldAppear($field_name)): ?>
                <div class="row">
                    <div class="span4"><strong><?php echo ModKeymanagerHelper::renderTranslatableHeader($params, $field_name); ?></strong></div>
                    <div class="span8"><?php echo ModKeymanagerHelper::renderElement($params->get('item_table'), $field_name, $field_value); ?></div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>