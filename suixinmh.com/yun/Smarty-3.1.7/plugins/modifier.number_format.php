<?php
/**
 * Smarty extends plugin
 * @package Smarty
 * @subpackage Extends PluginsModifier
 */

/**
 * Smarty number_format modifier plugin
 *
 * Type:     modifier<br>
 * Name:     number_format<br>
 * @author Kevin <monte at 254056198@qq.com>
 * @param float  $data
 * @param int $mod
 * @return string $res
 */
function smarty_modifier_number_format($data, $mod = 0) {
    $res = $mod == 0 ? round($data) : round($data, $mod);
	return number_format($res);
}
?>