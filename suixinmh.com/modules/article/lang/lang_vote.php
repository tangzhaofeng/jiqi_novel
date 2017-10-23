<?php
/**
 * 语言包-文章推荐
 *
 * 语言包-文章推荐
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: lang_vote.php 228 2008-11-27 06:44:31Z juny $
 */

$jieqiLang['article']['vote']=1; //表示本语言包已经包含

$jieqiLang['article']['article_not_exists']='对不起，该文章不存在！';
//uservote.php
$jieqiLang['article']['vote_not']='您今天的推荐票已经用完，明天再来吧！';
$jieqiLang['article']['vote_times_limit']='推荐票数量不足！您当前还剩 %s 张推荐票！';
$jieqiLang['article']['vote_book_times_limit']='系统限制每天对一本书投票数不能超过 %s 张推荐票！您当前对本书已经投了 %s 张推荐票。';
$jieqiLang['article']['vote_success']='我们已经记录了本次推荐，感谢您的参与！<br />您今天拥有 %s 张推荐票、已经使用 %s 张。';
$jieqiLang['article']['vote_need_score']='您今天的推荐次数已达上限 %s 次，如仍推荐，将消耗您的积分 %s 点<br /><br /><a href="%s">点击这里确认继续推荐</a>';
$jieqiLang['article']['vote_min_articlesize']='对不起，内容在 %s 字节以上的文章才允许推荐！';
$jieqiLang['article']['low_vote_score']='对不起，您的积分不足，不允许继续投票！';
$jieqiLang['article']['userchap_vote_success']='提交成功，感谢您的支持！';
?>