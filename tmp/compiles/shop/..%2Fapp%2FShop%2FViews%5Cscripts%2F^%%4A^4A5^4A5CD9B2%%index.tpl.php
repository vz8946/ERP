<?php /* Smarty version 2.6.19, created on 2014-11-18 03:26:40
         compiled from group-goods/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'group-goods/index.tpl', 30, false),)), $this); ?>
<link rel="stylesheet" type="text/css" href="/_static/css/list.css"/>
<link rel="stylesheet" type="text/css" href="/Public/css/sale.css"/>
<div class="position wbox">
	<b><a href="/group-goods/">组合商品</a></b><span> &gt; 最值得信赖的保健商城</span>
</div>

<div class="wbox">
	<div class="rotave">
		<ul id="adv_113_100" style="position: relative; width: 758px; height: 302px;">
			<li style="position: absolute; top: 0px; left: 0px; display: block; z-index: 4; opacity: 1; width: 758px; height: 302px;">
				<a target="_blank" href="#"><img alt="组合商品" src="/images/group.jpg"></a>
			</li>
		</ul>
	</div>
	<div class="radv218">
		<a title="基维斯蜂蜜" target="_blank" href="#"><img width="218" height="298" border="0" alt="基维斯蜂蜜" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/img/2012/10/31/210019_218X298.jpg"></a>
	</div>
</div>
<?php echo '<div class="groups mar" style="width: 990px;margin: 0px auto;"><div class="ad" style="display:none;"><a href="/special-38.html" target="_blank"><img src="'; ?><?php echo $this->_tpl_vars['imgBaseUrl']; ?><?php echo '/images/shop/index_ad02.jpg" /></a></div><ul class="clear grouplist">'; ?><?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['data']):
?><?php echo '<li style="'; ?><?php if ($this->_tpl_vars['key']%2 == 1): ?><?php echo 'margin-right: 0px;'; ?><?php endif; ?><?php echo 'position:relative;" ><div style="position: absolute;left:0px;top: 0px;"><a style="position: absolute;left:20px;top:26px;display: block;"href="/groupgoods-'; ?><?php echo $this->_tpl_vars['data']['group_id']; ?><?php echo '.html" target="_blank"><img style="border:none;" src="'; ?><?php echo $this->_tpl_vars['imgBaseUrl']; ?><?php echo '/'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['group_goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_180_180.') : smarty_modifier_replace($_tmp, '.', '_180_180.')); ?><?php echo '" alt="'; ?><?php echo $this->_tpl_vars['data']['goods_name']; ?><?php echo '"/></a><p style="position: absolute;left:220px;top:24px;width: 250px;"><a href="/groupgoods-'; ?><?php echo $this->_tpl_vars['data']['group_id']; ?><?php echo '.html" target="_blank" > <span style="font-size: 1.4em;font-family: Microsoft Yahei;">'; ?><?php echo $this->_tpl_vars['data']['group_sale_name']; ?><?php echo '</span> </a></p><p style="position:absolute;left:220px;top:90px; width: 271px;background: #f5f5f5;height: 90px;text-align: left;"><span style="padding:5px;font-size: 12px;">&nbsp;&nbsp;市场价：¥'; ?><?php echo $this->_tpl_vars['data']['group_market_price']; ?><?php echo '</span><span class="p1"><a href="javascript:;" onclick="addGroupCart('; ?><?php echo $this->_tpl_vars['data']['group_id']; ?><?php echo ',\'buy\',1);" class="buttons btn-add-cart"></a></span><span style="position: absolute;top:36px;left:18px;"><strong style="color: #fff;">¥'; ?><?php echo $this->_tpl_vars['data']['group_price']; ?><?php echo '</strong></span></p></div></li>'; ?><?php endforeach; endif; unset($_from); ?><?php echo '<div style="clear:both;"></div></ul><div style="clear:both;"></div><div class="pageNav">'; ?><?php echo $this->_tpl_vars['pageNav']; ?><?php echo '</div></div>'; ?>

<script type="text/javascript">
$(function(){
		$('.grouplist').find('li').hover(function(){
			$(this).css({'border':'1px solid red'});
		},function(){
			$(this).css({'border':'1px solid #ddd'});
		});
});
</script>