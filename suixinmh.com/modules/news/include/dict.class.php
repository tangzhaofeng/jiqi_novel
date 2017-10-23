<?php
/*
    *Í¨ÓÃÄÚÈÝ·Ö´ÊÀà
	[Cms News] (C) 2010-2012 Cms Inc.
	$Id: dict.class.php 12398 2010-06-25 11:26:38Z huliming QQ329222795 $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}
class Dict
{
	var $dicfile='';
	var $TagDic = Array();
	var $OneNameDic = Array();
	var $TwoNameDic = Array();
	var $HashDic=array();
	var $Result= array();
	var $InputString = '';
	var $SplitLen = 4; //±£Áô´Ê³¤¶È
	var $EspecialChar = "ºÍ|µÄ|ÊÇ|ÔÚ";
	var $NewWordLimit = "ÔÚ|µÄ|Óë|»ò|¾Í|Äã|ÎÒ|Ëû|Ëý|ÓÐ|ÁË|ÊÇ|Æä|ÄÜ|¶Ô|µØ";
	var $CommonUnit = "Äê|ÔÂ|ÈÕ|Ê±|·Ö|Ãë|µã|Ôª|°Ù|Ç§|Íò|ÒÚ|Î»|Á¾|Ìõ|¸ö";
	var $pchar=0;
	var $CnNumber=array("£°","£±","£²","£³","£´","£µ","£¶","£·","£¸","£¹","£«","£­","£ª","£¥","£®","£½","£¯","£Û","£Ý","£û","£ý","£¨","£©","¡«","¢ñ","¢ò","¢ó","¢ô","¢õ","¢ö","¢÷","¢ø","¢ù","£¤");

	//¹ýÂË×Ö·û
	var $TrimChars=array("¡ø","¡÷","¨‹","¨Œ","¡ï","¡î","¡ô","¡ó","¡ö","¡õ","¡ñ","¡ð","¡Ñ","©I","¡ò","¨y","¨x","¨z","¨{","¨|","¨}","¨~","¨€","¨‡","¨†","¨…","¨„","¨ƒ","¨‚","¨","¨Ž","¨","¨","¡ø","¨‹","¡â","¡á","…d","¡ù");

	var $fnums = "0123456789+-*%.=/[]{}()~123456789\$";
	var $CnSgNum = "0|1|2|3|4|5|6|7|8|9|Áã|Ò»|¶þ|Èý|ËÄ|Îå|Áù|Æß|°Ë|¾Å|Ê®|°Ù|Ç§|Íò|ÒÚ|Êý";
	var $MaxLen = 13;
	var $MinLen = 3;
	var $CnTwoName = "¶ËÄ¾ ÄÏ¹¬ ÚÛóÎ ÐùÔ¯ Áîºü ÖÓÀë ãÌÇð ³¤Ëï ÏÊÓÚ ÓîÎÄ Ë¾Í½ Ë¾¿Õ ÉÏ¹Ù Å·Ñô ¹«Ëï Î÷ÃÅ ¶«ÃÅ ×óÇð ¶«¹ù ºôÑÓ Ä½ÈÝ Ë¾Âí ÏÄºî Öî¸ð ¶«·½ ºÕÁ¬ »Ê¸¦ Î¾³Ù ÉêÍÀ";
	var $CnOneName = "ÕÔÇ®ËïÀîÖÜÎâÖ£Íõ·ë³ÂñÒÎÀ½¯Éòº«ÑîÖìÇØÓÈÐíºÎÂÀÊ©ÕÅ¿×²ÜÑÏ»ª½ðÎºÌÕ½ªÆÝÐ»×ÞÓ÷°ØË®ñ¼ÕÂÔÆËÕÅË¸ðÞÉ·¶ÅíÀÉÂ³Î¤²ýÂíÃç·ï»¨·½ÓáÈÎÔ¬ÁøÛº±«Ê·ÌÆ·ÑÁ®á¯Ñ¦À×ºØÄßÌÀëøÒóÂÞ±ÏºÂÚù°²³£ÀÖÓÚÊ±¸µÆ¤¿¨Æë¿µÎéÓàÔª²·¹ËÃÏÆ½»ÆÄÂÏôÒüÒ¦ÉÛ¿°ÍôÆîÃ«ÓíµÒÃ×±´Ã÷ê°¼Æ·ü³É´÷Ì¸ËÎÃ©ÅÓÐÜ¼ÍÊæÇüÏî×£¶­Á»¶ÅÈîÀ¶ãÉÏ¯¼¾ÂéÇ¿¼ÖÂ·Â¦Î£½­Í¯ÑÕ¹ùÃ·Ê¢ÁÖµóÖÓÐìÇñÂæ¸ßÏÄ²ÌÌï·®ºúÁè»ôÓÝÍòÖ§¿Â¾Ì¹ÜÂ¬Äª¾­·¿ôÃçÑ¸É½âÓ¦×ÚÐû¶¡êÚµËÓôµ¥º¼ºé°üÖî×óÊ¯´Þ¼ªÅ¥¹¨³ÌïúÐÏ»¬ÅáÂ½ÈÙÎÌÜ÷Ñòì¶»ÝÕçÎº¼Ó·âÜÇôà´¢½ù¼³ÚûÃÓËÉ¾®¶Î¸»Î×ÎÚ½¹°Í¹­ÄÁÚó¹È³µºîåµÅîÈ«Û­°àÑöÇïÖÙÒÁ¹¬Äþ³ðèï±©¸Êî×À÷ÈÖ×æÎä·ûÁõ½ªÕ²ÊøÁúÒ¶ÐÒË¾ÉØÛ¬Àè¼»±¡Ó¡ËÞ°×»³ÆÑÌ¨´Ó¶õË÷ÏÌ¼®Àµ×¿ÝþÍÀÃÉ³ØÇÇÒõÓôñãÄÜ²ÔË«ÎÅÝ·µ³µÔÌ·¹±ÀÍåÌ¼§Éê·ö¶ÂÈ½Ô×ÛªÓºàSè³É£¹ðå§Å£ÊÙÍ¨±ßìèÑà¼½Û£ÆÖÉÐÅ©ÎÂ±ð×¯êÌ²ñµÔÑÖ³äÄ½Á¬ÈãÏ°»Â°¬ÓãÈÝÏò¹ÅÒ×É÷¸êÁÎ¸ýÖÕôß¾Óºâ²½¶¼¹¢Âúºë¿ï¹úÎÄ¿Ü¹ãÂ»ãÚ¶«Å¹ì¯ÎÖÀûÎµÔ½ÙçÂ¡Ê¦¹®ØÇÄôêË¹´°½ÈÚÀäö¤ÐÁãÛÄÇ¼òÈÄ¿ÕÔøÉ³Ðë·á³²¹ØØáÏà²éºó½­ÓÎóÃ";
	function Dict($dicfile=''){
		$this->__construct($dicfile);
	}
	function __construct($dicfile=''){
		for($i=0;$i<strlen($this->CnOneName);$i++){
			$this->OneNameDic[$this->CnOneName[$i].$this->CnOneName[$i+1]] = 1;
			$i++;
		};
		$twoname = explode(" ",$this->CnTwoName);
		foreach($twoname as $n){ $this->TwoNameDic[$n] = 1; }
		unset($twoname,$this->CnOneName,$this->CnTwoName);
		if(!file_exists($dicfile))
		$dicfile = dirname(__FILE__)."/DICT_GBK.dat";
		$this->dicfile=$dicfile;
		$fp= fopen($dicfile,'rb');
		$i=0;
		while($this->HashDic[$i++]=fread($fp,65536));
		@fclose($fp);
	}

	function word_hash($word){
		$i=0;
		$c=$t='';
		$hashcode=$pincode=1;
		while($c=ord($word[$i++])){
			if($c&0x80){
				$t=ord($word[$i++]);
				$hashcode*=((($c&0x7F)<<8)|$t);
				$pincode*=$t;
			}else{
				$hashcode*=$c;
				$pincode*=$c;
			}
			$hashcode=abs($hashcode)%261223;
			$pincode=abs($pincode)%8285839;
		};
		if($hashcode<0)$hashcode=abs($hashcode)%261223;
		if($pincode<0)	$pincode=abs($pincode)%8285839;
		$hashcode+=47;
		$pincode++;
		return array('hash_pos'=>$hashcode*3,'pincode'=>$pincode);
	}

	function Clear(){
		unset($this->HashDic);
	}

	function mmSegWord($str="",$Method=0){
		$this->Result=array();
		$this->PChar=-1;
		if($str!="") $this->SetSource(trim($str));
		if($this->InputString=="") return "";
		$this->InputString = $this->InitString($this->InputString);
		$spwords = explode(" ",$this->InputString);
		$spLen = sizeof($spwords);
		for($i=0;$i<$spLen;$i++){
			if(trim($spwords[$i])=="") continue;
			if(!($oc=ord($spwords[$i][0])&0x80)){
				if($oc<43 || $oc>57|| $oc==44 ||$oc==47){
					$this->Result[++$this->PChar]= $spwords[$i];
				}else{
					$nextword = "";
					@$nextword = substr($this->ResultString,0,strpos($this->ResultString," "));
					if(ereg("^".$this->CommonUnit,$nextword)){
						$this->Result[$this->PChar].= $spwords[$i];
					}else{
						$this->Result[++$this->PChar]= $spwords[$i];
					}
				}
			}else{
				$c = $spwords[$i][0].$spwords[$i][1];
				$n = hexdec(bin2hex($c));
				if($c=="¡¶"||($n>0xA13F && $n < 0xAA40)){
					$this->Result[++$this->PChar]= $spwords[$i];
				}else{
					if(strlen($spwords[$i]) <= $this->SplitLen){
						if(ereg($this->EspecialChar."$",$spwords[$i],$regs)){
							$spwords[$i] = ereg_replace($regs[0]."$","",$spwords[$i]).$regs[0];
						}
						if(!ereg("^".$this->CommonUnit,$spwords[$i]) || $i==0){
							$this->Result[++$this->PChar]= $spwords[$i];
						}elseif($i!=0){
							$this->Result[$this->PChar].= $spwords[$i];
						}
					}else{
						if ($Method==0){
							//ÕýÏò×î´óÆ¥ÅäËã·¨
							$this->Seg_MM($spwords[$i]);
						}elseif ($Method==1){
							//ÕýÏò×îÐ¡Æ¥ÅäËã·¨
							$this->Seg_NM($spwords[$i]);
						}
					}
				}
			}
		}
		return $this->Result;
	}

	function Seg_MM($str){
		$slen=strlen($str);
		$maxpos= $slen-$this->MinLen-1;
		$WordArray = Array();
		for($i=0;$i<$slen;)
		{
			if($i>=$maxpos){
				if($this->MinLen==1){
					$WordArray[] = substr($str,$maxpos,2);
				}else{
					$w = substr($str,$i,$this->MinLen+1);
					if($this->IsWord($w)){
						$WordArray[] = $w;
					}else{
						while($i<=$slen-2){
							$WordArray[] = substr($str,$i,2);
							$i+=2;
						}
					}
				}
				$i = $slen; break;
			}
			$maxlenght=$this->MaxLen+1>$slen-$i?$slen-$i:$this->MaxLen+1;
			for($j=$maxlenght;$j>=$this->MinLen+1;$j=$j-2){
				$w = substr($str,$i,$j);
				if($this->IsWord($w)){
					$WordArray[] = $w;
					$i +=$j;
					break;
				}
			}
			if($j<$this->MinLen+1){
				$WordArray[] = $str[$i].$str[$i+1];
				$i += 2;
			}
		}
		$this->MatchOther($WordArray);
		return;
	}

	function nmSegWord($str=""){
		return $this->mmSegWord($str,1);
	}


	function Seg_NM($str){
		$slen=strlen($str);
		$maxpos= $slen-$this->MinLen-1;
		$WordArray = Array();
		for($i=0;$i<$slen;)
		{
			if($i>=$maxpos){
				if($this->MinLen==1){
					$WordArray[] = substr($str,$maxpos,2);
				}else{
					$w = substr($str,$i,$this->MinLen+1);
					if($this->IsWord($w)){
						$WordArray[] = $w;
					}else{
						while($i<=$slen-2){
							$WordArray[] = substr($str,$i,2);
							$i+=2;
						}
					}
				}
				break;
			}
			$maxlenght=$this->MaxLen+1>$slen-$i?$slen-$i:$this->MaxLen+1;
			for($j=$this->MinLen+1;$j<=$maxlenght;$j+=2){
				$w = substr($str,$i,$j);
				if($this->IsWord($w)){
					$WordArray[] = $w;
					$i +=$j;
					break;
				}
			}
			if($j>$maxlenght){
				$WordArray[] = substr($str,$i,2);
				$i += 2;
			}
		}
		$this->MatchOther($WordArray);
		return;
	}

	function MatchOther($WordArray)
	{
		$wordcount=count($WordArray)-1;
		for($i=0;$i<=$wordcount;$i++)
		{
			$this->Result[++$this->PChar]=$WordArray[$i];
			if(ereg($this->CnSgNum,$WordArray[$i])){
				if($i<$wordcount&& ereg("^".$this->CommonUnit,$WordArray[$i+1]))
				{ $this->Result[$this->PChar].= $WordArray[++$i]; }
				else{
					while($i<=$wordcount && ereg($this->CnSgNum,$WordArray[$i+1]))
					{ $this->Result[$this->PChar].= $WordArray[++$i]; }
				}
				continue;
			}
		}
	}

	function IsWord($InputWord){
		static $IsWordArray=array();
		if(isset($IsWordArray[$InputWord]))return true;
		if(!$hash=&$this->word_hash($InputWord))return false;

		$hash_pos=$hash['hash_pos'];
		$HashDic=&$this->HashDic;
		$segment=$hash['hash_pos']>>16;
		$offset=$hash['hash_pos']&0xFFFF;

		$hash_pin_key=(ord($HashDic[$segment][$offset+2])<<16)|(ord($HashDic[$segment][$offset+1])<<8)|ord($HashDic[$segment][$offset]);

		if($hash['pincode']==$hash_pin_key){
			$IsWordArray[$InputWord]=1;
			return true;
		}elseif($hash_pin_key&0x800000){
			$offsetpos=0x7FFFFF&$hash_pin_key;
			do{
				$segment=$offsetpos>>16;
				$offset=$offsetpos&0xFFFF;
				$hash_pin_code=(ord($HashDic[$segment][$offset+2])<<16)|(ord($HashDic[$segment][$offset+1])<<8)|ord($HashDic[$segment][$offset]);$offset+=3;
				if(($hash_pin_code&0x7FFFFF)==$hash['pincode']){
					$IsWordArray[$InputWord]=1;
					return true;
				}
				if($offset>=65536){
					$offset-=65536;
					$segment++;
				}
			}while(($hash_pin_code&0x800000)&&($offsetpos=(ord($HashDic[$segment][$offset+2])<<16)|(ord($HashDic[$segment][$offset+1])<<8)|ord($HashDic[$segment][$offset])));
		}
		return false;
	}

	function InitString($str){
		$spc =' ';
		$slen = strlen($str);
		if($slen==0) return '';
		$okstr = '';
		$oc=$i=0;
		$prechar = 0;
		while($oc=ord($str[$i])){
			if($oc < 0x81){
				if($oc < 33){
					if($prechar!=0&&$oc!=13&&$str[$i]!=10) $okstr .= $spc;
					$prechar=0;
					$i++;
					continue;
				}else if(($oc!=44)&&($oc<42 ||$oc>58)&&($oc<64 ||$oc>90)&&($oc<67 ||$oc>70)&&($oc<97 ||$oc>122)&&$oc!=95){
					if($prechar==0)
					{	$okstr .= $str[$i]; $prechar=3;}
					else
					{ $okstr .= $spc.$str[$i]; $prechar=3;}
				}else{
					if($prechar==2||$prechar==3)
					{ $okstr .= $spc.$str[$i]; $prechar=1;}
					else
					{
						$okstr .= $str[$i];
						$prechar=1;
						if($oc==58||$oc==67||$oc==69){$prechar=3; }
						else { $prechar=1; }
					}
				}
			}else{
				if($prechar!=0 && $prechar!=2) $okstr .= $spc;
				if(isset($str[$i+1])){
					$c = $str[$i].$str[$i+1];
					if(false!==$idx=array_search($c,$this->CnNumber))
					{ $okstr .= $this->fnums[$idx]; $prechar = 2; $i+=2; continue; }
					elseif(false!==array_search($c,$this->TrimChars)){
						$i+=2; continue;
					}
					$n = hexdec(bin2hex($c));
					if($n>0xA13F && $n < 0xAA40)
					{
						if($c=="¡¶"){
							if($prechar!=0) $okstr .= $spc." ¡¶";
							else $okstr .= " ¡¶";
							$prechar = 2;
						}
						else if($c=="¡·"){
							$okstr .= "¡· ";
							$prechar = 3;
						}
						else{
							if($prechar!=0) $okstr .= $spc.$c;
							else $okstr .= $c;
							$prechar = 3;
						}
					}
					else{
						$okstr .= $c;
						$prechar = 2;
					}
					$i++;
				}
			}
			$i++;
		}
		return $okstr;
	}

	function SetSource($str){
		$this->InputString = trim($this->InitString($str));
		$this->ResultString = "";
	}
}//End Class
?>