<?php

define("CONSUMEKEY","24330805");
define("SECRETKEY","7xW7kTXhwne2MmD6");


function sign($method,$url,$param) {
    $s=strtoupper($method).$url;
    $keyarr=array();
    foreach($param as $key=>$val) {
        $keyarr[]=$key;
    }
    array_multisort($keyarr,SORT_ASC,$param);
    foreach($param as $k=>$v) {
        $s.="$k=$v";
    }
    $s.=SECRETKEY;
    //echo "s=$s\n";
    $sign = MD5(urlencode($s));
    return $sign;
}


function booklist() {
    $testurl="http://testdistapi.yuedu.163.com/neptune/xml/bookList.xml";
    $url="http://distapi.yuedu.163.com/neptune/xml/bookList.xml";
    $param=array(
        "consumerKey"=>CONSUMEKEY,
        "timestamp"=>time()*1000,
        //"expires"=>time()+600
    );
    $qarr=array();
    foreach($param as $k=>$v) {
        $qarr[]="$k=$v";
    }
    $querystring=implode('&',$qarr);
    $sign=sign("GET",$url,$param);
    $request=$url."?$querystring&sign=$sign";
    $content=file_get_contents($request);
    $data = xml2array($content);
    return $data['booklist']['bookid'];
}

function bookinfo($bookid) {
    $testurl="http://testdistapi.yuedu.163.com/neptune/xml/bookInfo.xml";
    $url="http://distapi.yuedu.163.com/neptune/xml/bookInfo.xml";
    $param=array(
        "consumerKey"=>CONSUMEKEY,
        "timestamp"=>time()*1000,
        //"expires"=>time()+600,
        "bookId"=>$bookid
    );
    $qarr=array();
    foreach($param as $k=>$v) {
        $qarr[]="$k=$v";
    }
    $querystring=implode('&',$qarr);
    $sign=sign("GET",$url,$param);
    $request=$url."?$querystring&sign=$sign";
    //echo $request."\n";
    $content=file_get_contents($request);
    $tmparr1 = xml2array($content);
    return $tmparr1['bookinfo'];
}

function catlog($bookid) {
    $testurl="http://testdistapi.yuedu.163.com/neptune/xml/sectionList.xml";
    $url="http://distapi.yuedu.163.com/neptune/xml/sectionList.xml";
    $param=array(
        "consumerKey"=>CONSUMEKEY,
        "timestamp"=>time()*1000,
        //"expires"=>time()+600,
        "bookId"=>$bookid
    );
    $qarr=array();
    foreach($param as $k=>$v) {
        $qarr[]="$k=$v";
    }
    $querystring=implode('&',$qarr);
    $sign=sign("GET",$url,$param);
    $request=$url."?$querystring&sign=$sign";
    //echo $request."\n";
    $content=file_get_contents($request);
    return xml2array($content);
}

function chapter($bookid,$chapterid) {
    $url="http://distapi.yuedu.163.com/neptune/xml/sectionContent.xml";
    $param=array(
        "consumerKey"=>CONSUMEKEY,
        "timestamp"=>time()*1000,
        //"expires"=>time()*1000+600,
        "bookId"=>$bookid,
        "sectionId"=>$chapterid
    );
    $qarr=array();
    foreach($param as $k=>$v) {
        $qarr[]="$k=$v";
    }
    $querystring=implode('&',$qarr);
    $sign=sign("GET",$url,$param);
    $request=$url."?$querystring&sign=$sign";
    //echo $request."\n";
    $content=file_get_contents($request);
    //echo $content."\n";
    $tmparr1 = xml2array($content);
    $chapterinfo = $tmparr1['contentinfo'];
    return $chapterinfo;
}


function update_article($articleid,$articlename,$chapterorder,$chaptertype,$chapterinfo,$ocid) {
    global $db;
    $content=trim($chapterinfo['content']);
    $content=format_txt($content);
//    echo $content."\n\n";
//    return;
    $chaptername1=trim($chapterinfo['title']);
    $chaptercontent = iconv("utf-8", "GB18030",$content);
    $chaptername = addslashes(iconv("utf-8", "GB18030", trim($chapterinfo['title'])));

    $sql="select * from jieqi_article_chapter where articleid=$articleid and ocid='$ocid'";
    $r1=$db->query($sql);
    if ($db->getRowsNum($r1)) {
        echo $chaptername1." exists\n";
        return false;
    }



    $isvip = $chapterinfo['needPay'];
    $size=strlen($chaptercontent);
    $price=round($size*5/2000);
    $now=time();
    $sql = "insert into jieqi_article_chapter (articleid,articlename,poster,postdate,lastupdate,chaptername,chapterorder,size,saleprice,isvip,chaptertype,display,ocid)";
    $sql.=" values('$articleid','$articlename','admin',$now,$now,'$chaptername',$chapterorder,$size,$price,$isvip,$chaptertype,0,'$ocid')";
    echo iconv("GBK","utf-8",$sql)."\n";
    if ($db->query($sql)) {
        $chapterid=$db->getInsertId();
        if (!$chapterid) {
            echo "getInsertId error\n";
            exit();
        }
        $dirid=floor($articleid/1000);
        $dir=dirname(__FILE__)."/../../files/article/c_txt_www/$dirid/$articleid/";
        if (!is_dir($dir)) {
            exec("mkdir $dir -p");
            chown($dir,"www");
            echo "make $dir \n";
        }
        $txtfile=dirname(__FILE__)."/../../files/article/c_txt_www/$dirid/$articleid/$chapterid.txt";
        if (!file_put_contents($txtfile,$chaptercontent)){
            echo "put $txtfile error\n";
            exit();
        }
    }
}

function format_txt($s) {
    $x=explode("\n",$s);
    $content = "";
    for($i=0;$i<count($x);$i++) {
        $s1=trim($x[$i]);
        if (strlen($s1)>2) {
            $s1="    ".$s1."\r\n\r\n";
            $content .= $s1;
        }
    }
    return $content;
}

function insert_volumn($articleid,$articlename,$vname,$order) {
    echo "---------insert volumn------\n";
    global $db;
    $articlename = addslashes($articlename);
    $vname = addslashes($vname);
    $sql="select * from jieqi_article_chapter where articleid=$articleid and chaptertype=1 and chaptername='".$vname."'";
    $res=$db->query($sql);
    if ($db->getRowsNum($res)) {
        return false;
    }
    $sql="insert into jieqi_article_chapter (articleid,articlename,chaptername,chapterorder,chaptertype) values('$articleid','$articlename','$vname','$order','1')";
    echo iconv("gbk","utf-8",$sql)."\n\n";
    return $db->query($sql);
}


include_once(dirname(__FILE__)."/../../global.php");
include_once(dirname(__FILE__)."/../../core/lib/lib_database.php");
include_once(dirname(__FILE__)."/../../modules/article/lib/my_article.php");
$db=new Database();
$lib_article = new MyArticle();

if (!$db) {
    die("db error\n");
}


$booklist=array();
if ($argv[1] == "update") {
    $db->init("article", "articleid", "article");
    $db->setCriteria(new Criteria('firstflag', 7));
    $db->criteria->add(new Criteria('lastchaptervip', 0));
    $res = $db->queryObjects();
    while ($row = $db->fetchArray($res)) {
        $booklist[] = $row['oldaid'];
    }
}
else {
    $booklist=booklist();
}

$booklist=array(9012);

foreach($booklist as $bookid) {
    $bookinfo=bookinfo($bookid);
    //print_r($bookinfo);
    $tmparr1 = catlog($bookid);
    //print_r($tmparr1);
    //exit();

    $db->init("article","articleid","article");
    $db->setCriteria(new Criteria('oldaid',$bookid));
    $db->criteria->add(new Criteria('firstflag',7));
    $res = $db->queryObjects();

    if ($row = $db->getRow($res)) {
        $articleid=$row['articleid'];
        $articlename=$row['articlename'];
    }
    else {
        echo "article not found $bookid\n";
        continue;
    }

    if ($row['fullflag']) {
        echo "article:$articleid finished\n";
        continue;
    }


    $sql="select * from jieqi_article_chapter where articleid=$articleid order by chapterorder desc limit 1";
    $res=$db->query($sql);
    if ($lastchapter = $db->getRow($res)) {
        $last_chaptername=$lastchapter['chaptername'];
        $last_chapterorder=$lastchapter['chapterorder'];
        $last_chapteroid=$lastchapter['ocid'];
    }
    else {
        $last_chaptername='';
        $last_chapterorder=0;
        $last_chapteroid=0;
    }
    echo '$last_chaptername='.iconv("GBK","UTF-8",$last_chaptername).',$last_chapterorder='.$last_chapterorder."\n";
    $last_found=false;
    if ($tmparr1['chapterinfo']['volume']['volumeid']) {
        $volarr=array($tmparr1['chapterinfo']['volume']);
    }
    else {
        $volarr=$tmparr1['chapterinfo']['volume'];
    }

    if (is_array($volarr)) {
        foreach ($volarr as $v) {
            //print_r($v);
            insert_volumn($articleid,$articlename,iconv("utf-8","gbk",$v['volname']),$chapterorder);
            if (is_array($v) && is_array($v['chapters']) && is_array($v['chapters']['chapter'])) {
                foreach ($v['chapters']['chapter'] as $chapter) {
                    $chapterid = $chapter['chapterid'];

                    $chaptername = iconv("utf-8", "gbk", trim($chapter['chaptername']));



                    if (!$last_found) {
                        if ($last_chapterorder > 0) {
                            if ($chaptername == $last_chaptername  || $last_chapteroid && $chapterid==$last_chapteroid) {
                                echo $chapter['chaptername']."...\n";
                                $chapterorder = $last_chapterorder;
                                $last_found = true;
                            }
                            continue;
                        } else {
                            $last_found = true;
                        }
                        $chapterorder = $last_chapterorder;
                    }

                    $chapterorder ++;

                    $chapterinfo = chapter($bookid, $chapterid);


                    echo "bookid=$bookid\n";
                    $msg="chapterid=$chapterid,articleid=$articleid,chapterorder=$chapterorder,chaptername=$chaptername\n";
                    echo iconv("GBK","utf-8",$msg);
                    update_article($articleid,$articlename,$chapterorder,0,$chapterinfo,$chapterid);
                    //print_r($chapterinfo);
                    echo iconv("GBK","utf-8",$chaptercontent);

                }
            } else {
                continue;
            }
        }
    }
    else {
        echo "------error 2---------\n";
    }
    $res=$db->query("select * from jieqi_article_chapter where articleid=$articleid and display=0 and chaptertype=0 order by chapterorder desc limit 1");
    $row=$db->getRow($res);
    $sql="update jieqi_article_article set lastchapterid=".$row['chapterid'].",lastchapter='".addslashes($row['chaptername'])."',lastchaptervip=".$row['isvip']." where articleid=$articleid";
    echo $sql."\n";
    if (!$db->query($sql)){
        echo mysql_error()."\n";
    }
    $lib_article->article_repack($articleid, array('makeopf'=>1));
}



function xml2ary(&$string) {
    $parser = xml_parser_create();
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parse_into_struct($parser, $string, $vals, $index);
    xml_parser_free($parser);
    $mnary=array();
    $ary=&$mnary;
    foreach ($vals as $r) {
        $t=$r['tag'];
        if ($r['type']=='open') {
            if (isset($ary[$t])) {
                if (isset($ary[$t][0])) $ary[$t][]=array(); else $ary[$t]=array($ary[$t], array());
                $cv=&$ary[$t][count($ary[$t])-1];
            } else $cv=&$ary[$t];
            if (isset($r['attributes'])) {foreach ($r['attributes'] as $k=>$v) $cv['_a'][$k]=$v;}
            $cv['_c']=array();
            $cv['_c']['_p']=&$ary;
            $ary=&$cv['_c'];
        } elseif ($r['type']=='complete') {
            if (isset($ary[$t])) { // same as open
                if (isset($ary[$t][0])) $ary[$t][]=array(); else $ary[$t]=array($ary[$t], array());
                $cv=&$ary[$t][count($ary[$t])-1];
            } else $cv=&$ary[$t];
            if (isset($r['attributes'])) {foreach ($r['attributes'] as $k=>$v) $cv['_a'][$k]=$v;}
            $cv['_v']=(isset($r['value']) ? $r['value'] : '');
        } elseif ($r['type']=='close') {
            $ary=&$ary['_p'];
        }
    }

    _del_p($mnary);
    return $mnary;
}
// _Internal: Remove recursion in result array
function _del_p(&$ary) {
    foreach ($ary as $k=>$v) {
        if ($k==='_p') unset($ary[$k]);
        elseif (is_array($ary[$k])) _del_p($ary[$k]);
    }
}
// Array to XML
function ary2xml($cary, $d=0, $forcetag='') {
    $res=array();
    foreach ($cary as $tag=>$r) {
        if (isset($r[0])) {
            $res[]=ary2xml($r, $d, $tag);
        } else {
            if ($forcetag) $tag=$forcetag;
            $sp=str_repeat("\t", $d);
            $res[]="$sp<$tag";
            if (isset($r['_a'])) {foreach ($r['_a'] as $at=>$av) $res[]=" $at=\"$av\"";}
            $res[]=">".((isset($r['_c'])) ? "\n" : '');
            if (isset($r['_c'])) $res[]=ary2xml($r['_c'], $d+1);
            elseif (isset($r['_v'])) $res[]=$r['_v'];
            $res[]=(isset($r['_c']) ? $sp : '')."</$tag>\n";
        }
    }
    return implode('', $res);
}
// Insert element into array
function ins2ary(&$ary, $element, $pos) {
    $ar1=array_slice($ary, 0, $pos); $ar1[]=$element;
    $ary=array_merge($ar1, array_slice($ary, $pos));
}









function xml2array($contents, $get_attributes=1, $priority = 'tag')
{
    if(!$contents) return array();

    if(!function_exists('xml_parser_create')) {
        //print "'xml_parser_create()' function not found!";
        return array();
    }

    //Get the XML parser of PHP - PHP must have this module for the parser to work
    $parser = xml_parser_create('');
    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, trim($contents), $xml_values);
    xml_parser_free($parser);

    if(!$xml_values) return;//Hmm...

    //Initializations
    $xml_array = array();
    $parents = array();
    $opened_tags = array();
    $arr = array();

    $current = &$xml_array; //Refference

    //Go through the tags.
    $repeated_tag_index = array();//Multiple tags with same name will be turned into an array
    foreach($xml_values as $data) {
        unset($attributes,$value);//Remove existing values, or there will be trouble

        //This command will extract these variables into the foreach scope
        // tag(string), type(string), level(int), attributes(array).
        extract($data);//We could use the array by itself, but this cooler.

        $result = array();
        $attributes_data = array();

        if(isset($value)) {
            if($priority == 'tag') $result = $value;
            else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
        }

        //Set the attributes too.
        if(isset($attributes) and $get_attributes) {
            foreach($attributes as $attr => $val) {
                if($priority == 'tag') $attributes_data[$attr] = $val;
                else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
            }
        }

        //See tag status and do the needed.
        if($type == "open") {//The starting of the tag '<tag>'
            $parent[$level-1] = &$current;
            if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                $current[$tag] = $result;
                if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
                $repeated_tag_index[$tag.'_'.$level] = 1;

                $current = &$current[$tag];

            } else { //There was another element with the same tag name

                if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                    $repeated_tag_index[$tag.'_'.$level]++;
                } else {//This section will make the value an array if multiple tags with the same name appear together
                    $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
                    $repeated_tag_index[$tag.'_'.$level] = 2;

                    if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                        $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                        unset($current[$tag.'_attr']);
                    }

                }
                $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
                $current = &$current[$tag][$last_item_index];
            }

        } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
            //See if the key is already taken.
            if(!isset($current[$tag])) { //New Key
                $current[$tag] = $result;
                $repeated_tag_index[$tag.'_'.$level] = 1;
                if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;

            } else { //If taken, put all things inside a list(array)
                if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...

                    // ...push the new element into that array.
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;

                    if($priority == 'tag' and $get_attributes and $attributes_data) {
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                    }
                    $repeated_tag_index[$tag.'_'.$level]++;

                } else { //If it is not an array...
                    $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
                    $repeated_tag_index[$tag.'_'.$level] = 1;
                    if($priority == 'tag' and $get_attributes) {
                        if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well

                            $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                            unset($current[$tag.'_attr']);
                        }

                        if($attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                        }
                    }
                    $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
                }
            }

        } elseif($type == 'close') { //End of tag '</tag>'
            $current = &$parent[$level-1];
        }
    }

    return($xml_array);
}
?>
