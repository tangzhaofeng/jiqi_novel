{?set jieqi_page_template = "empty.html"?}
// JavaScript Document
function w_str_replace(str){
	{?section name=i loop=$hideword?}
	var str   =   str.replace(/(~{?$i.key?}~)/ig,"<img src=\"http://www.home.com/a.php?n={?$hideword[i]|urlencode?}\" align=\'absmiddle\'>"); 
	{?/if?}
	return str;
}
//alert(document.all.content.innerHTML);
document.all.content.innerHTML = w_str_replace(document.all.content.innerHTML);
