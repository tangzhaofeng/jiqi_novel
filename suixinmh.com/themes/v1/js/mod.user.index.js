 /**
 * $description	: 用户中心签到所有javascript内容
 * 				: *需要jQuery支持
 * $copyright	: shuhai@2015-01-14
 * $createtime	: 2015-01-14
 */
  function showDate(option) {
    this.obj = option.id;
  }
  //初始化showDate
  showDate.prototype.init = function () {
    this.td = $("#"+this.obj+" .sel_date .data_table tbody td");
    //填充年、月
    this.showNow();
  }
  //填充年、月
  showDate.prototype.showNow = function () {
    var month = new Date().getMonth();
    //填充当月的所有日期
    this.showAllDay(month);
  }
  //填充当月的所有日期
  showDate.prototype.showAllDay = function (Mn) {
    var firstD = new Date();
    firstD.setFullYear(y, Mn, 1);
    //当月第一天是一周中第几天，0是星期日
    var firstDay = firstD.getDay();
    //当月有多少天,daycount继承user_index.html中的js
    for(var j = 0; j < daycount; j++) {
    	//console.log(this.td[j+firstDay].innerHTML);
    	this.td[j + firstDay].innerHTML = j + 1;
    }
    //删除多余行
    this.rmLastrow(daycount);
  }
  //删除多余行
  showDate.prototype.rmLastrow = function(maxD){
  	var sRowIndex;
    $("tbody tr:gt(2) td").each(function(){
    	if($(this).html() == maxD) sRowIndex = $(this).parent()[0].sectionRowIndex;
    });
    $("tbody tr:gt("+sRowIndex+")").remove();
  }