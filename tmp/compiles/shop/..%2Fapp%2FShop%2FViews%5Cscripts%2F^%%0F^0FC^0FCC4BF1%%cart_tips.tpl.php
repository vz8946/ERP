<?php /* Smarty version 2.6.19, created on 2014-11-01 15:03:14
         compiled from _library/cart_tips.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', '_library/cart_tips.tpl', 9, false),array('modifier', 'number_format', '_library/cart_tips.tpl', 43, false),)), $this); ?>
﻿
<?php if ($this->_tpl_vars['data'] || $this->_tpl_vars['other']): ?>
<div id="msg_cart" class="fl"><b class="c2">该商品已成功放入购物车</b></div>
<table class="cart-goods" style="width:470px">	 
	<?php if ($this->_tpl_vars['data']): ?>
		<?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['goods']):
?>
		<tr>
			<td width="300">
			<a href="/goods-<?php echo $this->_tpl_vars['goods']['goods_id']; ?>
.html" target="_blank"><img width="30" height="30" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['goods']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
"/></a>
			<span><a href="/goods-<?php echo $this->_tpl_vars['goods']['goods_id']; ?>
.html" target="_blank" title="<?php echo $this->_tpl_vars['goods']['goods_name']; ?>
"><?php echo $this->_tpl_vars['goods']['goods_name']; ?>
</a></span></td>
			<td width="*" align="right" style="padding-right:10px;">
			<span class="c2">￥<?php echo $this->_tpl_vars['goods']['price']; ?>
</span> <em>×<?php echo $this->_tpl_vars['goods']['number']; ?>
</em><br/>
			<a class="c5"  href="javascript:;" onclick="delCartGoods(<?php echo $this->_tpl_vars['goods']['product_id']; ?>
,<?php echo $this->_tpl_vars['goods']['number']; ?>
,'tips');return false;">删除</a></td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
	<?php endif; ?>		
		<?php if ($this->_tpl_vars['other']): ?>	
		<?php $_from = $this->_tpl_vars['other']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['other']):
?>
		<tr id="del_id_<?php echo $this->_tpl_vars['other']['group_id']; ?>
">
			<td width="300"><a href="/group-goods" target="_blank"><img width="30" height="30" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['other']['group_goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
"/></a>
			<span><a href="/group-goods" target="_blank"><?php echo $this->_tpl_vars['other']['group_goods_name']; ?>
</a></span></td>
			<td width="*" align="right" style="padding-right:10px;">
				<span class="c2">￥<?php echo $this->_tpl_vars['other']['group_price']; ?>
</span> <em>×<?php echo $this->_tpl_vars['other']['number']; ?>
</em><br/>
				<a  class="c5" href="javascript:;" onclick="delGroupGoods(<?php echo $this->_tpl_vars['other']['group_id']; ?>
,'tips');">删除</a></td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>	
	<?php endif; ?>		
	</table>
<?php endif; ?>

 <?php if ($this->_tpl_vars['data'] || $this->_tpl_vars['other']): ?>
	    <div class="fr" style="padding:10px 30px 10px 0;">
		<div class="tot" >
		<?php if ($this->_tpl_vars['offers']): ?>
		   <?php $_from = $this->_tpl_vars['offers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tmp']):
?>
			<?php $_from = $this->_tpl_vars['tmp']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['o']):
?>
			<?php if ($this->_tpl_vars['o']['offers_type'] == 'minus'): ?>
			<span class="fb">活动</span>(<span class="c2"><?php echo $this->_tpl_vars['o']['offers_name']; ?>
</span>)：<span class="c2"><?php echo $this->_tpl_vars['o']['price']; ?>
</span>元 <br/>
			<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
			<?php endforeach; endif; unset($_from); ?>
	    <?php endif; ?>			
		购物车内共<?php echo $this->_tpl_vars['number']; ?>
件商品总计：<b class="c2 f14">￥<?php echo ((is_array($_tmp=$this->_tpl_vars['amount'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>
</b>
		</div>
					
		<div id="checknow" style="text-align:center;clear:both;padding-top:5px">
		  <a href="/flow/index"><img src="/_static/images/shop/btn_check_right_now.jpg" /></a>&nbsp;&nbsp;
		  <a style="position:relative;top:-12px;" href="javascript:location.reload();">继续购物&raquo;&raquo;</a>
		</div>
		
	<?php else: ?>	 
	 <div style="text-align:center;"><b class="c2">您的购物车中没有任何商品</b> </div>
<?php endif; ?>
</div>