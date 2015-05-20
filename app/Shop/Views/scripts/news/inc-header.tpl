<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type">	
		<title>{{$seo_title}}-垦丰商城</title>
		<meta http-equiv="X-UA-Compatible" content="IE=7" />
		<meta name="baidu-site-verification" content="7tdg1Kfqym" />
		<meta name="Keywords" content="{{$seo_keywords}}" />
		<meta name="Description" content="{{$seo_description}}" />		
		<link type="image/x-icon" href="{{$_static_}}/images/home.ico" rel="Shortcut Icon">	
		<link type="text/css" href="{{$_static_}}/css/css.php?t=css&f=base.css,header.css{{$css_more}}&v={{$sys_version}}.css" rel="stylesheet" />
		<script src="{{$_static_}}/js/js.php?t=js&f=jquery.js,jquery.lazyload.js,common.js,header.js{{$js_more}}&v={{$sys_version}}.js" type="text/jscript"></script>
		<link type="text/css" href="/newstatic/styles/news.css?v={{$sys_version}}" rel="stylesheet" />
		<script type="text/javascript" src="/newstatic/scripts/jquery.flow.1.2.auto.js"></script>
		
		<script type="text/javascript">
		$(function(){
			$(".article_sort").hover(function(){
				$(this).css("border","1px solid #df5686");
			},function(){
				$(this).css("border","1px solid #fff");
			})
			
			$("#myController1").jFlow({
				slides: "#slides1",
				controller: ".jFlowControl1", 
				slideWrapper : "#jFlowSlide1", 
				selectedWrapper: "jFlowSelected1", 
				auto: true,
				duration: 600,
				width: "399px",
				height: "300px",
				prev: ".jPrev", 
				next: ".jNext" 
			});
		})
		</script>		
	</head>
		<!--顶部导航-->
		<div class="topnav">
			<div class="{{if $is_index_page}}wbox_1200{{else}}wbox990{{/if}}">
				{{include file="news/header_top.tpl"}}
			</div>
		</div>
		
		<!-- 页头-->
		<div class="{{if $is_index_page}}wbox_1200{{else}}wbox990{{/if}} header">
			{{include file="news/header_middle.tpl"}}
		</div>
		
		<div class="mainnavbox">
		  <div class="{{if $is_index_page}}wbox_1200{{else}}wbox990{{/if}}">
			{{include file="news/header_nav.tpl"}}
		  </div>
		</div>
		
		<!--end 页头-->