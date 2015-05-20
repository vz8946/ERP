<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Insert title here</title>

		<script language="javascript" src="/Public/js/jquery-1.9.1.min.js"></script>

		<link rel="stylesheet" type="text/css" href="/Public/js/easyui/themes/default/easyui.css">
		<link rel="stylesheet" type="text/css" href="/Public/js/easyui/themes/icon.css">
		<script language="javascript" src="/Public/js/easyui/jquery.easyui.min.js"></script>

		<!--loadmask-->
		<link rel="stylesheet" type="text/css" href="/Public/js/loadmask/jquery.loadmask.css">
		<script type="text/javascript" src="/Public/js/loadmask/jquery.loadmask.min.js"></script>

		<!--asyncbox-->
		<link rel="stylesheet" type="text/css" href="/Public/js/asyncbox/skins/Ext/asyncbox.css">
		<script type="text/javascript" src="/Public/js/asyncbox/AsyncBox.v1.4.5.js"></script>

		<link href="/Public/css/newsadmin.css" type="text/css" rel="stylesheet"/>
		<script language="javascript" src="/Public/js/js.js"></script>

	</head>
	<body class="easyui-layout">
<div data-options="region:'north',split:false" style="height:55px;overflow: hidden;">
	
	<div id="layout-header" class="layout-header">
		<div class="header-logo"><a href="/newsadmin"><img src="/images/news-logo.png" height="50"/></a></div>
		<div class="header-link">
			<span>您好：资讯管理员</span>
			<i>|</i>
			<a target="_blank" href="http://www.1jiankang.com">官网</a>
			<i>|</i>
			<a href="/newsadmin/loginout">退出</a>
		</div>
		<div class="nav-bar">
		</div>
		<div class="header-nav">
			<ul>
				{{foreach from=$menu item=v key=k}}
				<li class="{{if $v.is_c}}c{{/if}}"><a href="{{$v.url}}?__M={{$v.model}}&__L=F">{{$v.title}}</a></li>
				{{/foreach}}
			</ul>
		</div>
	</div>
		
</div>
<div data-options="region:'south',split:false" style="height:30px;overflow: hidden;">
	<div id="layout-footer" class="layout-footer">
		Powered copyright © 2012-2013 www.1jiankang.com all rights reserved 沪ICP备09002332号
	</div>	
</div>
<div data-options="region:'center'" style="padding:5px;background:#fff;overflow: hidden;">

