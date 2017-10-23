<?php
include_once(dirname(__FILE__)."/../../global.php");
include_once(dirname(__FILE__)."/../../core/lib/lib_database.php");
include_once(dirname(__FILE__)."/../../modules/article/lib/my_article.php");
$db=new Database();
$lib_article = new MyArticle();

if (!$db) {
    die("db error\n");
}



$articlelist_url = "http://tools.ishufun.net/fetch_yyd.php?action=booklist";
$str=file_get_contents($articlelist_url);
$tmparr = xml2array($str);
$booklist=$tmparr['booklist']['bookid'];



foreach($booklist as $bookid) {
    $articleinfo_url = "http://tools.ishufun.net/fetch_yyd.php?action=bookinfo&bookid=$bookid";
    $str=file_get_contents($articleinfo_url);
    $tmparr1 = xml2array($str);
    $bookinfo=$tmparr1['bookinfo'];
    $bookname=$bookinfo['bookname'];


    $chapterlist_url = "http://tools.ishufun.net/fetch_yyd.php?action=catlog&bookid=$bookid";
    $str=file_get_contents($chapterlist_url);
    $tmparr1 = xml2array($str);

    $db->init("article","articleid","article");
    $db->setCriteria(new Criteria('oldaid',$bookid));
    $db->criteria->add(new Criteria('firstflag',7));
    $res = $db->queryObjects();

    if ($row = $db->getRow($res)) {
        $articleid=$row['articleid'];
        $author=$bookinfo['authorname'];
        $size=round($row['size']/2);
        $size=round($size/10000)."w";
        $url="http://m.eshuku.com/read/$articleid.htm";
        echo "$bookid\t$bookname\t$author\t$size\t$url\n";
        continue;

        //echo "articleid=$articleid\n";
    }
    else {
        echo "article not found $bookid\n";
        continue;
    }
    //continue;
    $volarr=$tmparr1['chapterinfo']['volume'];
    if (is_array($volarr)) {
        foreach ($volarr as $v) {
            if (is_array($v)) {
                foreach ($v['chapters']['chapter'] as $chapter) {
                    $chapterid = $chapter['chapterid'];
                    $chapter_url = "http://tools.ishufun.net/fetch_yyd.php?action=chapter&bookid=$bookid&chapterid=$chapterid";
                    $str = file_get_contents($chapter_url);
                    $tmparr1 = xml2array($str);

                    $chapterinfo = $tmparr1['contentinfo'];
                    $chaptername = iconv("utf-8", "gbk", trim($chapter['chaptername']));

                    $isvip = $chapterinfo['needPay'];
                    if ($isvip) {
                        $db->init("chapter", "chapterid", "article");
                        $db->setCriteria(new Criteria('chaptername', $chaptername));
                        $db->criteria->add(new Criteria('articleid', $articleid));
                        $res = $db->queryObjects();
                        if ($row = $db->getRow($res)) {
                            $cid = $row['chapterid'];
                            $price = round($row['size']/2*0.005);
                            $orderid = $row['chapterorder'];
                            $sql = "update jieqi_article_chapter set isvip=$isvip,saleprice=$price  where articleid=$articleid and chapterid = $cid\n";
                            echo $sql . "\n";
                            $db->query($sql);

                            //$sql="update jieqi_article_article set "
                            //break 2;
                        } else {
                            echo "chapter not found\n";
                        }
                    }
                }

                //echo "$bookid,$chapterid,$isvip\n";

                //print_r($tmparr1);
                //exit();

            } else {
                //print_r($v);
            }
        }
    }
    else {
        print_r($tmparr1);
    }
    $res=$db->query("select * from jieqi_article_chapter where articleid=$articleid and display=0 order by chapterorder desc limit 1");
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