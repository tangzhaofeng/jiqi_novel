<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/2/24
 * Time: 上午1:40
 */
include("../global.php");
session_destroy();
header("location:/user/logout");