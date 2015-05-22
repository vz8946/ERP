<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type">	
		<title>{{if $page_title}}{{$page_title}}{{else}}垦丰电商 -专业的种子商城{{/if}}</title>
		<meta http-equiv="X-UA-Compatible" content="IE=7" />
		<meta name="Keywords" content="{{if $page_keyword}}{{$page_keyword}}{{else}}垦丰电商 ,网上种子商城{{/if}}" />
		<meta name="Description" content="{{if $page_description}}{{$page_description}}{{else}}垦丰电商 -专业的种子商城！{{/if}}" />		
		<link type="image/x-icon" href="{{$_static_}}/images/home.ico" rel="Shortcut Icon">	
		<link type="text/css" href="{{$_static_}}/css/css.php?t=css&f=base.css,header.css,jcl/skins/default/skin.css{{$css_more}}&v={{$sys_version}}.css" rel="stylesheet" />
		<script src="{{$_static_}}/js/js.php?t=js&f=jquery.js,jquery.lazyload.js,jquery.tab.js,jcl/jquery.jcarousel.min.js,jquery.goTop.js,common.js,header.js{{$js_more}}&v={{$sys_version}}.js" type="text/jscript"></script>
	</head>
	
	<!--顶部导航-->
	<div class="topnav">
		<div class="{{if $is_index_page}}wbox_1200{{else}}wbox990{{/if}}">
			{{include file="_library/header_top.tpl"}}
		</div>
	</div>

	<!-- 页头-->
	<div class="{{if $is_index_page}}wbox_1200{{else}}wbox990{{/if}} header">
		{{include file="_library/header_middle.tpl"}}
	</div>
		
	<div class="mainnavbox">
	  <div class="{{if $is_index_page}}wbox_1200{{else}}wbox990{{/if}}">
		 <div class="mallCategory">
			  <div class="mallSort"><a class="sortLink s_hover" href="#"><s></s></a>
				<!--所有商品分类-->
				<div class="sort"  id="cat-menu"  style="display:none;">	            
					{{include file="_library/catnav.tpl"}}
				</div>
				<!--end 所有商品分类-->
			 </div>
		 </div>
		{{include file="_library/header_nav.tpl"}}
		 <div class="rightnav fr">
		  </div>
	  </div>
	</div>
	<!--end 页头-->