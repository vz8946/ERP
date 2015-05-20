<?php /* Smarty version 2.6.19, created on 2014-10-24 15:06:15
         compiled from data-analysis/user-goods.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'data-analysis/user-goods.tpl', 70, false),)), $this); ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm">
    <span style="float:left;line-height:18px;">
    <select name="shop_id">
        <option value="">请选择店铺...</option>
        <?php $_from = $this->_tpl_vars['shopDatas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['shop']):
?>
        <option value="<?php echo $this->_tpl_vars['shop']['shop_id']; ?>
" <?php if ($this->_tpl_vars['shop']['shop_id'] == $this->_tpl_vars['param']['shop_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['shop']['shop_name']; ?>
</option>
        <?php endforeach; endif; unset($_from); ?>
        <option value="0" <?php if ($this->_tpl_vars['param']['shop_id'] == '0'): ?>selected<?php endif; ?>>内部订单</option>
    </select>&nbsp;&nbsp;
    </span>
    <span style="float:left;line-height:18px;">选择日期从：</span>
    <span style="float:left;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="15" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
" class="Wdate" onClick="WdatePicker()" /></span>
    <span style="float:left;line-height:18px;">到：</span>
    <span style="float:left;line-height:18px;"><input type="text" name="todate" id="todate" size="15" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
" class="Wdate" onClick="WdatePicker()" /></span>
    &nbsp;&nbsp;
    订单状态:<select name="status">
			<option value="1" <?php if ($this->_tpl_vars['param']['status'] == '1'): ?>selected<?php endif; ?>>有效单</option>
			<option value="2" <?php if ($this->_tpl_vars['param']['status'] == '2'): ?>selected<?php endif; ?>>取消单/无效单</option>
			</select>
    <br><br>
    商品编码：<input name="goods_sn" id="goods_sn" type="text"  size="10" value="<?php echo $this->_tpl_vars['param']['goods_sn']; ?>
" />
    商品名称：<input name="goods_name" id="goods_name" type="text"  size="18" value="<?php echo $this->_tpl_vars['param']['goods_name']; ?>
" />

<input type="button" name="dosearch" value="按条件搜索" onclick="if (document.getElementById('goods_sn').value == '' && document.getElementById('goods_name').value == ''){alert('必须输入搜索的商品信息!');return false;} ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('todo'=>'search',)));?>','ajax_search')"/>
  </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
<?php endif; ?>

<div id="ajax_search">

<div class="title">客户购买记录 [<a href="<?php echo $this -> callViewHelper('url', array(array('todo'=>'export',)));?>" target="_blank">导出信息</a>] </div>
	<div class="content">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td >姓名</td>
				<!--<td >手机</td>-->
				<!--<td >固话</td>-->
				<td >渠道站点来源</td>
				<td >购买产品</td>
				<td >购买次数</td>
				<td >购买数量</td>
				<td >购买总金额</td>
				<td >首次购买时间</td>
				<td >最近购买时间</td>
			  </tr>
		</thead>
		<tbody>
		<?php $_from = $this->_tpl_vars['userGoodslist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
		<tr>
			<td><?php echo $this->_tpl_vars['item']['addr_consignee']; ?>
 </td>
			<!--<td><?php echo $this->_tpl_vars['item']['addr_mobile']; ?>
 </td>-->
			<!--<td><?php echo $this->_tpl_vars['item']['addr_tel']; ?>
 </td>-->
			<td><?php echo $this->_tpl_vars['item']['shop_name']; ?>
 </td>
			<td><?php echo $this->_tpl_vars['item']['goods_name']; ?>
 </td>
			<td><?php echo $this->_tpl_vars['item']['order_count']; ?>
 </td>
			<td><?php echo $this->_tpl_vars['item']['goods_number']; ?>
 </td>
			<td><?php echo $this->_tpl_vars['item']['amount']; ?>
 </td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M")); ?>
 </td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['last_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M")); ?>
 </td>
		  </tr>
		<?php endforeach; endif; unset($_from); ?>
		</tbody>
		</table>
	</div>
	<div style="padding:0 5px;">
	</div>
	<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</div>	