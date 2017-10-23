<?php
if (function_exists('jieqi_hooks_footer')) jieqi_hooks_footer();
if (!empty($jieqiTset['jieqi_contents_template'])) {
    if (!isset($jieqiTset['jieqi_contents_cacheid'])) $jieqiTset['jieqi_contents_cacheid'] = NULL;
    if (!isset($jieqiTset['jieqi_contents_compileid'])) $jieqiTset['jieqi_contents_compileid'] = NULL;
    $jieqiTpl->include_compiled_inc($jieqiTset['jieqi_contents_template'], $jieqiTset['jieqi_contents_compileid']);
}
if (!empty($jieqiTset['jieqi_blocks_config'])) {
    if (!empty($jieqiTset['jieqi_blocks_module'])) jieqi_getConfigs($jieqiTset['jieqi_blocks_module'], $jieqiTset['jieqi_blocks_config'], 'jieqiBlocks');
    else jieqi_getConfigs(JIEQI_MODULE_NAME, $jieqiTset['jieqi_blocks_config'], 'jieqiBlocks');
}
if (!isset($jieqi_showlblock)) $jieqi_showlblock = false;
if (!isset($jieqi_showcblock)) $jieqi_showcblock = false;
if (!isset($jieqi_showrblock)) $jieqi_showrblock = false;
if (!isset($jieqi_showtblock)) $jieqi_showtblock = false;
if (!isset($jieqi_showbblock)) $jieqi_showbblock = false;
if (isset($jieqiBlocks) && is_array($jieqiBlocks)) {
    reset($jieqiBlocks);
    while (list($i) = each($jieqiBlocks)) {
        $blockindex = 'bid' . $i;
        $blockvalue = jieqi_get_block($jieqiBlocks[$i]);
        if (!empty($blockvalue)) {
            $jieqi_pageblocks[$blockindex] = $blockvalue;
            ${$jieqi_blockside}[] = &$jieqi_pageblocks[$blockindex];
        }
    }
    unset($blockindex);
    unset($blockvalue);
    unset($jieqiBlocks);
}
$jieqi_showblock = $jieqi_showlblock | $jieqi_showcblock | $jieqi_showrblock | $jieqi_showtblock | $jieqi_showbblock;
$jieqiTpl->assign('jieqi_showblock', intval($jieqi_showblock));
if (isset($jieqi_pageblocks)) $jieqiTpl->assign_by_ref('jieqi_pageblocks', $jieqi_pageblocks);
if ($jieqi_showlblock) {
    $jieqiTpl->assign('jieqi_showlblock', 1);
    if (isset($jieqi_lblocks) && is_array($jieqi_lblocks)) $jieqiTpl->assign_by_ref('jieqi_lblocks', $jieqi_lblocks);
} else {
    $jieqiTpl->assign('jieqi_showlblock', 0);
}
if ($jieqi_showcblock) {
    $jieqiTpl->assign('jieqi_showcblock', 1);
    if (isset($jieqi_clblocks) && is_array($jieqi_clblocks)) $jieqiTpl->assign_by_ref('jieqi_clblocks', $jieqi_clblocks);
    if (isset($jieqi_crblocks) && is_array($jieqi_crblocks)) $jieqiTpl->assign_by_ref('jieqi_crblocks', $jieqi_crblocks);
    if (isset($jieqi_ctblocks) && is_array($jieqi_ctblocks)) $jieqiTpl->assign_by_ref('jieqi_ctblocks', $jieqi_ctblocks);
    if (isset($jieqi_cmblocks) && is_array($jieqi_cmblocks)) $jieqiTpl->assign_by_ref('jieqi_cmblocks', $jieqi_cmblocks);
    if (isset($jieqi_cbblocks) && is_array($jieqi_cbblocks)) $jieqiTpl->assign_by_ref('jieqi_cbblocks', $jieqi_cbblocks);
} else {
    $jieqiTpl->assign('jieqi_showcblock', 0);
}
if ($jieqi_showrblock) {
    $jieqiTpl->assign('jieqi_showrblock', 1);
    if (isset($jieqi_rblocks) && is_array($jieqi_rblocks)) $jieqiTpl->assign_by_ref('jieqi_rblocks', $jieqi_rblocks);
} else {
    $jieqiTpl->assign('jieqi_showrblock', 0);
}
if ($jieqi_showtblock) {
    $jieqiTpl->assign('jieqi_showtblock', 1);
    if (isset($jieqi_tblocks) && is_array($jieqi_tblocks)) $jieqiTpl->assign_by_ref('jieqi_tblocks', $jieqi_tblocks);
} else {
    $jieqiTpl->assign('jieqi_showtblock', 0);
}
if ($jieqi_showbblock) {
    $jieqiTpl->assign('jieqi_showbblock', 1);
    if (isset($jieqi_bblocks) && is_array($jieqi_bblocks)) $jieqiTpl->assign_by_ref('jieqi_bblocks', $jieqi_bblocks);
} else {
    $jieqiTpl->assign('jieqi_showbblock', 0);
}
if (!empty($jieqiTset['jieqi_contents_template'])) {
    $jieqiTpl->assign('jieqi_contents', $jieqiTpl->fetch($jieqiTset['jieqi_contents_template'], $jieqiTset['jieqi_contents_cacheid'], $jieqiTset['jieqi_contents_compileid']));
}
if (!empty($_REQUEST['ajax_request']) && !empty($_REQUEST['ajax_gets'])) {
    header('Content-Type:text/html; charset=' . JIEQI_CHAR_SET);
    header("Cache-Control:no-cache");
    if (is_array($_REQUEST['ajax_gets'])) {
        $out_var = array();
        foreach ($_REQUEST['ajax_gets'] as $v) if (isset($jieqiTpl->_tpl_vars[$v])) $out_var[$v] =& $jieqiTpl->_tpl_vars[$v];
    } else {
        if (isset($jieqiTpl->_tpl_vars[$_REQUEST['ajax_gets']])) $out_var =& $jieqiTpl->_tpl_vars[$_REQUEST['ajax_gets']];
        else $out_var = '';
    }
    if (is_array($out_var)) exit(serialize($out_var));
    if ($_REQUEST['CALLBACK']) {
        include_once(JIEQI_ROOT_PATH . '/include/changecode.php');
        exit($_REQUEST['CALLBACK'] . '(' . json_encode(jieqi_gb2utf8($out_var)) . ');');
    } else exit($out_var);
}
$tmpvar = explode(' ', microtime());
$jieqiTpl->assign('jieqi_exetime', round($tmpvar[1] + $tmpvar[0] - JIEQI_START_TIME, 6));
$jieqiTpl->setCaching(0);
if (empty($jieqiTset['jieqi_page_template'])) {
    if ($_REQUEST['controller']=='article' || $_REQUEST['controller']=='chapter') {
        $jieqiTpl->display(JIEQI_ROOT_PATH . '/themes/' . JIEQI_THEME_NAME . '/theme_writer.html');
    }
    else {
        $jieqiTpl->display(JIEQI_ROOT_PATH . '/themes/' . JIEQI_THEME_NAME . '/theme.html');
    }
} else {
    if ($jieqiTset['jieqi_page_template'][0] != '/' && $jieqiTset['jieqi_page_template'][1] != ':') $jieqiTpl->display(JIEQI_ROOT_PATH . '/' . $jieqiTset['jieqi_page_template']);
    else $jieqiTpl->display($jieqiTset['jieqi_page_template']);
}
if (!empty($_GET['fromuid']) && defined('JIEQI_PROMOTION_VISIT') && defined('JIEQI_PROMOTION_VISIT') && (JIEQI_PROMOTION_VISIT > 0 || JIEQI_PROMOTION_REGISTER > 0)) {
    jieqi_includedb();
    $query = JieqiQueryHandler::getInstance('JieqiQueryHandler');
    if (JIEQI_PROMOTION_VISIT > 0) {
        $query->execute("REPLACE INTO " . jieqi_dbprefix('system_promotions') . " (ip, uid, username) VALUES ('" . jieqi_userip() . "', '" . $_GET['fromuid'] . "', '')");
    }
    if (JIEQI_PROMOTION_REGISTER > 0 && empty($_COOKIE['jieqiPromotion'])) {
        @setcookie('jieqiPromotion', $_GET['fromuid'], 0, '/', JIEQI_COOKIE_DOMAIN, 0);
    }
}
if (defined('JIEQI_PROMOTION_VISIT') && JIEQI_PROMOTION_VISIT > 0 && substr(date('is', JIEQI_NOW_TIME), -3) == '000') {
    jieqi_includedb();
    $query = JieqiQueryHandler::getInstance('JieqiQueryHandler');
    $uidarray = array();
    $query->execute("SELECT * FROM " . jieqi_dbprefix('system_promotions'));
    while ($promotion = $query->getRow()) {
        if (is_numeric($promotion['uid'])) {
            $uidarray[] = intval($promotion['uid']);
        }
    }
    if ($uidarray) {
        $countarray = array();
        foreach (array_count_values($uidarray) as $uid => $count) {
            $countarray[$count][] = $uid;
        }
        foreach ($countarray as $count => $uids) {
            $query->execute("UPDATE " . jieqi_dbprefix('system_users') . " SET credit=credit+" . intval($count * JIEQI_PROMOTION_VISIT) . " WHERE uid IN (" . implode(',', $uids) . ")");
        }
        $query->execute("DELETE FROM " . jieqi_dbprefix('system_promotions'));
    }
}
if (function_exists('jieqi_hooks_end')) jieqi_hooks_end();
jieqi_freeresource();
if (defined('JIEQI_DEBUG_MODE') && JIEQI_DEBUG_MODE > 0) {
    $runtime = explode(' ', microtime());
    $debuginfo = 'Processed in ' . round($runtime[1] + $runtime[0] - JIEQI_START_TIME, 6) . ' second(s), ';
    if (function_exists('memory_get_usage')) $debuginfo .= 'Memory usage ' . round(memory_get_usage() / 1024) . 'K, ';
    $sqllog = array();
    if (defined('JIEQI_DB_CONNECTED')) {
        $instance =& JieqiDatabase::retInstance();
        if (!empty($instance)) {
            foreach ($instance as $db) {
                $sqllog = array_merge($sqllog, $db->sqllog('ret'));
            }
        }
    }
    $queries = count($sqllog);
    $debuginfo .= $queries . ' queries, ';
    if (defined('JIEQI_USE_GZIP') && JIEQI_USE_GZIP > 0) $debuginfo .= 'Gzip enabled.';
    else $debuginfo .= 'Gzip disabled.';
    if ($queries > 0) {
        foreach ($sqllog as $sql) $debuginfo .= '<br />' . $sql;
    }
    echo '<div class="divbox">' . $debuginfo . '</div>';
}
