<?php

class articleModel extends Model
{

    function main($param = array()) {
        $articleid=intval($param['articleid']);
        $chapterlist=$this->get_chapter_list($articleid);
        $this->db->init("history","id","article");
        $this->db->setCriteria(new Criteria('articleid', $articleid));
        if ($param['qd']) {
            $this->db->setCriteria(new Criteria('qd', $param['qd']));
        }
        $this->db->criteria->setFields("count(uid) as usernum,chapterid");
        $this->db->criteria->setGroupby("chapterid");
        $datalist=$this->db->lists();
        $readlist=array();

        $totalusers=0;
        foreach($datalist as $data) {
            $totalusers += $data['usernum'];
            $readlist[$data['chapterid']]=array('chapterid'=>$data['chapterid'],'usernum'=>$data['usernum']);
        }

        $i=0;
        $resultarr=array();
        foreach($chapterlist as $chapter) {
            if ($i<100) {
                $resultarr[]=array(
                    'chapterid'=>$chapter['chapterid'],
                    'chaptername'=>iconv("GBK","UTF-8",$chapter['chaptername']),
                    'chapterorder'=>$chapter['chapterorder'],
                    'usernum'=>$readlist[$chapter['chapterid']]['usernum'],
                    'isvip'=>$chapter['isvip'],
                    'percent'=>(round($readlist[$chapter['chapterid']]['usernum']*10000/$totalusers)/100).'%'
                );
                $i++;
            }
            else {
                break;
            }
        }
        $param['chapterlist']=$resultarr;
        return $param;
    }


    private function get_chapter_list($articleid) {
        $this->db->init("chapter","chapterid","article");
        $this->db->setCriteria(new Criteria('articleid', $articleid));
        $this->db->criteria->add(new Criteria('display', 0, "="));
        //$this->db->criteria->add(new Criteria('isvip', 0));
        $this->db->criteria->setSort("chapterorder");
        $chapterlist=$this->db->lists();
        $res=array();
        foreach($chapterlist as $chapter) {
            $res[$chapter['chapterid']]=$chapter;
        }
        return $res;
    }




    function chaptervisit($param = array())
    {
        $this->db->init('article', 'articleid', 'article');
        $this->db->setCriteria(new Criteria('articleid', 10000, ">"));
        //$this->db->setCriteria(new Criteria('articleid', 10027, "="));
        $this->db->criteria->add(new Criteria('display', 0, "="));

        $articlelist=$this->db->lists();

        foreach($articlelist as $article) {
            $vdata=$this->get_chapter_visit($article['articleid']);
            $vdata['articleid']=iconv("GBK","utf-8",$article['articleid']);
            $vdata['articlename']=iconv("GBK","utf-8",$article['articlename']);
            $param['vdata'][]=$vdata;
        }

        return $param;
    }


    function get_chapter_visit($articleid) {
        $sql="select * from jieqi_article_chapter where articleid=$articleid order by chapterorder limit 100";
        $res=$this->db->query($sql);

        while ($chapter=$this->db->fetchArray($res)){
            $chapterlist[]=$chapter;
        }
        $maxvisit=0;
        $vipvisit=0;
        $maxpos=0;
        $vippercent1=0;
        $vippercent2=0;
        $maxchapter="";
        for($i=0;$i<count($chapterlist);$i++) {
            $visit=$this->redis->hget('ChapterVisit',$chapterlist[$i]['chapterid']);
            $chapterlist[$i]['visit']=$visit;
            if ($visit > $maxvisit) {
                $maxvisit=$visit;
                $maxchapter=iconv('gbk','utf-8',$chapterlist[$i]['chaptername']);
                $maxpos=$i;
            }
            if (!$chapterlist[$i-1]['isvip'] && $chapterlist[$i]['isvip']) {
                $vipvisit = $this->get_chapter_salecount($chapterlist[$i]['chapterid']);
                if ($chapterlist[$i-1]['visit']) {
                    $vippercent1=number_format($vipvisit*100/$chapterlist[$i-1]['visit'],2);
                }
            }
        }
        if ($maxvisit) {
            $nextclick=$chapterlist[$maxpos+1]['visit'];
            $nextpercent=number_format($nextclick*100/$maxvisit,2);
            $vippercent2=number_format($vipvisit*100/$maxvisit,2);
        }
        else {
            $nextclick=0;
            $nextpercent=0;
        }
        return array(
            "maxvisit"=>$maxvisit,
            "maxpos"=>$maxpos,
            'maxchapter' => $maxchapter,
            "vipvisit"=>$vipvisit,
            'chapterlist'=>$chapterlist,
            'nextvisit'=>$nextclick,
            'nextpercent'=>$nextpercent,
            'vippercent1'=>$vippercent1,
            'vippercent2'=>$vippercent2,
            'adflag'=>$maxchapter>10000 ? "YES":"NO"
        );
    }

    function get_chapter_salecount($chapterid) {
        $this->db->init('statlog', 'statlogid', 'article');
        $this->db->setCriteria(new Criteria('chapterid', $chapterid, "="));
        $this->db->criteria->add(new Criteria('mid', 'sale', "="));
        return $this->db->getCount();
    }

    function blocklist($param) {
        global $article_block_list;
        include_once(dirname(__FILE__)."/../../configs/article/blocks.php");
        $param['rows']=$article_block_list;
        //echo 'rows=';
        //print_r($param['rows']);
        //exit();
        return $param;
    }

    function addblock($param) {
        global $article_block_list;
        include_once(dirname(__FILE__)."/../../configs/article/blocks.php");
        if ($param['type']=='post') {
            $this->db->init("article","articleid","article");
            $articleinfo = $this->db->get($param['articleid']);
            if ($articleinfo['articlename']) {
                $chapterid=$param['chapterid'];
                $this->db->init("chapter","chapterid","article");
                $chapterinfo=$this->db->get($chapterid);
                if ($chapterinfo['chaptername']) {
                    $file = $_FILES['blockpic'];//得到传输的数据
                    if ($file) {
                        $name = $file['name'];
                        $type = strtolower(substr($name, strrpos($name, '.') + 1)); //得到文件类型，并且都转化成小写
                        $allow_type = array('jpg'); //定义允许上传的类型

                        if (!in_array($type, $allow_type)) {
                            echo 'jpg file needed';
                            exit();
                        }

                        if (!is_uploaded_file($file['tmp_name'])) {
                            echo 'system error';
                            exit();
                        }
                        $upload_path = dirname(__FILE__) . "/../../upload/"; //上传文件的存放路径
                        $filename = $param['articleid'] . '.jpg';

                        if (move_uploaded_file($file['tmp_name'], $upload_path . $filename)) {
                            $up_arr = array(
                                "articleid" => $param['articleid'],
                                'articlename' => iconv("gbk","utf-8",$articleinfo['articlename']),
                                'chapterid' => $param['chapterid'],
                                'chaptername' => iconv("gbk","utf-8",$chapterinfo['chaptername']),
                                'blockpic' => JIEQI_URL.'/upload/'.$filename
                            );
                            $article_block_list[$param['articleid']] = $up_arr;
                            $vars = var_export($article_block_list, true);
                            $vars = '<?php $article_block_list=' . $vars.";?>";
                            $phpfile = dirname(__FILE__) . "/../../configs/article/blocks.php";
                            file_put_contents($phpfile, $vars);
                            header("location:index.php?controller=article&method=blocklist");
                            exit();
                        } else {
                            echo "Failed!";
                            exit();
                        }
                    }
                    else {
                        die("请上传文件!");
                    }
                }
                else{
                    echo 'bad chapterid';
                    exit();
                }
            }
            else {
                echo 'articleid='.$param['articleid'];
                print_r($articleinfo);
                echo 'bad articleid';
                exit();
            }
        }
        return $param;
    }

    function delblock($param) {
        global $article_block_list;
        include_once(dirname(__FILE__)."/../../configs/article/blocks.php");
        $delid=intval($param['delid']);
        unset($article_block_list[$delid]);
        $vars = var_export($article_block_list, true);
        $vars = '<?php $article_block_list=' . $vars.";?>";
        $phpfile = dirname(__FILE__) . "/../../configs/article/blocks.php";
        file_put_contents($phpfile, $vars);
        header("location:index.php?controller=article&method=blocklist");
        exit();
    }

    function booktotal($param) {
        $time1=$param['fromtime']?strtotime($param['fromtime']):0;
        $time2=$param['totime']?strtotime($param['totime'].' 23:59:59'):0;

        $sql="select count(*) as regcount,book_id from jieqi_system_users where 1 ";
        if ($time1)
            $sql.=" and regdate>=$time1";
        if ($time2)
            $sql.=" and regdate<=$time2";

        $sql.=" group by book_id order by regcount desc";

        //echo $sql."<br>";
        $res=$this->db->query($sql);

        $total_arr=array();

        while ($row=$this->db->fetchArray($res)) {
            if (!$row['book_id']) {
                $articleid=0;
                $articlename="unknow";
            }
            else{

                $sql="select * from jieqi_article_article where articleid=".$row['book_id'];
                $r1=$this->db->query($sql);
                $article=$this->db->fetchArray($r1);
                $articlename=iconv("gbk","utf-8",$article['articlename']);
                $articleid=$row['book_id'];
            }

            //echo "articleid=$articleid\n";

            $total_arr[$articleid]=array("articleid"=>$articleid,'articlename'=>$articlename,'regcount'=>$row['regcount']);

        }

        $sql="select count(distinct buyid) as paycount,round(sum(money)/100) as paymoney,b.book_id from jieqi_pay_paylog a,jieqi_system_users b where a.buyid=b.uid and a.payflag=1  ";
        if ($time1)
            $sql.=" and b.regdate>=$time1";
        if ($time2)
            $sql.=" and b.regdate<=$time2";

        $sql.=" group by b.book_id ";
        //echo $sql."<br>";
        $res=$this->db->query($sql);

        while ($row=$this->db->fetchArray($res)) {
            $articleid=$row['book_id'];
            $total_arr[$articleid]['paycount']=$row['paycount'];
            $total_arr[$articleid]['paymoney']=$row['paymoney'];
            if ($total_arr[$articleid]['regcount']) {
                $total_arr[$articleid]['payx'] = round($total_arr[$articleid]['paycount']*10000/$total_arr[$articleid]['regcount'])/100;
            }
        }

        $param['rows']=$total_arr;
        return $param;

    }
} 

?>