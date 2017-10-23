<?php
/**
 * 批量上传文章类
 * @author huliming  2015-04-21
 *
 */
class MyPostbook extends JieqiObject {

     public $fh;           //文件操作的句柄
	 public $article;      //存放文章内容的容器
	 public $ending = '\n';//段落结束的句柄
	 public $volumestart = '@@@';//分卷的标记
	 public $chapterstart = '###';//章节的标记
	 //初始化参数
	 public function init($filename){
		 if(is_file($filename)) $this->fp = fopen($filename, 'r'); //文件 
		 else return false;
	 }
	 
	 //加载文章内容
	 public function getArticles(){
	     include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
		 $line = 1;
		 $i = 0;
		 while (!feof($this->fp)) {
		     $temp = stream_get_line($this->fp, 65535, "\n");
		     $tempstr = trim(is_utf8($temp) ? jieqi_utf82gb($temp) : $temp); 
			 if(!$tempstr) continue;
			 else{
			     if($line==1) $this->article['articlename'] = str_replace('书名：','',$tempstr);
				 elseif($line==2) $this->article['author'] = str_replace('作者：','',$tempstr);
				 elseif($line==3) $this->article['intro'] = str_replace('简介：','',$tempstr);
				 else{//处理章节
				     if(strpos($tempstr, $this->volumestart) === 0){
					    $chaptername = str_replace($this->volumestart,'',$tempstr);
						if(!$chaptername) continue;
					    $i++;
					    $this->article['chapters'][$i]['chaptername'] = $chaptername;
						$this->article['chapters'][$i]['chaptertype'] = 1;
					 }elseif(strpos($tempstr, $this->chapterstart) === 0){
					    $chaptername = str_replace($this->chapterstart,'',$tempstr);
						if(!$chaptername) continue;
					    $i++;
					    $this->article['chapters'][$i]['chaptername'] = $chaptername;
						$this->article['chapters'][$i]['chaptertype'] = 0;
					 }else{
					    if(!isset($this->article['chapters'][$i]['chaptername'])){//针对简介可能的换行
						    $this->article['intro'].= "\r\n".$tempstr;
						}else{
							if($this->article['chapters'][$i]['chaptercontent']){
								$this->article['chapters'][$i]['chaptercontent'].= "\r\n".$tempstr;
							}else $this->article['chapters'][$i]['chaptercontent'].= $tempstr;
						}
					 }
				 }
			 }
			 $line++;
		 }
		 return $this->article;
	 }
}
?>