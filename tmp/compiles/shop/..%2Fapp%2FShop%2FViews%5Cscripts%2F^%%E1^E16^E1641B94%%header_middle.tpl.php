<?php /* Smarty version 2.6.19, created on 2014-10-30 10:40:46
         compiled from _library/header_middle.tpl */ ?>
﻿<div class="header-inner" style="position: relative;">
<div class="logo">
	<a href="/"><img 　　alt="垦丰种业商城"  src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/logo1.gif"/></a>

</div>


<div class="serach_area" style="">
	<div class="topsearch"  id="searchBoxAjax">
		<form onsubmit="return chkValue(this);" method="get" action="/search.html" name="searchForm" id="searchBox">
			<input class="search_put"
				name="keyword"  id="search_keyword"  type="text"
				value="<?php if (! $this->_tpl_vars['keyword']): ?>请输入关键词<?php else: ?><?php echo $this->_tpl_vars['keyword']; ?>
<?php endif; ?>"
				autocomplete="off" onfocus="if(this.value!='请输入关键词'){this.style.color='#404040'}else{this.value='';this.style.color='#404040'}"
				onblur="if(this.value==''){this.value='请输入关键词';this.style.color='#B6B7B9'}"
				onkeydown="this.style.color='#404040';" name="keyword">
				<input class="search_btn" type="submit" value="搜索">
				<?php if ($this->_tpl_vars['u']): ?>
				<input type="hidden" name="u" value="<?php echo $this->_tpl_vars['u']; ?>
">
			<?php endif; ?>
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