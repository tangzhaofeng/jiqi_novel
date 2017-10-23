<?php 
/** 
 * 文章活动功能模型 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */
class huodongModel extends Model
{

    function vote($params = array())
    {
        global $jieqiHonors;
        $this->addConfig('article', 'configs');
        $this->addConfig('article', 'right');
        $this->addConfig('system', 'vipgrade');
        $this->addLang('article', 'vote');
        $this->addLang('system', 'users');
        $jieqiConfigs['article'] = $this->getConfig('article', 'configs');
        jieqi_getconfigs('system', 'honors');
        $jieqiConfigs['vipgrade'] = $this->getconfig('system', 'vipgrade');
        $jieqiRight['article'] = $this->getConfig('article', 'right');
        $jieqiLang['system'] = $this->getLang('system');
        $jieqiLang['article'] = $this->getLang('article'); //所有语言包配置赋值
        if (!$article = $this->getArticle($params))
            return array(
                'stat' => "failed",
                'msg' => $jieqiLang['article']['article_not_exists']
            );
        //$this->printfail($jieqiLang['article']['article_not_exists']);//判断文章是否存在

        $auth = $this->getAuth();
        $users_handler = $this->getUserObject();//查询用户是否存在
        $users = $users_handler->get($auth['uid']);
        if (!is_object($users) || $users->getVar('groupid') == 1)
            return array(
                'stat' => "failed",
                'msg' => LANG_NO_USER
            );
        //$this->printfail(LANG_NO_USER);

        $honorid = jieqi_gethonorid($users->getVar('score'), $jieqiHonors);
        $maxvote = 0;//初始化参数
        if ($honorid && isset($jieqiRight['article']['dayvotes']['honors'][$honorid]) && is_numeric($jieqiRight['article']['dayvotes']['honors'][$honorid])) $maxvote = intval($jieqiRight['article']['dayvotes']['honors'][$honorid]);//每天可投多少票
        $vipgrade = jieqi_gethonorarray($users->getVar('isvip'), $jieqiConfigs['vipgrade']);//VIP等级数组
        if (intval($vipgrade['setting']['tuijianpiao']) > 0) $maxvote += intval($vipgrade['setting']['tuijianpiao']);//VIP加成

        //查询已经投票数
        $this->db->init('statlog', 'statlogid', 'article');
        $this->db->setCriteria(new Criteria('uid', $auth['uid'], '='));
        $this->db->criteria->add(new Criteria('mid', 'vote', '='));
        $this->db->criteria->add(new Criteria('addtime', strtotime(date('Y-m-d', JIEQI_NOW_TIME)), '>='));
        $pollnum = $this->db->getsum('stat', $this->db->criteria);//当天已经投票数
        //提交数据

        if ($this->submitcheck()) {// print_r($params);exit();
            if (!$params['nosubmitcheck']) {
                if ($params['checkcode'] != $_SESSION['jieqiCheckCode']) {
                    $this->printfail($jieqiLang['system']['error_checkcode']);
                }
            }
            if ($pollnum >= (int)$maxvote)
                return array(
                    'stat' => "failed",
                    'msg' => $jieqiLang['article']['vote_not']
                );
            //$this->printfail($jieqiLang['article']['vote_not']);
            if ($params['stat'] == 'all') $params['stat'] = (int)$maxvote - $pollnum;
            if ($pollnum + $params['stat'] > (int)$maxvote)
                return array(
                    'stat' => "failed",
                    'msg' => sprintf($jieqiLang['article']['vote_times_limit'], $maxvote - $pollnum)
                );
            //$this->printfail(sprintf($jieqiLang['article']['vote_times_limit'], $maxvote-$pollnum));
            if (!(int)$params['stat'])
                return array(
                    'stat' => "failed",
                    'msg' => LANG_ERROR_PARAMETER
                );
            //$this->printfail(LANG_ERROR_PARAMETER);

            //限制投票数
            $jieqiConfigs['article']['dayvotes'] = intval($jieqiConfigs['article']['dayvotes']);
            if ($jieqiConfigs['article']['dayvotes'] > 0) {
                //查询已经投票数
                $this->db->init('statlog', 'statlogid', 'article');
                $this->db->setCriteria(new Criteria('uid', $auth['uid'], '='));
                $this->db->criteria->add(new Criteria('mid', 'vote', '='));
                $this->db->criteria->add(new Criteria('articleid', $article['articleid'], '='));
                $this->db->criteria->add(new Criteria('addtime', $this->getTime('day'), '>='));
                $bookpollnum = $this->db->getsum('stat', $this->db->criteria);//当天已经对该本书投票数
                if ($bookpollnum + $params['stat'] > $jieqiConfigs['article']['dayvotes'])
                    return array(
                        'stat' => "failed",
                        'msg' => sprintf($jieqiLang['article']['vote_book_times_limit'], $jieqiConfigs['article']['dayvotes'], $bookpollnum)
                    );
                //$this->printfail(sprintf($jieqiLang['article']['vote_book_times_limit'], $jieqiConfigs['article']['dayvotes'],$bookpollnum));
            }
            //记录购买日志
            $package = $this->load('article', 'article');
            if ($package->addArticleStat($article['articleid'], $article['authorid'], 'vote', $params['stat'])) {
                if ($jieqiConfigs['article']['scoreuservote'] > 0) {
                    //加积分
                    $users_handler->changeScore($auth['uid'], $jieqiConfigs['article']['scoreuservote'] * $params['stat'], true);
                }
                //写评论
                if (!$params['pcontent']) {
                    $params['pcontent'] = "书写得很好，作者大大辛苦了，投" . $params['stat'] . "张推荐票鼓励一下！";
                }
                $this->addReview($params);
                return array(
                    'stat' => "succ",
                    'msg' => sprintf($jieqiLang['article']['vote_success'], $maxvote, $pollnum + $params['stat'])
                );
                //$this->jumppage($article['url_module_articleinfo'], LANG_DO_SUCCESS, sprintf($jieqiLang['article']['vote_success'], $maxvote, $pollnum+$params['stat']));
            } else {
                return array(
                    'stat' => "failed",
                    'msg' => '投票失败'
                );
                //$this->printfail();
            }
        }
        return array(
            'egolds' => $users->getVar('egold', 'n'),
            'maxvote' => $maxvote,
            'pollnum' => $pollnum,
            'getscore' => $jieqiConfigs['article']['scoreuservote'],
            'article' => $article
        );
    }

    function vipvote($params = array())
    {
        $this->addConfig('article', 'configs');
        $this->addLang('article', 'vipvote');
        $this->addLang('system', 'users');
        $this->addConfig('system', 'vipgrade');
        $jieqiConfigs['article'] = $this->getConfig('article', 'configs');
        $jieqiLang['system'] = $this->getLang('system');
        $jieqiLang['article'] = $this->getLang('article'); // 所有语言包配置赋值
        $jieqiConfigs['vipgrade'] = $this->getconfig('system', 'vipgrade');

        if (!$article = $this->getArticle($params)) $this->printfail($jieqiLang['article']['article_not_exists']);//判断文章是否存在
        $auth = $this->getAuth();
        $users_handler = $this->getUserObject();//查询用户是否存在
        $users = $users_handler->get($auth['uid']);
        if (!is_object($users) || $users->getVar('groupid') == 1) $this->printfail(LANG_NO_USER);
        $monthstart = $this->getTime('month');

        $vipgrade = jieqi_gethonorarray($users->getVar('isvip'), $jieqiConfigs['vipgrade']);//VIP等级数组
        $maxvote = 0;//初始化参数
        if (in_array($users->getVar('groupid'), array(9, 10))) {
            $maxvote = 50;
        } elseif (!$this->checkisadmin()) {//如果不是管理员
            //查询保底
            if (intval($vipgrade['setting']['baodiyuepiao']) > 0) {
                if (isset($_SESSION['jieqiEgoldPreMonth'])) $egoldpremonth = $_SESSION['jieqiEgoldPreMonth'];
                else {
                    //查询订阅章节数
                    $this->db->init('sale', 'saleid', 'article');
                    $this->db->setCriteria(new Criteria('accountid', $auth['uid'], '='));
                    $this->db->criteria->add(new Criteria('buytime', $this->getTime('premonth'), '>='));
                    $this->db->criteria->add(new Criteria('buytime', $monthstart, '<'));
                    $this->db->criteria->add(new Criteria('pricetype', 0));
                    $egoldpremonth = $this->db->getsum('saleprice', $this->db->criteria);//上月定阅总额
                    $this->db->init('statlog', 'statlogid', 'article');
                    $this->db->setCriteria(new Criteria('uid', $auth['uid'], '='));
                    $this->db->criteria->add(new Criteria('addtime', $this->getTime('premonth'), '>='));
                    $this->db->criteria->add(new Criteria('addtime', $monthstart, '<'));
                    $this->db->criteria->add(new Criteria('mid', 'reward', '='));
                    $egoldpremonth += $this->db->getsum('stat', $this->db->criteria);//加打赏总额
                    $_SESSION['jieqiEgoldPreMonth'] = $egoldpremonth;
                }
                //默认每月保义月票数
                if ($egoldpremonth >= intval($jieqiConfigs['article']['vipvotes'])) $maxvote += $vipgrade['setting']['baodiyuepiao'];
            }
            //查询消费月票
            if (intval($vipgrade['setting']['xiaofeiyuepiao']) > 0) {
                //查询订阅章节数
                $this->db->init('sale', 'saleid', 'article');
                $this->db->setCriteria(new Criteria('accountid', $auth['uid'], '='));
                $this->db->criteria->add(new Criteria('buytime', $this->getTime('month'), '>='));
                $egoldmonth = $this->db->getsum('saleprice', $this->db->criteria);//本月定阅总额
                $maxvote += floor($egoldmonth / intval($jieqiConfigs['article']['vipvegold'])) * $vipgrade['setting']['xiaofeiyuepiao'];
            }
        } else $maxvote = 1000;
        //查询已经投票数
        $this->db->init('statlog', 'statlogid', 'article');
        $this->db->setCriteria(new Criteria('mid', 'vipvote', '='));
        $this->db->criteria->add(new Criteria('uid', $auth['uid'], '='));
        $this->db->criteria->add(new Criteria('addtime', $monthstart, '>='));
        $pollnum = $this->db->getsum('stat', $this->db->criteria);//当天已经投票数
        unset($this->db->criteria);
        $this->db->setCriteria(new Criteria('uid', $auth['uid'], '='));
        $this->db->criteria->add(new Criteria('mid', 'vipvote', '='));
        $this->db->criteria->add(new Criteria('articleid', $article['articleid'], '='));
        $this->db->criteria->add(new Criteria('addtime', $monthstart, '>='));
        $yitou = $this->db->getsum('stat', $this->db->criteria);//单本书当月已投票数
        //提交数据
        if ($this->submitcheck()) {
            if ($article['permission'] < 1) {
                $this->printfail($jieqiLang['article']['vipvote_nosign']);
            }
            if (!$params['nosubmitcheck']) {
                if ($params['checkcode'] != $_SESSION['jieqiCheckCode']) {
                    return array(
                        'stat' => "failed",
                        'msg' => $jieqiLang['system']['error_checkcode']
                    );

                    //$this->printfail($jieqiLang['system']['error_checkcode']);
                }
            }
            if ($pollnum >= (int)$maxvote)
                return array(
                    'stat' => "failed",
                    'msg' => $jieqiLang['article']['vote_not']
                );
            //$this->printfail($jieqiLang['article']['vote_not']);
            if ($params['stat'] == 'all') $params['stat'] = (int)$maxvote - $pollnum;
            if ($pollnum + $params['stat'] > (int)$maxvote)
                return array(
                    'stat' => "failed",
                    'msg' => $jieqiLang['article']['vote_times_limit'] . ($maxvote - $pollnum),
                );
            //$this->printfail(sprintf($jieqiLang['article']['vote_times_limit'], $maxvote-$pollnum));
            if (!(int)$params['stat'])
                return array(
                    'stat' => "failed",
                    'msg' => LANG_ERROR_PARAMETER
                );
            //$this->printfail(LANG_ERROR_PARAMETER);
            //限制投票数
            $jieqiConfigs['article']['monthvipvotes'] = 2;
            if ($jieqiConfigs['article']['monthvipvotes'] > 0) {
                //查询已经投票数
                $this->db->init('statlog', 'statlogid', 'article');
                $this->db->setCriteria(new Criteria('uid', $auth['uid'], '='));
                $this->db->criteria->add(new Criteria('mid', 'vipvote', '='));
                $this->db->criteria->add(new Criteria('articleid', $article['articleid'], '='));
                $this->db->criteria->add(new Criteria('addtime', $this->getTime('month'), '>='));
                $bookpollnum = $this->db->getsum('stat', $this->db->criteria);//当月已经对该本书投票数
                if ($bookpollnum + $params['stat'] > $jieqiConfigs['article']['monthvipvotes'])
                    return array(
                        'stat' => "failed",
                        'msg' => sprintf($jieqiLang['article']['vipvote_book_times_limit'], $jieqiConfigs['article']['monthvipvotes'], $bookpollnum)
                    );
                //$this->printfail(sprintf($jieqiLang['article']['vipvote_book_times_limit'], $jieqiConfigs['article']['monthvipvotes'],$bookpollnum));
            }

            //记录购买日志
            $package = $this->load('article', 'article');
            if ($package->addArticleStat($article['articleid'], $article['authorid'], 'vipvote', $params['stat'])) {
                if ($jieqiConfigs['article']['scorevipvote'] > 0) {
                    //加积分
                    $users_handler->changeScore($auth['uid'], $jieqiConfigs['article']['scorevipvote'] * $params['stat'], true);
                }
                //写评论
                if (!$params['pcontent']) {
                    $params['pcontent'] = "如此佳作怎能不支持？" . $params['stat'] . "张月票奉上，祝本书大火！";
                }
                $this->addReview($params);
                return array(
                    'stat' => "succ",
                    'msg' => sprintf($jieqiLang['article']['vote_success'], $maxvote, $pollnum + $params['stat'])
                );
                //$this->jumppage($article['url_module_articleinfo'],LANG_DO_SUCCESS, sprintf($jieqiLang['article']['vote_success'], $maxvote, $pollnum+$params['stat']));
            } else {
                return array(
                    'stat' => "failed",
                    'msg' => '失败了'
                );
                //$this->printfail();
            }
        }

        $result = array(
            'egolds' => $users->getVar('egold', 'n'),
            'maxvote' => $maxvote,
            'pollnum' => $pollnum,
            'getscore' => $jieqiConfigs['article']['scorevipvote'],
            'article' => $article,
            'yitou' => $yitou
        );

        return $result;
    }

    function reward($params = array())
    {
        global $jieqiSetting;
        jieqi_getconfigs('article', 'reward_item', 'jieqiSetting');
        $item = intval($params['item']);

        $reward_pic = $jieqiSetting['article']['reward_item'][$item]['pic'];
        $reward_name = $jieqiSetting['article']['reward_item'][$item]['name'];
        $reward_price = $jieqiSetting['article']['reward_item'][$item]['price'];

        $this->addConfig('article', 'configs');
        $this->addLang('article', 'article');
        $this->addLang('system', 'users');
        $jieqiConfigs['article'] = $this->getConfig('article', 'configs');
        $jieqiLang['system'] = $this->getLang('system');
        $jieqiLang['article'] = $this->getLang('article'); //所有语言包配置赋值
        if (!$article = $this->getArticle($params)) $this->printfail($jieqiLang['article']['article_not_exists']);//判断文章是否存在
        $auth = $this->getAuth();
        $users_handler = $this->getUserObject();//查询用户是否存在
        $users = $users_handler->get($auth['uid']);
        if (!is_object($users) || $users->getVar('groupid') == 1) $this->printfail(LANG_NO_USER);
        //查询已经打赏的金额
        $this->db->init('statlog', 'statlogid', 'article');
        $this->db->setCriteria(new Criteria('mid', 'reward', '='));
        $this->db->criteria->add(new Criteria('uid', $auth['uid'], '='));
        $this->db->criteria->add(new Criteria('articleid', $params['aid'], '='));
        $pollnum = $this->db->getsum('stat', $this->db->criteria);//已经打赏
        //提交数据

        if ($this->submitcheck()) {
            if (!$params['nosubmitcheck']) {
                if ($params['checkcode'] != $_SESSION['jieqiCheckCode']) {
                    $this->printfail($jieqiLang['system']['error_checkcode']);
                }
            }


            $need_egold = intval($params['num'] * $reward_price);

            if ((int)$need_egold > (int)$users->getVar('egold', 'n')) {
                if (JIEQI_MODULE_NAME == 'wap') {
                    $url = $this->geturl('wap', 'pay');
                } else {
                    $url = $this->geturl('pay', 'home');
                }
                $this->printfail(sprintf($jieqiLang['article']['money_notenough'], $users->getVar('egold', 'n') . ' ' . JIEQI_EGOLD_NAME, $url));
            }
            if (!$need_egold) $this->printfail(LANG_ERROR_PARAMETER);
            //记录购买日志

            $package = $this->load('article', 'article');
            if ($package->addArticleStat($article['articleid'], $article['authorid'], 'reward', $need_egold)) {
                $score = ceil($need_egold * 0.2); //打赏金额的20%换算积分
                if ($score > 0) {
                    //加积分
                    $users_handler->changeScore($auth['uid'], $score, true);
                }
                $users_handler->payout($users->getVar('uid', 'n'), $need_egold);
                //写评论
                if (!$params['pcontent']) {
                    $params['pcontent'] = "这本书太棒了！打赏" . $params['num'] . "个" . $reward_name . "，希望后续更加精彩！";
                }
                $this->addReview($params);
                $this->jumppage($article['url_module_articleinfo'], LANG_DO_SUCCESS, $jieqiLang['article']['batch_reward_success']);
            } else {
                $this->printfail();
            }
        }


        return array(
            'egolds' => $users->getVar('egold', 'n'),
            'pollnum' => $pollnum,
            'getscore' => $jieqiConfigs['article']['scorevipvote'],
            'article' => $article,
            'reward_item' => $jieqiSetting['article']['reward_item'],
            'reward_pic' => $reward_pic,
            'reward_name' => $reward_name,
            'reward_price' => $reward_price,
            'reward_id' => $item
        );
    }

    function cuigeng($params = array())
    {
        return array(
            'article' => $this->getArticle($params)
        );
    }

    function reviews($params = array())
    {
        //提交数据
        if ($this->submitcheck()) {
            $reviewsObj = $this->model('reviews', 'article');
            $reviewsObj->add($params);
        }
        return array(
            'article' => $this->getArticle($params)
        );
    }

    function addReview($params)
    {
        global $jieqiConfigs;
        //写评论
        if (!$params['pcontent']) {
            $params['pcontent'] = "书写的真好撒，继续哦……";
        }

        $plen = strlen($params['pcontent']);
        if ($plen >= $jieqiConfigs['article']['minreviewsize'] && (!$jieqiConfigs['article']['maxreviewsize'] || $plen <= $jieqiConfigs['article']['maxreviewsize'])) {
            $reviews = $this->load('reviews', 'article');
            $reviews->addReview($params);
        }
    }

    /**
     * 登录用户是否收藏aid书籍
     * @author chengyuan 2015-6-4 下午4:20:31
     * @param unknown $aid 书籍id
     * @return json
     */
    function asyncBookcaseState($aid)
    {
        $auth = $this->getAuth();
        if ($aid && $auth['uid'] > 0) {
            $this->db->init('bookcase', 'caseid', 'article');
            $this->db->setCriteria();
            $this->db->criteria->add(new Criteria('userid', $auth['uid']));
            $this->db->criteria->add(new Criteria('articleid', $aid));
            $this->db->queryObjects();
            $state = $this->db->getObject() ? 'true' : 'false';
            $this->msgwin('', $state);
        }
    }

    /**
     * 自动添加书签
     * <p>
     * goodnum 总收藏+1
     * <p>
     * 设置自动goodnum+1
     * @author chengyuan 2015-6-3 下午2:49:40
     * @param unknown $aid
     * @param unknown $cid
     */
    function autoAddBookCase($aid, $cid)
    {
        $_USER = $this->getAuth();
        $bookcaseModel = $this->model('bookcase', 'article');
        //addBookmark 内是新增
        $result = $bookcaseModel->addBookmark($_USER['uid'], $_USER['username'], $aid, $cid);
        if ($result > 1) {
            //设置手动goodnum+1
            $package = $this->load('article', 'article');
            $this->addLang('article', 'bookcase');
            $jieqiLang['article'] = $this->getLang('article');
            $package->addArticleStat($aid, 0, 'autogoodnum');//这个是自动的
            $this->msgwin(LANG_DO_SUCCESS, $jieqiLang['article']['add_chaptermark_success']);
        }
    }

    function addBookCase($params)
    {
        global $jieqiModules;
        global $jieqiHonors;

        $this->addConfig('article', 'configs');
        $this->addLang('article', 'article');
        $this->addLang('article', 'bookcase');
        $this->addLang('system', 'honors');
        $this->addConfig('article', 'right');
        $jieqiConfigs['article'] = $this->getConfig('article', 'configs');
        $jieqiLang['article'] = $this->getLang('article'); //所有语言包配置赋值
        $jieqiRight['article'] = $this->getConfig('article', 'right');
        $users_handler = $this->getUserObject();
        $maxnum = $jieqiConfigs['article']['maxbookmarks'];
        $honorid = jieqi_gethonorid($_SESSION['jieqiUserScore'], $jieqiHonors);
        if ($honorid && isset($jieqiRight['article']['maxbookmarks']['honors'][$honorid]) && is_numeric($jieqiRight['article']['maxbookmarks']['honors'][$honorid])) {
            $maxnum = intval($jieqiRight['article']['maxbookmarks']['honors'][$honorid]);
        }
        $_USER = $this->getAuth();
        if (!$article = $this->getArticle($params)) $this->printfail($jieqiLang['article']['article_not_exists']);//判断文章是否存在
        $this->db->init('bookcase', 'caseid', 'article');
        $this->db->setCriteria();
        $this->db->criteria->add(new Criteria('userid', $_USER['uid'], '='));
        $cot = $this->db->getCount($this->db->criteria);
        if (!empty($params['cid']) && !empty($params['aid'])) {
            $bookcaseModel = $this->model('bookcase', 'article');
            $result = $bookcaseModel->addBookmark($_USER['uid'], $_USER['username'], $params['aid'], $params['cid']);
            if ($result == 1) {
                $this->printfail($jieqiLang['article']['add_chaptermark_failure']);
            } elseif ($result == 2) {
                //书签新增
                $this->jumppage($this->geturl(JIEQI_MODULE_NAME, 'reader', "SYS=aid={$params['aid']}&cid={$params['cid']}"), LANG_DO_SUCCESS, $jieqiLang['article']['add_chaptermark_success']);
                //$this->msgwin(LANG_DO_SUCCESS, $jieqiLang['article']['add_chaptermark_success']);
            } elseif ($result == 3) {
                //书签更新
                $this->jumppage($this->geturl(JIEQI_MODULE_NAME, 'reader', "SYS=aid={$params['aid']}&cid={$params['cid']}"), LANG_DO_SUCCESS, $jieqiLang['article']['add_chaptermark_success']);
            }
        } elseif (!empty($params['aid']) && empty($params['cid'])) {
            $this->db->criteria->add(new Criteria('articleid', $params['aid'], '='));
            $this->db->queryObjects();
            $bookcase = $this->db->getObject();
            unset($this->db->criteria);
            if ($bookcase) { //已经在书架
                $this->printfail($jieqiLang['article']['article_has_incase']);
            } else {
                $bookcase['joindate'] = JIEQI_NOW_TIME;
                $bookcase['lastvisit'] = JIEQI_NOW_TIME;
                $bookcase['flag'] = 0;

            }
            $bookcase['articleid'] = $article['articleid'];
            $bookcase['articlename'] = $article['articlename'];
            $bookcase['userid'] = $_USER['uid'];
            $bookcase['username'] = $_USER['username'];
            $bookcase['chapterid'] = 0;
            $bookcase['chaptername'] = '';
            $bookcase['chapterorder'] = 0;
            $this->db->init('bookcase', 'caseid', 'article');
            if (!$this->db->add($bookcase)) {
                $this->printfail($jieqiLang['article']['add_articlemark_failure']);
            } else {
                //增加日志
                $package = $this->load('article', 'article');
                if ($package->addArticleStat($article['articleid'], $article['authorid'], 'goodnum')) {
                    if ($jieqiConfigs['article']['addcasescore'] > 0) {
                        //增加
                        $users_handler->changeScore($_USER['uid'], $jieqiConfigs['article']['addcasescore'], true);
                    }
                }
            }
            $this->jumppage($article['url_module_articleinfo'], LANG_DO_SUCCESS, $jieqiLang['article']['add_articlemark_success']);
        } else {
            $this->printfail($jieqiLang['article']['article_not_exists']);
        }
    }

    function getArticle($params = array())
    {
        $this->db->init('article', 'articleid', 'article');
        $this->db->setCriteria(new Criteria('articleid', $params['aid']));
        if ($article = $this->db->get($this->db->criteria)) {
            $package = $this->load('article', 'article');
            return $package->article_vars($article);
        } else {
            return false;
        }
    }

    function lunpan($params)
    {
        $auth = $this->getAuth();
        $uid = $auth['uid'];
        if ($params['action']) {
            if ($this->is_first($uid) || $this->get_user_activity_num($uid, 2017) && $this->dec_user_activity_num($uid, 2017)) {
                $reward_id = $this->get_lunpan_reward();
                $msg = $this->lunpan_reward($uid, $reward_id);
                if ($msg) {
                    $arr=array(
                        'stat'=>'succ',
                        'times'=> $this->get_user_activity_num($uid, 2017),
                        'num'=>$reward_id,
                        'msg'=>iconv("gbk","utf-8",$msg)
                    );

                }
                else {
                    $arr=array(
                        'stat'=>"failed",
                        'msg'=>iconv("gbk","utf-8",'系统繁忙，请稍后再试')
                    );
                }
            }
            else {
                $arr=array(
                    'stat'=>"failed",
                    'msg'=>iconv("gbk","utf-8",'您的旋转次数已经用完了，明天再来吧，详细规则请点“活动规则”')
                );
            }
            echo json_encode($arr);
            exit();
        }
        else {
            $params['list']=$this->get_lunpan_rewardlist();
            if ($this->is_first($uid)) {
                $times=$this->get_user_activity_num($uid,2017)+1;
            }
            else {
                $times=$this->get_user_activity_num($uid,2017);
            }
            $params['times']=$times;
            return $params;
        }
    }

    private function get_user_activity_num($uid,$hid) {
        $sql="select * from jieqi_system_activity where uid=$uid and hid=$hid";
        $res=$this->db->query($sql);
        $row=$this->db->getRow($res);
        if ($row) {
            return $row['num'];
        }
        else {
            return 0;
        }
    }

    private  function dec_user_activity_num($uid,$hid) {
        $sql="update jieqi_system_activity set num=num-1 where uid=$uid and hid=$hid and num>=1";
        $this->db->query($sql);
        return $this->db->getAffectedRows();
    }

    private function get_lunpan_reward() {
        $key=rand(0,9999);
        if ($key<1000) {
            return 1;
        }
        if ($key<1500) {
            return 2;
        }
        if ($key<2500) {
            return 3;
        }
        if ($key<6500) {
            return 4;
        }
        if ($key<6700) {
            return 5;
        }
        if ($key<7700) {
            return 6;
        }
        if ($key<7800) {
            return 7;
        }
        if ($key<10000) {
            return 8;
        }
    }

    private function add_user_activity_num($uid,$hid) {
        $num=$this->get_user_activity_num($uid,$hid);
        if ($num===false) {
            $sql="insert into jieqi_system_activity (uid,hid,num) values('$uid','$hid',1)";
            $num=1;
        }
        else {
            $sql="update jieqi_system set num=num+1 where uid=$uid and hid=$hid";
            $num=$num+1;
        }

        return $num;
    }

    private function is_first($uid) {
        $time=strtotime(date("Y-m-d"));
        $sql="select * from jieqi_system_lunpan where uid=$uid and time>=$time limit 1";
        $r1=$this->db->query($sql);
        if ($this->db->getRow($r1)) {
            return false;
        }
        else {
            return true;
        }
    }

    private function lunpan_reward($uid,$reward_id) {
        $auth=$this->getAuth();
        $uname=addslashes($auth['useruname']);
        $gift=0;
        $addnum=0;
        switch($reward_id) {
            case 1:$msg="谢谢参与";break;
            case 2:$gift=1888;$addnum=0;$msg="恭喜{$uname}抽中了1888书券";break;
            case 3:$gift=0;$addnum=1;$msg="恭喜{$uname}抽中了再来一次";break;
            case 4:$gift=88;$addnum=0;$msg="恭喜{$uname}抽中了88书券";break;
            case 5:$msg="恭喜{$uname}抽中了金鸡公仔";break;
            case 6:$gift=666;$addnum=0;$msg="恭喜{$uname}抽中了666书券";break;
            case 7:$msg="恭喜{$uname}抽中了阿狸公仔";break;
            case 8:$gift=168;$addnum=0;$msg="恭喜{$uname}抽中了168书券";break;
        }
        if ($gift>0) {
            $users_handler =  $this->getUserObject();
            $ret=$users_handler->income($auth['uid'], $gift, 1, 0, 0);
            if (!$ret) {
                $this->printfail('发放书券失败');
                exit();
            }
        }
        if ($addnum) {
            $this->add_user_activity_num($uid,2017);
        }

        $time=time();
        $sql="insert into jieqi_system_lunpan (uid,time,reward_num,msg ) values('$uid','$time','$reward_id','$msg')";
        $this->db->query($sql);
        return $msg;
    }

    private function get_lunpan_rewardlist() {
        $sql="select * from jieqi_system_lunpan order by id desc limit 10";
        $res=$this->db->query($sql);
        $infolist=array();
        while ($row=$this->db->getRow($res)) {
            $infolist[]=array('info'=>$row['msg']);
        }
        return $infolist;
    }


    function dati($params) {
        $qlist=array(
            1=>array('2017-01-28','2017年是什么年？','马年','牛年','鸡年','龙年','c'),
            2=>array('2017-01-29','以下哪个是本站的正确名称？','爱书坊','爱书房','书房网','书房网','a'),
            3=>array('2017-01-30','《爱一次不够》的女主角名称叫什么？','安若婉','李娜','幕潇潇','阮颜','d'),
            4=>array('2017-01-31','《我们复婚吧》的女主角名称叫什么？','木清竹','安若婉','幕潇潇','阮颜','a'),
            5=>array('2017-02-01','下列故事不是《三国演义》中的一项是','三顾茅庐','三气周瑜','三打白骨精','桃园三结义','c'),
            6=>array('2017-02-02','“遥远的东方有一条龙”是哪首歌中的歌词？','非龙非影','龙的传人','龙子龙孙','我的祖国','b'),
            7=>array('2017-02-03','我国的国球是','篮球','足球','乒乓球','羽毛球','c'),
            8=>array('2017-02-04','藏历新年，人们见面时都要说“扎西德勒”是什么意思？','新年好','吉祥如意','你好','恭喜发财','b')
        );
        $date=date("Y-m-d");
        $params['id']=0;
        foreach($qlist as $id=>$q) {
            if ($q[0]==$date) {
                $params['id']=$id;
            }
        }

        $id=$params['id'];
        $q=$qlist[$id];
        if ($q[0]!=$date) {
            $params['errmsg']='今天暂时没有答题活动，请您关注我们的活动公告，谢谢';
            $params['succ']=0;
            $params['action']=1;
            return $params;
        }
        $auth=$this->getAuth();
        $uid=$auth['uid'];


        $time=strtotime(date("Y-m-d"));
        $sql="select * from jieqi_system_dati where uid=$uid and time>=$time";
        $r=$this->db->query($sql);
        if ($this->db->getRow($r)) {
            $params['errmsg']='您今天已经答过题了，明天再来吧';
            $params['succ']=0;
            $params['action']=1;
            return $params;
        }
        $params['question']=$q[1];
        $params['c1']=$q[2];
        $params['c2']=$q[3];
        $params['c3']=$q[4];
        $params['c4']=$q[5];
        $answer=$q[6];

        if ($params['action']) {
            if ($params['pay']==$answer) {
                $gift=rand(30,50);
                $params['succ']=1;
                $params['answer']=$answer;
                $params['gift']=$gift;
                $users_handler =  $this->getUserObject();
                $ret=$users_handler->income($auth['uid'], $gift, 1, 0, 0);
                if (!$ret) {
                    $this->printfail('发放书券失败');
                    exit();
                }
            }
            else{
                $params['answer']=$answer;
                switch($answer) {
                    case 'a':$params['answer'].=",".$q[2];break;
                    case 'b':$params['answer'].=",".$q[3];break;
                    case 'c':$params['answer'].=",".$q[4];break;
                    case 'd':$params['answer'].=",".$q[5];break;
                }
                $params['succ']=0;
            }
            $id=intval($id);
            $time=time();
            $stat=$params['succ'];
            $sql="insert into jieqi_system_dati(uid,qid,time,gift,stat) values('$uid','$id','$time','$gift','$stat')";
            $this->db->query($sql);
        }
        $params['num']=round(time()/23456);

        return $params;
    }
} 

?>