var Default_isFT = 0		//Ä¬ÈÏÊÇ·ñ·±Ìå£¬0-¼òÌå£¬1-·±Ìå
var StranIt_Delay = 20 //·­ÒëÑÓÊ±ºÁÃë£¨ÉèÕâ¸öµÄÄ¿µÄÊÇÈÃÍøÒ³ÏÈÁ÷³©µÄÏÔÏÖ³öÀ´£©

//£­£­£­£­£­£­£­´úÂë¿ªÊ¼£¬ÒÔÏÂ±ð¸Ä£­£­£­£­£­£­£­
//×ª»»ÎÄ±¾
function StranText(txt,toFT,chgTxt)
{
	if(txt==""||txt==null)return ""
	toFT=toFT==null?BodyIsFt:toFT
	if(chgTxt)txt=txt.replace((toFT?"¼ò":"·±"),(toFT?"·±":"¼ò"))
	if(toFT){return Traditionalized(txt)}
	else {return Simplized(txt)}
}
//×ª»»¶ÔÏó£¬Ê¹ÓÃµÝ¹é£¬Öð²ã°þµ½ÎÄ±¾
function StranBody(fobj)
{
	
	if(typeof(fobj)=="object"){var obj=fobj.childNodes}
	else 
	{
		var tmptxt=StranLink_Obj.innerHTML.toString()
		
		if(tmptxt.indexOf("¼ò")<0)
		{
			BodyIsFt=1
			StranLink_Obj.innerHTML=StranText(tmptxt,0,1)
			StranLink.title=StranText(StranLink.title,0,1)
		}
		else
		{
			BodyIsFt=0
			StranLink_Obj.innerHTML=StranText(tmptxt,1,1)
			StranLink.title=StranText(StranLink.title,1,1)
		}
		setCookie(JF_cn,BodyIsFt,7)
		var obj=document.body.childNodes
	}
	for(var i=0;i<obj.length;i++)
	{
		var OO=obj.item(i)
		if("||BR|HR|TEXTAREA|".indexOf("|"+OO.tagName+"|")>0||OO==StranLink_Obj)continue;
		if(OO.title!=""&&OO.title!=null)OO.title=StranText(OO.title);
		if(OO.alt!=""&&OO.alt!=null)OO.alt=StranText(OO.alt);
		if(OO.tagName=="INPUT"&&OO.value!=""&&OO.type!="text"&&OO.type!="hidden")OO.value=StranText(OO.value);
		if(OO.nodeType==3){OO.data=StranText(OO.data)}
		else StranBody(OO)
	}
}
function JTPYStr()
{
	return 'ÍòÓë×¨Òµ´Ô¶«Ë¿¶ªÁ½ÑÏÉ¥¸öÁÙÎªÀö¾ÙÒåÎÚÀÖÇÇÏ°ÏçÊéÂòÂÒÕù¿÷Ø¨ÑÇ²úÄ¶Ç×ÙôÒÚ½ö´ÓÂØ²ÖÒÇÃÇ¼ÛÖÚÓÅ»áØñÉ¡Î°´«ÉËØöÂ×Ø÷Î±ØùÌåÙÝÏÀÂÂ½ÄÕì²àÇÈ¿ëÙ­Ù¯Ù¶Ù±Ù²Á©Ù³¼óÕ®ÇãÙÌÙÍÙÇ³¥ÙÎÙÏ´¢ÙÐ¶ù¶ÒÙðµ³À¼¹ØÐË×ÈÑøÊÞÙæÄÚ¸Ô²áÐ´¾üÅ©·ë³å¾ö¿ö¶³¾»ÆàÁ¹¼õ´ÕÁÝ·ïÙìÆ¾¿­»÷ÔäÛ»ÁõÔò¸Õ´´É¾±ðØÙÉ²¹ôØÛØÜ¼Á¹Ð½£°þ¾çÈ°°ìÎñÛ½¶¯Àø¾¢ÀÍÊÆÑ«ÔÈØÐØÑÇøÒ½»ªÐ­µ¥ÂôÂ¬Â±ÎÔÎÀÈ´Úá³§ÌüÀúÀ÷Ñ¹ÑáØÇ²ÞÏáØÉÏÃ³ø¾ÇÏØÈþ²ÎË«·¢±äÐðµþÒ¶ºÅÌ¾ß´ÏÅÂÀÂð¶ÖÌýÆôÎâÄÅß¼ß½Å»ß¿ßÂÔ±ßÃÇºÎØÓ½ÁüßÌÏìÑÆßÕßØßÙßÜ»©ßàßâßæÓ´ßéßëßï»½ßõØÄßùÄöÐ¥Åçà¶à·à¿àÈÐêàÓÖöààÏùÍÅÔ°´ÑÎ§àð¹úÍ¼Ô²Ê¥ÛÛ³¡»µ¿é¼áÌ³ÛÞ°ÓÎë·Ø×¹Â¢ÛäÀÝ¿ÑÛÑµæÛëÛîÛõÛ÷ÛöÇµ¶éÇ½×³Éù¿Çºø´¦±¸¸´¹»Í·¼Ð¶áÞÆÛ¼·Ü½±°Â×±¸¾Âèåüåýæ£æ©Â¦æ«æ¬½¿æ®Óéæ´æµÓ¤æ¿ÉôæÁæÈæÉæÍæÖËïÑ§ÂÏÄþ±¦Êµ³èÉóÏÜ¹¬¿í±öÇÞ¶ÔÑ°µ¼ÊÙ½«¶û³¾³¢Ò¢ÞÏ¾¡²ãÌë½ìÊôÂÅåðÓìËêÆñá«¸Úá­á°µºÁë¿ùá»Ï¿á½á¿ÂÍáÀáÁÕ¸áÉáÎáÐáÛ¹®ÛÏ±ÒË§Ê¦àøÕÊÖÄ´øÖ¡°ïàüàýàþÃÝ¹ãÇìÂ®âÐ¿âÓ¦ÃíÅÓ·ÏâÞ¿ªÒìÆúß±ÕÅÃÖåòÍäµ¯Ç¿¹éµ±Â¼Ñå³¹¾¶áâÒäâãÓÇâé»³Ì¬ËËâäâæâêâëÁ¯×Üí¡âøÁµºã¿Ò¶ñâúâûâýâüÄÕã¢ÔÃí¨Ðüã¥Ãõ¾ª¾å²Ò³Í±¹ã«²Ñµ¬¹ßã³·ßã´Éåí¯ÀÁãÁí°ê§Ï·ê¨Õ½ê¯»§Ö´À©ÞÑÉ¨ÑïÈÅ¸§Å×ÞÒ¿ÙÂÕÇÀ»¤±¨µ£ÄâÂ£¼ðÓµÀ¹Å¡²¦ÔñÖ¿ÂÎÎÎÌ¢Ð®ÄÓµ²ÞØÕõ¼·»ÓÀÌËð¼ñ»»µ·¾ÝÂ°ÞâÖÀµ§²ôÞèÀ¿Þì²ó¸éÂ§½ÁÐ¯ÉãÞó°ÚÒ¡±÷Ì¯Þü³ÅÄìß¢ß£ß¥ËÓÔÜµÐÁ²ÊýÕ«ìµÕ¶¶ÏÎÞ¾ÉÊ±¿õê¼ÖçÏÔ½úÏþêÊÔÎêÍÔÝêÓÊõ»úÉ±ÔÓÈ¨ÌõÀ´Ñîè¿¼«¹¹èÈÊàÔæèÀèÇÇ¹·ãèÉÄûèßèÙÕ¤±êÕ»èÎèÐ¶°èÓèÝÀ¸Ê÷ÆÜÑùèïèâèãèåµµèçÇÅèëèí½°×®ÃÎ¼ìèùé¤èüèýé¡ÍÖÂ¥é­é´éµé·¼÷éÄéÆºáéÉÓ£éÍ³÷éÖéÚéÝ»¶ì£Å·¼ßéâéä²ÐéæéçéééëÅ¹»Ùì±±Ï±ÐÕ±ë§ëªÆøÇâë²ëµÙÛ»ãººÌÀÐÚ¹µÃ»ããÅ½Á¤ÂÙ²×ãí»¦Å¢Àáí´ãñãòãøÐºÆÃÔóãþ½àÈ÷ÍÝä¤Ç³½¬½½ä¥×Ç²âä«¼Ãä¯»ëä°Å¨ä±ÌÎÀÔäµÁ°ä¶ÎÐ»ÁµÓÈó½§ÕÇÉ¬µíÔ¨äË×ÕäÂ½¥äÅÓæäÉÉøÎÂÍåÊªÀ£½¦äÓää¹öÖÍäÙäÜÂúäÞÂËÀÄÂÐ±õÌ²äëäìäòÎ«Ç±äóÀ½äþ±ôå°ÃðµÆÁéÔÖ²Óì¾Â¯ìÀì¿ìÁµãÁ¶³ãË¸ÀÃÌþÖòÑÌ·³ÉÕìÇ»âÌÌ½ýÈÈ»ÀìËìâ°®Ò¯ë¹Ç£Îþ¶¿×´áîÓÌ±·Äü¶ÀÏÁÊ¨áöÕøÓüáøáýÁÔâ¨â¤ÖíÃ¨Ï×Ì¡çáÂêçâ»·ÏÖçôçå·©çççõçöËöÇíÑþè¨è¬è¶ê±µç»­³©î´³ëðÜÁÆÅ±ðÝÑñ´¯·èÓ¸¾·Ñ÷ðì»¾ðï±Ôð÷ðù±ñÌ±ñ«ñ¨ñ®Ñ¢ñ²°¨ÖåñäÕµÑÎ¼à¸ÇµÁÅÌÃÐ×ÅÕöíùíúØºÂ÷Öõ½Ãí¶·¯¿óí¸Âë×©íºÑâíÂíÃÀù´¡Ë¶íÌíÍÈ·¼ï°­íÓí×¼îíçÀñìòìõµ»»öÙ÷Â»ìøÀëÍº¸ÑÖÖÃØ»ý³Æ»àË°öÕÎÈð£ÇîÇÔÇÏÒ¤´ÜÎÑ¿úñ¼ñÀÊú¾ºóÆËñ±ÊóÈ¼ãÁýóÖóÙÉ¸óÝ³ïÇ©¼òóåóæóêÂáóìóïóñÂ¨ÀºÀéóýô¥ÙáÀàôÐôÏÔÁ·àÁ¸ôÖ½ôôê¾ÀæúºìæûÏËæüÔ¼¼¶æýæþ¼ÍÈÒÎ³ç¡´¿ç¢É´¸ÙÄÉ×ÝÂÚ·×Ö½ÎÆ·ÄÅ¦ç£Ïßç¤ç¥ç¦Á·×éÉðÏ¸Ö¯ÖÕç§°íç¨ç©ÉÜÒï¾­çª°óÈÞ½áÈÆç¬»æ¸øÑ¤ç­Âç¾ø½ÊÍ³ç®ç¯¾îÐåËçÌÐ¼Ìç°¼¨Ð÷ç±Ðøç²ç³´ÂçµÉþÎ¬Ãàç·±Á³ñç¸ç¹×ÛÕÀçºÂÌ×ºç»ç¼ç½¼êÃåÀÂç¾ç¿¼©çÀçÁç¶¶ÐçÂçÃçÄ»ºµÞÂÆ±àçÅÔµçÆ¸¿çÈçÇ·ìçÉ²øçÊçËçÌçÍçÎçÏçÐÓ§ËõçÑçÒçÓçÔÉÉçÕç×çØçÙ½ÉçÚó¿ÍøÂÞ·£°Õî¼î¿ôÇÏÛÇÌñïËÊ³ÜÄôÁûÖ°ñ÷Áªñù´ÏËà³¦·ô°¹ÉöÖ×ÕÍÐ²ëÐµ¨Ê¤ëÊëÍëÖ½ºÂöëÚÔàÆêÄÔÅ§Ùõ½ÅÍÑëáÁ³À°ÄåëðÌÚë÷Óßô¯½¢²Õôµ¼èÑÞÒÕ½ÚØÂÜ¼ÎßÂ«ÜÊÎ­ÜÂÜÈÜÉ²ÔÜÑËÕÆ»¾¥Ü×ÜàÜãÜä¼ë¾£¼ÔÜéÜêÜñÜöÜùµ´ÈÙ»çÜþÜýÓ«Ý¡Ý£Ý¥ÒñÝ¦Ò©Ý°À³Á«ÝªÝ«Ý²»ñÝµÓ¨ÝºÂÜÓ©ÓªÝÓÏôÈø´ÐÝÛÝÞ½¯ÝäÀ¶¼»ÝñÝ÷ÝöÝëÇ¾ÝüÝþ°ªÞ­ÔÌÞ´ÞºÂ²ÂÇÐéò°ËäÏºò²Ê´ÒÏÂì²Ïò¹¹ÆòÃòÉÂùÕÝòÌòÍòÏòÓÍÉÎÏÀ¯Ó¬òå²õÐ«ò÷òîó½ÐÆÏÎ²¹³ÄÙò°ÀôÁÍàÏ®×°ñÉñÍñÏ¿ãñÐñÚñÜ¼û¹Û¹æÃÙÊÓêèÀÀ¾õêéêêêëêìêíêîêïõü´¥ö£ÓþÌÜ¼Æ¶©¸¼ÈÏ¼¥Ú¦Ú§ÌÖÈÃÚ¨ÆýÑµÒéÑ¶¼Ç½²»äÚ©ÚªÑÈÚ«Ðí¶ïÂÛËÏ·íÉè·Ã¾÷Ö¤Ú¬Ú­ÆÀ×çÊ¶Õ©ËßÕïÚ®Öß´ÊÚ°Ú¯ÒëÚ±Ú²Ú³ÊÔÚ´Ê«ÚµÚ¶³ÏÖïÚ·»°µ®Ú¸Ú¹¹îÑ¯ÒèÚº¸ÃÏê²ïÚ»Ú¼½ëÎÜÓïÚ½ÎóÚ¾ÓÕ»åÚ¿ËµËÐÚÀÇëÖîÚÁÅµ¶ÁÚÂ·Ì¿ÎÚÃÚÄË­ÚÅµ÷ÚÆÁÂ×»ÚÇÌ¸ÒêÄ±ÚÈµý»ÑÚÉÐ³ÚÊÚËÎ½ÚÌÚÍÚÎ²÷ÚÑÚÏÑèÚÐÃÕÚÒÚÓÚÔÚÕÐ»Ò¥°ùÚÖÇ«Ú×½÷Ã¡ÚØÚÙÃýÌ·ÚÚÚÛÀ¾Æ×ÚÜÚÝÇ´ÚÞÚß±´Õê¸º¹±²ÆÔðÏÍ°ÜÕË»õÖÊ··Ì°Æ¶±á¹ºÖü¹á·¡¼úêÚêÛÌù¹óêÜ´ûÃ³·ÑºØêÝÔôêÞ¼Ö»ßêßÁÞÂ¸Ôß×ÊêàêáêäêâêãÉÞ¸³¶ÄÊêÉÍ´ÍâÙÅâêæÀµ×¸êç×¬ÈüØÓØÍÔÞÔùÉÄÓ®¸ÓÕÔ¸ÏÇ÷ôõõ»Ô¾õÄõÈ¼ùõÎõÏõÑõÒÓ»³ì×ÙõÙõÜõæõçõé´ÚõïõòÇû³µÔþ¹ìÐùéí×ªéîÂÖÈíºäéðéñÖáéòéóéôéöé÷ÇáéøÔØéù½Îéúéû½Ïéü¸¨Á¾éý±²»Ô¹õéþê¡ê¢ê£·ø¼­ÊäàÎÔ¯Ï½Õ·ê¤ÕÞê¥´Ç±ç±è±ßÁÉ´ïÇ¨¹ýÂõÔË»¹Õâ½øÔ¶Î¥Á¬³ÙåÇåÉ¼£ÊÊÑ¡Ñ·µÝåÎÂßÒÅÒ£µËÚ÷ÚùÓÊ×ÞÚþÁÚÛ£Û¦Ö£Û©ÛªÔÇµ¦ÔÍ½´õ¦õ§ÄðÊÍ¼øöÇöÉîÅîÆÕë¶¤îÈîÇîÉîÊîËîÌ·°µöîÍîÏîÎ¸ÆîÑîÒ¶Û³®ÖÓÄÆ±µ¸ÖîÓîÔÔ¿ÇÕ¾ûÎÙ¹³îÖîÕîØî×Å¥îÙîÚÇ®îÛÇ¯îÜ²§îÝîßîàîá×êîâîã¼ØîäÓËÌú²¬ÁåîåÇ¦Ã­îæîçîèîéîêîëîìîíîîîïîðîñîòîóîõÍ­ÂÁî÷îøÕ¡îùÏ³îúîûîüîýï¡îþï¢¸õÃúï£ï¤½ÂÒ¿²ùï¥ï¦ï§Òøï¨Öýï©ÆÌïªï«Á´ï¬ÏúËøï®³ú¹øï¯ï°Ðâï±ï²·æÐ¿ÈñÌàï¶ï·ï¸ï¹ïºÕà´íÃªï¼ï¾ï¿ÎýïÀÂà´¸×¶½õïÃïÄ¶§¼ü¾âÃÌïÅïÆïÇïÏïÈïÉïÊÇÂïñ¶ÍïËïÌ¶ÆÃ¾ïÎïÐïÒÕòïÓÄ÷ïÔÄøïÖ¸ä°÷ï×ïØïÙéFïÚïÛïÜïÝïÞ¾µïáïßïàïâÁÍïäïæïçïèïêïëïìÀØïíÁ­ïîïðÏâ³¤ÃÅãÅÉÁãÆ±ÕÎÊ´³ÈòãÇÏÐãÈ¼äãÉãÊÃÆÕ¢ÄÖ¹ëÎÅãËÃöãÌ·§¸óºÒãÍãÎÔÄãÏãÐÑËãÑãÒãÓãÔÑÖãÕ²ûÀ»ãÖÀ«ã×ãØãÙãÚãÛ¶ÓÑôÒõÕó½×¼ÊÂ½Â¤³ÂÚêÉÂÚíÔÉÏÕËæÒþÁ¥öÁÄÑ³ûöÅö¨Îíö«ö°ö¦¾²ØÌ÷²÷µÎ¤ÈÍº«è¸è¹èºÔÏÒ³¶¥ÇêñüÏîË³ÐëçïÍç¹Ë¶Ùñý°äËÌñþÔ¤Â­ÁìÆÄ¾±ò¡¼Õò¢ò£ò¤ÒÃÆµÍÇò¥Ó±¿ÅÌâò¦ò§ÑÕ¶îò¨ò©µßòªò«²üò­È§·çì©ìªì«ì¬Æ®ì­·É÷Ï÷Ð¼¢â¼â½â¾â¿âÀâÁ·¹Òû½¤ÊÎ±¥ËÇâÂ¶üÈÄâÃ½È±ýâÄ¶öâÅÄÙâÆÏÚ¹ÝÀ¡âÈ²öâÉÁóâÊâËÂøâÍÂíÔ¦ÍÔÑ±³ÛÇý²µÂ¿æàÊ»æáæâ¾Ôæã×¤ÍÕæå¼ÝæäæææçÂî½¾æèÂæº§æéæê³ÒÑé¿¥æëÆïæìæíæîÆ­æïÉ§æðæñæòå¹æóæôÂâæõæöÖèæ÷æø÷Ã÷Å÷Æ÷Þ÷Ê÷ËÓãöÏÂ³öÐöÑöÔöÖ±«ö×öØöÙöÚöÛöÜöÝöÞÏÊößöàöáöâöãöäÀðöåöæöçöèöéöêöëöìöíöîöïöðöòöóöô¾¨öööøÈúöùöúöûöü÷¡÷¢÷£÷¤÷¥÷¦÷§÷¨±î÷©÷¬÷­ÁÛ÷®÷¯Äñð¯¼¦ð°ÃùÅ¸Ñ»ð±ð²ð³ð´ðµÑ¼Ñìð·ð¶Ô§ÍÒð¸ðºð¹ð»ð¼¸ëð½ºèð¾ð¿¾éðÀ¶ìðÁðÂðÃðÄÈµðÆðÇÅôðÈðÉðÊ÷½ðÍðÎðÏº×ðÐðÑðÒðÓðÔðÕðÖðØÓ¥ðÙõºÂóôï÷á»ÆÙä÷ò÷õö¼ö½ö¾÷úÆëì´³Ýö³ö´Áäöµö¶ö·ö¸ö¹öºÈ£ö»Áú¹¨íè¹ê';
}
function FTPYStr()
{
	return 'ÈfÅcŒ£˜I…²–|½zGƒÉ‡À†Ê‚€ÅRžéûÅeÁxžõ˜·†ÌÁ•àl•øÙIy ŽÌƒ†®a®€ÓHÒCƒ|ƒHÄö‚}ƒx‚ƒƒr±Šƒž•þ‚ø‚ã‚¥‚÷‚û‚t‚‚á‚ÎÐówƒL‚b‚Hƒe‚É‚ÈƒSƒ~ƒŠƒz‚Rƒ‰ƒ°‚zƒ«ƒ€‚ùƒA‚ôƒEƒfƒ”ƒ¯ƒ†ƒ¦ƒ®ƒºƒ¶ƒ¼ühÌmêPÅdÆðB«F‡ÏƒÈŒùƒÔŒ‘ÜŠÞrñT›_›Q›rƒöœQœD›öœpœ„CøPøD‘{„P“ôèÆc„¢„t„‚„“„h„e„q„x„£„¥„’„©„Ž„¦„ƒ„¡„ñÞk„Õ„ê„Ó„î„Å„Ú„Ý„×„ò…Q…T…^átÈA…f†ÎÙu±RûuÅPÐl…sŽ„Sdšv…–‰º…’…‡ŽúŽû…˜BNŽý¿hÈý…¢ëp°l×ƒ”¢¯BÈ~Ì–‡@‡\‡˜…Î†á‡Â †¢…Ç…È‡`‡Ò‡I‡³†h†T†J†Ü†èÔ‡µ‡“í‘†¡‡}‡^†ô‡‚‡W‡ˆ‡‡†Ñ‡O‡Z†î†¾‡K†Ý‡Ê‡§‡[‡Š‡D‡¿‡Ë‡†‡u‡Â‡Ú‡£‡ÌˆFˆ@‡è‡ú‡÷‡øˆDˆAÂ}‰¿ˆö‰Ä‰KˆÔ‰¯‰È‰Î‰]‰ž‰‹‰Å‰À‰¾‰¨ˆ×‰|ˆº‰N‰P‰_ˆå‰q‰™ ‰ÑÂ•š¤‰ØÌŽ‚äÍ‰òî^ŠAŠZŠYŠJŠ^ª„ŠWŠy‹D‹Œ‹³‹ž‹‚Š™Šä‹I‹Æ‹ÉŒDŠÊ‹z‹¹‹ë‹È‹ð‹‹‹Ü‹å‹Ô‹ßŒOŒWŒ\ŒŽŒšŒŒ™Œ‘—ŒmŒ’ÙeŒ‹Œ¦Œ¤Œ§‰ÛŒ¢ –‰m‡LˆòŒÀ±MŒÓŒÏŒÃŒÙŒÒŒÕŽZšqØMçs¹uŽXŽhŽF{þ˜Žn÷ˆäŽV£âŽpì–Ã^ŽÅŽ›ŽŸŽ®Ž¤ŽÃŽ§Ž¬ŽÍŽÎŽ¾Ž½ƒçV‘c]TŽì‘ªRý‹U[é_®—‰sˆ›†—Ššw®”ä›©Ø½Æ‘›‘Ô‘n÷‘Ñ‘B‘Z‘“‘Yí‘z¿‚‘»‘«‘Ùa‘©º‘Q‘ÃðÅÀÁ‚â‘Ò‘a‘‘ó@‘Ö‘K‘Í‘vÜ‘M‘„‘T‘C‘‘|‘Ø‘¿‘Ð‘¬‘ß‘â‘ò‘ê‘ð‘ì‘ôˆÌ”U’Ð’ß“P”_“á’“»“¸’à“Œ×oˆó“ú”M”n’þ“í”r”Q“Ü“ñ“´”“ë“é’¶“Ï“õ“×’ê”D“]“Æ“p“ì“Q“v“þ“ï“”S“Û“½“¥”ˆ“å”v”R“§”‡”y”z”d”[“u”P”‚”t“Î”f”X”]”x”\”€”³”¿”µýS”Ì”Ø”àŸoÅf•r•ç•Ò•ƒï@•x•Ô•Ï•ž•Ÿ•º•á–X™Cš¢ës™à—lí—î˜q˜O˜‹˜º˜Ð——™À—–˜Œ—÷—n™Ž™f—d–Å˜Ë—£™±™É—™¾™µ™Ú˜ä—«˜Ó™è—¿˜ï˜E™n˜˜ò˜å™u˜ª˜¶‰ô™z™Ð˜¡™³˜ ™å™E˜Ç™ì™Â™°™Î™‘™‰™½™M™{™Ñ™Á™»™©™´™_šgšešWšžš{š‘šˆšŒššš—š›šªš§Ýž®…”ÀšÖšÐšÚšâšäšåšèÙà…Rhœ«›°œÏ›]ž–ažrœSœæœ¿œûôœIÍž{žožTžaŠÉ›Üž¢¸D›Ñœ\{²œáœyÒúžgœ†Gâ¡ý³œZi¬œuœoœì™¾q­ÕœYœOnž^uÆOžcBœØž³ñ¢žRs§Lœþž¹ž—Mž]žVžEž´žIž©žužtž‡žH“žzž‘ž|žlž®œçŸôì`žÄ NŸ¬ tŸõŸ˜ŸÍücŸ’Ÿë q €ŸN TŸŸŸ©ŸýŸî Z C aŸáŸ¨ F cÛ ” © ¿ Þ Ù î«EªqªNªŸªšªMª{ªœªbªzªsª«C«J«MØiØˆ«I«H­^¬”¬|­h¬F­t«k¬m­‡¬q­I¬­‚¬Ž­a­‹­‘®TëŠ®‹•³®Œ® °X¯Ÿ¯‘°O¯ƒ¯¯‚°b¯d°W°A¯ˆ°B¯w°D¯Ž°T°c°a°`°]°_°d°}°™°—±Kû}±OÉw±I±P²[Öø± ²A²€²G²m²š³C´‰µ\µV´X´a´u³Œ³ŽµZµaµ[µA´T³ˆ´“´_û|µK´ƒ´~û|µR¶Y™µ¶\µœ·Aµ“¶Uëx¶d¶’·Nµz·e·Q·x¶·d·€·w¸F¸`¸[¸G¸Z¸C¸Q¸]¸MØQ¸‚ºV¹S¹P¹a¹{»\»eº`ºY¹~»Iºžº†ºjºD»X»jº„ººˆºt»@»h»f»[¼eî¼g¼c»›¼S¼Z¼R¾o¿{¼m¼u¼t¼qÀw¼v¼s¼‰¼wÀk¼o¼x¾•¼‹¼ƒ¼„¼†¾V¼{¿v¾]¼Š¼ˆ¼y¼¼~¼‚¾€½C¼œ¼›¾š½M¼¼š¿—½K¿U½O½E½I½BÀ[½›½H½‰½q½YÀ@½WÀL½o½k½{½j½^½g½y½Ž½‹½ÀC½—½dÀ^½¿ƒ¾w¾cÀm¾_¾p¾b¾iÀK¾S¾d¾R¿‡¾I¾^¾J¾C¾`¾U¾G¾Y¾l¾~¾|¾}¾’À|¾Ÿ¾˜¾ƒÀD¾Œ¾E¾„¾œ¾—¿P¾¾†¿|¾Ž¾‡¾‰¿N¿`¿d¿b¿p¿cÀp¿r¿O¿VÀ_¿~¿z¿wÀt¿s¿Š¿‰Ài¿¿˜¿•À`ÀRÀQÀUÀyÀ›¾WÁ_ÁPÁTÁ`ÁbÁuÁwÂNÂeÂ–uÂ™Ã@ÂšÂœÂ“Â˜Â”ÃCÄcÄwóaÄIÄ[Ã›Ã{ƒÙÄ‘„Ù–VÅFÃ„ÄzÃ}Ä’ÅKÄšÄXÄ“ÅLÄ_Ã“ÄTÄ˜ÅDÄÄeòvÄœÝ›ÅœÅžÅ“ÆAÆDÆGË‡¹ÁdËGÊÌJÉÈ”ËžÇ{ÈOÉnÆrÌKÌOÇoÌdÊ\‰LŸ¦ÀOÇGÇvÊÉœÊwËCËjÊŽ˜sÈœî ÎŸÉÊnË|ÉpÊaÈ‡ËŽÉWÈRÉÉPÈnËW«@Ê~¬“úLÌ}Îž I¿MÊ’Ë_Ê[ÊrÊ‰ÊYÊVË{ËEÌyÊšævò‡ËNÌ`ÌAÌ@ÌIÌNË’Ì\Ì”‘]Ì“ÍAëmÎrÏŠÎgÏÎ›ÐQÍ˜ÐMÏ Ï|ÐUÏUÍÏuÎ‡Ï“Í‘ÎÏžÏ‰ÏXÏsÏÏNÏ”ÐRá…ã•ÑaÒrÐ–Ò\ÑUÒmÒuÑbÒdÑžÒcÑÒOÒ@ÒhÒŠÓ^ÒŽÒ’Ò•Ò—Ó[ÓXÓJÒ Ó]ÓDÓMÓPÓUÓxÓ|Óz×uÖ`Ó‹Ó†Ó‡ÕJ×IÓ“ÓÓ‘×ŒÓ˜Ó™Ó–×hÓÓ›ÖvÖMÖŽÔnÓ ÔGÔSÓžÕ“ÔAÖSÔOÔLÔEÔ^ÔbÔXÔuÔ{×RÔpÔVÔ\ÔgÖaÔ~ÔxÔt×gÔrÕEÕCÔ‡ÔŸÔŠÔ‘ÔœÕ\ÕDÔ–Ô’ÕQÔÔÔŽÔƒÔ„ÕŠÔ“Ô”ÔŒÕŸÔ‚Õ]Õ_ÕZÕVÕ`ÕaÕTÕdÕNÕfÕbÕOÕˆÖTÕŒÖZ×xÕŽÕuÕnÕ†Õ˜ÕlÕ”Õ{Õ~ÕÕÕrÕ„ÕxÖ\ÖRÕ™ÖeÖGÖCÖoÖ]Ö^Ö@ÖIÖX×‹ÖJÖOÖVÖBÖiÕ›Öƒ×•ÖqÖxÖ{ÖrÖuÖtÖkÖ”Ö™Ö†×vÖ‡×T×P×S×Ž×V×H×—×l×d×ØØ‘Ø“Ø•Ø”ØŸÙt”¡Ù~Ø›Ù|ØœØØšÙHÙÙAØžÙEÙvÙSÙBÙNÙFÙLÙJÙQÙMÙRÙOÙ\Ù—ÙZÙVÙDÙUÙTÚEÙYÙWÚBÙgÙcÙlÙdÙxÙ€ÚHÙpÙnÙsÙrÙyÙ‡Ù˜ÙŽÙÙÙ‘ÚIÙÙ›Ù ÚAÚMÚwÚsÚ…ÚŽÜOÜSÛ„ÜVÛ`ÜEÛ‹Ü]ÜQÛxÜPÛ™ÜWÜUÜbÛ˜ÜXÜfÜkÜgÜ|Ü‡ÜˆÜ‰ÜŽÜÞDÜ—Ý†Ü›ÞZÝVÞ_ÝSÝTÝWÝFÞ]ÝUÝpÝYÝdÝeÞIÝbÝ`Ý^ÝmÝoÝvÝ‚Ý…ÝxÝÝyÝzÝwÝÝ—Ý‹Ý”Þ\Þ@Ý ÝšÞAÞHÞOÞoÞqÞpß…ß|ß_ßwß^ß~ß\ß€ß@ßMßhß`ßBßtßƒÞŸÛEßmßxßdßfßŠß‰ßzßbà‡à—àwà]àuà’ààPà”ààiáBàyàájáuá‰á‡á„áŒèaèŽçYááá˜á”á“á•á‘âQâAáŸâCážå{âSâOâ}ââ âgânçŠâcä^ä“âkâjè€šJâxæuã^â‚â[â€â^âoâZâ•åXã`ãQâ’ÀâŽâ˜â“ãXèãfãgâ›âšâ™èFãKâèpãUãTâ‹ãCãBãGâ‰â”èIäDã™ãsäBä…äeçtèKã~äXãŸæzåŽããŠäbäAçfãŒæ|ãxã“ãtã‘åPäCãqãžçPã|ç|ä@ãyãœèTç„äånäˆæœçHäNæiä‡äzåä†ä~çnäSäsähä\äJäRäZäuä|åHäæNåeå^åQä˜åKåaådèŒåNåFå\äŸåUåVæIäåiåOå›å|çIæJåŠåšæ@æRå‘æ}æDåƒæVçUçšæŸæ‚ækè‡çæ‡æyæ€æ^æ„æ‰è\ægçSçMçNæ çOçRçCæ—æ›ç†ç‚çhç…è|ç’çjç‹èZèDèCç èOèsè‚éLéTéVéWéZé]†–êJécééeébégéhé`žélô[é|Â„êYé}é‚éyéwéué€ôbé†éé“éŽé‹ô]é”é’éé‘êUê@é˜éŸé êHêDêIêRê ê–êŽê‡ëAëHê‘ë]êê€ê„êŸëEëUëSë[ë`ëhëyër×‡ìZìFìVì\ìnìoìví^ídífígínítíyíwíí“í”í•í™í—í˜íšíœîBî™îDí îCížî@îAïBîIîHîiîRîaîM}îWîUîlîjîh·fîwî}î€î…îî~ïDî”îî‹î—îïAïEïLïRïSïZï`ïhïjïwð‹ðï|ðhï‚ðqïƒï„ï†ïˆï‹ðTï—ï–ï•ïðDðˆðAïœïžðGðIðNðHðQðWð^ððtð’ðxðsð}ð~ðzð‚ñRñSñWñZñYòŒñgóHñzñ‚ñ†ñ€ñxò|ñvñ„ñwñ{óAñ~ò”ÁRòœò‘ñ˜ñ”ñ‰óPòGòžòEòUòTòSòKò‰ò_òsò}ò\òˆòtòqò~òŠò…ò‹ò–óEóKóJótóyóxôWô|ôuô~ôœô”ô™÷Q÷|õVõU÷cõTõqõ^õnõb÷qõoõrõœ÷\õ†÷~ö–öžõŽöˆöœõ…õõŒöaõ›öNöOöEöHöKöFöTõ öLõ™ölöw÷{öqövömö—ö’ööŠöŽö˜÷B÷L÷Mö ÷Z÷X÷[÷V÷køBøFëuøSøQútøfødøcøù…ûRø†ø„ø|øzøxørúƒúvøøŽø øû[ø™ùPûZùNù]ùZùOú’ùYù^ùoùgùlùiù‡ù–ù˜úXúFú_úYúQûWúpúwúú„úú–ú˜ú—ûXûzûœûŸüNüSüZütüoüwüxüƒýBýRýWýXýZýeýgý_ýfýbýlýrýpýxý}ýˆýýý”';
}
function Traditionalized(cc){
	var str='',ss=JTPYStr(),tt=FTPYStr();
	for(var i=0;i<cc.length;i++)
	{
		if(cc.charCodeAt(i)>10000&&ss.indexOf(cc.charAt(i))!=-1)str+=tt.charAt(ss.indexOf(cc.charAt(i)));
  		else str+=cc.charAt(i);
	}
	return str;
}
function Simplized(cc){
	var str='',ss=JTPYStr(),tt=FTPYStr();
	for(var i=0;i<cc.length;i++)
	{
		if(cc.charCodeAt(i)>10000&&tt.indexOf(cc.charAt(i))!=-1)str+=ss.charAt(tt.indexOf(cc.charAt(i)));
  		else str+=cc.charAt(i);
	}
	return str;
}

function setCookie(name, value)		//cookiesÉèÖÃ
{
	var argv = setCookie.arguments;
	var argc = setCookie.arguments.length;
	var expires = (argc > 2) ? argv[2] : null;
	if(expires!=null)
	{
		var LargeExpDate = new Date ();
		LargeExpDate.setTime(LargeExpDate.getTime() + (expires*1000*3600*24));
	}
	document.cookie = name + "=" + escape (value)+((expires == null) ? "" : ("; expires=" +LargeExpDate.toGMTString()));
}

function getCookie(Name)			//cookies¶ÁÈ¡
{
	var search = Name + "="
	if(document.cookie.length > 0) 
	{
		offset = document.cookie.indexOf(search)
		if(offset != -1) 
		{
			offset += search.length
			end = document.cookie.indexOf(";", offset)
			if(end == -1) end = document.cookie.length
			return unescape(document.cookie.substring(offset, end))
		 }
	else return ""
	  }
}

var StranLink_Obj=document.getElementById("StranLink");

if (StranLink_Obj)
{
	var JF_cn="ft"+self.location.hostname.toString().replace(/\./g,"")
	var BodyIsFt=getCookie(JF_cn)
	if(BodyIsFt!="1")BodyIsFt=Default_isFT
	with(StranLink_Obj)
	{
		if(typeof(document.all)!="object") 	//·ÇIEä¯ÀÀÆ÷
		{
			href="javascript:StranBody()"
		}
		else
		{
			href="#";
			onclick= new Function("StranBody();return false")
		}
		title=StranText("µã»÷ÒÔ·±ÌåÖÐÎÄ·½Ê½ä¯ÀÀ",1,1)
		innerHTML=StranText(innerHTML,1,1)
	}
	if(BodyIsFt=="1"){setTimeout("StranBody()",StranIt_Delay)}
}