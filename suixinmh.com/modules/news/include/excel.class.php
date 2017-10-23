<?php
/**
* excel导出类
*
* 使用方法
$excel=new Excel();
* //设置编码：
*$excel->setEncode("utf-8","gb2312"); //如果不转码，参数写一样即可，例如$excel->setEncode("utf-8","utf-8");
* //设置标题栏
* $titlearr=array("a","b","c","d");
* //设置内容栏
* $contentarr=array(
* 1=>array("ab","ac","ad","ae"),
* 2=>array("abc","acc","adc","aec"),
* 3=>array("abd","acd","add","aed"),
* 4=>array("abe","ace","ade","aee"),
* );
* $excel->getExcel($titlearr,$contentarr,"abc");
*/
/*$excel=new Excel();
 //设置编码：
$excel->setEncode("utf-8","gb2312"); //如果不转码，参数写一样即可，例如$excel->setEncode("utf-8","utf-8");
//设置标题栏
$titlearr=array("a","b","c","d");
//设置内容栏
$contentarr=array(
1=>array("ab","ac","ad","ae"),
2=>array("abc","acc","adc","aec"),
3=>array("abd","acd","add","aed"),
4=>array("abe","ace","ade","aee"),
);
$excel->getExcel($titlearr,$contentarr,"abc");*/
class Excel {
	var $inEncode; //一般是页面编码
 
	var $outEncode; //一般是Excel文件的编码
 
	function __construct(){
 
	}
	/**
	*设置编码
	*/
	function setEncode($incode,$outcode){
		$this->inEncode=$incode;
 
		$this->outEncode=$outcode;
	}
	/**
	*设置Excel的标题栏
	*/
	function setTitle($titlearr){
		$title="";
		foreach($titlearr as $v){
			if($this->inEncode!=$this->outEncode){
				$title.=iconv($this->inEncode,$this->outEncode,$v)."\t";
			}
			else{
				$title.=$v."\t";
			}
		}
		$title.="\n";
		return $title;
	}
	/**
	*设置Excel内容
	*/
	function setRow($array){
		$content="";
		foreach($array as $k => $v){
			foreach($v as $vs){
				if($this->inEncode!=$this->outEncode){
					$content.=iconv($this->inEncode,$this->outEncode,$vs)."\t";
				}
				else{
					$content.=$vs."\t";
				}
			}
			$content.="\n";
		}
		return $content;
	}
	/**
	*生成并自动下载Excel
	* $titlearr 标题栏数组
	* $array 内容数组
	* $filename 文件名称 (为空，已当前日期为名称)
	*/
	function getExcel($titlearr,$array,$filename=''){
		if($filename==''){
			$filename=date("Y-m-d");
		}
		$title=$this->setTitle($titlearr);
		$content=$this->setRow($array);
		$p_new_lines = array("\r\n", "\n", "\r","\r\n", "<pre>","</pre>","<br>","</br>","<br/>"); 
		$p_change_line_in_excel_cell = '<br style="mso-data-placement:same-cell;" />';
		//$content = str_replace( $p_new_lines,$p_change_line_in_excel_cell,$content);
		header("Content-type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename=".$filename.".xls");
		echo $title;
		echo $content;
	}
}
?>