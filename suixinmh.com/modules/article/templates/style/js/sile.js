function radioShow(){
	var myradio=document.getElementsByName("myradio");  //获取标签名为myradio的标签
	var div=document.getElementById("c").getElementsByTagName("div");
	for(i=0;i<div.length;i++){
	if(myradio[i].checked){
	div[i].style.display="block";
	}
	else{
	div[i].style.display="none";
	}
	}
	}