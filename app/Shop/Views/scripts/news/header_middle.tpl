<div class="header-inner" style="position: relative;">
<div class="logo">
	{{if $is_index_page}}
	<a href="/"><img 　　alt="垦丰商城"  src="{{$_static_}}/images/logo1.gif"/></a>
	<a href="/"><img 　　alt="垦丰商城" src="{{$_static_}}/images/logo2.gif" /></a>
	{{else}}
	<a href="/"><img 　　alt="垦丰商城" src="{{$_static_}}/images/logo1.gif"/></a>
	{{/if}}
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
		<span>热索：</span>
		 <a href="/b-niubeilun/detail722.html?ozc=6&ozs=37265" target="_blank">补肾袋鼠精</a>
		 <a href="/zt/detail-60.html" target="_blank">男性玛卡</a>
		 <a href="/b-napule/detail690.html" target="_blank">降糖降压</a>
		 <a href="/zt/detail-55.html" target="_blank">降血脂</a>
		 <a href="/zt/detail-63.html?ozc=6&ozs=22338" target="_blank" >酵素减肥</a> 
	</div>
</div>
</div>