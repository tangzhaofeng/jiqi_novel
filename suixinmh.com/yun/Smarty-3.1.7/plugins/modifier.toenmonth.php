<?php
/**
 * Smarty extends plugin
 * @package Smarty
 * @subpackage Extends PluginsModifier
 */

/**
 * Smarty getstatus modifier plugin
 *
 * Type:     modifier<br>
 * Name:     toenmonth<br>
 * @author Kevin <monte at 254056198@qq.com>
 * @param integer  $month
 * @return string
 */
function smarty_modifier_toenmonth($month, $imageShow = true) {
    include(DATA_DIR.'month.php');
	return $monthConf[$month];
}

?>