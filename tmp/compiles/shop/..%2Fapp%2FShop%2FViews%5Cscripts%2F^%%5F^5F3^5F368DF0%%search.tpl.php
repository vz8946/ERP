<?php /* Smarty version 2.6.19, created on 2014-10-30 13:41:05
         compiled from goods/search.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'goods/search.tpl', 69, false),array('modifier', 'replace', 'goods/search.tpl', 71, false),)), $this); ?>
<div style="width: 1200px; margin: 0px auto;">
	<style>
.list_side .stitle2 {
	height: 36px;
}

.left ul {
	border: none;
	padding: 0px;
}

ul.choose_list li {
	padding: 5px;
	padding-bottom: 10px;
}

.item-h img {
	border: 1px solid #FF6600;
	width: 152px !important;
	height: 152px !important;
	padding: 3px;
}

.item-h {
	background: #f5f5f5;
}

.clearfix {
    display: inline-block;
}

.list_side .mod .keylist li {
    background: url("/images/listfix2.png") no-repeat scroll 22px 14px transparent;
    color: #999999;
    line-height: 16px;
    padding-left: 40px;
}

</style>

	<div class="main mar">
		<div class="cleardiv"></div>
		<!--<div class="pos"><?php echo $this->_tpl_vars['ur_here']; ?>
</div>-->
		<div class="left list_side" style="padding-top: 20px; width: 220px;">
			<div class="mod">
				<h2 class="stitle">种子</h2>
				<div class="conts">
					<ul class="keylist">
						<?php $_from = $this->_tpl_vars['cat_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
						<li>
							<a href="/gallery-<?php echo $this->_tpl_vars['v']['cat_id']; ?>
-0-0-0-1.html" target="_blank" ><?php echo $this->_tpl_vars['v']['cat_name']; ?>
</a>
						</li>
						<?php endforeach; endif; unset($_from); ?>
					</ul>
				</div>
			</div>

			<div class="mod">
				<h2 class="stitle2">
					<a rel="nofollow" href="javascript:clearCook('/clearhistory');">清空</a>历史浏览记录
				</h2>
				<div class="conts" id="historyBox">
					<ul class="hislist">
						<?php if ($this->_tpl_vars['history']): ?> <?php $_from = $this->_tpl_vars['history']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
						<li class="clearfix">
							<div class="img">
								<div class="wh60 verticalPic">
									<a
										href="/b-<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['as_name'])) ? $this->_run_mod_handler('default', true, $_tmp, 'jiankang') : smarty_modifier_default($_tmp, 'jiankang')); ?>
/detail<?php echo $this->_tpl_vars['v']['goods_id']; ?>
.html"
										title="<?php echo $this->_tpl_vars['v']['goods_name']; ?>
"><img
										src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['v']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_380_380.') : smarty_modifier_replace($_tmp, '.', '_380_380.')); ?>
"
										alt="<?php echo $this->_tpl_vars['v']['goods_name']; ?>
" width="60" height="60"></a>
								</div>
							</div>
							<div class="txt"
								style="padding-left: 10px; width: 120px; overflow: hidden;">
								<p class="title">
									<a
										href="/b-<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['as_name'])) ? $this->_run_mod_handler('default', true, $_tmp, 'jiankang') : smarty_modifier_default($_tmp, 'jiankang')); ?>
/detail<?php echo $this->_tpl_vars['v']['goods_id']; ?>
.html"
										title="<?php echo $this->_tpl_vars['v']['goods_name']; ?>
"><?php echo $this->_tpl_vars['v']['goods_name']; ?>
</a>
								</p>
								<p class="Sprice">
									￥<span><?php echo $this->_tpl_vars['v']['price']; ?>
</span>
								</p>
							</div>
						</li> <?php endforeach; endif; unset($_from); ?> <?php else: ?>
						<div style="padding: 10px;">暂无浏览记录！</div>
						<?php endif; ?>

					</ul>
				</div>
			</div>

		</div>
		<!--left end-->
		<div class="rig">



			<div id="selection_container" class="choose"
				style="border: none; background: none; padding: 0px; height: auto;">
				<div class="product-filter">
					<div class="product-filter-title">
						<span class="lfloat greenColor"><b>商品筛选 - <?php echo $this->_tpl_vars['keywords']; ?>
</b></span><span
							id="show-filter" class="rfloat"></span>
					</div>
					<div class="product-filter-content">
						<dl>
							<dd class="filter-name">
								品牌:
							</dd>
							<dd class="filter-info">
								<?php $_from = $this->_tpl_vars['filter_brand']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
								<a href="<?php echo $this->_tpl_vars['v']['url']; ?>
" class="<?php if ($this->_tpl_vars['v']['is_c']): ?>all_screen<?php endif; ?>" ><?php echo $this->_tpl_vars['v']['brand_name']; ?>
</a>&nbsp;
								<?php endforeach; endif; unset($_from); ?>
							</dd>
						</dl>
						<dl style="border-top:1px dashed #d9d9d9">
							<dd class="filter-name">
								价格:
							</dd>
							<dd class="filter-info">
								<?php $_from = $this->_tpl_vars['filter_price']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
								<a href="<?php echo $this->_tpl_vars['v']['url']; ?>
" class="<?php if ($this->_tpl_vars['v']['is_c']): ?>all_screen<?php endif; ?>"><?php echo $this->_tpl_vars['v']['price_name']; ?>
</a>&nbsp;
								<?php endforeach; endif; unset($_from); ?>
							</dd>
						</dl>
					</div>
				
				</div>

				<ul class="choose_list" id="itemArray" style="clear:both;">

					<?php if ($this->_tpl_vars['list']): ?><?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
					<li><a
						href="/b-<?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['as_name'])) ? $this->_run_mod_handler('default', true, $_tmp, 'jiankang') : smarty_modifier_default($_tmp, 'jiankang')); ?>
/detail<?php echo $this->_tpl_vars['data']['goods_id']; ?>
.html"
						target="_blank"> <img  src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_380_380.') : smarty_modifier_replace($_tmp, '.', '_380_380.')); ?>
" alt="<?php echo $this->_tpl_vars['data']['goods_name']; ?>
" /></a>
						<p style="height: 60px; padding-top: 8px;">
							<a href="/b-<?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['as_name'])) ? $this->_run_mod_handler('default', true, $_tmp, 'jiankang') : smarty_modifier_default($_tmp, 'jiankang')); ?>
/detail<?php echo $this->_tpl_vars['data']['goods_id']; ?>
.html"><?php echo $this->_tpl_vars['data']['goods_title']; ?>
 <?php echo $this->_tpl_vars['data']['goods_style']; ?>
 </a>
						</p> <span><?php echo $this->_tpl_vars['data']['goods_alt']; ?>
</span> <em>市场价：￥<?php echo $this->_tpl_vars['data']['market_price']; ?>
</em>

						<br /> <span style="display: inline; color: #666;">
							
						<?php if ($this->_tpl_vars['data']['org_price']): ?>	
							<?php if ($this->_tpl_vars['data']['offers_type'] == 'exclusive'): ?>
							专享价：
							<?php elseif ($this->_tpl_vars['data']['offers_type'] == 'price-exclusive'): ?>
							特 价：
							<?php elseif ($this->_tpl_vars['data']['offers_type'] == 'fixed'): ?>
							抢 购 价：
						  <?php elseif ($this->_tpl_vars['data']['offers_type'] == 'discount'): ?>
							<?php echo $this->_tpl_vars['data']['discount_title']; ?>
 	<span>折    价：</span>
							<?php endif; ?>
						
						 <?php else: ?> 

						垦丰价：
						 <?php endif; ?>
							
					</span>

						<span
						style="color: red; display: inline; font-weight: bold;">￥<?php echo $this->_tpl_vars['data']['price']; ?>
</span>

						<!--<a  onclick="addGalleryCart('<?php echo $this->_tpl_vars['data']['goods_sn']; ?>
','1')" alt="放入购物车" name="addtocart" ><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/shop/fenlei_r15_c14.jpg" alt="购买" /></a>
						<a  href="javascript:void(0);" onclick="window.location.replace('/goods/favorite/goodsid/<?php echo $this->_tpl_vars['data']['goods_id']; ?>
');" ><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/shop/fenlei_r15_c16.jpg" alt="收藏"/></a>-->

					</li>
					<?php endforeach; endif; unset($_from); ?>
					<?php else: ?>
					<li style="padding: 20px;text-align: center;">没有搜索到相应的商品！</li>
					<?php endif; ?>
					
					<div style="clear: both;"></div>
				</ul>
				<div style="clear: both;"></div>
				<div class="pagenav1" style="padding-top: 10px;"><?php echo $this->_tpl_vars['pagenav']; ?>
</div>
			</div>
			<!--rig end-->
		</div>
		<!--main end-->
		<div style="clear: both;"></div>
		<script type="text/javascript">
			if (!window.navigator.cookieEnabled)
				alert('请打开您浏览器的Cookie，否则将影响您下单!');
			function addGalleryCart(goods_sn, number) {
				$.ajax({
					url : '/goods/check/product_sn/' + goods_sn + '/number/' + number,
					type : 'get',
					success : function(data) {
						if (data != '') {
							alert(data);
							window.location.replace('<?php echo $this -> callViewHelper('url', array());?>');
						} else {
							window.location.replace('/flow/buy/product_sn/' + goods_sn + '/number/' + number);
						}
					}
				})
			}

			//添加组合商品
			function addGroupCart(g_id) {
				var tmp = parseInt(g_id);
				if (tmp < 1)
					return;
				var num = 1;
				//第一次ajax
				$.ajax({
					url : '/group-goods/check',
					data : {
						group_id : tmp,
						number : num
					},
					type : 'post',
					success : function(msg) {
						if (msg != '') {
							alert(msg);
						} else {
							window.location.replace('/flow/index/');
						}
					}
				});
			}

			/*Start::搜索结果高亮*/
			//关键字高亮
			function SearchHighlight(idVal, kw) {
				var keyword = sortkeyword(kw);
				var pucl = document.getElementById(idVal);
				if ("" == keyword)
					return;
				var temp = pucl.innerHTML;
				var htmlReg = new RegExp("\<.*?\>", "i");
				var arrA = new Array();
				//替换HTML标签
				for (var i = 0; true; i++) {
					var m = htmlReg.exec(temp);
					if (m) {
						arrA[i] = m;
					} else {
						break;
					}
					temp = temp.replace(m, "{[(" + i + ")]}");
				}
				words = unescape(keyword.replace(/\+/g, ' ')).split(/\s+/);
				//替换关键字
				for ( w = 0; w < words.length; w++) {
					var r = new RegExp("(" + words[w].replace(/[(){}.+*?^$|\\\[\]]/g, "\\$&") + ")", "ig");
					temp = temp.replace(r, "<b style='color:Red;'>$1</b>");
				}
				//恢复HTML标签
				for (var i = 0; i < arrA.length; i++) {
					temp = temp.replace("{[(" + i + ")]}", arrA[i]);
				}
				pucl.innerHTML = temp;
			}

			//英文数字排在前边
			function sortkeyword(kw) {
				var key = $.trim(kw.replace(/\+/g, ' '));
				arr = key.split(' ');
				arrayA = new Array();
				arrayB = new Array();
				arrayC = new Array();
				for ( i = 0; i < arr.length; i++) {
					if (/^[A-Za-z]+$/g.test(arr[i])) {
						arrayA.push(arr[i]);
					} else if (/[0-9]/g.test(arr[i])) {
						;
					} else {
						arrayB.push(arr[i]);
					}
				}
				arrayC = arrayA.concat(arrayB);
				keyword = arrayC.join(" ");
				return $.trim(keyword);
			}


			$(document).ready(function() {
				sw = $.trim($.cookie('searchkeywords'));
				if (!sw)
					return;
				SearchHighlight("itemArray", sw);

				$('#itemArray').find('li').hover(function() {
					$(this).addClass('item-h');
				}, function() {
					$(this).removeClass('item-h');
				});

			});
			/*End::搜索结果高亮*/

		</script>
	</div>