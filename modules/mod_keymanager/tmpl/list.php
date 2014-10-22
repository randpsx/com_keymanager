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
$elements = ModKeymanagerHelper::getList($params);
?>

<?php if (!empty($elements)) : ?>
    <table class="table">
        <?php foreach ($elements as $element): ?>
            <tr>
                <th><?php echo ModKeymanagerHelper::renderTranslatableHeader($params, $params->get('field')); ?></th>
                <td><?php echo ModKeymanagerHelper::renderElement($params->get('table'), $params->get('field'), $element->{$params->get('field')}); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>