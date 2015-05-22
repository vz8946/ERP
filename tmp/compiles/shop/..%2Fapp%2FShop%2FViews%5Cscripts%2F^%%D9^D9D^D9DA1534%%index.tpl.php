<?php /* Smarty version 2.6.19, created on 2014-10-30 13:07:19
         compiled from brand/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'brand/index.tpl', 32, false),)), $this); ?>
<link href="../Public/css/global-city.css" type="text/css" rel="stylesheet" />
<link href="../Public/css/detail-bak.css" type="text/css" rel="stylesheet" />
<div class="global-width" style="overflow:hidden;">
<div class="position"><b><a href="/">垦丰商城</a></b> &gt; <a href="/brand.html">品牌馆</a> &gt; <a href="/b-<?php echo $this->_tpl_vars['brand']['as_name']; ?>
/" title="<?php echo $this->_tpl_vars['brand']['brand_name']; ?>
"><?php echo $this->_tpl_vars['brand']['brand_name']; ?>
</a> </div>
<!-- 左侧导航信息 -->
<div class="product-left">
  
	<!-- 产品分类 -->
	<div class="product-leftbox mt10">
		<div class="category-leftbox-title"></div>
		<div class="category">
			<ul>
				<?php $_from = $this->_tpl_vars['cateList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['vo']):
?>
				<li class="greenColor">
					<span class="cate-child-ico"></span>
					<a href="/b-<?php echo $this->_tpl_vars['brand']['as_name']; ?>
/list<?php echo $this->_tpl_vars['vo']['cat_id']; ?>
/" title="<?php echo $this->_tpl_vars['vo']['cat_name']; ?>
"><?php echo $this->_tpl_vars['vo']['cat_name']; ?>
</a>
				</li>
				<?php endforeach; endif; unset($_from); ?>
			</ul>
		</div>
	</div>
	<!-- End 产品分类 -->
  		
	<div class="hot-product mt10">
		<div class="product-box"><span class="product-box-type">HOT</span><span class="product-box-name greenColor">热销排行榜</span></div>
		<div class="product-list">
			<ul>
				<?php $_from = $this->_tpl_vars['hotGoods']['details']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['vo']):
?>
					<?php if ($this->_tpl_vars['key'] == 0): ?>
					<li>
						<p class="product-seq-box"><?php echo $this->_tpl_vars['key']+1; ?>
</p>
						<p class="img_60_60"><a href="/b-<?php echo $this->_tpl_vars['vo']['as_name']; ?>
/detail<?php echo $this->_tpl_vars['vo']['goods_id']; ?>
.html" title="<?php echo $this->_tpl_vars['vo']['goods_name']; ?>
" target="_blank"><img  alt="<?php echo $this->_tpl_vars['vo']['goods_name']; ?>
" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['vo']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
" border="0" height="60px" width="60px"></a></p>
						<p class="product-name"><a href="/b-<?php echo $this->_tpl_vars['vo']['as_name']; ?>
/detail<?php echo $this->_tpl_vars['vo']['goods_id']; ?>
.html" title="<?php echo $this->_tpl_vars['vo']['goods_name']; ?>
" target="_blank"><?php echo $this->_tpl_vars['vo']['goods_name']; ?>
</a></p>
						<p class="product-price">￥<?php echo $this->_tpl_vars['vo']['price']; ?>
 元</p>
					</li>
					<?php else: ?>
					<li>
						<p class="product-seq-box-unshow"><?php echo $this->_tpl_vars['key']+1; ?>
</p>
						<p class="display"><a href="/b-<?php echo $this->_tpl_vars['vo']['as_name']; ?>
/detail<?php echo $this->_tpl_vars['vo']['goods_id']; ?>
.html" title="<?php echo $this->_tpl_vars['vo']['goods_name']; ?>
" target="_blank"><img alt="<?php echo $this->_tpl_vars['vo']['goods_name']; ?>
" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['vo']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
" border="0" height="60px" width="60px"></a></p>
						<p class="product-name-unshow"><a href="/b-<?php echo $this->_tpl_vars['vo']['as_name']; ?>
/detail<?php echo $this->_tpl_vars['vo']['goods_id']; ?>
.html" title="<?php echo $this->_tpl_vars['vo']['goods_name']; ?>
" target="_blank"><?php echo $this->_tpl_vars['vo']['goods_name']; ?>
</a></p>
						<p class="display">￥<?php echo $this->_tpl_vars['vo']['price']; ?>
 元</p>
					</li>
					<?php endif; ?>
				<?php endforeach; endif; unset($_from); ?>
			</ul>
		</div>
	</div>

	<div class="new-product mt10">
		<div class="product-box"><span class="product-box-type">POI</span><span class="product-box-name greenColor">最受关注的产品</span></div>
		<div class="product-list">
			<ul>
				<?php $_from = $this->_tpl_vars['focusGoods']['details']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['vo']):
?>
					<?php if ($this->_tpl_vars['key'] == 0): ?>
					<li>
						<p class="product-seq-box"><?php echo $this->_tpl_vars['key']+1; ?>
</p>
						<p class="img_60_60"><a href="/b-<?php echo $this->_tpl_vars['vo']['as_name']; ?>
/detail<?php echo $this->_tpl_vars['vo']['goods_id']; ?>
.html" title="<?php echo $this->_tpl_vars['vo']['goods_name']; ?>
" target="_blank"><img  alt="<?php echo $this->_tpl_vars['vo']['goods_name']; ?>
" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['vo']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
" border="0" height="60px" width="60px"></a></p>
						<p class="product-name"><a href="/b-<?php echo $this->_tpl_vars['vo']['as_name']; ?>
/detail<?php echo $this->_tpl_vars['vo']['goods_id']; ?>
.html" title="<?php echo $this->_tpl_vars['vo']['goods_name']; ?>
" target="_blank"><?php echo $this->_tpl_vars['vo']['goods_name']; ?>
</a></p>
						<p class="product-price">￥<?php echo $this->_tpl_vars['vo']['price']; ?>
 元</p>
					</li>
					<?php else: ?>
					<li>
						<p class="product-seq-box-unshow"><?php echo $this->_tpl_vars['key']+1; ?>
</p>
						<p class="display"><a href="/b-<?php echo $this->_tpl_vars['vo']['as_name']; ?>
/detail<?php echo $this->_tpl_vars['vo']['goods_id']; ?>
.html" title="<?php echo $this->_tpl_vars['vo']['goods_name']; ?>
" target="_blank"><img alt="<?php echo $this->_tpl_vars['vo']['goods_name']; ?>
" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['vo']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
" border="0" height="60px" width="60px"></a></p>
						<p class="product-name-unshow"><a href="/b-<?php echo $this->_tpl_vars['vo']['as_name']; ?>
/detail<?php echo $this->_tpl_vars['vo']['goods_id']; ?>
.html" title="<?php echo $this->_tpl_vars['vo']['goods_name']; ?>
" target="_blank"><?php echo $this->_tpl_vars['vo']['goods_name']; ?>
</a></p>
						<p class="display">￥<?php echo $this->_tpl_vars['vo']['price']; ?>
 元</p>
					</li>
					<?php endif; ?>
				<?php endforeach; endif; unset($_from); ?>
			</ul>
		</div>
	</div>

  <div class="product-leftbox mt10">
    <div class="blod paddingLeft13px global-title-bg greenColor">您最近浏览过的商品</div>
    <div class="search_left_history">
    	<?php if ($this->_tpl_vars['history']): ?>
    	<?php $_from = $this->_tpl_vars['history']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['vo']):
?>
    	<ul>
      		<li>
			     <div class="left_history_pic lfloat di"> <a href="/b-<?php echo $this->_tpl_vars['vo']['as_name']; ?>
/detail<?php echo $this->_tpl_vars['vo']['goods_id']; ?>
.html" title="<?php echo $this->_tpl_vars['vo']['goods_name']; ?>
" target="_blank"><img  alt="<?php echo $this->_tpl_vars['vo']['goods_name']; ?>
" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['vo']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
" border="0" height="58px" width="58px"></a></div>
			     <div class="left_history_text lfloat"><a href="/b-<?php echo $this->_tpl_vars['vo']['as_name']; ?>
/detail<?php echo $this->_tpl_vars['vo']['goods_id']; ?>
.html" title="<?php echo $this->_tpl_vars['vo']['goods_name']; ?>
"><?php echo $this->_tpl_vars['vo']['goods_name']; ?>
</a></div>
                 <span class="left_history-price">￥<?php echo $this->_tpl_vars['vo']['price']; ?>
 元</span>
			     <div class="cb mt5"></div>
			</li>
		</ul>
		<?php endforeach; endif; unset($_from); ?>
		<?php else: ?>
			<font style='color:#999999;padding-left:50px;'>暂无浏览记录！</font>
		<?php endif; ?>
	</div>
    <div class="history_more"><a href="javascript:clearCook('/clearhistory.html');" rel="nofollow">清除列表</a></div>
  </div>
  
  <div style="width:183px; clear:both; ">&nbsp;&nbsp;&nbsp;</div>
  <script>
	  	//清空浏览记录
	  function clearCook(url){
	        $.ajax({
				type: "GET",
				cache:false,
				url: url,
				success: function(){
					$(".search_left_history").html("<font style='color:#999999;padding-left:50px;'>暂无浏览记录！</font>");
				}
			});
	  }
  </script>
  <!--history  end-->


</div><!-- end product-left -->

<div class="product-right">
	<div class="product-filter">
    	<div class="product-filter-title">
                <span class="lfloat greenColor"><?php echo $this->_tpl_vars['brand']['brand_name']; ?>
</span>
                <span class="rfloat" id="show-filter"><strong>-</strong> 收起</span>
        </div>
        <div id="filtercontent" class="product-filter-content">
        	<dl>
            	<img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['brand']['big_logo']; ?>
" height="243" width="800">
             </dl>
            <dl class="brand-introduce-box">
            	<dd class="brand-introduce-text">
            		<?php echo $this->_tpl_vars['brand']['brand_desc']; ?>

            	</dd>
            </dl>
        </div>
    </div>
<!-- 产品列表 -->
    <div class="list-bar">排序：
    	<?php if ($this->_tpl_vars['orderby'] == 'time'): ?>
    		 <a rel="nofollow" href="/b-<?php echo $this->_tpl_vars['brand']['as_name']; ?>
/sort-time-<?php if ($this->_tpl_vars['asc'] == 'asc'): ?>desc<?php else: ?>asc<?php endif; ?>.html" class="all_screen" title="上架时间">上架时间 <span class="product-<?php if ($this->_tpl_vars['asc'] == 'asc'): ?>asc<?php else: ?>desc<?php endif; ?>"></span></a>
    	<?php else: ?>
    		 <a rel="nofollow" href="/b-<?php echo $this->_tpl_vars['brand']['as_name']; ?>
/sort-time-asc.html" class="all_screen_off" title="上架时间">上架时间 <span class="product-asc"></span></a>
    	<?php endif; ?>
    	<?php if ($this->_tpl_vars['orderby'] == 'price'): ?>
    		 <a rel="nofollow" href="/b-<?php echo $this->_tpl_vars['brand']['as_name']; ?>
/sort-price-<?php if ($this->_tpl_vars['asc'] == 'asc'): ?>desc<?php else: ?>asc<?php endif; ?>.html" class="all_screen" title="价格">价格 <span class="product-<?php if ($this->_tpl_vars['asc'] == 'asc'): ?>asc<?php else: ?>desc<?php endif; ?>"></span></a>
    	<?php else: ?>
			 <a rel="nofollow" href="/b-<?php echo $this->_tpl_vars['brand']['as_name']; ?>
/sort-price-asc.html" class="all_screen_off" title="价格">价格 <span class="product-asc"></span></a>
    	<?php endif; ?>
	</div>

    <div class="product-list-list">
    	<?php $_from = $this->_tpl_vars['goodsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['vo']):
?>
    	<ul>
			<li class="product-list-goods-img">
				<a title="<?php echo $this->_tpl_vars['vo']['goods_name']; ?>
" href="/b-<?php echo $this->_tpl_vars['brand']['as_name']; ?>
/detail<?php echo $this->_tpl_vars['vo']['goods_id']; ?>
.html">
						<img alt="<?php echo $this->_tpl_vars['vo']['goods_name']; ?>
" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['vo']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_180_180.') : smarty_modifier_replace($_tmp, '.', '_180_180.')); ?>
" border="0" height="168" width="168">
				</a>
			</li>
			<li class="product-list-goods-info" style="height:40px;">
				<a title="<?php echo $this->_tpl_vars['vo']['goods_name']; ?>
" href="/b-<?php echo $this->_tpl_vars['brand']['as_name']; ?>
/detail<?php echo $this->_tpl_vars['vo']['goods_id']; ?>
.html"><?php echo $this->_tpl_vars['vo']['goods_name']; ?>
</a>
			</li>
			<li class="product-list-goods-info"><span class="throughtText global-color">市场价：<?php echo $this->_tpl_vars['vo']['market_price']; ?>
元</span>
            </li>
            <li class="product-list-goods-info global-color"><span class="priceColor blod">￥<?php echo $this->_tpl_vars['vo']['price']; ?>
元</span> </li>
		</ul>
		<?php endforeach; endif; unset($_from); ?>
		</div><!-- end 产品列表></div> -->
    <div class="product-split-page"><?php echo $this->_tpl_vars['pagenav']; ?>
          </div>
    
</div><!-- end product-right -->
</div>
<?php if ($this->_tpl_vars['news']): ?>
<div class="category-news global-width">
   <dl class="category-news-title global-title-bg greenColor"><span class="lfloat">相关资讯</span> <span class="global-more-bg rfloat a-color-white marginTop10Left20"></span></dl>
   <dl class="category-news-list">
   		<ul>
   			<?php $_from = $this->_tpl_vars['news']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['vo']):
?>
   				<?php if ($this->_tpl_vars['key'] < 3): ?>
	            <li style="border-top:none;">
	            	<span class="global-dian"></span>
					<a target="_blank" title="<?php echo $this->_tpl_vars['vo']['title']; ?>
" href="<?php echo $this->_tpl_vars['newsBaseUrl']; ?>
/<?php echo $this->_tpl_vars['vo']['asName']; ?>
/news-<?php echo $this->_tpl_vars['vo']['id']; ?>
.html"><?php echo $this->_tpl_vars['vo']['title']; ?>
</a>
				</li>
				<?php else: ?>
				<li>
	            	<span class="global-dian"></span>
					<a target="_blank" title="<?php echo $this->_tpl_vars['vo']['title']; ?>
" href="<?php echo $this->_tpl_vars['newsBaseUrl']; ?>
/<?php echo $this->_tpl_vars['vo']['asName']; ?>
/news-<?php echo $this->_tpl_vars['vo']['id']; ?>
.html"><?php echo $this->_tpl_vars['vo']['title']; ?>
</a>
				</li>
				<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
		</ul>
    </dl>
</div>
<?php endif; ?>
<script type="text/jscript" src="../Public/js/minBrand.js"></script>