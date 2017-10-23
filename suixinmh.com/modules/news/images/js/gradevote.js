/*
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
//评分系统 BY huliming 2010-11-18
//    函数：
//     CreateVote(Max,Def)            创建平分星星数量 Max为总共多少星星，Def为默认分数
//     AddContent(sNA)             添加平分内容sNA
//     GradeVoteImage1             星星图片一
//     GradeVoteImage2             星星图片二
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/
var WindowVote = new GradeVote();
function GradeVote() {
     this.VoteMaxStar=1;
     this.VoteCounter=1;
     this.VoteContent=new Array();
     this.GradeVoteImage1=siteurl+"/modules/news/images/mood/mark.gif";
     this.GradeVoteImage2=siteurl+"/modules/news/images/mood/unmark.gif";
	 this.Moodid=1;
	 this.ContentId=0;
	 this.MouseEvent=true;

     this.AddContent=function (sNA) {
          this.VoteContent["_"+this.VoteCounter]=sNA;
          this.VoteCounter++;
     }
     /*创建评分星星*/
     this.CreateVote=function (MaxStar,DefaultStar) {
          var i=1,j=1;
          var VoteImgHTML="";
          this.VoteMaxStar=MaxStar;
          for (i=1;i<=MaxStar;i++) {
               VoteImgHTML+="<img id=\"_GradeVoteID"+i+"\" src=\""+(j<=DefaultStar ? this.GradeVoteImage1 : this.GradeVoteImage2)+"\" border=\"0\" onMouseOver=\"WindowVote.HitVote('"+i+"');\" onClick=\"WindowVote.VoteSubmit('"+i+"');\" style=\"cursor:pointer\">";
               j++;
          }
		  document.getElementById("GradeVoteScore").innerHTML=this.VoteScoreContent(DefaultStar);
          if (document.getElementById("GradeVoteArea")!=null) {
               document.getElementById("GradeVoteArea").innerHTML=VoteImgHTML;
          }
          else {
               alert("Object not found!!");
          }
     }
     /*评分等级内容*/
     this.VoteScoreContent=function (sID) {
          var VoteContent=this.VoteContent["_"+sID];
          if (VoteContent=="undefined" || VoteContent==null) VoteContent="Not defined!!";
          return VoteContent;
     }
     /*鼠标放到星星上*/
     this.HitVote=function (sID) {
		 if(this.MouseEvent==false) return false;
          var i=1;
          for (i=1;i<=sID;i++) {
               document.getElementById("_GradeVoteID"+i).src=this.GradeVoteImage1;
          }
          document.getElementById("GradeVoteScore").innerHTML=this.VoteScoreContent(sID);
          sID++;
          for (i=sID;i<=this.VoteMaxStar;i++) {
               document.getElementById("_GradeVoteID"+i).src=this.GradeVoteImage2;
          }
     }
     /*提交评分*/
     this.VoteSubmit=function (sID) {
          if(this.MouseEvent==false) return false;
		  var url = siteurl+'/modules/news/mood.php?moodid='+this.Moodid+'&contentid='+this.ContentId+'&vote_id='+sID;
		  GData.getpage(url, function(data){
				if(data.total!=undefined){
					var avg = data.avg;
					var avgs = avg.split('.');
					$('#gradevote_avg1').html(avgs[0]);
					$('#gradevote_avg2').html('.'+avgs[1]);
					$('#gradevote_total').html(data.total);
					WindowVote.MouseEvent=false;
					//alert('评分巳提交!');
				}else alert('请不要重复操作!');
		  });
     }
}