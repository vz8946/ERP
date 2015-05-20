<?php /* Smarty version 2.6.19, created on 2014-10-30 13:36:27
         compiled from index/brand.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'index/brand.tpl', 97, false),)), $this); ?>
<div class="brand-city">
<script type=text/javascript src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/Public/topic/js/jquery.slides.min.js"></script>
<div class="position wbox">
	<div class="share">
		<span>分享好友：</span>
		<a href="javascript:openSina();" rel="nofollow"><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/Public/img/detail_icons01.gif"></a>
		<a href="javascript:openQZone();" rel="nofollow"><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/Public/img/detail_icons02.gif"></a>
		<a href="javascript:openWangyi();" rel="nofollow"><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/Public/img/detail_icons04.gif"></a>
		<a href="javascript:openRenrRen();" rel="nofollow"><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/Public/img/detail_icons03.gif"></a>
		<a href="javascript:openQQ();" rel="nofollow"><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/Public/img/detail_icons05.gif"></a>
		<a href="javascript:openKaixin();" rel="nofollow"><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/Public/img/detail_icons06.gif"></a>
	</div>
	<b><a href="/group-goods/">品牌城</a></b><span> &gt; 最值得信赖的种业商城</span>
</div>
<div class="wbox">
	<!-- 滚动图片 -->
	<div id="slides" class="banner slides" style="height: 200px;overflow: hidden;">
		<?php $_from = $this->_tpl_vars['list_banner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
		<a href="<?php echo $this->_tpl_vars['v']['url']; ?>
" target="_blank"><img alt="<?php echo $this->_tpl_vars['v']['name']; ?>
"
		src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['v']['imgUrl']; ?>
"></a>
		<?php endforeach; endif; unset($_from); ?>
	</div>

	<script>
		$(function() {
			$('#slides').slidesjs({
				start : 1,
				play : {
					auto : true,
					interval : 3000,
					swap : true,
					effect : 'fade'
				},
				pagination : {
					active : true,
					effect : "fade"
				},
				effect : {
					fade : {
						speed : 120
					}
				},
				callback : {//回调
					start : function(c, i) {
					}
				}
			});
		});
	</script>
</div>

<div class="query wbox">
	<div class="tops">
		<h3>按首字母查找</h3>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_library/banner-letter.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</div>
</div>

<div class="brand clearfix wbox">
	<div class="bleft">
		<a href="<?php echo $this->_tpl_vars['banner_left']['url']; ?>
" target="_blank" title="<?php echo $this->_tpl_vars['banner_left']['name']; ?>
"> <img width="200" height="160" border="0" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['banner_left']['imgUrl']; ?>
" alt="<?php echo $this->_tpl_vars['banner_left']['name']; ?>
"> </a>
	</div>
	<div class="bright">
		<ul>
			<?php $_from = $this->_tpl_vars['list_banner_right']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
			<li>
				<a target="_blank" title="<?php echo $this->_tpl_vars['v']['name']; ?>
" href="<?php echo $this->_tpl_vars['v']['url']; ?>
">
					<img width="120" height="60" alt="<?php echo $this->_tpl_vars['v']['name']; ?>
"
					src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['v']['imgUrl']; ?>
"></a>
			</li>
			<?php endforeach; endif; unset($_from); ?>
		</ul>
	</div>
</div>

<div class="wbox">

	<?php $_from = $this->_tpl_vars['list_brand']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
	<div class="bitem clearfix">
		<div class="tops">
			<span class="t1"><?php echo $this->_tpl_vars['v']['brand_name']; ?>
</span>
			<span class="t2"></span>
		</div>
		<div class="conts">
			<div class="left">
				<?php if ($this->_tpl_vars['v']['adv']): ?>
				<a href="<?php echo $this->_tpl_vars['v']['adv']['url']; ?>
" target="_blank"> <img width="220" height="340" border="0" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['v']['adv']['imgUrl']; ?>
" alt=""></a>
				<?php endif; ?>
			</div>
			<div class="right">
				<ul>
					<?php if ($this->_tpl_vars['v']['goods']): ?>
					<?php $_from = $this->_tpl_vars['v']['goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['kk'] => $this->_tpl_vars['vv']):
?>
					<li>
						<div class="pic">
							<div class="verticalPic wh180">
								<a href="/b-<?php echo $this->_tpl_vars['vv']['as_name']; ?>
/detail<?php echo $this->_tpl_vars['vv']['goods_id']; ?>
.html" title="<?php echo $this->_tpl_vars['vv']['goods_name']; ?>
" target="_blank"><img width="180" height="180" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['vv']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_180_180.') : smarty_modifier_replace($_tmp, '.', '_180_180.')); ?>
" alt="<?php echo $this->_tpl_vars['vv']['goods_name']; ?>
"></a>
							</div>
						</div>
						<div class="title">
							<a href="/b-<?php echo $this->_tpl_vars['vv']['as_name']; ?>
/detail<?php echo $this->_tpl_vars['vv']['goods_id']; ?>
.html" title="<?php echo $this->_tpl_vars['vv']['goods_name']; ?>
" target="_blank"><?php echo $this->_tpl_vars['vv']['goods_name']; ?>
</a>
						</div>
						<div class="other clearfix">
							<span class="oprice">市场价：<del>¥<?php echo $this->_tpl_vars['vv']['market_price']; ?>
</del></span>
							<span class="discount">折扣：<?php echo $this->_tpl_vars['vv']['saleoff']; ?>
折</span>
						</div>
						<div class="Sprice">
							¥<span><?php echo $this->_tpl_vars['vv']['price']; ?>
</span>
						</div>
						<div class="buy-btn">
							<a href="/b-<?php echo $this->_tpl_vars['vv']['as_name']; ?>
/detail<?php echo $this->_tpl_vars['vv']['goods_id']; ?>
.html" title="<?php echo $this->_tpl_vars['vv']['goods_name']; ?>
" target="_blank">加入购物车</a>
						</div>
					</li>
					<?php endforeach; endif; unset($_from); ?>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</div>
	<?php endforeach; endif; unset($_from); ?>
</div>
<script>
	//品牌类别展示
	$("#letter_menu ul li a").hover(function(){
		var v = $(this).attr("value");
		var small = "#small_"+v;
		var menuList = "#menuList_"+v;
		$(small).show();
		$(menuList).show();
	},function(){
		var v = $(this).attr("value");
		var small = "#small_"+v;
		var menuList = "#menuList_"+v;
		$(menuList).mouseover(function(){
			$(small).show();
			$(menuList).show();
		}).mouseout(function(){
			$(small).hide();
			$(menuList).hide();
		});
		$(small).hide();
			$(menuList).hide();
	});
</script>
<script type=text/javascript src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/Public/js/otherPd.js"></script>
</div>