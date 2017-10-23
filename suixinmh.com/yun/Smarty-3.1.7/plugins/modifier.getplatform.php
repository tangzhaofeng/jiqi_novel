<?php
/**
 * Smarty extends plugin
 * @package Smarty
 * @subpackage Extends PluginsModifier
 */

/**
 * Smarty getplatform modifier plugin
 *
 * Type:     modifier<br>
 * Name:     getplatform<br>
 * @author Kevin <monte at 254056198@qq.com>
 * @param integer  $platform
 * @return string $res string
 */
function smarty_modifier_getplatform($platform) {
    include(DATA_DIR.'plateformlist.php');
    $res = $plateform[$platform]['title'];
	return $res;
}

?>