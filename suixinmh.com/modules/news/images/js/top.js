// JavaScript Document
function setTab(m,n){
var tli=document.getElementById("menu"+m).getElementsByTagName("li"); /*获取选项卡的LI对象*/
var mli=document.getElementById("main"+m).getElementsByTagName("ul"); /*获取主显示区域对象*/
for(i=0;i<tli.length;i++){
  tli[i].className=i==n?"hover":""; /*更改选项卡的LI对象的样式，如果是选定的项则使用.hover样式*/
  mli[i].style.display=i==n?"block":"none"; /*确定主区域显示哪一个对象*/
}
}