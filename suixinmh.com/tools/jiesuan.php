<?php
$conlist = array(
    461707,
30239,
412861,
149982,
306121,
649280,
3049,
625215,
1357290,
12333,
3800676,
1242,
13890,
469647,
127573,
4709,
3749290,
6305,
1608746,
1331,
11038,
2371,
7758,
7406877,
6920656
);
$sum=0;
foreach($conlist as $coin) {
    $sum+=$coin;
}

$total_pay=$argv[1];

foreach($conlist as $coin) {
    $pay=round($coin/$sum*$total_pay);
    echo $pay."\n";
}
