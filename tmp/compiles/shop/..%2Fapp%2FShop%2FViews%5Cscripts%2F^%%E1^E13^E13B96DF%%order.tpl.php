<?php /* Smarty version 2.6.19, created on 2014-11-20 09:59:41
         compiled from member/order.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'member/order.tpl', 11, false),array('modifier', 'truncate', 'member/order.tpl', 45, false),)), $this); ?>
<div class="member">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "member/menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div class="memberright">
		
        <!--<div class="memberddbg">
			<p>
				符合条件订单总数为：<font color="#D52319"><?php echo $this->_tpl_vars['total']; ?>
</font>
			</p>
		</div>-->
        <div class="person_info"><span class="fl"><em><?php if ($this->_tpl_vars['member']['nick_name']): ?><?php echo $this->_tpl_vars['member']['nick_name']; ?>
<?php else: ?><?php echo $this->_tpl_vars['member']['user_name']; ?>
<?php endif; ?> </em> 欢迎回来！</span>
		<span class="fr"><b>最后登录时间：</b>：<?php echo ((is_array($_tmp=$this->_tpl_vars['member']['last_login'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</span></div>
		<div class="ordertype">
			<select name="timesection" onchange="javascript:location.href=this.value;">
				<option value="/member/order/timesection/1" <?php if ($this->_tpl_vars['params']['timesection'] == 1): ?>selected="selected"<?php endif; ?>>最近一个月</option>
				<option value="/member/order/timesection/2" <?php if ($this->_tpl_vars['params']['timesection'] == 2): ?>selected="selected"<?php endif; ?>>往期订单</option>
				<option value="/member/order/timesection/3" <?php if ($this->_tpl_vars['params']['timesection'] == 3): ?>selected="selected"<?php endif; ?>>所有订单</option>
			</select>
			<div class="ordertypea">
				<a href="/member/order/timesection/<?php echo $this->_tpl_vars['params']['timesection']; ?>
/ordertype/1" <?php if ($this->_tpl_vars['params']['ordertype'] == 1 || ! $this->_tpl_vars['params']['ordertype']): ?> class="sel" <?php endif; ?>>所有</a>
				<a href="/member/order/timesection/<?php echo $this->_tpl_vars['params']['timesection']; ?>
/ordertype/2" <?php if ($this->_tpl_vars['params']['ordertype'] == 2): ?> class="sel" <?php endif; ?>>待确认订单</a>
				<a href="/member/order/timesection/<?php echo $this->_tpl_vars['params']['timesection']; ?>
/ordertype/3" <?php if ($this->_tpl_vars['params']['ordertype'] == 3): ?> class="sel" <?php endif; ?>>已取消订单</a>
				<a href="/member/order/timesection/<?php echo $this->_tpl_vars['params']['timesection']; ?>
/ordertype/4" <?php if ($this->_tpl_vars['params']['ordertype'] == 4): ?> class="sel" <?php endif; ?>>需付款订单</a>
				<a href="/member/order/timesection/<?php echo $this->_tpl_vars['params']['timesection']; ?>
/ordertype/5" <?php if ($this->_tpl_vars['params']['ordertype'] == 5): ?> class="sel" <?php endif; ?>>已付款待发货订单</a>
				<a href="/member/order/timesection/<?php echo $this->_tpl_vars['params']['timesection']; ?>
/ordertype/6" <?php if ($this->_tpl_vars['params']['ordertype'] == 6): ?> class="sel" <?php endif; ?>>已发货订单</a>
				<a href="/member/order/timesection/<?php echo $this->_tpl_vars['params']['timesection']; ?>
/ordertype/7" <?php if ($this->_tpl_vars['params']['ordertype'] == 7): ?> class="sel" <?php endif; ?>>已完成订单</a>
			</div>
		</div>
		<div  style=" cclear:both;">
			<table width="100%" cellspacing="0" cellpadding="0" border="0"  class="re_table">
				<thead>
					<tr>
						<th>订单号</th>
						<th>下单时间</th>
						<th>收货人</th>
						<th>订单总金额</th>
						<th>订单状态</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					<?php $_from = $this->_tpl_vars['orderInfo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['order']):
?>
					<tr>
						<td><strong><a href="<?php echo $this -> callViewHelper('url', array(array('action'=>"order-detail",'batch_sn'=>$this->_tpl_vars['order']['batch_sn'],)));?>"><?php echo $this->_tpl_vars['order']['batch_sn']; ?>
</a></strong></td>
						<td><?php echo $this->_tpl_vars['order']['add_time']; ?>
</td>
						<td><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['addr_consignee'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 8, "...") : smarty_modifier_truncate($_tmp, 8, "...")); ?>
</td>
						<td>￥<?php echo $this->_tpl_vars['order']['price_pay']; ?>
</td>
						<td><?php echo $this->_tpl_vars['order']['deal_status']; ?>
</td>
						<td> <?php if ($this->_tpl_vars['order']['pay_amount'] > 0 && $this->_tpl_vars['order']['status'] == 0 && $this->_tpl_vars['order']['status_logistic'] < 5 && ( $this->_tpl_vars['order']['price_pay']- ( $this->_tpl_vars['order']['price_payed']+$this->_tpl_vars['order']['price_from_return'] ) ) > 0): ?><a class="<?php echo $this->_tpl_vars['order']['batch_sn']; ?>
" href="/member/order-detail/batch_sn/<?php echo $this->_tpl_vars['order']['batch_sn']; ?>
">付款</a><?php endif; ?>
						
						<?php if ($this->_tpl_vars['order']['pay_amount'] > 0 && $this->_tpl_vars['order']['price_payed'] == 0 && $this->_tpl_vars['order']['status'] == 0 && $this->_tpl_vars['order']['status_logistic'] == 0 && $this->_tpl_vars['order']['parent_batch_sn'] == ''): ?><a href="javascript:;" onclick="cancelOrder(<?php echo $this->_tpl_vars['order']['batch_sn']; ?>
);" class="<?php echo $this->_tpl_vars['order']['batch_sn']; ?>
" style="color:#999999;">取消订单</a><?php endif; ?>
						
						<?php if ($this->_tpl_vars['order']['status'] == 0 && $this->_tpl_vars['order']['status_logistic'] >= 3 && $this->_tpl_vars['order']['status_logistic'] <= 4 && $this->_tpl_vars['order']['is_fav'] != 1): ?>
						<form method="post" action="<?php echo $this -> callViewHelper('url', array(array('action'=>'fav','batch_sn'=>$this->_tpl_vars['order']['batch_sn'],)));?>">
							<input type="hidden" name="batch_sn" value="<?php echo $this->_tpl_vars['order']['batch_sn']; ?>
" />
							<input type="submit" value="满意无需退换货"  class="not_return" style="position:relative;top:12px;margin:0"/>
						</form> <?php endif; ?>
						&nbsp; </td>
					</tr>
					<?php endforeach; endif; unset($_from); ?>
				</tbody>
			</table>
			<div class="page_nav">
				<?php echo $this->_tpl_vars['pageNav']; ?>

			</div>

			<?php if ($this->_tpl_vars['showTip'] == 'allow'): ?>
			<div class="remind-txt" style="line-height: 24px;">
				<strong>※温馨提示：</strong>当您购买后三十天内，点击了“满意无须退换货”后，系统将立即赠送您积分，订单商品不再享受退换货服务。如果您未点击“满意无须退换货”系统将在您购物后30天，自动赠送您积分。
			</div>
			<?php endif; ?>

		</div>
	</div>
	<div style="clear:both;"></div>
</div>
	<script>
		function cancelOrder(batch_sn) {
			$.ajax({
				url : '/member/cancel-order/batch_sn/' + batch_sn,
				success : function(msg) {
					if (msg == 'setOrderCancelSucess') {
						alert('取消订单成功');
						$('.' + batch_sn).remove();
					} else if (msg == 'noCancel') {
						alert('不能取消！');
					} else if (msg == 'error') {
						alert('网络繁忙，请稍后重试！');
					} else {
						alert(msg);
					}
				},
				error : function() {
					alert('网络繁忙，请稍后重试！');
				}
			});
		}
	</script>