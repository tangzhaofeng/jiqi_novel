<?php
include_once($GLOBALS['jieqiModules']['article']['path'] . '/include/collectfunction.php');
include_once(JIEQI_ROOT_PATH . '/include/changecode.php');
include_once(JIEQI_ROOT_PATH . '/lib/text/textfunction.php');

/**
 * article采集类
 * @copyright Copyright(c) 2014
 * @author huliming
 * @version 1.0
 */
class MyCollect_action extends Model
{
    var $siteid;
    var $jieqiCollectsite = array();
    var $colary = array();
    var $jieqiCollect = array();
    var $jieqiLang = array();
    var $pageary = array();
    var $article = array();//当前采集的文章信息数组
    var $collectConfigs = array();//程序运行过程中加载过的采集配置
    var $tmpchapter = array();
    var $error_continue = false;//是否直接跳出报错页面
    var $checkvolume = false; //对比章节时候要不要匹配卷名（默认不要）
    var $retflag = 0;
    var $articlelib = false;
    //var $infodata = '';

    //初始化采集参数
    function init($siteid)
    {
        global $jieqiCollectsite;
        //加载语言包
        $this->addLang('article', 'collect');
        $this->jieqiLang['article'] = $this->getLang('article');
        //加载采集配置
        if (!$jieqiCollectsite) {
            $this->addConfig('article', 'collectsite');
            $jieqiCollectsite = $this->getConfig('article', 'collectsite');
        }
        $this->jieqiCollectsite = $jieqiCollectsite;
        if (!$jieqiCollect = $this->loadConfig($siteid)) jieqi_printfail($this->jieqiLang['article']['collect_rule_notfull']);
        $this->jieqiCollect = $jieqiCollect;
        //设置采集URL参数
        $this->colary = array('repeat' => 4, 'referer' => $this->jieqiCollect['referer'], 'siteid' => $_REQUEST['siteid'], 'wget' => $this->jieqiCollect['wget'], 'proxy_host' => $this->jieqiCollect['proxy_host'], 'proxy_port' => $this->jieqiCollect['proxy_port'], 'proxy_user' => $this->jieqiCollect['proxy_user'], 'proxy_pass' => $this->jieqiCollect['proxy_pass']);
        if (!empty($this->jieqiCollect['pagecharset'])) $this->colary['charset'] = $this->jieqiCollect['pagecharset'];
        $this->siteid = $siteid;
    }

    //加载采集配置
    function loadConfig($siteid)
    {
        if (!isset($this->collectConfigs[$siteid])) {
            $collectsite = JIEQI_ROOT_PATH . '/configs/article/site_' . $this->jieqiCollectsite[$siteid]['config'] . '.php';
            if (file_exists($collectsite)) include_once($collectsite);
            if (empty($jieqiCollect['articletitle'])) return false;
            $this->collectConfigs[$siteid] = $jieqiCollect;
        }
        return $this->collectConfigs[$siteid];
    }

    //采集文章信息
    function getBook($fromid)
    {
        $url = str_replace('<{articleid}>', $fromid, $this->jieqiCollect['urlarticle']);
        if (!empty($this->jieqiCollect['subarticleid'])) {
            $subarticleid = 0;
            $articleid = $fromid;
            $tmpstr = '$subarticleid = ' . $this->jieqiCollect['subarticleid'] . ';';
            eval($tmpstr);
            $url = str_replace('<{subarticleid}>', $subarticleid, $url);
        }
        $source = $this->getPage($url, 'info');
        if (empty($source)) {
            $this->outMsg(sprintf($this->jieqiLang['article']['collect_url_failure'], $url, jieqi_htmlstr($url)));
            $this->retflag = 3;
            return false;
        }
        $ret = array();
        //标题
        $pregstr = jieqi_collectstoe($this->jieqiCollect['articletitle']);
        $matchvar = jieqi_cmatchone($pregstr, $source);
        if (empty($matchvar)) {
            if (!$this->error_continue) $this->outMsg(sprintf($this->jieqiLang['article']['parse_articletitle_failure'], jieqi_htmlstr($url), jieqi_htmlstr($source)));
            $this->retflag = 3;
            return false;
        }
        $ret['articlename'] = jieqi_sbcstr(trim(jieqi_textstr($matchvar)));
        //作者
        $ret['author'] = '';
        $pregstr = jieqi_collectstoe($this->jieqiCollect['author']);
        if (!empty($pregstr)) {
            $matchvar = jieqi_cmatchone($pregstr, $source);
            if (!empty($matchvar)) $ret['author'] = trim(jieqi_textstr($matchvar));
        }
        //类型
        $ret['sort'] = '';
        $pregstr = jieqi_collectstoe($this->jieqiCollect['sort']);
        if (!empty($pregstr)) {
            $matchvar = jieqi_cmatchone($pregstr, $source);
            if (!empty($matchvar)) $ret['sort'] = trim(jieqi_textstr($matchvar));
        }
        //子类型
        $ret['type'] = '';
        //定制版、豪华版允许
        if (in_array(strtoupper(JIEQI_MODULE_VTYPE), array('CUSTOM'))) {
            $pregstr = jieqi_collectstoe($this->jieqiCollect['type']);
            if (!empty($pregstr)) {
                $matchvar = jieqi_cmatchone($pregstr, $source);
                if (!empty($matchvar)) $ret['type'] = trim(jieqi_textstr($matchvar));
            }
        }
        //关键字
        $ret['keywords'] = '';
        $pregstr = jieqi_collectstoe($this->jieqiCollect['keyword']);
        if (!empty($pregstr)) {
            $matchvar = jieqi_cmatchone($pregstr, $source);
            if (!empty($matchvar)) $ret['keywords'] = str_replace('  ', ' ', trim(jieqi_textstr($matchvar)));
        }
        //简介
        $ret['intro'] = '';
        $pregstr = jieqi_collectstoe($this->jieqiCollect['intro']);
        if (!empty($pregstr)) {
            $matchvar = jieqi_cmatchone($pregstr, $source);
            if (!empty($matchvar)) $ret['intro'] = '    ' . trim(jieqi_textstr($matchvar));
        }
        //封面
        $articleimage = '';
        $pregstr = jieqi_collectstoe($this->jieqiCollect['articleimage']);
        if (substr($pregstr, 0, 4) == 'http') {
            $articleimage = str_replace('<{articleid}>', $fromid, $pregstr);
            $pregstr = '';
        }
        if (!empty($pregstr)) {
            $matchvar = jieqi_cmatchone($pregstr, $source);
            if (!empty($matchvar)) $articleimage = trim(jieqi_textstr($matchvar));
        }
        //是不是需要过滤封面
        if (!empty($articleimage) && !empty($this->jieqiCollect['filterimage'])) {
            if (strpos($articleimage, $this->jieqiCollect['filterimage']) !== false) $articleimage = '';
        }
        if (!empty($articleimage) && !in_array(strrchr(strtolower($articleimage), '.'), array('.gif', '.jpg', '.jpeg', '.bmp', '.png'))) $articleimage = '';
        //图片如果相对地址，改成绝对的
        if (!empty($articleimage) && strpos($articleimage, 'http') !== 0) {
            if (substr($articleimage, 0, 1) == '/') {
                $matches = array();
                preg_match('/https?:\/\/[^\/]+/is', $url, $matches);
                if (!empty($matches[0])) $articleimage = $matches[0] . $articleimage;
                else $articleimage = $this->jieqiCollect['siteurl'] . $articleimage;
            } else {
                $tmpdir = dirname($url);
                while (strpos($articleimage, '../') === 0) {
                    $tmpdir = dirname($tmpdir);
                    $articleimage = substr($articleimage, 3);
                }
                $articleimage = $tmpdir . '/' . $articleimage;
            }
        }
        $ret['articleimage'] = $articleimage;
        //全文标记
        $pregstr = jieqi_collectstoe($this->jieqiCollect['fullarticle']);
        if (!empty($pregstr)) {
            $matchvar = jieqi_cmatchone($pregstr, $source);
            if (!empty($matchvar)) $fullarticle = 1;
            else $fullarticle = 0;
        } else {
            if (!empty($this->jieqiCollect['defaultfull'])) $fullarticle = 1;
            else $fullarticle = 0;
        }
        $ret['fullflag'] = $fullarticle;
        //VIP开始章节
        $this->vipstart = $this->vipstartorder = 0;
        if (is_numeric($this->jieqiCollect['vipstart']) && $this->jieqiCollect['vipstart'] > 0) {
            $this->vipstartorder = $this->jieqiCollect['vipstart'];
        } else {
            $pregstr = jieqi_collectstoe($this->jieqiCollect['vipstart']);
            if (!empty($pregstr)) {
                $matchvar = jieqi_cmatchone($pregstr, $source);
                if (!empty($matchvar)) $this->vipstart = trim(jieqi_textstr($matchvar));
            }
        }
        $ret['display'] = $this->jieqiCollect['display'] ? 1 : 0;
        $ret['firstflag'] = $this->jieqiCollect['firstflag'];
        $ret['permission'] = $this->jieqiCollect['firstflag'] ? 1 : 2;
        $ret['oldaid'] = $fromid;
        return $ret;
    }

    //采集文章列表信息
    function getChapters($fromid)
    {
        //需要从信息页面解析目录页面链接
        $indexlink = '';
        if (strpos($this->jieqiCollect['urlindex'], '<{indexlink}>') !== false && !empty($this->jieqiCollect['indexlink'])) {
            if (!$this->pageary['infodata']) $article = $this->getBook($fromid);
            //目录链接
            $pregstr = jieqi_collectstoe($this->jieqiCollect['indexlink']);
            if (!empty($pregstr)) {
                $matchvar = jieqi_cmatchone($pregstr, $this->pageary['infodata']);
                if (!empty($matchvar)) $indexlink = trim(jieqi_textstr($matchvar));
            }
        }
        //解析目录页地址
        if (!empty($indexlink)) $tmpstr = str_replace('<{indexlink}>', $indexlink, $this->jieqiCollect['urlindex']);
        else $tmpstr = $this->jieqiCollect['urlindex'];
        $url = str_replace('<{articleid}>', $fromid, $tmpstr);
        if (!empty($this->jieqiCollect['subarticleid'])) {
            $subarticleid = 0;
            $articleid = $fromid;
            $tmpstr = '$subarticleid = ' . $this->jieqiCollect['subarticleid'] . ';';
            eval($tmpstr);
            $url = str_replace('<{subarticleid}>', $subarticleid, $url);
        }
        //取得目录页内容
        $source = $this->getPage($url, 'chapters');//echo $source;exit;
        if (empty($source)) {//如果没有成功取到章节列表页内容
            $this->outMsg(sprintf($this->jieqiLang['article']['collect_index_failure'], $url, $url));
            $this->retflag = 3;
            return false;
        } else {//如果成功取到章节列表页内容

            //VIP开始章节
            if (!$this->vipstartorder && !$this->vipstart && $this->jieqiCollect['vipstart']) {
                $pregstr = jieqi_collectstoe($this->jieqiCollect['vipstart']);
                if (!empty($pregstr)) {
                    $matchvar = jieqi_cmatchone($pregstr, $source);
                    if (!empty($matchvar)) $this->vipstart = trim(jieqi_textstr($matchvar));
                }
            }
            //开始解析章节
            $newCollect = array();
            $newCollect['chapter'] = $this->jieqiCollect['chapter'];
            $newCollect['volume'] = $this->jieqiCollect['volume'];
            $newCollect['chapterid'] = $this->jieqiCollect['chapterid'];
            $newCollect['isvip'] = $this->jieqiCollect['isvip'];
            $newCollect['content'] = $this->jieqiCollect['content'];
            //匹配章节名
            $pregstr = jieqi_collectstoe($this->jieqiCollect['chapter']);
            $matchvar = jieqi_cmatchall($pregstr, $source, PREG_OFFSET_CAPTURE);
            //echo count($matchvar);exit;
            if (empty($matchvar)) {//章节解析失败
                $this->outMsg(sprintf($jieqiLang['article']['parse_chapter_failure'], $url, $url));
                $this->retflag = 3;
                return false;
            } else {//章节成功解析
                if (is_array($matchvar)) $chapterary = $matchvar;
                else $chapterary = array();
                //匹配章节序号
                $pregstr = jieqi_collectstoe($this->jieqiCollect['chapterid']);
                $matchvar = jieqi_cmatchall($pregstr, $source, PREG_OFFSET_CAPTURE);
                if (is_array($matchvar)) $chapteridary = $matchvar;
                else $chapteridary = array();

                //匹配是否VIP标记
                if ($newCollect['isvip']) {
                    $pregstr = jieqi_collectstoe($this->jieqiCollect['isvip']);
                    $matchvar = jieqi_cmatchall($pregstr, $source, PREG_OFFSET_CAPTURE);
                    if (is_array($matchvar)) $chaptervipary = $matchvar;
                    else $chaptervipary = array();
                }
                else {
                    $chaptervipary = array();
                }

                //echo count($chapteridary);exit;
                //匹配卷名
                $volumeary = array();
                $pregstr = jieqi_collectstoe($this->jieqiCollect['volume']);
                if (!empty($pregstr)) {
                    $matchvar = jieqi_cmatchall($pregstr, $source, PREG_OFFSET_CAPTURE);
                    if (is_array($matchvar)) $volumeary = $matchvar;
                    else $volumeary = array();
                }
                //过滤章节名称
                $filterary = $repfrom = array();
                if (!empty($this->jieqiCollect['chapterfilter'])) {
                    $filterary = explode("\n", $this->jieqiCollect['chapterfilter']);
                    $repfrom = array();
                    foreach ($filterary as $filterstr) {
                        $filterstr = trim($filterstr);
                        if (!empty($filterstr)) {
                            if (preg_match('/^\/[^\/\\\\]*(?:\\\\.[^\/\\\\]*)*\/[imsu]*$/is', $filterstr)) $repfrom[] = $filterstr;
                            else $repfrom[] = '/' . jieqi_pregconvert($filterstr) . '/is';
                        }
                    }
                    $repto = '';
                    if (!empty($this->jieqiCollect['chapterreplace'])) {
                        $repto = explode("\n", str_replace("\r\n", "\n", $this->jieqiCollect['chapterreplace']));
                    }
                    //if(count($repfrom) > 0) $chaptercontent=preg_replace($repfrom, $repto, $chaptercontent);
                }
                $cleanchapterfilter = '';//过滤章节
                if (!empty($this->jieqiCollect['cleanchapter'])) {
                    if (preg_match('/^\/[^\/\\\\]*(?:\\\\.[^\/\\\\]*)*\/[imsu]*$/is', $this->jieqiCollect['cleanchapter'])) $cleanchapterfilter = $this->jieqiCollect['cleanchapter'];
                    else $cleanchapterfilter = '/' . jieqi_pregconvert($this->jieqiCollect['cleanchapter']) . '/is';
                }
                //组合章节和分卷名称
                $fromrows = array();
                $i = 0;
                $j = 0;
                $k = 0;
                $chapternum = count($chapterary);
                $volumenum = count($volumeary);
                $volumename = '';
                $tmpchapter = $tmpids = array();
                while ($j < $chapternum || $k < $volumenum) {
                    if ($j < $chapternum) $a = $chapterary[$j][1];
                    else $a = 99999999;
                    if ($k < $volumenum) $b = $volumeary[$k][1];
                    else $b = 99999999;
                    if ($a < $b) {
                        $tmpvar = preg_replace("/\(([\d]*):([\d]*)\)/", "", trim(jieqi_textstr($chapterary[$j][0])));//增加过滤章节后面的时间
                        if ($tmpvar != '' && !in_array($chapteridary[$j][0], $tmpids)) {
                            if (!$this->jieqiCollect['cleantargetsiterepeatchapter'] || !in_array($tmpvar, $tmpchapter)) {//过滤目标网站重复章节
                                if (count($repfrom) > 0) $tmpvar = preg_replace($repfrom, $repto, $tmpvar);
                                if (!$cleanchapterfilter || !preg_match($cleanchapterfilter, $tmpvar)) {//过滤章节
                                    $fromrows[$i]['title'] = $tmpvar;
                                    $fromrows[$i]['type'] = 0;
                                    $fromrows[$i]['id'] = $chapteridary[$j][0];
                                    $fromrows[$i]['vname'] = $volumename;
                                    if (!empty($chaptervipary)) {
                                        $fromrows[$i]['isvip'] = $chaptervipary[$j][0];
                                    }
                                    $tmpchapter[] = $tmpvar;
                                    $tmpids[] = $fromrows[$i]['id'];
                                    $i++;
                                }
                            }
                        }
                        $j++;
                    } else {
                        $tmpvar = preg_replace("/\(([\d]*):([\d]*)\)/", "", trim(jieqi_textstr($volumeary[$k][0])));//增加过滤章节后面的时间
                        if ($tmpvar != '' && strlen($tmpvar) > 1) {
                            if (!$this->jieqiCollect['cleantargetsiterepeatchapter'] || !in_array($tmpvar, $tmpchapter)) {//过滤目标网站重复章节
                                if (count($repfrom) > 0) $tmpvar = preg_replace($repfrom, $repto, $tmpvar);
                                if (!$cleanchapterfilter || !preg_match($cleanchapterfilter, $tmpvar)) {//过滤章节
                                    $fromrows[$i]['title'] = $tmpvar;
                                    $fromrows[$i]['type'] = 1;
                                    $fromrows[$i]['id'] = 0;
                                    $fromrows[$i]['vname'] = $tmpvar;
                                    $volumename = $tmpvar;
                                    $tmpchapter[] = $tmpvar;
                                    $i++;
                                }
                            }
                        }
                        $k++;
                    }
                }
                if (count($fromrows > 0)) {
                    if ($fromrows[count($fromrows) - 1]['type'] > 0) unset($fromrows[count($fromrows) - 1]);
                }
                unset($tmpchapter);
                unset($tmpids);

                $order = 0;
                $old_fromrows = array();
                if ($this->jieqiCollect['chapteridorder']) {
                    foreach ($fromrows as $jk => $vvk) {
                        if (!$vvk['type']) $order = $vvk['id'] + 100;
                        else {
                            if (!isset($fromrows[$jk + 1])) $order = 999999999;
                            else {
                                if (isset($fromrows[$jk + 1])) $order = $fromrows[$jk + 1]['id'] + 99;
                            }
                        }
                        $fromrows[$jk]['order'] = $order;
                    }
                    $this->multisort($fromrows, 'order');//print_r($fromrows);exit;
                    //ksort($fromrows);$fromrows = array_values($fromrows);
                }
                $vorder = 0;
                $corder = 0;
                $vipstart = false;
                //去掉分卷中的文章名
                $chapter_num = 0;
                foreach ($fromrows as $key => $value) {
                    //array_values
                    $fromrows[$key]['vname'] = trim(str_replace($this->article['articlename'], '', $fromrows[$key]['vname']));
                    if ($value['type'] > 0) {
                        $fromrows[$key]['title'] = $fromrows[$key]['vname'];
                        $vorder++;
                        $corder = 0;
                    } else {
                        $corder++;
                        $chapter_num++;
                        if ($vorder > 1 && $corder == 1) {
                            $tempary = jieqi_splitchapter($fromrows[$key]['vname'] . ' ' . $fromrows[$key]['title']);
                            //分卷大于一，章节从一开始的情况
                            if ($tempary['vid'] > 1 && $tempary['cid'] == 1) $this->checkvolume = true;
                        }
                    }
                    if (empty($chaptervipary)) {
                        $fromrows[$key]['vipstart'] = 0;
                        if ($vipstart) {
                            $fromrows[$key]['isvip'] = 1;
                        } else {
                            if ($this->vipstart && $fromrows[$key]['id'] == $this->vipstart) {
                                $fromrows[$key]['isvip'] = 1;
                                $fromrows[$key]['vipstart'] = 1;
                                $vipstart = true;
                            } elseif ($this->vipstartorder && $chapter_num >= $this->vipstartorder) {
                                $fromrows[$key]['isvip'] = 1;
                                $fromrows[$key]['vipstart'] = 1;
                                $vipstart = true;
                            } else {
                                $fromrows[$key]['isvip'] = 0;
                            }
                        }
                    }
                }

                //echo $this->vipstart;exit;
                //print_r($fromrows);exit;
                return $fromrows;
            }//章节成功解析
        }//如果成功取到章节列表页内容
    }

    //分析本地章节列表
    function getMyChapters($toid)
    {
        $this->db->init('chapter', 'chapterid', 'article');
        $this->db->setCriteria(new Criteria('articleid', $toid, '='));
        //$this->db->criteria->add(new Criteria('chaptername', '上架感言', '<>'));   //跳过上架感言避免造成不匹配
        $this->db->criteria->setSort('chapterorder');
        $this->db->criteria->setOrder('ASC');
        $this->db->queryObjects();
        $torows = array();
        $i = 0;
        $volumename = '';
        $tmpchapter = $tmpchapterid = array();
        while ($row = $this->db->getRow()) {
            $tmptitle = preg_replace("/\(([\d]*):([\d]*)\)/", "", trim(jieqi_textstr($row['chaptername'])));//增加过滤章节后面的时间
            if (in_array($tmptitle, $tmpchapter) && $this->jieqiCollect['cleansiterepeatchapter'] > 0) {
                $tmpchapterid[] = $row['chapterid'];
                continue;
            }
            $torows[$i]['title'] = $tmptitle;//str_replace($this->article['articlename'], '', $tmptitle);
            $torows[$i]['type'] = $row['chaptertype'];
            if ($row['chaptertype'] == 0) {
                $torows[$i]['vname'] = $volumename;
            } else {
                $torows[$i]['vname'] = $torows[$i]['title'];
                $volumename = $torows[$i]['title'];
            }
            $tmpchapter[] = $torows[$i]['title'];
            $i++;
        }
        $this->tmpchapter = $tmpchapter;
        if (count($tmpchapterid) > 0 && $this->jieqiCollect['cleansiterepeatchapter'] > 0) {
            $articlelib = $this->loadArticleClass();
            $articlelib->batchDelChapter($this->article, implode(',', $tmpchapterid), false);
            echo '删除' . count($tmpchapterid) . '条重复章节！<br />';
            ob_flush();
            flush();
        }//print_r($torows);exit;
        return $torows;
    }

    //根据书名获取文章
    function getArticleByName($data)
    {
        if (is_array($data)) {
            if (isset($data['articlename'])) $articlename = $data['articlename'];
            if (isset($data['author'])) $author = $data['author'];
        } else $articlename = $data;
        if (!$articlename) return false;
        $this->db->init('article', 'articleid', 'article');
        if (isset($data['firstflag']) && $data['firstflag'] && $data['oldaid']) {//不使用书名定位
            $this->db->setCriteria(new Criteria ('firstflag', $data['firstflag'], '='));
            $this->db->criteria->add(new Criteria('oldaid', $data['oldaid'], '='));
        } else {
            $this->db->setCriteria(new Criteria ('articlename', $articlename, '='));
            if ($author) $this->db->criteria->add(new Criteria('author', $author, '='));
        }
        $this->db->queryObjects();
        $result = $this->db->getRow();
        return $result;
    }

    //写入文章
    function newArticle($data)
    {
        static $articlelib;
        if (!isset($articlelib)) $articlelib = $this->load('article', 'article');
        $P = array();

        $P['articlename'] = $data['articlename'];
        $P['author'] = $data['author'];

        $auth = $this->getAuth();
        if ($this->checkpower($articleLib->jieqiPower ['article'] ['transarticle'], $this->getUsersStatus(), $this->getUsersGroup(), true)) {
            //允许转载的情况
            if ($data ['author'] == $auth ['username']) {
                $P['authorid'] = $auth ['uid'];
                $P['author'] = $auth ['username'];
            } else {
                // 转载作品
                if ($data['authorflag']) {
                    $authorobj = $users_handler->getByname($data ['author'], 3);
                    if (is_object($authorobj)) $P['authorid'] = $authorobj->getVar('uid');
                    else $P['authorid'] = 0;
                } else {
                    $P['authorid'] = 0;
                }
            }
            if ($data['permission']) {
                $P['permission'] = $data['permission'];
                if ($P['permission'] >= 4) $P['signdate'] = JIEQI_NOW_TIME;
            }
            if (!empty($data['firstflag'])) {
                $P['firstflag'] = trim($data['firstflag']);
            }
        } else {
            $P['authorid'] = 0;
        }


        if ($data['sortid']) $P['sortid'] = $data['sortid'];
        elseif ($data['sort']) $P['sortid'] = $this->getSortidBySort($data['sort']);
        else $P['sortid'] = 0;

        if ($P['sortid'] < 100) $P['siteid'] = 0;
        elseif ($P['sortid'] < 200) $P['siteid'] = 100;
        else $P['siteid'] = 200;

        if ($data['sortid']) {
            if ($data['typeid']) $P['typeid'] = $data['typeid'];
            elseif ($data['type']) $P['typeid'] = $this->getTypeidByType($data['type']);
            else $P['typeid'] = 0;
        }

        $P['keywords'] = $data['keywords'];
        $P['intro'] = $data['intro'];
        if ($data['articleimage'] && eregi("\.(gif|jpg|jpeg|png|bmp)$", $data['articleimage'])) {
            $data['articleimage'] = jieqi_gb2utf8($data['articleimage']);
            $P['articlelpic']['name'] = basename($data['articleimage']);
            $P['articlelpic']['tmp_name'] = $data['articleimage'];
            $P['articlespic']['name'] = basename($data['articleimage']);
            $P['articlespic']['tmp_name'] = $data['articleimage'];
        }
        if ($data['fullflag']) $P['fullflag'] = $data['fullflag'];
        else $P['fullflag'] = 0;
        $P['display'] = $data['display'];
        if (isset($data['oldaid'])) $P['oldaid'] = $data['oldaid'];
        //if(eregi("\.(gif|jpg|jpeg|png|bmp)$",$data['articleimage'])) echo 'dddd';
        //print_r($P);exit;
        return $articlelib->newArticle($P);
    }

    //根据分类名获得本站对应的分类ID
    function getSortidBySort($sort)
    {
        if (!empty($sort) && isset($this->jieqiCollect['sortid'][$sort])) $sortid = $this->jieqiCollect['sortid'][$sort];
        elseif (isset($this->jieqiCollect['sortid']['default'])) $sortid = $this->jieqiCollect['sortid']['default'];
        else $sortid = 0;
        return $sortid;
    }

    //根据二级分类名获得本站对应的分类ID
    function getTypeidByType($sort, $type)
    {
        $typeid = 0;
        if (in_array(strtoupper(JIEQI_MODULE_VTYPE), array('CUSTOM'))) {
            //if(!isset($Version160)) exit('PHP:SYSTEM CODE ERROR!');
            if (!empty($type) && isset($this->jieqiCollect['typeid'][$sort][$type]) && is_numeric($this->jieqiCollect['typeid'][$sort][$type])) {
                if (substr_count($this->jieqiCollect['typeid'][$sort][$type], '|')) {
                    $tmparr = explode('|', $this->jieqiCollect['typeid'][$sort][$type]);
                    $sortid = $tmparr[0];
                    $typeid = $tmparr[1];
                } else $typeid = $this->jieqiCollect['typeid'][$sort][$type];
            } elseif (isset($this->jieqiCollect['typeid'][$sort]['default'])) {
                $typeid = $this->jieqiCollect['typeid'][$sort]['default'];
            } else {
                $typeid = 0;
            }
        }
        return $typeid;
    }

    //获取内容
    function getPage($url, $tag)
    {
        if (strpos($url, '||') !== false) {//多个模板地址的时候进行尝试
            $tmpurl = explode('||', $url);
            foreach ($tmpurl as $k => $v) {
                if ($content = jieqi_urlcontents($v, $this->colary)) {
                    $url = $v;
                    break;
                } else {
                    $url = $v;
                }
            }
        } else {
            $content = jieqi_urlcontents($url, $this->colary);
        }
        if (!empty($this->colary['referer']) && $content) $this->colary['referer'] = $url;
        $this->pageary[$tag . 'data'] = $content;
        $this->pageary[$tag . 'url'] = $url;
        return $this->pageary[$tag . 'data'];
    }

    //输出消息
    function outMsg($msg, $error_continue = false)
    {
        if ($this->error_continue == true || $error_continue) {
            $sapi = php_sapi_name();
            if ($sapi == 'cgi-fcgi') {
                echo str_pad($msg, 1024 * 64);
            } else {
                if ($this->first_out) {
                    echo str_repeat(' ', 4096);
                    $this->first_out = false;
                }
                echo $msg;
            }
            ob_flush();
            flush();
        } else {
            jieqi_printfail($msg);
        }
    }

    //加载文章类
    function loadArticleClass()
    {
        if (!$this->articlelib) $this->articlelib = $this->load('article', 'article');
        return $this->articlelib;
    }

    //数组排序
    function multisort(&$array, $key_name, $sort_order = 'SORT_ASC', $sort_type = 'SORT_REGULAR')
    {
        if (!is_array($array)) {
            return $array;
        }
        // Get args number.
        $arg_count = func_num_args();
        // Get keys to sort by and put them to SortRule array.
        for ($i = 1; $i < $arg_count; $i++) {
            $arg = func_get_arg($i);
            if (!preg_match('/SORT/', $arg)) {
                $key_name_list[] = $arg;
                $sort_rule[] = '$' . $arg;
            } else {
                $sort_rule[] = $arg;
            }
        }
        // Get the values according to the keys and put them to array.
        foreach ($array as $key => $info) {
            foreach ($key_name_list as $key_name) {
                ${$key_name}[$key] = $info[$key_name];
            }
        }
        // Create the eval string and eval it.
        $eval_str = 'array_multisort(' . implode(',', $sort_rule) . ', $array);';
        eval($eval_str);
        return $array;
    }
}

class MyCollect extends MyCollect_action
{
    var $retchapinfo = array(); //返回对应不上的章节

    //处理采集一篇文章的完整流程
    function updateone($aid, $notaddnew = 0)
    {
        $this->retflag = 0;
        $this->checkvolume = false;
        $this->error_continue = true;
        $this->retchapinfo = array(); //返回对应不上的章节
        $this->article = array();
        if ($article = $this->getBook($aid)) {
            //检查文章是否存在，没有就新建文章
            $errtext = '';
            //检查标题
            if (strlen($article['articlename']) == 0) $errtext .= $this->jieqiLang['article']['collect_title_empty'] . '<br />';
            elseif (!jieqi_safestring($article['articlename'])) $errtext .= $this->jieqiLang['article']['collect_title_formaterr'] . '<br />';
            if (!empty($errtext)) {
                //echo $errtext;
                //ob_flush();
                //flush();
                $this->outMsg($errtext);
            } else {
                $toid = 0;
                //$author = $this->jieqiCollect['cleansiterepeatarticle'] ? $article['author'] : '';
                $temparticle = $article;
                if (!$this->jieqiCollect['cleansiterepeatarticle']) {
                    unset($temparticle['author']);
                }
                if ($this->article = $this->getArticleByName($temparticle)) {
                    /*if($this->article['display'] != 0){
						$this->outMsg(sprintf($jieqiLang['article']['collect_article_notaudit'], $article['articlename']));
					}elseif($this->article['fullflag']){
					    $this->outMsg('《'.$article['articlename'].'》已经完本，跳转采集！');
					}else{*/
                    $toid = $this->article['articleid'];
                    if ($article['fullflag'] && !$this->article['fullflag']) {
                        $this->db->updatetable('article_article', array('fullflag' => 1), 'articleid = ' . $toid);
                    }
                    //}
                } else {//没有书，添加
                    if ($notaddnew) {
                        /*echo sprintf($this->jieqiLang['article']['collect_article_notexists'], $article['articlename']);
						ob_flush();
						flush();*/
                        $this->outMsg(sprintf($jieqiLang['article']['collect_article_notaudit'], $article['articlename']));
                    } else {
                        if (!$this->article = $this->newArticle($article)) {
                            /*echo sprintf($this->jieqiLang['article']['collect_article_notexists'], $article['articlename']);
							ob_flush();
							flush();*/
                            $this->outMsg(sprintf($jieqiLang['article']['collect_article_notexists'], $article['articlename']));
                        } else {
                            $toid = $this->article['articleid'];
                        }
                    }
                }
                if (!empty($toid)) {
                    $fromid = $aid;
                    $this->colectArticle($fromid, $toid);
                }
            }
        } else {
            /*echo sprintf($this->jieqiLang['article']['parse_articleinfo_failure'], $this->pageary['infourl'], $this->pageary['infourl']);
			ob_flush();
			flush();*/
            $this->outMsg(sprintf($this->jieqiLang['article']['parse_articleinfo_failure'], $this->pageary['infourl'], $this->pageary['infourl']));
        }
        echo '<hr />';
    }

    //采集文章
    function colectArticle($fromid, $toid)
    {
        global $jieqi_file_postfix;
        $this->addConfig('article', 'configs');
        $jieqiConfigs['article'] = $this->getConfig('article', 'configs');
        //$this->retflag=1;
        //return $this->retflag;
        //echo str_repeat(' ',1024*4);
        $this->retflag = 0;
        $this->checkvolume = false;
        $this->error_continue = true;
        $this->retchapinfo = array(); //返回对应不上的章节
        $retlogs = array(); //返回日志
        $jieqi_collect_time = time(); //采集时间
        $auth = $this->getAuth();
        $a = array();
        if (!$fromrows = $this->getChapters($fromid)) {
            $fromrows = array();
            //没有需要更新的
            $this->retflag = 2;
            return $this->retflag;
        }
        //检查文章
        if (!$this->article) {
            $this->db->init('article', 'articleid', 'article');
            if (!$this->article = $this->db->get($toid)) jieqi_printfail($this->jieqiLang['article']['article_not_exists']);
        }
        $torows = $this->getMyChapters($toid);
        //比较更新内容 $fp=frompoint， $tp=topoint
        $fromnum = count($fromrows);

        $tonum = count($torows);
        $maxchapterorder = $tonum;
        if ($tonum == 0) {
            $fp = 0;  //重新开始采集
            $tp = 0;
        } elseif ($tonum - 20 > $fromnum || $tonum * 0.7 > $fromnum) { //echo $torows[$tonum-1]['title'];exit($tonum.'='.$fromnum.'m');
            $fp = $tonum;  //不采集
            $tp = $tonum;
        } else {//开始分析章节
            //检查是否有新章节（可能是中间插入章节的情况）
            $fp = 0;
            $tp = 0;
            //从开头章节开始判断，章节是否完全对应(错一个章节，如果后面章节对应，仍旧可以算对应)
            while ($fp < $fromnum && $tp < $tonum) {
                if ((jieqi_equichapter($fromrows[$fp]['title'], $torows[$tp]['title']) && $fromrows[$fp]['type'] == $torows[$tp]['type'])) {
                    $fp++;
                    $tp++;
                } elseif ($fp < $fromnum - 1 && $tp < $tonum - 1 && jieqi_equichapter($fromrows[$fp + 1]['title'], $torows[$tp + 1]['title']) && $fromrows[$fp + 1]['type'] == $torows[$tp + 1]['type']) {
                    $this->retchapinfo[] = array('fchapter' => ($fromrows[$fp]['type'] == 0) ? $fromrows[$fp]['vname'] . ' ' . $fromrows[$fp]['title'] : $fromrows[$fp]['vname'], 'tchapter' => ($torows[$tp]['type'] == 0) ? $torows[$tp]['vname'] . ' ' . $torows[$tp]['title'] : $torows[$tp]['vname']);
                    $fp += 2;
                    $tp += 2;
                } else {
                    $this->retchapinfo[] = array('fchapter' => ($fromrows[$fp]['type'] == 0) ? $fromrows[$fp]['vname'] . ' ' . $fromrows[$fp]['title'] : $fromrows[$fp]['vname'], 'tchapter' => ($torows[$tp]['type'] == 0) ? $torows[$tp]['vname'] . ' ' . $torows[$tp]['title'] : $torows[$tp]['vname']);
                    break;
                }
            }
            if ($tp < $tonum) {//中间插入章节的情况，检查后面的章节是不是可以完全同步
                $j = $tp;
                $k = $tp;
                //检查后面的章节能不能对应
                while ($j < $tonum) {
                    while ($k < $fromnum) {
                        //本章节不对应
                        if (!jieqi_equichapter($fromrows[$k]['title'], $torows[$j]['title']) || $fromrows[$k]['type'] != $torows[$j]['type']) {
                            //下章节对应也允许通过
                            if ($k < $fromnum - 1 && $j < $tonum - 1 && jieqi_equichapter($fromrows[$k + 1]['title'], $torows[$j + 1]['title']) && $fromrows[$k + 1]['type'] == $torows[$j + 1]['type']) {
                                $k++;
                                $j++;
                                break;
                            } else {
                                $k++;
                            }
                        } else {
                            break;
                        }
                    }
                    if ($k < $fromnum) $j++;
                    else break;
                }
                //后面章节不能完全对应，就看本站最后章节后面是否需要更新(倒过来检查)
                if ($k >= $fromnum) {
                    $j = $tp;
                    $mn = $fromnum - $j;
                    $j = $fromnum;
                    $m = 1;
                    while ($m <= $mn) {
                        if (jieqi_equichapter($fromrows[$fromnum - $m]['title'], $torows[$tonum - 1]['title']) && $fromrows[$fromnum - $m]['type'] == $torows[$tonum - 1]['type'] && ($this->checkvolume == false || $fromrows[$fromnum - $m]['vname'] == $torows[$tonum - 1]['vname'])) {
                            $j = $fromnum - $m;
                            //echo '倒过来检查对应<br>';
                            $this->outMsg('倒过来检查对应<br>', true);
                            break;
                        }
                        $m++;
                    }
                    //本站最后章节能对上
                    if ($j < $fromnum) {
                        $fp = $j + 1;
                        $tp = $tonum;
                    } else {
                        //最后一章对应某一章就接下去采
                        $lastchapteristrue = $this->jieqiCollect['autochaptercollect'];
                        $nextcollectarticle = false;
                        if ($lastchapteristrue) {
                            foreach ($fromrows as $kk => $vv) {
                                if (jieqi_equichapter(preg_replace(array("/\((.*?){3}\)/", "/\（(.*?){3}\）/", "/\((.*?){3}\）/", "/\（(.*?){3}\)/", "/\【(.*?){3}\】/", "/\《(.*?){3}\》/"), '', $fromrows[$kk]['title']), preg_replace(array("/\((.*?){3}\)/", "/\（(.*?){3}\）/", "/\((.*?){3}\）/", "/\（(.*?){3}\)/", "/\【(.*?){3}\】/", "/\《(.*?){3}\》/"), '', $torows[$tonum - 1]['title'])) && $torows[$tonum - 1]['type'] < 1) {
                                    /*echo '最后章节对应:'.$torows[$tonum-1]['title'].'<br>';//exit;
									   ob_flush();
									   flush();*/
                                    $this->outMsg('最后章节对应:' . $torows[$tonum - 1]['title'] . '<br>', true);
                                    $fp = $kk + 1;
                                    $tp = $tonum;
                                    $nextcollectarticle = true;
                                    break;
                                } elseif (isset($torows[$tonum - 2]['title'])) { //倒数第二章对应
                                    if (jieqi_equichapter(preg_replace(array("/\((.*?){3}\)/", "/\（(.*?){3}\）/", "/\((.*?){3}\）/", "/\（(.*?){3}\)/", "/\【(.*?){3}\】/", "/\《(.*?){3}\》/"), '', $fromrows[$kk]['title']), preg_replace(array("/\((.*?){3}\)/", "/\（(.*?){3}\）/", "/\((.*?){3}\）/", "/\（(.*?){3}\)/", "/\【(.*?){3}\】/", "/\《(.*?){3}\》/"), '', $torows[$tonum - 2]['title'])) && $torows[$tonum - 2]['type'] < 1) {
                                        if (isset($fromrows[$kk + 2]['title'])) {
                                            $fp = $kk + 2;
                                            $tp = $tonum;
                                            /*echo '倒数第二章对应:'.$torows[$tonum-2]['title'].'<br>';//exit;
												 echo '从:'.$fromrows[$kk+2]['title'].' 开始采集<br>';
												 ob_flush();
												 flush();*/
                                            $this->outMsg('倒数第二章对应:' . $torows[$tonum - 2]['title'] . '<br>从:' . $fromrows[$kk + 2]['title'] . ' 开始采集<br>', true);
                                            $nextcollectarticle = true;
                                            break;
                                        }
                                    }
                                }
                                if (!$nextcollectarticle && isset($torows[$tonum - 3]['title'])) { //倒数第二章对应
                                    if (jieqi_equichapter(preg_replace(array("/\((.*?){3}\)/", "/\（(.*?){3}\）/", "/\((.*?){3}\）/", "/\（(.*?){3}\)/", "/\【(.*?){3}\】/", "/\《(.*?){3}\》/"), '', $fromrows[$kk]['title']), preg_replace(array("/\((.*?){3}\)/", "/\（(.*?){3}\）/", "/\((.*?){3}\）/", "/\（(.*?){3}\)/", "/\【(.*?){3}\】/", "/\《(.*?){3}\》/"), '', $torows[$tonum - 3]['title'])) && $torows[$tonum - 3]['type'] < 1) {
                                        if (isset($fromrows[$kk + 3]['title'])) {
                                            $fp = $kk + 3;
                                            $tp = $tonum;
                                            /*echo '倒数第三章对应:'.$torows[$tonum-3]['title'].'<br>';//exit;
												 echo '从:'.$fromrows[$kk+3]['title'].' 开始采集<br>';
												 ob_flush();
												 flush();*/
                                            $this->outMsg('倒数第三章对应:' . $torows[$tonum - 3]['title'] . '<br>从:' . $fromrows[$kk + 3]['title'] . ' 开始采集<br>', true);
                                            $nextcollectarticle = true;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                        if (!$nextcollectarticle) {//最后一章对应某一章就接下去采
                            //无法对应，允许的话清空重新采集
                            //后面的章节不能完全对应上，如果上次是采集同一个网站的文章，并且允许清空，则清空后重新采集，否则就不采集
                            //允许清空，并且不是标记完成的书
                            if ($this->jieqiCollect['autoclear'] == 1 && !$this->article['fullflag']) {
                                //echo sprintf($this->jieqiLang['article']['article_collect_clean'], jieqi_htmlstr($this->article['articlename']));
                                $this->outMsg(sprintf($this->jieqiLang['article']['article_collect_clean'], jieqi_htmlstr($this->article['articlename'])), true);
                                //清除文章统计
                                $oldchapters = $this->article['chapters'];
                                $this->db->init('article', 'articleid', 'article');
                                $this->article['lastchapter'] = '';
                                $this->article['lastchapterid'] = 0;
                                $this->article['lastvolume'] = '';
                                $this->article['lastvolumeid'] = 0;
                                $this->article['lastupdate'] = 0;
                                $this->article['chapters'] = 0;
                                $this->article['size'] = 0;
                                $articlelib = $this->loadArticleClass();
                                $articlelib->articleClean($this->article, false);
                                $fp = 0;
                                $tp = 0;
                                $torows = array();
                                $tonum = 0;
                                $maxchapterorder = 0;
                            } else {
                                $fp = $fromnum;
                                $tp = $fromnum;
                                if ($this->error_continue) {
                                    $errchapter = '';
                                    foreach ($this->retchapinfo as $v) {
                                        $errchapter .= $v['fchapter'] . ' => ' . $v['tchapter'] . '<br />';
                                    }
                                    ////////新增，检查上一次采集来源站
                                    $jieqiCollect = false;
                                    $setting = unserialize($this->article['setting']);
                                    if (!is_array($setting)) $setting = array();
                                    if (isset($setting['fromsite'])) {
                                        $fromsite = $this->jieqiCollectsite[$setting['fromsite']]['name'];
                                        if ($setting['fromsite'] != $this->siteid) {
                                            $jieqiCollect = $this->loadConfig($setting['fromsite']);
                                        }
                                    }
                                    if ($jieqiCollect) {//如果上一次采集源站存在
                                        $collectsiteurl = str_replace("<{articleid}>", $setting['fromarticle'], $jieqiCollect['urlindex']);
                                        if (!empty($jieqiCollect['subarticleid'])) {
                                            $subarticleid = 0;
                                            $articleid = $setting['fromarticle'];
                                            $tmpstr = "\$subarticleid = " . $jieqiCollect['subarticleid'] . ";";
                                            eval($tmpstr);
                                            $collectsiteurl = str_replace("<{subarticleid}>", $subarticleid, $collectsiteurl);
                                        }
                                        $old_url = $this->getadminurl('collect', 'action=updatecollect&siteid=' . $setting['fromsite'] . "&fromid=" . $setting['fromarticle'] . "&toid=" . $toid . "&jieqi_username=" . $auth['useruname'] . "&jieqi_userpassword=!!@@phpers&formhash=" . form_hash());
                                        $fromsite = "<a href=" . $collectsiteurl . " target=_blank>{$fromsite}</a> <a href=" . $old_url . " target=_blank>采" . ($this->article['fullflag'] ? '(已完本)' : '(连载中)') . "</a>";
                                        $articleinindexurl = jieqi_htmlstr($this->article['articlename']);
                                        $articleinindexurl = "<a href=" . $url . " target=_blank>{$articleinindexurl}</a>";
                                        $msg = sprintf($this->jieqiLang['article']['collect_cant_update'], $articleinindexurl, date("Y/m/d H:i:s", $this->article['lastupdate']), $fromsite, $errchapter, $this->geturl('article', 'chapter', "SYS=aid=$toid&method=cmView"), $this->getadminurl('collect'), $this->geturl('article', 'article', "SYS=aid=$toid&method=articleClean&jumpurl=" . urlencode($this->getadminurl('collect', 'action=updatecollect&siteid=' . $this->siteid . "&fromid=" . $fromid . "&toid=" . $toid . '&formhash=' . form_hash()))));
                                        $this->outMsg($msg, true);
                                        if ($this->siteid != $setting['fromsite'] && $this->article['fullflag']) {
                                            //echo '<br>自动从原站采集<br>';
                                            $this->outMsg('<br>自动从原站采集<br>', true);
                                            jieqi_socket_url($old_url);
                                        }

                                    } else {
                                        $msg = sprintf($this->jieqiLang['article']['collect_cant_update_noformsite'], $errchapter, $this->geturl('article', 'chapter', "SYS=aid=$toid&method=cmView"), $this->geturl('article', 'article', "SYS=aid=$toid&method=articleClean&jumpurl=" . urlencode($this->getadminurl('collect', 'action=updatecollect&siteid=' . $this->siteid . "&fromid=" . $fromid . "&toid=" . $toid . '&formhash=' . form_hash()))), $this->getadminurl('collect'));
                                        $this->outMsg($msg, true);
                                    }
                                }
                                $this->retflag = 4; //有需要采集的，但是没法对应上
                            }
                        }//最后一章对应某一章就接下去采
                    } //连本站最后一章也对应不上
                } //后面章节不能完全对应，就看本站最后章节后面是否需要更新
            }//中间插入章节的情况，检查后面的章节是不是可以完全同步
        }//开始分析章节结束
        //echo dirname($this->pageary['chaptersurl']);exit;
        //准备更新章节
        if ($fp < $fromnum && $tp <= $tonum) {
            //需要从目录页面解析章节页面链接
            $chapterlink = '';
            if (strpos($this->jieqiCollect['urlchapter'], '<{chapterlink}>') !== false && !empty($this->jieqiCollect['chapterlink'])) {
                //章节链接
                $pregstr = jieqi_collectstoe($this->jieqiCollect['chapterlink']);
                if (!empty($pregstr)) {
                    $matchvar = jieqi_cmatchone($pregstr, $source);
                    if (!empty($matchvar)) $chapterlink = trim(jieqi_textstr($matchvar));
                }
            }
            //用户设置的替换
            if (!empty($this->jieqiCollect['contentfilter'])) {
                $filterary2 = explode("\n", $this->jieqiCollect['contentfilter']);
                $repfrom = array();
                foreach ($filterary2 as $filterstr) {
                    $filterstr = trim($filterstr);
                    if (!empty($filterstr)) {
                        if (preg_match('/^\/[^\/\\\\]*(?:\\\\.[^\/\\\\]*)*\/[imsu]*$/is', $filterstr)) $repfrom[] = $filterstr;
                        else $repfrom[] = '/' . jieqi_pregconvert($filterstr) . '/is';
                    }
                }
                $repto = '';
                if (!empty($this->jieqiCollect['contentreplace'])) {
                    $repto = explode("\n", str_replace("\r\n", "\n", $this->jieqiCollect['contentreplace']));
                }
            }
            //系统设置的隐藏关键词
            $this->addConfig('article', 'configs');
            $jieqiConfigs['article'] = $this->getConfig('article', 'configs');
            if (!empty($jieqiConfigs['article']['hidearticlewords'])) {
                $articlewordssplit = (strlen($jieqiConfigs['article']['articlewordssplit']) == 0) ? ' ' : $jieqiConfigs['article']['articlewordssplit'];
                $filterary = explode($articlewordssplit, $jieqiConfigs['article']['hidearticlewords']);
                $filter = true;
            } else {
                $filter = false;
            }

            include_once(JIEQI_ROOT_PATH . '/lib/text/texttypeset.php');
            $texttypeset = new TextTypeset();
            //增加章节

            $size = $this->article['size'];
            $lastchapter = $this->article['lastchapter'];
            $lastchapterid = $this->article['lastchapterid'];
            $lastvolume = $this->article['lastvolume'];
            $lastvolumeid = $this->article['lastvolumeid'];
            $lastchapterorder = $tp + 1;

            echo '                                                                                                                                                                                                                                                                ';
            if ($tp == $tonum) $tmpvar = $fromnum - $fp;
            else $tmpvar = $fromnum - $tonum;

            if ($tmpvar < 1) {
                /*echo sprintf('分析章节时出现错误，继续采集可能会出现章节重复！终止采集！');
			   ob_flush();
			   flush();*/
                $this->outMsg('分析章节时出现错误，继续采集可能会出现章节重复！终止采集！', true);
                $fp = $fromnum;
            } else {
                $msg = sprintf($this->jieqiLang['article']['collect_chapter_doing'], jieqi_htmlstr($this->article['articlename']), $tmpvar . '(待更新：' . ($fromnum - $fp) . ')');
                $this->outMsg($msg, true);
            }
            $c = 1;
            $k = $fp;
            $q = $tp;
            //定义开始更新的章节索引
            $startupdateOrder = $q;

            //$k用于$fromrows， $q用于$torows
            while ($k < $fromnum) {
                if (in_array($fromrows[$k]['title'], $this->tmpchapter)) {
                    $k++;
                    $q++;
                    continue;
                }
                //如果章节对应上就不用采集了
                if ($q < $tonum && jieqi_equichapter($fromrows[$k]['title'], $torows[$q]['title']) && $fromrows[$k]['type'] == $torows[$q]['type']) {
                    $k++;
                    $q++;
                    continue;
                } elseif ($k < $fromnum - 1 && $q < $tonum - 1 && jieqi_equichapter($fromrows[$k + 1]['title'], $torows[$q + 1]['title']) && $fromrows[$k + 1]['type'] == $torows[$q + 1]['type']) {
                    $k += 2;
                    $q += 2;
                    continue;
                }
                //取章节内容
                if ($fromrows[$k]['type'] == 0) {
                    if (!empty($indexlink)) $tmpstr = str_replace('<{indexlink}>', $indexlink, $this->jieqiCollect['urlchapter']);
                    else $tmpstr = $this->jieqiCollect['urlchapter'];
                    if (!empty($chapterlink)) $tmpstr = str_replace('<{chapterlink}>', $chapterlink, $tmpstr);
                    $url = str_replace('<{articleid}>', $fromid, $tmpstr);
                    $url = str_replace('<{chapterid}>', $fromrows[$k]['id'], $url);
                    if (!empty($this->jieqiCollect['subarticleid'])) {
                        $subarticleid = 0;
                        $articleid = $fromid;
                        $chapterid = $fromrows[$k]['id'];
                        $tmpstr = '$subarticleid = ' . $this->jieqiCollect['subarticleid'] . ';';
                        eval($tmpstr);
                        $url = str_replace('<{subarticleid}>', $subarticleid, $url);
                    }
                    if (!empty($this->jieqiCollect['subchapterid'])) {
                        $subchapterid = 0;
                        $articleid = $fromid;
                        $chapterid = $fromrows[$k]['id'];
                        $tmpstr = '$subchapterid = ' . $this->jieqiCollect['subchapterid'] . ';';
                        eval($tmpstr);

                        $url = str_replace('<{subchapterid}>', $subchapterid, $url);
                    }
                    if (strpos($this->jieqiCollect['urlchapter'], '<{auto}>') !== false) {
                        $url = str_replace('<{auto}>', '', $url);
                        if (substr($url, 0, 7) != 'http://') {
                            if (substr($url, 0, 1) == '/') {
                                $url = $this->jieqiCollect['siteurl'] . $url;
                            } else {
                                if (substr($this->pageary['chaptersurl'], strlen($this->pageary['chaptersurl']) - 1, 1) != '/') {
                                    $url = dirname($this->pageary['chaptersurl']) . '/' . $url;
                                } else $url = $this->pageary['chaptersurl'] . $url;
                            }
                        }
                    }

                    $chaptercontent = $this->getPage($url, 'chapter');
                    //章节发布时间
                    $pregstr = jieqi_collectstoe($this->jieqiCollect['postdate']);
                    if (!empty($pregstr)) {
                        $matchvar = jieqi_cmatchone($pregstr, $chaptercontent);
                        if (!empty($matchvar)) $jieqi_collect_time = strtotime(trim(jieqi_textstr($matchvar)));
                    }
                    //echo $jieqi_collect_time;exit();

                    $tempcontentjieqiCollect = $this->jieqiCollect['content'];
                    if (substr_count($url, '.txt')) {
                        $tempcontentjieqiCollect['left'] = '';
                        $tempcontentjieqiCollect['right'] = '';
                    }
                    //分离章节内容
                    $pregstr = jieqi_collectstoe($tempcontentjieqiCollect);
                    $chaptercontent1 = $chaptercontent;
                    $chaptercontent = jieqi_cmatchone($pregstr, $chaptercontent);
                    if (!$chaptercontent) {
                        $len1 = strlen($tempcontentjieqiCollect['left']);
                        $pos1 = strpos($chaptercontent1,$tempcontentjieqiCollect['left']);
                        $pos2 = strpos($chaptercontent1,$tempcontentjieqiCollect['right']);
                        $tmp1 = substr($chaptercontent1,$pos1+$len1,$pos2-$pos1-$len1);
                        $tmp1 = str_replace("<p>","",$tmp1);
                        $tmp1 = str_replace("</p>","\n",$tmp1);
                        $chaptercontent = $tmp1;
                    }

                    //如果是文本地址或者js地址，则采集内容
                    if (strlen($chaptercontent) > 3 && strlen($chaptercontent) < 200) {
                        $urlcontent = trim($chaptercontent);
                        //看看是不是 script src="" 的代码
                        $matches = array();
                        preg_match('/\<script[^\<\>]*src=(\'|")([^\<\>\'"]*)(\'|")[^\<\>]*\>/is', $urlcontent, $matches);
                        if (!empty($matches[2])) $urlcontent = $matches[2];
                        $tmpstr = strtolower(strrchr($urlcontent, '.'));
                        if ($tmpstr == '.txt' || $tmpstr == '.js') {
                            //内容如果相对地址，改成绝对的
                            if (strpos($urlcontent, 'http') !== 0) {
                                if (substr($urlcontent, 0, 1) == '/') {
                                    $urlmatches = array();
                                    preg_match('/https?:\/\/[^\/]+/is', $url, $urlmatches);
                                    if (!empty($urlmatches[0])) $urlcontent = $urlmatches[0] . $urlcontent;
                                    else $urlcontent = $this->jieqiCollect['siteurl'] . $urlcontent;
                                } else {
                                    $tmpdir = dirname($url);
                                    while (strpos($urlcontent, '../') === 0) {
                                        $tmpdir = dirname($tmpdir);
                                        $urlcontent = substr($urlcontent, 3);
                                    }
                                    $urlcontent = $tmpdir . '/' . $urlcontent;
                                }
                            }
                            $newcontent = jieqi_urlcontents($urlcontent, $this->colary);
                            if (!empty($newcontent)) {
                                $matches = array();
                                preg_match('/document.write\((\'|")(.*)(\'|")\);/is', $newcontent, $matches);
                                if (!empty($matches[2])) $chaptercontent = $matches[2];
                            }
                        }
                    }
                    $attachnum = 0;
                    if (strlen(trim($chaptercontent)) > 0) {//////处理内容中的图片
                        $imagecontentary = array();
                        $infoary = array();
                        //$attachnum=0;
                        $attachinfo = '';
                        //检查是否有图片地址，有的话采集成自己的附件
                        if ($this->jieqiCollect['collectimage'] == 1) {
                            $matches = array();
                            preg_match_all('/\<img[^\<\>]+src=[\'"]?((https?:\/\/|www\.)?[a-z0-9\/\-_+=.~!%@?#%&;:$\\│]+(\.gif|\.jpg|\.jpeg|\.png|\.bmp))[^\<\>]*\>/is', $chaptercontent, $matches);
                            //有图片
                            if (!empty($matches[1])) {
                                $imageurls = array();
                                //采集图片
                                foreach ($matches[1] as $s => $v) {
                                    $imageurls[] = $v;
                                    $imageurl = $v;
                                    //内容如果相对地址，改成绝对的
                                    if (strpos($imageurl, 'http') !== 0) {
                                        if (substr($imageurl, 0, 1) == '/') {
                                            $urlmatches = array();
                                            preg_match('/https?:\/\/[^\/]+/is', $url, $urlmatches);
                                            if (!empty($urlmatches[0])) $imageurl = $urlmatches[0] . $imageurl;
                                            else $imageurl = $this->jieqiCollect['siteurl'] . $imageurl;
                                        } else {
                                            $tmpdir = dirname($url);
                                            while (strpos($imageurl, '../') === 0) {
                                                $tmpdir = dirname($tmpdir);
                                                $imageurl = substr($imageurl, 3);
                                            }
                                            $imageurl = $tmpdir . '/' . $imageurl;
                                        }
                                    }
                                    $img_colary = $this->colary;
                                    $img_colary['charset'] = 'image';
                                    $imagecontentary[$attachnum] = jieqi_urlcontents($imageurl, $img_colary);
                                    if ($s == 0 && empty($imagecontentary[$attachnum])) break;
                                    $infoary[$attachnum] = array('name' => basename($imageurl), 'class' => 'image', 'postfix' => substr(strrchr($imageurl, '.'), 1), 'size' => strlen($imagecontentary[$attachnum]));

                                    //图片入库
                                    $this->db->init('attachs', 'attachid', 'article');
                                    $newAttach = array();
                                    $newAttach ['articleid'] = $toid;
                                    $newAttach ['chapterid'] = 0;
                                    $newAttach ['name'] = $infoary[$attachnum]['name'];
                                    $newAttach ['class'] = $infoary[$attachnum]['class'];
                                    $newAttach ['postfix'] = $infoary[$attachnum]['postfix'];
                                    $newAttach ['size'] = $infoary[$attachnum]['size'];
                                    $newAttach ['hits'] = 0;
                                    $newAttach ['needexp'] = 0;
                                    $newAttach ['uptime'] = $jieqi_collect_time;
                                    $attachid = $this->db->add($newAttach);
                                    if ($attachid) {
                                        $infoary[$attachnum]['attachid'] = $attachid;
                                    } else {
                                        $infoary[$attachnum]['attachid'] = 0;
                                    }
                                    $attachnum++;
                                }
                                if ($attachnum > 0) {
                                    $chaptercontent = str_replace($imageurls, '', $chaptercontent);
                                    $attachinfo = serialize($infoary);
                                    $chaptercontent = strip_tags($chaptercontent);
                                }
                            }
                        } else {
                            $matches = array();
                            preg_match_all('/\<img[^\<\>]+src=[\'"]?((https?:\/\/|www\.)?[a-z0-9\/\-_+=.~!%@?#%&;:$\\│]+(\.gif|\.jpg|\.jpeg|\.png|\.bmp))[^\<\>]*\>/is', $chaptercontent, $matches);
                            //有图片
                            if (!empty($matches[1])) {
                                $imageurls = array();
                                //采集图片
                                foreach ($matches[1] as $s => $v) {
                                    $imageurl = $v;
                                    //内容如果相对地址，改成绝对的
                                    if (strpos($imageurl, 'http') !== 0) {
                                        if (substr($imageurl, 0, 1) == '/') {
                                            $urlmatches = array();
                                            preg_match('/https?:\/\/[^\/]+/is', $url, $urlmatches);
                                            if (!empty($urlmatches[0])) $imageurl = $urlmatches[0] . $imageurl;
                                            else $imageurl = $this->jieqiCollect['siteurl'] . $imageurl;
                                        } else {
                                            $tmpdir = dirname($url);
                                            while (strpos($imageurl, '../') === 0) {
                                                $tmpdir = dirname($tmpdir);
                                                $imageurl = substr($imageurl, 3);
                                            }
                                            $imageurl = $tmpdir . '/' . $imageurl;
                                        }
                                        $chaptercontent = str_replace($v, $imageurl, $chaptercontent);
                                    }
                                }
                            }
                        }////处理内容中的图片
                        if (strlen(trim($chaptercontent)) > 0) {
                            //转换成文本20091224
                            if (!substr_count($url, '.txt')) $chaptercontent = jieqi_textstr($chaptercontent, true);
                            //内容排版
                            $chaptercontent = $texttypeset->doTypeset($chaptercontent);
                            //过滤用户设置的内容
                            if (count($repfrom) > 0) $chaptercontent = preg_replace($repfrom, $repto, $chaptercontent);

                            //隐藏网站设置的内容
                            if ($filter) $chaptercontent = str_replace($filterary, '', $chaptercontent);
                        }
                    }
                } else {
                    $chaptercontent = '';
                }

                if ($fromrows[$k]['type'] == 0 && strlen(trim($chaptercontent)) == 0 && !$attachnum) {
                    $msg = sprintf($this->jieqiLang['article']['chapter_collect_failure'], $c, jieqi_htmlstr($fromrows[$k]['title']), $this->pageary['chapterurl'], $this->pageary['chapterurl']);
                    $this->outMsg($msg, true);
                } else {

                    $this->db->init('chapter', 'chapterid', 'article');
                    //如果是插入章节，则原来章节的序号加一位
                    if ($q < $tonum) {
                        $this->db->updatetable('article_chapter', array('chapterorder' => '++'), 'articleid = ' . $toid . ' AND chapterorder >' . $q);
                    }

                    $this->db->init('chapter', 'chapterid', 'article');
                    $this->db->setCriteria(new Criteria('articleid', $toid));
                    $this->db->criteria->add(new Criteria('isvip', 1));
                    if ($this->db->get()) {
                        $fromrows[$k]['isvip'] = 1;
                    }

                    $chaptersize = jieqi_strlen($chaptercontent);
                    $newChapter = array();
                    $newChapter ['siteid'] = $this->article['siteid'];
                    $newChapter ['articleid'] = $toid;
                    $newChapter ['articlename'] = $this->article['articlename'];
                    $newChapter ['volumeid'] = 0;
                    if (!empty($auth['uid'])) {
                        $newChapter ['posterid'] = 0;//$auth['uid'];
                        $newChapter ['poster'] = $this->article['author'] ? $this->article['author'] : $auth['username'];//$auth['username'];
                    } else {
                        $newChapter ['posterid'] = 0;
                        $newChapter ['poster'] = $this->article['author'] ? $this->article['author'] : $auth['username'];
                    }
                    $newChapter ['postdate'] = $jieqi_collect_time;
                    $newChapter ['lastupdate'] = $jieqi_collect_time;
                    $newChapter ['chaptername'] = $fromrows[$k]['title'];
                    $newChapter ['chapterorder'] = $q + 1;
                    $newChapter ['size'] = $chaptersize;
                    $newChapter ['chaptertype'] = $fromrows[$k]['type'];
                    if (!$fromrows[$k]['isvip']) $newChapter ['saleprice'] = 0;
                    else {
                        if (is_numeric($jieqiConfigs['article']['wordsperegold']) && $jieqiConfigs['article']['wordsperegold'] > 0) {
                            $wordsperegold = ceil($jieqiConfigs['article']['wordsperegold']) * 2;//2倍整数
                            if ($jieqiConfigs['article']['priceround'] == 1) {
                                $newChapter ['saleprice'] = floor($chaptersize / $wordsperegold);//向下舍入，取整。
                            } elseif ($jieqiConfigs['article']['priceround'] == 2) {
                                $newChapter ['saleprice'] = ceil($chaptersize / $wordsperegold);//向上舍入，取整。
                            } else {
                                $newChapter ['saleprice'] = round($chaptersize / $wordsperegold);//四舍五入
                            }
                        }
                    }
                    $newChapter ['attachment'] = $attachinfo;
                    $newChapter ['isvip'] = $fromrows[$k]['isvip'];
                    $newChapter ['display'] = 0;
                    if (!$newid = $this->db->add($newChapter)) $this->outMsg($this->jieqiLang['article']['add_chapter_failure']);
                    else {
                        $txtdir = jieqi_uploadpath($jieqiConfigs['article']['txtdir'], 'article');
                        if (!file_exists($txtdir)) jieqi_createdir($txtdir);
                        $txtdir = $txtdir . jieqi_getsubdir($toid);
                        if (!file_exists($txtdir)) jieqi_createdir($txtdir);
                        $txtdir = $txtdir . '/' . $toid;
                        if (!file_exists($txtdir)) jieqi_createdir($txtdir);
                        if ($fromrows[$k]['type'] == 1) {
                            jieqi_writefile($txtdir . '/' . $newid . $jieqi_file_postfix['txt'], $chaptercontent);
                            $lastvolume = $fromrows[$k]['title'];
                            $lastvolumeid = $newid;
                        } else {
                            jieqi_writefile($txtdir . '/' . $newid . $jieqi_file_postfix['txt'], $chaptercontent);
                            $lastchapter = $fromrows[$k]['title'];
                            $lastchapterid = $newid;
                            $size += $chaptersize;
                        }
                        //处理图片附件
                        if ($attachnum > 0) {// && is_object($attachs_handler)
                            $this->db->query("UPDATE " . $this->dbprefix('article_attachs') . " SET chapterid=" . $newid . " WHERE articleid=" . $toid . " AND chapterid=0");
                            $attachdir = jieqi_uploadpath($jieqiConfigs['article']['attachdir'], 'article');
                            if (!file_exists($attachdir)) jieqi_createdir($attachdir);
                            $attachdir .= jieqi_getsubdir($toid);
                            if (!file_exists($attachdir)) jieqi_createdir($attachdir);
                            $attachdir .= '/' . $toid;
                            if (!file_exists($attachdir)) jieqi_createdir($attachdir);
                            $attachdir .= '/' . $newid;
                            if (!file_exists($attachdir)) jieqi_createdir($attachdir);
                            //是否允许图片处理
                            if ($this->jieqiCollect['imagetranslate'] && function_exists("gd_info") && JIEQI_MODULE_VTYPE != '' && JIEQI_MODULE_VTYPE != 'Free') $canimagetrans = true;
                            else  $canimagetrans = false;
                            //是否加水印
                            $make_image_water = false;

                            if ($this->jieqiCollect['addimagewater'] == 1) {
                                if (strpos($jieqiConfigs['article']['attachwimage'], '/') === false && strpos($jieqiConfigs['article']['attachwimage'], '\\') === false) $water_image_file = $GLOBALS['jieqiModules']['article']['path'] . '/images/' . $jieqiConfigs['article']['attachwimage'];
                                else $water_image_file = $jieqiConfigs['article']['attachwimage'];
                                if (is_file($water_image_file)) {
                                    $make_image_water = true;
                                    include_once(JIEQI_ROOT_PATH . '/lib/image/imagewater.php');
                                }
                            }

                            foreach ($infoary as $s => $v) {
                                $imgattach_save_path = $attachdir . '/' . $infoary[$s]['attachid'] . '.' . $infoary[$s]['postfix'];
                                @jieqi_writefile($imgattach_save_path, $imagecontentary[$s]);

                                $imagetype = '';
                                if (preg_match("/\.(jpg|jpeg|gif|png)$/i", $imgattach_save_path, $itmatches)) $imagetype = strtolower($itmatches[1]);
                                if ($imagetype == 'jpg') $imagetype = 'jpeg';


                                //图片处理
                                if ($canimagetrans && !empty($imagetype)) {
                                    $funname = 'imagecreatefrom' . $imagetype;
                                    $imageres = $funname($imgattach_save_path);
                                    $imagewidth = imagesx($imageres);  //图片宽度
                                    $imageheight = imagesy($imageres);  //图片高度
                                    if (!preg_match("/^#[a-f0-9]{6}$/i", $this->jieqiCollect['imagebgcolor'], $tmpmatches)) {
                                        //自动判断背景色
                                        $tmpary = array();
                                        $tmpvar = imagecolorat($imageres, 1, 1);
                                        $tmpary[$tmpvar] = isset($tmpary[$tmpvar]) ? $tmpary[$tmpvar] + 1 : 1;

                                        $tmpvar = imagecolorat($imageres, 1, $imageheight - 1);
                                        $tmpary[$tmpvar] = isset($tmpary[$tmpvar]) ? $tmpary[$tmpvar] + 1 : 1;

                                        $tmpvar = imagecolorat($imageres, $imagewidth - 1, 1);
                                        $tmpary[$tmpvar] = isset($tmpary[$tmpvar]) ? $tmpary[$tmpvar] + 1 : 1;

                                        $tmpvar = imagecolorat($imageres, $imagewidth - 1, $imageheight - 1);
                                        $tmpary[$tmpvar] = isset($tmpary[$tmpvar]) ? $tmpary[$tmpvar] + 1 : 1;

                                        $tmpvar = imagecolorat($imageres, 1, floor($imageheight / 2));
                                        $tmpary[$tmpvar] = isset($tmpary[$tmpvar]) ? $tmpary[$tmpvar] + 1 : 1;

                                        $tmpvar = imagecolorat($imageres, $imagewidth - 1, floor($imageheight / 2));
                                        $tmpary[$tmpvar] = isset($tmpary[$tmpvar]) ? $tmpary[$tmpvar] + 1 : 1;

                                        arsort($tmpary);
                                        reset($tmpary);
                                        $imagebgcolor = key($tmpary);
                                    } else {
                                        $imagebgcolor = imagecolorclosest($imageres, hexdec(substr($this->jieqiCollect['imagebgcolor'], 1, 2)), hexdec(substr($this->jieqiCollect['imagebgcolor'], 3, 2)), hexdec(substr($this->jieqiCollect['imagebgcolor'], 1, 5)));
                                    }


                                    $filterwater = false;

                                    //去文字水印
                                    if (!empty($this->jieqiCollect['imageareaclean'])) {
                                        $imageareaary = explode('|', $this->jieqiCollect['imageareaclean']);
                                        foreach ($imageareaary as $area) {
                                            $xyary = explode(',', $area);
                                            if (count($xyary) >= 4) {
                                                $x1 = intval(trim($xyary[0]));
                                                if ($x1 < 0) $x1 = $imagewidth + $x1;
                                                $y1 = intval(trim($xyary[1]));
                                                if ($y1 < 0) $y1 = $imageheight + $y1;
                                                $x2 = intval(trim($xyary[2]));
                                                if ($x2 <= 0) $x2 = $imagewidth + $x2;
                                                $y2 = intval(trim($xyary[3]));
                                                if ($y2 <= 0) $y2 = $imageheight + $y2;
                                                imagefilledrectangle($imageres, $x1, $y1, $x2, $y2, $imagebgcolor);
                                                $filterwater = true;
                                            }
                                        }
                                    }

                                    //去图片颜色水印
                                    if (!empty($this->jieqiCollect['imagecolorclean'])) {
                                        $imagecolorary = explode('|', $this->jieqiCollect['imagecolorclean']);
                                        foreach ($imagecolorary as $fcolor) {
                                            $fcolor = trim($fcolor);
                                            if (preg_match("/^#[a-f0-9]{6}$/i", $fcolor, $tmpmatches)) {
                                                $filtercolor = imagecolorexact($imageres, hexdec(substr($fcolor, 1, 2)), hexdec(substr($fcolor, 3, 2)), hexdec(substr($fcolor, 5, 2)));
                                                if ($filtercolor >= 0) {
                                                    $cindexary = imagecolorsforindex($imageres, $imagebgcolor);
                                                    imagecolorset($imageres, $filtercolor, $cindexary['red'], $cindexary['green'], $cindexary['blue']);
                                                    $filterwater = true;
                                                }
                                            }
                                        }
                                    }

                                    //保存去水印后图片
                                    if ($filterwater) {
                                        $funname = 'image' . $imagetype;
                                        $funname($imageres, $imgattach_save_path);
                                    }

                                    //图片加水印
                                    if ($make_image_water && eregi("\.(gif|jpg|jpeg|png)$", $imgattach_save_path)) {
                                        $img = new ImageWater();
                                        $img->save_image_file = $imgattach_save_path;
                                        $img->codepage = JIEQI_SYSTEM_CHARSET;
                                        $img->wm_image_pos = $jieqiConfigs['article']['attachwater'];
                                        $img->wm_image_name = $water_image_file;
                                        $img->wm_image_transition = $jieqiConfigs['article']['attachwtrans'];
                                        $img->jpeg_quality = $jieqiConfigs['article']['attachwquality'];
                                        $img->create($imgattach_save_path);
                                        unset($img);
                                    }
                                }
                                @chmod($imgattach_save_path, 0777);
                            }
                        }
                    }
                    unset($newChapter);
                    //更新文章信息(采集一章更新一下)
                    $this->db->init('article', 'articleid', 'article');
                    $this->article['lastchapter'] = $lastchapter;
                    $this->article['lastchapterid'] = $lastchapterid;
                    $this->article['lastvolume'] = $lastvolume;
                    $this->article['lastvolumeid'] = $lastvolumeid;
                    $this->article['chapters'] = $maxchapterorder + 1;
                    $this->article['size'] = $size;
                    $this->article['lastupdate'] = $jieqi_collect_time;
                    if ($fromrows[$k]['vipstart'] || $fromrows[$k]['isvip']) {
                        if (!$this->article['vipdate']) $this->article['vipdate'] = $jieqi_collect_time;
                        if ($this->article['permission'] < 4) $this->article['permission'] = 4;
                        if (!$this->article['signdate']) $this->article['signdate'] = $jieqi_collect_time;
                        if (!$this->article['lastchaptervip']) $this->article['lastchaptervip'] = 1;
                        if (!$this->article['articletype']) $this->article['articletype'] = 1;
                    }
                    //判断完本
                    if (preg_match("/大结局|完美结局|终章|尾声|（结局）|\(结局\)|完结|（完结）|（完）|\(完\)|（结束）|\(结束\)|\(完结\)|圆满结局|（后记）|\(后记\)|终落幕|（终|\(终）/is", $fromrows[$k]['title'])) {
                        if (!$this->article['fullflag']) $this->article['fullflag'] = 1;
                    }

                    $setting = unserialize($this->article['setting']);
                    if (!is_array($setting)) $setting = array();
                    $setting['fromsite'] = $this->siteid;
                    $setting['fromarticle'] = $fromid;
                    $this->article['setting'] = serialize($setting);
                    $this->db->edit($toid, $this->article);

                    $lastchapterorder = $q + 1;
                    $maxchapterorder++;
                    //本站章节数组加一项
                    for ($n = $tonum; $n > $q; $n--) $torows[$n] = $torows[$n - 1];
                    $torows[$q]['title'] = $fromrows[$k]['title'];
                    $torows[$q]['type'] = $fromrows[$k]['type'];
                    $tonum++;
                    $q++;
                    $msg = $c . '.' . jieqi_htmlstr($fromrows[$k]['title']) . ' ';
                    $this->outMsg($msg, true);
                }
                $k++;
                $c++;
            }
            //全部章节采集完之后
            //根据最新章节重新搜索最新卷
            /*$criteria=new CriteriaCompo(new Criteria('articleid', $_REQUEST['toid']));
			$criteria->add(new Criteria('chapterorder', $lastchapterorder, '<'));
			$criteria->add(new Criteria('chaptertype', 1, '='));
			$criteria->setSort('chapterorder');
			$criteria->setOrder('DESC');
			$criteria->setLimit(1);
			$chapter_handler->queryObjects($criteria, true);
			$tmpchapter=$chapter_handler->getObject();
			if(is_object($tmpchapter)){
				$article->setVar('lastvolume', $tmpchapter->getVar('chaptername', 'n'));
				$article->setVar('lastvolumeid', $tmpchapter->getVar('chapterid', 'n'));
			}else{
				$article->setVar('lastvolume', '');
				$article->setVar('lastvolumeid', 0);
			}
			unset($tmpchapter);
			//更新参数
			$setting=unserialize($article->getVar('setting', 'n'));
			if(!is_array($setting)) $setting=array();
			$setting['fromsite']=$_REQUEST['siteid'];
			$setting['fromarticle']=$_REQUEST['fromid'];
			$article->setVar('setting', serialize($setting));
			$article_handler->insert($article);
            */
            $k = $c - 1;

            $msg = $this->jieqiLang['article']['chapter_collect_success'];
            $this->outMsg($msg, true);

            //生成html和zip和全文阅读
            $articlelib = $this->loadArticleClass();
            $this->addConfig('article', 'url');
            $setreader = $this->getConfig('article', 'url', 'reader_main');
            $setindex = $this->getConfig('article', 'url', 'index_main');

            $msg = $this->jieqiLang['article']['collect_create_readfile'];
            $this->outMsg($msg, true);

            if ((isset($setreader['ishtml']) && $setreader['ishtml']) || (isset($setindex['ishtml']) && $setindex['ishtml'])) {
                $old_makezip = $jieqiConfigs['article']['makezip'];
                $old_maketxtfull = $jieqiConfigs['article']['maketxtfull'];
                $old_makeumd = $jieqiConfigs['article']['makeumd'];
                $old_makejar = $jieqiConfigs['article']['makejar'];
                $old_makefull = $jieqiConfigs['article']['makefull'];

                //定制版、豪华版允许
                if (in_array(strtoupper(JIEQI_MODULE_VTYPE), array('CUSTOM', 'DELUXE'))) {
                    if (!isset($Version160)) exit('PHP:SYSTEM CODE ERROR!');
                    $makeset = @explode(",", $this->jieqiCollect['makeset']);
                    if (!in_array('makehtml', $makeset)) $old_makehtml = 0;
                    if (!in_array('makezip', $makeset)) $old_makezip = 0;
                    if (!in_array('maketxtfull', $makeset)) $old_maketxtfull = 0;
                    if (!in_array('makeumd', $makeset)) $old_makeumd = 0;
                    if (!in_array('makejar', $makeset)) $old_makejar = 0;
                }

                $updatetype = 1;
                $articlelib->article_repack($toid, array('makeopf' => 1, 'makehtml' => $old_makehtml, 'makezip' => $old_makezip, 'makefull' => $old_makefull, 'maketxtfull' => $old_maketxtfull, 'makeumd' => $old_makeumd, 'makejar' => $old_makejar, 'startupdateOrder' => $startupdateOrder), $updatetype);
                $startupdateOrder = 1;
            } else {
                $articlelib = $this->loadArticleClass();
                $articlelib->article_repack($toid, array('makeopf' => 1, 'makehtml' => 0, 'makezip' => 0, 'makefull' => 0, 'maketxtfull' => 0, 'makeumd' => 0, 'makejar' => 0), 1);
            }
            //采集完成
            $this->retflag = 1;
        } else {
            //没有需要更新的
            if ($this->retflag == 0) $this->retflag = 2;
        }
        return $this->retflag;
    }

}

?>