<?php

class TextTypeset extends JieqiObject
{
    var $freplace = array();
    var $treplace = array();
    var $delmoreblank = true;
    var $delchars = array();
    var $errstartchars = array();
    var $fmore = array();
    var $tmore = array();

    function TextTypeset()
    {
        $this->freplace = array(',', '.', '¡¤', '£®', ';', '!', '?', ':', '(', ')');
        $this->treplace = array('£¬', '¡£', '¡£', '¡£', '£»', '£¡', '£¿', '£º', '£¨', '£©');
        $this->delmoreblank = true;
        $this->delchars = array(' ', '¡¡', "\r");
        $this->errstartchars = array('¡£', '£¿', '£¡', '¡¹', '¡±', '£©');
        $this->fmore = array('.', '¡£', '-');
        $this->tmore = array('¡­¡­', '¡­¡­', '¡ª¡ª');
    }

    function doTypeset(&$str)
    {
        $ret = '';
        $tmpstr = '';
        $tmpstr1 = '';
        $repeatnum = 0;
        $start = true;
        $linestart = true;
        $sectionstart = true;
        $strlen = strlen($str);
        for ($i = 0; $i < $strlen; $i++) {
            $tmpstr = $str[$i];
            if (ord($str[$i]) > 0x80 && $i + 1 < $strlen) {
                $tmpstr .= $str[++$i];
            }
            if (in_array($tmpstr, $this->delchars)) continue;
            if ($tmpstr == "\n") {
                $sectionstart = true;
                continue;
            }
            if ($sectionstart && in_array($tmpstr, $this->errstartchars)) $sectionstart = false;
            $tmpvar = $repeatnum;
            if (in_array($tmpstr, $this->fmore)) {
                if ($tmpstr == $tmpstr1) {
                    $repeatnum++;
                } else {
                    $tmpstr1 = $tmpstr;
                    $repeatnum = 1;
                }
                continue;
            }
            if ($tmpvar > 0 && $tmpvar == $repeatnum) {
                if ($repeatnum == 1) {
                    $ret .= $tmpstr1;
                } else {
                    $key = array_search($tmpstr1, $this->fmore);
                    if ($key) $ret .= $this->tmore[$key];
                }
                $tmpstr1 = '';
                $repeatnum = 0;
            }
            if ($sectionstart) {
                if (!$start) $ret .= "\r\n\r\n";
                else $start = false;
                $ret .= '    ';
                $sectionstart = false;
            }
            $ret .= $tmpstr;
        }
        if ($repeatnum == 1) {
            $ret .= $tmpstr1;
        } elseif ($repeatnum > 1) {
            $key = array_search($tmpstr1, $this->fmore);
            if ($key) $$ret .= $this->tmore[$key];
        }
        return $ret;
    }
}
