<div class="header-inner" style="position: relative;">
<div class="logo">
	<a href="/"><img 　　alt="垦丰种业商城"  src="{{$_static_}}/images/logo1.gif"/></a>

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
		 <a href="/b-demeiya" target="_blank">德美亚</a>
		 <a href="/b-kendan" target="_blank">垦单</a>
		 <a href="/b-longdan" target="_blank">龙单</a>
	</div>

</div>
</div>