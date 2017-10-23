<?php
global $Version160;
if (!isset($Version160)) exit('PHP:SYSTEM CODE ERROR!');

class JieqiCompiler extends JieqiTpl
{
    var $unite = false;
    var $tplinc = '';
    var $functions = array(
        'noparam' => array('addslashes', 'htmlspecialchars', 'htmlentities', 'nl2br', 'rawurlencode', 'rawurldecode', 'bin2hex', 'strip_tags', 'stripslashes', 'strlen', 'strtolower', 'strtoupper', 'trim', 'ucfirst', 'ucwords', 'sizeof', 'basename', 'dirname', 'base64_encode', 'base64_decode', 'empty', 'is_array', 'isset', 'getdate', 'crc32', 'md5', 'count', 'ceil', 'floor', 'round', 'abs', 'urlencode', 'urldecode', 'intval', 'strval', 'serialize', 'unserialize', 'subdirectory'),
        'right' => array('strrchr', 'strstr', 'str_pad', 'number_format', 'substr', 'wordwrap', 'truncate', 'arithmetic', 'defaultval', 'jieqi_geturl', 'geturl'),
        'left' => array('date', 'implode', 'sprintf', 'str_replace')
    );
    var $regexp = array(
        'sqstr' => '"[^"\\\\]*(?:\\\\.[^"\\\\]*)*"',
        'dqstr' => '\'[^\'\\\\]*(?:\\\\.[^\'\\\\]*)*\'',
        'qstr' => '(?:"[^"\\\\]*(?:\\\\.[^"\\\\]*)*"|\'[^\'\\\\]*(?:\\\\.[^\'\\\\]*)*\')',
        'set' => ' *set +([\$a-zA-Z_0-9]+) *= *[\'"]?([^\'"]*)[\'"]? *',
        'block' => ' *block (.*)',
        'var' => ' *[\$]([a-zA-Z_0-9]+.*) *',
        'eval' => ' *eval\s+(.+?)\s*',
        'tag' => ' *tag_(.*)',
        'loop' => ' *section +name *=(.*)loop *=(.*)(columns *=(.*))?',
        'if' => ' *(else if|elseif|if)(.*)(!=|>=|<=|==|>|<)(.*)',
        'include' => ' *include +file *=(.*)',
        'function' => ' *function ([a-zA-Z_0-9]+.*) *'
    );

    function &getInstance()
    {
        static $instance;
        if (!isset($instance)) $instance = new JieqiCompiler();
        return $instance;
    }

    function _addslashes($str)
    {
        return str_replace(array('\\', '\''), array('\\\\', '\\\''), $str);
    }

    function _init_template_vars(&$template)
    {
        $this->template_dir = $template->template_dir;
        $this->compile_dir = $template->compile_dir;
        $this->force_compile = $template->force_compile;
        $this->caching = $template->caching;
        $this->left_delimiter = $template->left_delimiter;
        $this->right_delimiter = $template->right_delimiter;
        $this->left_comments = $template->left_comments;
        $this->right_comments = $template->right_comments;
        $this->_tpl_vars = &$template->_tpl_vars;
        $this->compile_id = $template->compile_id;
    }

    function _compile_file(&$resource_name, $isfile = true)
    {
        $this->tplinc = '';
        if ($isfile) $str = jieqi_readfile($resource_name);
        else $str = &$resource_name;
        $rep_from = array(
            '/' . $this->left_comments . '.*' . $this->right_comments . '/isU',
            '/<\?.*\?>/isU',
            '/<%.*%>/isU',
            '/<\s*script[^>]+language\s*=\s*[\'"]?php[\'"]?.*>.*<\/\s*script\s*>/isU'
        );
        $rep_to = array('', '', '', '');
        $str = preg_replace($rep_from, $rep_to, $str);
        $htmlStrs = preg_split("/(" . $this->left_delimiter . ".*" . $this->right_delimiter . ")/isU", $str, -1, PREG_SPLIT_DELIM_CAPTURE);
        $str = '';
        $n = count($htmlStrs);
        $this->unite = false;
        for ($i = 0; $i < $n; $i++) {
            if (strlen($htmlStrs[$i]) > 0) {
                if ($this->unite) $str .= ".'" . $this->_addslashes($htmlStrs[$i]) . "'";
                else $str .= "echo '" . $this->_addslashes($htmlStrs[$i]) . "'";
                $this->unite = true;
            }
            $i++;
            if ($i < $n) {
                $tmpflag = $this->unite;
                $tmpvar = strval($this->gettplstr($htmlStrs[$i]));
                if ($tmpflag == true && $this->unite == true) $str .= "." . $tmpvar;
                elseif ($tmpflag == true && $this->unite == false) $str .= ";\r\n" . $tmpvar;
                elseif ($tmpflag == false && $this->unite == true) $str .= "echo " . $tmpvar;
                elseif ($tmpflag == false && $this->unite == false) $str .= $tmpvar;
            }
        }
        if ($this->unite) $str .= ";";
        unset($regs);
        unset($htmlStrs);
        return $str;
    }

    function gettplstr($tplstr)
    {
        $regs = array();
        if (preg_match("/" . $this->left_delimiter . " *\/(if|section) *" . $this->right_delimiter . "/isU", $tplstr, $regs) > 0) {
            $ret = "}\r\n";
            $this->unite = false;
        } elseif (preg_match("/" . $this->left_delimiter . " *else *" . $this->right_delimiter . "/isU", $tplstr, $regs) > 0) {
            $ret = "}else{\r\n";
            $this->unite = false;
        } elseif (preg_match("/" . $this->left_delimiter . $this->regexp['var'] . $this->right_delimiter . "/isU", $tplstr, $regs) > 0) {
            return $this->getVar($regs);
        } elseif (preg_match("/" . $this->left_delimiter . $this->regexp['eval'] . $this->right_delimiter . "/isU", $tplstr, $regs) > 0) {
            return $this->getEvaltags($regs);
        } elseif (preg_match("/" . $this->left_delimiter . $this->regexp['tag'] . $this->right_delimiter . "/isU", $tplstr, $regs) > 0) {
            return $this->getTag($regs);
        } elseif (preg_match("/" . $this->left_delimiter . $this->regexp['set'] . $this->right_delimiter . "/isU", $tplstr, $regs) > 0) {
            $ret = $this->getSet($regs);
        } elseif (preg_match("/" . $this->left_delimiter . $this->regexp['block'] . $this->right_delimiter . "/isU", $tplstr, $regs) > 0) {
            return $this->getBlock($regs);
        } elseif (preg_match("/" . $this->left_delimiter . $this->regexp['loop'] . $this->right_delimiter . "/isU", $tplstr, $regs) > 0) {
            return $this->getLoop($regs);
        } elseif (preg_match("/" . $this->left_delimiter . $this->regexp['if'] . $this->right_delimiter . "/isU", $tplstr, $regs) > 0) {
            return $this->getIf($regs);
        } elseif (preg_match("/" . $this->left_delimiter . $this->regexp['include'] . $this->right_delimiter . "/isU", $tplstr, $regs) > 0) {
            return $this->getInclude($regs);
        } elseif (preg_match("/" . $this->left_delimiter . $this->regexp['function'] . $this->right_delimiter . "/isU", $tplstr, $regs) > 0) {
            return $this->getFunction($regs);
        }
        if ($ret === false) {
            $this->unite = true;
            return "'" . $this->_addslashes($tplstr) . "'";
        } else return $ret;
    }

    function getSet($regs)
    {
        $var = isset($regs[1]) ? $regs[1] : '';
        $value = isset($regs[2]) ? $regs[2] : '';
        $params = array();
        preg_match_all("/[\$][^!=<>,\-\)\s\?]+/i", $var, $params, PREG_SET_ORDER);
        $fromvar = array();
        $tovar = array();
        foreach ($params as $k => $v) {
            $fromvar[$k] = $v[0];
            $tovar[$k] = $this->getVarStr($v[0]);
        }
        $params = array();
        preg_match_all("/[\$][^!=<>,\-\$\)\xa1-\xff\s\?]+/i", $value, $params, PREG_SET_ORDER);
        $fromval = array();
        $toval = array();
        foreach ($params as $k => $v) {
            $fromval[$k] = $v[0];
            $toval[$k] = $this->getVarStr($v[0]);
        }
        if (strlen($var) > 0) {
            $this->unite = false;
            $tmpvalue = empty($fromval) ? '\'' . $this->_addslashes(stripslashes($value)) . '\'' : '"' . preg_replace('/\$([a-zA-Z_0-9->\'\[\]])+\]+/i', "{\$0}", str_replace($fromval, $toval, $value)) . '"';
            if (empty($fromvar)) {
                $ret = "\$GLOBALS['jieqiTset']['" . $this->_addslashes(stripslashes($var)) . "'] = " . $tmpvalue . ";\r\n";
                $this->tplinc .= $ret;
            } else {
                $ret = str_replace($fromvar, $tovar, $var) . " = " . $tmpvalue . ";\r\n";
            }
            return $ret;
        } else {
            return false;
        }
    }

    function getEvaltags($regs)
    {
        $phpnote = isset($regs[1]) ? $regs[1] : '';
        if (strlen($phpnote) > 0) {
            $this->unite = false;
            $matches = array();
            preg_match_all("/[\$][^!=<>,\;\+\-\*\/\%\-\)\xa1-\xff\s\?]+/i", $phpnote, $matches);
            foreach ($matches[0] as $note) {
                $matche = str_replace(array('$'), array(''), $note);
                $phpnote = str_replace($note, $this->getVarStr($matche), $phpnote);
            }
            return $phpnote . " \r\n";
        } else {
            return false;
        }
    }

    function getTag($regs)
    {
        $regs = explode('_', $regs[1], 3);
        if (count($regs) > 1) {
            if ($regs[0]) {
                $module = trim($regs[0]);
            } else return false;
        } else {
            $module = 'system';
        }
        preg_match_all("/[\$][^!=<>,\;\+\-\*\/\%\-\)\s\?]+/i", $regs[2], $matches);
        $encode = false;
        foreach ($matches[0] as $note) {
            $matche = str_replace(array('$'), array(''), $note);
            $regs[2] = str_replace($note, '\'.' . $this->getVarStr($matche) . '.\'', $regs[2]);
            $encode = true;
        }
        if (substr($regs[1], 0, 1) == '$') $regs[1] = str_replace($regs[1], $this->getVarStr($regs[1]), $regs[1]);
        if (!$encode) $regs[2] = urlencode($regs[2]);
        $ret = "jieqi_geturl('" . $module . "', 'tags', array('id'=>" . $regs[1] . ", 'name'=>'" . $regs[2] . "'))";
        return $ret;
    }

    function getBlock($regs)
    {
        $blockconfig = isset($regs[1]) ? trim($regs[1]) : '';
        if (strlen($blockconfig) > 0) {
            preg_match_all("/([a-zA-Z_0-9]+) *= *['\"]([^'\"]*)['\"]/isU", $blockconfig, $bcs, PREG_SET_ORDER);
            $bcstr = '';
            foreach ($bcs as $bc) {
                if (!empty($bcstr)) $bcstr .= ', ';
                if (preg_match("/[~\$]+([a-zA-Z_0-9])+[~$]/isU", $bc[2], $matches)) {
                    $matche = str_replace(array('~', '$'), array('', ''), $matches[0]);
                    $bc[2] = '\'' . str_replace($matches[0], '\'.' . $this->getVarStr($matches[0]) . '.\'', $bc[2]) . '\'';
                } elseif (preg_match_all("/[\$][^!=<>,\-\)\xa1-\xff\s\?]+/i", $bc[2], $matches, PREG_SET_ORDER)) {
                    $fromval = array();
                    $toval = array();
                    foreach ($matches as $k => $v) {
                        $fromval[$k] = $v[0];
                        $toval[$k] = '\'.' . $this->getVarStr($v[0]) . '.\'';
                    }
                    $bc[2] = '\'' . str_replace($fromval, $toval, $bc[2]) . '\'';
                } else {
                    $bc[2] = '\'' . $this->_addslashes(stripslashes($bc[2])) . '\'';
                }
                $bcstr .= '\'' . $bc[1] . '\'=>' . $bc[2];
            }
            $this->unite = true;
            $ret = "jieqi_get_block(array(" . $bcstr . "), 1)";
            return $ret;
        } else {
            return false;
        }
    }

    function getFunction($funcs)
    {
        $func = isset($funcs[1]) ? trim($funcs[1]) : '';
        if (!empty($func)) {
            return $this->getFunctionStr($func);
        } else {
            return false;
        }
    }

    function getFunctionStr($func, $varStr = '')
    {
        if (!empty($func)) {
            $func = str_replace(chr(0), '', $func);
            $funstrs = array();
            preg_match_all('/' . $this->regexp['qstr'] . '/i', $func, $funstrs);
            if (!empty($funstrs)) {
                $func = preg_replace('/' . $this->regexp['qstr'] . '/i', chr(0), $func);
            }
            $func = explode("|", $func);
            $p = 0;
            for ($i = 0, $n = count($func); $i < $n; $i++) {
                $cfunc = explode(":", $func[$i]);
                $funcname = trim($cfunc[0]);
                if (strlen($funcname) > 0) {
                    $param = array();
                    for ($j = 1, $k = count($cfunc); $j < $k; $j++) {
                        $funcvars = array();
                        if (strpos($cfunc[$j], chr(0)) !== false) {
                            $tmpi = 0;
                            $tmpl = strlen($cfunc[$j]);
                            $tmps = '';
                            for ($m = 0; $m < $tmpl; $m++) {
                                if (ord($cfunc[$j][$m]) > 0) $tmps .= $cfunc[$j][$m];
                                else {
                                    $tmps .= "'" . $this->_addslashes(stripslashes(substr($funstrs[0][$p], 1, -1))) . "'";
                                    $p++;
                                }
                            }
                            if (!preg_match_all('/\$([^!=<>,\-\)\xa1-\xff\s\?\&]+)/is', $tmps, $funcvars, PREG_SET_ORDER)) {
                                $param[] = trim($tmps);
                            } else {
                                $fromval = array();
                                $toval = array();
                                $tmps = str_replace('\'', '', $tmps);
                                foreach ($funcvars as $v) {
                                    $fromval[] = str_replace('\'', '', $v[0]);
                                    $toval[] = '\'.' . $this->getVarStr($v[1]) . '.\'';
                                }
                                $param[] = '\'' . str_replace($fromval, $toval, $tmps) . '\'';
                            }
                        } else {
                            $cfunc[$j] = trim($cfunc[$j]);
                            if (!preg_match_all('/[\$][^!=<>,\-\)\xa1-\xff\s\?\&]+/is', $cfunc[$j], $funcvars, PREG_SET_ORDER)) {
                                $param[] = "'" . $this->_addslashes($cfunc[$j]) . "'";
                            } else {
                                $fromval = array();
                                $toval = array();
                                foreach ($funcvars as $k => $v) {
                                    $fromval[$k] = $v[0];
                                    $toval[$k] = '\'.' . $this->getVarStr($v[0]) . '.\'';
                                }
                                $param[] = '\'' . str_replace($fromval, $toval, $cfunc[$j]) . '\'';
                            }
                        }
                    }
                    if (in_array($funcname, $this->functions['noparam'])) $varStr = $funcname . "($varStr)";
                    elseif (in_array($funcname, $this->functions['right'])) $varStr = ($varStr != '') ? $funcname . "(" . $varStr . "," . implode(",", $param) . ")" : $funcname . "(" . implode(",", $param) . ")";
                    elseif (in_array($funcname, $this->functions['left'])) {
                        if ($funcname != 'date') $varStr = ($varStr != '') ? $funcname . "(" . implode(",", $param) . "," . $varStr . ")" : $funcname . "(" . implode(",", $param) . ")";
                        else $varStr = ($varStr != '') ? $funcname . "('" . str_replace("'", '', implode(":", $param)) . "'," . $varStr . ")" : $funcname . "('" . str_replace("'", '', implode(":", $param)) . ")";
                    }
                }
            }
            return $varStr;
        } else {
            return false;
        }
    }

    function getVar($regs)
    {
        $name = isset($regs[1]) ? trim($regs[1]) : '';
        $newStr = $this->getVarStr($name);
        if ($newStr !== false) {
            $this->unite = true;
            return $newStr;
        } else return false;
    }

    function getVarStr($str)
    {
        preg_match('/([a-zA-Z_0-9]+) *(\[[^\|]*\])*((\.[a-zA-Z_0-9]+)*)( *\|.*)*/is', $str, $regs);
        $name = isset($regs[1]) ? $regs[1] : '';
        $cname = isset($regs[2]) ? $regs[2] : '';
        $sname = isset($regs[3]) ? $regs[3] : '';
        $func = isset($regs[5]) ? trim($regs[5]) : '';
        $cname = preg_replace('/\[\$([a-zA-Z_0-9]+)/i', "[\$this->_tpl_vars['$1']", $cname);
        $cname = preg_replace('/\.([a-zA-Z_0-9]+)/i', "['$1']", $cname);
        $cname = preg_replace('/\[([^\'"\[\]]*)\]/i', "[\$this->_tpl_vars['$1']['key']]", $cname);
        $varStr = "\$this->_tpl_vars['$name']" . $cname;
        if (!empty($sname)) $varStr .= preg_replace('/\.([a-zA-Z_0-9]+)/i', "['$1']", trim($sname));
        if (!empty($func)) {
            return $this->getFunctionStr($func, $varStr);
        }
        unset($regs);
        return $varStr;
    }

    function getLoop($regs)
    {
        $name = isset($regs[1]) ? trim($regs[1]) : '';
        $data = isset($regs[2]) ? trim($regs[2]) : '';
        $columns = isset($regs[4]) ? intval(trim($regs[4])) : 1;
        if ($columns < 1) $columns = 1;
        $dataStr = $this->getVarStr($data);
        $this->unite = false;
        return "if (empty($dataStr)) $dataStr = array();
elseif (!is_array($dataStr)) $dataStr = (array)$dataStr;
\$this->_tpl_vars['$name']=array();
\$this->_tpl_vars['$name']['columns'] = $columns;
\$this->_tpl_vars['$name']['count'] = count($dataStr);
\$this->_tpl_vars['$name']['addrows'] = count($dataStr) % \$this->_tpl_vars['$name']['columns'] == 0 ? 0 : \$this->_tpl_vars['$name']['columns'] - count($dataStr) % \$this->_tpl_vars['$name']['columns'];
\$this->_tpl_vars['$name']['loops'] = \$this->_tpl_vars['$name']['count'] + \$this->_tpl_vars['$name']['addrows'];
reset($dataStr);
for(\$this->_tpl_vars['$name']['index'] = 0; \$this->_tpl_vars['$name']['index'] < \$this->_tpl_vars['$name']['loops']; \$this->_tpl_vars['$name']['index']++){
	\$this->_tpl_vars['$name']['order'] = \$this->_tpl_vars['$name']['index'] + 1;
	\$this->_tpl_vars['$name']['row'] = ceil(\$this->_tpl_vars['$name']['order'] / \$this->_tpl_vars['$name']['columns']);
	\$this->_tpl_vars['$name']['column'] = \$this->_tpl_vars['$name']['order'] % \$this->_tpl_vars['$name']['columns'];
	if(\$this->_tpl_vars['$name']['column'] == 0) \$this->_tpl_vars['$name']['column'] = \$this->_tpl_vars['$name']['columns'];
	if(\$this->_tpl_vars['$name']['index'] < \$this->_tpl_vars['$name']['count']){
		list(\$this->_tpl_vars['$name']['key'], \$this->_tpl_vars['$name']['value']) = each($dataStr);
		\$this->_tpl_vars['$name']['append'] = 0;
	}else{
		\$this->_tpl_vars['$name']['key'] = '';
		\$this->_tpl_vars['$name']['value'] = '';
		\$this->_tpl_vars['$name']['append'] = 1;
	}
	";
    }

    function getIf($regs)
    {
        $tplStr = isset($regs[0]) ? $regs[0] : '';
        preg_match_all("/[\$][^!=<>\)\s\?]+/i", $tplStr, $params, PREG_SET_ORDER);
        $fromary = array();
        $toary = array();
        foreach ($params as $k => $v) {
            $fromary[$k] = $v[0];
            $toary[$k] = $this->getVarStr($v[0]);
        }
        $ifStr = isset($regs[1]) ? $regs[1] : '';
        $param1 = isset($regs[2]) ? $regs[2] : '';
        $operator = isset($regs[3]) ? $regs[3] : '';
        $param2 = isset($regs[4]) ? $regs[4] : '';
        $tmpStr = '';
        if (strtolower($ifStr) == "if") $tmpStr .= $ifStr;
        else  $tmpStr .= "}" . $ifStr;
        $tmpStr .= "(" . str_replace($fromary, $toary, trim($param1 . $operator . $param2)) . "){\r\n";
        $this->unite = false;
        return $tmpStr;
    }

    function getInclude($regs)
    {
        $file = isset($regs[1]) ? trim($regs[1]) : '';
        if (!preg_match("/^['\"].*['\"]$/", $file)) $file = $this->getVarStr($file);
        else $file = substr($file, 1, -1);
        $expFile = explode("?", $file);
        $fileName = &$expFile[0];
        $varstr = '';
        if (isset($expFile[1])) {
            $expVars = explode("&", trim($expFile[1]));
            foreach ($expVars as $val) {
                if (!empty($val)) {
                    $expVar = explode("=", $val);
                    if (!empty($varstr)) $varstr .= ',';
                    $varstr .= "'" . str_replace("'", '"', $expVar[0]) . "'=>'" . str_replace("'", '"', $expVar[1]) . "'";
                }
            }
        }
        $varstr = 'array(' . $varstr . ')';
        $this->unite = false;
        return "\$_template_tpl_vars = \$this->_tpl_vars;\r\n \$this->_template_include(array('template_include_tpl_file' => '" . $fileName . "', 'template_include_vars' => " . $varstr . "));\r\n \$this->_tpl_vars = \$_template_tpl_vars;\r\n unset(\$_template_tpl_vars);\r\n";
    }
}
