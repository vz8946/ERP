{{include file="header.tpl"}}
<div class="discount">
<!-- 主题促销广告-->
<div class="disc_banner">
  <style type="text/css">
.jpzb-pd {
	margin:0 auto;
	padding:0;
	width:1000px;
	font-size:12px;
	color:#555;
	line-height:20px;
	font-family:"宋体", Arial, Helvetica, sans-serif;
}
.bg-main {
	position: relative;
}
.text-1 {
	font-family: "微软雅黑";
	font-size: 15px;
	color: #868384;
	font-weight: 100;
	float: right;
	padding-right:14px;
	padding-top:11px;
	height: 18px;
	width: 980px;
	text-align: right;
	margin-top: -35px;
}
.box-label {
	z-index:100;
	float: left;
	margin-top: -22px;
	margin-left: -50px;
}
.content-left {
	height: 172px;
	width: 480px;
	border: 1px solid #d6d6d6;
	float: left;
	margin-left:8px;
	margin-top:12px;
	display:inline;
}
.content-right {
	height: 172px;
	width: 480px;
	border: 1px solid #d6d6d6;
	float: left;
	margin-left:9px;
	margin-top:12px;
	display:inline;
}
.mainbox {
	margin:0 auto;
	border: 0px solid #d6d6d6;
	width: 988px;
	position: relative;
	margin-top:20px;
	margin-bottom:20px;
}
.mainbox-2 {
	margin:0 auto;
	border: 1px solid #d6d6d6;
	width: 988px;
	height:607px;
	position: relative;
	margin-top:20px;
	margin-bottom:40px;
}
.mainbox-3 {
	margin:0 auto;
	border: 1px solid #d6d6d6;
	width: 988px;
	height:605px;
	position: relative;
	margin-top:20px;
	margin-bottom:40px;
}
.mainbox-4 {
	margin:0 auto;
	border: 1px solid #d6d6d6;
	width: 988px;
	height:605px;
	position: relative;
	margin-top:20px;
	margin-bottom:40px;
}
.jpzb-pd .whitetext {
	color: #000;
}
.aaa {
	clear: both;
}
.jpzb-pd ul {
	margin:0;
	padding:0;
}
ul li {
	margin:0;
	padding:0;
	list-style-type:none;
	float:left;
}
.jpzb-pd img {
	display:block;
	border:0;
}
.box-content {
	clear: both;
	margin-bottom:12px;
	display:inline;
}

.bg-jpzb {
	background-image: url(/Public/img/660046.jpg);
	background-repeat: no-repeat;
	background-position: center top;
	width:1500px;
	height:327px;
	position:absolute;
	margin:0 0 0 -254px;
	z-index:-100;
}
</style>
  <div style="margin: auto; width: 990px">
    <div class="bg-jpzb"> &nbsp;</div>
    <div class="jpzb-pd"> <a id="top" name="top"></a>
	  <div id="jptj-index02"> <img width="990" height="70" alt="" lazy_src="{{$imgBaseUrl}}/Public/img/660048.jpg" src="{{$imgBaseUrl}}/Public/img/660048.jpg"></div>
      <div id="jptj-index02"> <img width="990" height="70" alt="" lazy_src="{{$imgBaseUrl}}/images/jptj-index-304.jpg" src="{{$imgBaseUrl}}/images/jptj-index-304.jpg"></div>
      <div id="jptj-index04"> <img width="990" height="70" alt="" lazy_src="{{$imgBaseUrl}}/images/jptj-index-305.jpg" src="{{$imgBaseUrl}}/images/jptj-index-305.jpg"></div>
      <div id="jptj-index05"> <img width="990" height="70" alt="" lazy_src="{{$imgBaseUrl}}/images/jptj-index-306.jpg" src="{{$imgBaseUrl}}/images/jptj-index-306.jpg"></div>
   
      <a id="f1" name="top"></a>
      <div id="jptj-index07"> <img width="990" height="47" alt="" lazy_src="{{$imgBaseUrl}}/Public/img/670054.jpg" src="{{$imgBaseUrl}}/Public/img/670054.jpg"></div>
    </div>
    <div class="mainbox"> <span class="text-1"></span>
	<!--
    <div style="margin-left:10px;"><a href="/zt/detail-36.html" target="_blank"><img width="970" height="172" lazy_src="{{$imgBaseUrl}}/Public/img/index/bandcomm/ztadv.jpg"></a></div>
	-->
      <div class="box-content">
      	{{foreach from=$lists item=vo}}
      		<div class="content-left"><a href="/zt/detail-{{$vo.id}}.html" target="_blank"><img width="480" height="172" lazy_src="{{$imgBaseUrl}}/{{$vo.imgUrl}}"></a></div>
      	{{/foreach}}
      </div>
    </div>
  </div>
  <!-- end 主题促销广告-->
  <div class="discList clearfix promote"> </div>
</div>
<!-- foot start-->
{{include file="_library/foot.tpl"}}
<!-- foot end-->
<script language="javascript">
	$("#float-tq-small").mouseover(function() {
		$(this).hide();
		$("#float-tq").show();
	});
	
	$("#float-tq").mouseleave(function() {
		$(this).hide();
		$("#float-tq-small").show();
	});
	
	$("#float-tq").mouseenter(function() {
		$(this).show();
		$("#float-tq-small").hide();
		
	});
</script>
<script src="{{$imgBaseUrl}}/Public/js/jquery-1.4.2.min.js" type="text/jscript"></script>
<script src="{{$imgBaseUrl}}/Public/js/global.js" type="text/jscript"></script>
<script src="{{$imgBaseUrl}}/Public/js/groupon.js" type="text/javascript"></script>
</body>
</html>
