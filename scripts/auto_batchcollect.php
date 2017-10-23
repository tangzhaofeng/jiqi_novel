<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 15/12/10
 * Time: 上午1:20
 */
function login($username,$password)
{
    $cookie_file = dirname(__FILE__) . '/cookie.txt';
    $url = "http://www.ishufun.net/web_admin/?controller=login&method=login&do=submit&jumpurl=http%3A%2F%www.ishufun.net%2Fadmin%2Findex.php";
    $post_data = array("username" => $username, "password" => $password, "formhash" => "0b9d7830");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    curl_setopt($ch, CURLOPT_POST, 1);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    $output = curl_exec($ch);
    curl_close($ch);
}

function batchcollect($url) {
    $cookie_file = dirname(__FILE__) . '/cookie.txt';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file); //使用上面获取的cookies
    $response = curl_exec($ch);
    echo $response."\n\n";
    curl_close($ch);
}

login("admin","196002LYHblue");
//9库
$url="http://www.ishufun.net/modules/article/web_admin/?controller=batchcollect&action=pagecollect&siteid=4&collectname=0&startpageid=Array&maxpagenum=1&collectpagenum=1&notaddnew=0&formhash=da4c08f8";
batchcollect($url);
