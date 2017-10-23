/*进度条定义-GooProgress类*/
//Div :要被绑定的已被JQUERY封装的DOM对象，必须要有其ID
//property  :JSON变量，Progress的详细参数设置
function GooProgress(Div,property){
	this.$div=Div;
	this.$div.addClass("Progress");
	if(property.bd_col) this.$div.css("border-color",property.bd_col);
	if(property.background)	this.$div.css("background",property.background);
	this.$width=property.width;
	if(property.height)	this.$div.css({width:this.$width+"px",height:property.height+"px","line-height":property.height+"px"});
	else	this.$div.css({width:this.$width+"px"});
	temp="<div class='bar' style='width:0px";
	if(property.bar_background)	temp+=";background:"+property.bar_background;
	if(property.bar_bd_col) 	temp+=";border-color:"+property.bar_bd_col;
	temp+=";'></div>";
	this.$bar=$(temp);
	this.$innerUnit=null;
	this.$initText=property.initText||"";
	if(property.innerUnit){
		this.$innerUnit=property.innerUnit;
		this.$div.append("<b>"+this.$initText+"</b>");
		this.$bar.append("<b style='width:"+property.width+"px;'>"+this.$initText+"</b>");
	}
	this.$div.append(this.$bar);
	if(property.finalText)	this.$finalText=property.finalText;
	this.$minValue=0;
	if(property.minValue)	this.$minValue=property.minValue;
	this.$maxValue=100;
	if(property.maxValue)	this.$maxValue=property.maxValue;
	/*每个像素代表的步进值*/
	this.$step=(this.$maxValue-this.$minValue)/property.width;
	this.$value=this.$minValue;//当前进度条的值，初始为this.$minValue
	//绑定进度变化过程中触发的事件
	this.bindChange=function(fn){
		this.$changeFn=fn;
	};
	//绑定进度完成后触发的事件
	this.bindFinish=function(fn){
		this.$finishFn=fn;
	};
	//设置当前的进度值
	this.setValue=function(val){
		if(val<this.$minValue||val>this.$maxValue)	return;
		this.$value=val;
		this.$bar.css({width:Math.round(val/this.$step)+"px"});
		if(this.$innerUnit){
			if(this.$innerUnit.indexOf("&d")>-1)	newStr=this.$innerUnit.replace(/\&d/g,val);
			else newStr=this.$innerUnit;
			this.$div.children("b").text(newStr);
			this.$bar.children("b").text(newStr);
		}
		if(val==this.$maxValue){
			this.$bar.css({"border-right":"0px"});
			inthis=this;
			setTimeout("inthis.showFinalText();inthis.$finishFn();",500);
		}
		this.$changeFn();
	};
	//在进度条中显示进度完成后的提示信息
	this.showFinalText=function(){
		if(this.$finalText){
			this.$div.children("b").text(this.$finalText);
			this.$bar.children("b").text(this.$finalText);
		}
	};
	//重新把进度条变成初始状态
	this.resetReady=function(){
		this.$bar.css({width:"0px","border-right":"1px solid"})
		this.$div.children("b").text(this.$initText);
		this.$bar.children("b").text(this.$initText);
	}
	//重设进度条的宽度,最大，最小值
	this.resetParam=function(wide,minValue,maxValue){
		if(minValue)	this.$minValue=minValue;
		if(maxValue)	this.$maxValue=maxValue;
		if(wide){
			this.$width=wide;
			this.$div.css({width:wide+"px"});
			this.$bar.children("b").css({width:wide+"px"});
		}
	}
	//隐藏/显示进度条
	this.display=function(show){
		this.$div.css("display",show);
	};
	this.$timing=null;//定时器:用来作执行长轮询方式从远程服务器端定时获取值
	//从远程服务器端获取最新进度(add为BOOL型变量:true时，远程获取值将被当作为增量，而不是实际进度值)
	this.setValueAjax=function(Url,Para,Type,Add,Interval){
		inthis=this;
		fn=function(){
		$.ajax({
			type: Type,//传输方式
			url: Url,
			dataType:"text",
			data:Para,//传参
			success: function(data){
				val=parseInt(data);
				if(val){
					if(Add&&val!=0)	inthis.setValue(inthis.$value+val);
					else if(val!=inthis.$value)	inthis.setValue(val);
					if(val==inthis.$maxValue&&inthis.timing!=null);
						clearInterval(inthis.$timing);
				}
			}
		});
		};
		//如果Interval不为空，则执行长轮询方式定时获取值，如果为空则只立即执行一次
		if(Interval)	inthis.$timing=setInterval(fn(),Interval);
		else	fn();
	};
	//停止定时器的工作
	this.stopInterval=function(){
		if(this.$timing!=null)	clearInterval(this.$timing);
	};
}

//将此类的构造函数加入至JQUERY对象中
jQuery.extend({
	createGooProgress: function(Div,property) {
		return new GooProgress(Div,property);
  }
}); 