<?php
class MyCheckcode extends JieqiObject{
private $charset = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789';
private $code;
private $codelen = 4;
private $width = 110;
private $height = 40;
private $spacex = 6;
private $spacey = 2;
private $spacem = 1;
private $img;
private $font;
private $fontsize = 20;
private $fontcolor;
public function __construct() {
$this->font = JIEQI_ROOT_PATH.'/t1.ttf';
}
private function createCode() {
$_len = strlen($this->charset)-1;
for ($i=0;$i<$this->codelen;$i++) {
$this->code .= $this->charset[mt_rand(0,$_len)];
}
}
private function createBg() {
$this->img = imagecreatetruecolor($this->width,$this->height);
$color = imagecolorallocate($this->img,mt_rand(157,255),mt_rand(157,255),mt_rand(157,255));
imagefill($this->img,0,0,$color);
}
private function createFont() {
$_x = $this->width / $this->codelen;
for ($i=0;$i<$this->codelen;$i++) {
$this->fontcolor = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
$fontsize = mt_rand($this->fontsize,$this->fontsize+6);
imagettftext($this->img,$fontsize,mt_rand(-35,35),$_x*$i+mt_rand(1,5),$this->height / 1.2,$this->fontcolor,$this->font,$this->code[$i]);
}
}
private function createLine() {
for ($i=0;$i<10;$i++) {
$color = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
imageline($this->img,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$color);
}
for ($i=0;$i<100;$i++) {
$color = imagecolorallocate($this->img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
imagestring($this->img,mt_rand(1,5),mt_rand(0,$this->width),mt_rand(0,$this->height),'*',$color);
}
$rand = mt_rand(5,30);
$rand1 = mt_rand(15,25);
$rand2 = mt_rand(5,10);
$white = imagecolorallocate($this->img,mt_rand(0,100),mt_rand(0,100),mt_rand(0,100));
for ($yy=$rand;$yy<=+$rand+6;$yy++){
for ($px=-80;$px<=80;$px=$px+1)
{
$x=$px/$rand1;
if ($x!=0)
{
$y=sin($x);
}
$py=$y*$rand2;
imagesetpixel($this->img,$px+80,$py+$yy,$white);
}
}
}
private function outPut() {
header('Content-type:image/png');
imagepng($this->img);
imagedestroy($this->img);
}
public function doimg() {
$this->createBg();
$this->createCode();
$this->createLine();
$this->createFont();
$this->outPut();
}
public function getCode() {
return strtolower($this->code);
}
}
