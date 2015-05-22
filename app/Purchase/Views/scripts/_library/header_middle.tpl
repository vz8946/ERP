<div class="header-inner" style="position: relative;">
<div class="logo">
	{{if $is_index_page}}
	<a href="/"><img 　　alt="垦丰-种子商城"  src="{{$_static_}}/images/nglogo.jpg"/></a>
	<a href="/"><img 　　alt="垦丰商城" src="{{$_static_}}/images/logo2.gif" /></a>
	{{else}}
	<a href="/"><img 　　alt="垦丰-种子商城" src="{{$_static_}}/images/nglogo.jpg"/></a>
	{{/if}}
</div>

<div class="topadv" style="">
	<img src="{{$_static_}}/images/top.jpg"/>
	<div class="tip1"><a href="/reg.html">￥200券</a></div>
	<div class="tip2">注册即送</div>
	<div class="tip3"><a href="/zp.html">聚焦垦丰</a></div>
	<div class="tip4">世界500强</div>
	<div class="tip5">满199包邮</div>
	<div class="tip6">货到付款</div>
</div>

<div class="serach_area" style="">
	<div class="topsearch"  id="searchBoxAjax">
		<form onsubmit="return chkValue(this);" method="get" action="/search.html" name="searchForm" id="searchBox">
			<input class="search_put"
				name="keyword"  id="search_keyword"  type="text"
				value="{{if !$keyword}}请输入关键词{{else}}{{$keyword}}{{/if}}"
				autocomplete="off" onfocus="if(this.value!='请输入关键词'){this.style.color='#404040'}else{this.value='';this.style.color='#404040'}"
				onblur="if(this.value==''){this.value='请输入关键词';this.style.color='#B6B7B9'}"
				onkeydown="this.style.color='#404040';" name="keyword">
				<input class="search_btn" type="submit" value="搜索">
				{{if $u}}
				<input type="hidden" name="u" value="{{$u}}">
			{{/if}}
		</form>
	</div>
	<div class="hotkeyword">
		<span>热门搜索：</span>
		 <a href="/b-niubeilun/detail722.html?ozc=6&ozs=37265" target="_blank">补肾袋鼠精</a>
		 <a href="/zt/detail-60.html" target="_blank">男性玛卡</a>
		 <a href="/b-napule/detail690.html" target="_blank">降糖降压</a>
		 <a href="/zt/detail-61.html" target="_blank">关节养护</a>
		 <a href="/zt/detail-21.html" target="_blank" >进口叶黄素</a> 
	</div>
</div>
</div>