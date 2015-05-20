<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type">	
		<title>{{if $page_title}}{{$page_title}}{{else}}垦丰-电商管理平台{{/if}}</title>
		<meta http-equiv="X-UA-Compatible" content="IE=7" />
		<meta name="baidu-site-verification" content="7tdg1Kfqym" />
		<meta name="Keywords" content="{{if $page_keyword}}{{$page_keyword}}{{else}}垦丰-电商管理平台{{/if}}" />
		<meta name="Description" content="{{if $page_description}}{{$page_description}}{{else}}垦丰-电商管理平台{{/if}}" />		
		<link type="image/x-icon" href="{{$_static_}}/images/home.ico" rel="Shortcut Icon">	
		<link type="text/css" href="{{$_static_}}/css/css.php?t=css&f=base.css,header.css,jcl/skins/default/skin.css{{$css_more}}&v={{$sys_version}}.css" rel="stylesheet" />
		<script>var static_url='{{$_static_}}',img_url='{{$imgBaseUrl}}',cur_time= '{{$smarty.now}}';</script>
		<script src="{{$_static_}}/js/js.php?t=js&f=jquery.js,jcl/jquery.jcarousel.min.js,JSmart.js,common.js,header.js{{$js_more}}&v={{$sys_version}}.js" type="text/jscript"></script>
	</head>
		<!--顶部导航-->
		<div class="topnav">
			<div class="{{if $is_index_page}}wbox_1200{{else}}wbox990{{/if}}">
				{{include file="_library/header_top.tpl"}}
			</div>
		</div>
		{{if $cur_position neq 'member'  and $cur_position neq 'help'}}	
	      {{widget class="AdvertWidget"  id="1"}}
	    {{/if}}
		<!-- 页头-->
		<div class="{{if $is_index_page}}wbox_1200{{else}}wbox990{{/if}} header">
			{{include file="_library/header_middle.tpl"}}
		</div>
		
		<div class="mainnavbox">
		  <div class="{{if $is_index_page}}wbox_1200{{else}}wbox990{{/if}}">
		     <div class="mallCategory">
		          <div class="mallSort"><a class="sortLink s_hover" href="/goods/all" target="_blank"><s></s></a>
		            <!--所有商品分类-->
		            <div class="sort" {{if $is_index_page}}id="index-cat-menu" {{else}} id="cat-menu"  style="display:none;"{{/if}}>	            
						{{include file="_library/catnav.tpl"}}
					</div>
		            <!--end 所有商品分类-->
		         </div>
		     </div>
			{{include file="_library/header_nav.tpl"}}
		    <!-- <div class="rightnav fr">
		         <ul>
		           <li><a title="优品" href="/zt/detail-65.html" target="_blank">精品优品</a></li>
		           <li><a title="" href="/zpbz.html" target="_blank">测试专题</a></li>
		         </ul>
		      </div>-->
		  </div>
		</div>
		<!--end 页头-->