<?php /* Smarty version 2.6.19, created on 2014-11-11 08:39:29
         compiled from data-analysis/order-margin.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'data-analysis/order-margin.tpl', 81, false),)), $this); ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>"goods-daily",)));?>">
    <span style="float:left;line-height:18px;">下单日期从：</span>
    <span style="float:left;width:100px;line-height:18px;">
      <input type="text" name="fromdate" id="fromdate" size="12" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
" class="Wdate" onClick="WdatePicker()"/>
    </span>
    <span style="float:left;line-height:18px;">到：</span>
    <span style="float:left;width:100px;line-height:18px;">
      <input type="text" name="todate" id="todate" size="12"  value="<?php echo $this->_tpl_vars['param']['todate']; ?>
" class="Wdate" onClick="WdatePicker()"/>
    </span>
    <span style="float:left;line-height:18px;">发货日期从：</span>
    <span style="float:left;width:100px;line-height:18px;">
      <input type="text" name="send_fromdate" id="send_fromdate" size="12" value="<?php echo $this->_tpl_vars['param']['send_fromdate']; ?>
" class="Wdate" onClick="WdatePicker()"/>
    </span>
    <span style="float:left;line-height:18px;">到：</span>
    <span style="float:left;width:100px;line-height:18px;">
      <input type="text" name="send_todate" id="send_todate" size="12"  value="<?php echo $this->_tpl_vars['param']['send_todate']; ?>
" class="Wdate" onClick="WdatePicker()"/>
    </span>
    <br><br>
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
    <select name="supplier_id">
      <option value="">请选择供应商...</option>
      <?php $_from = $this->_tpl_vars['supplierData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['supplier']):
?>
        <option value="<?php echo $this->_tpl_vars['supplier']['supplier_id']; ?>
" <?php if ($this->_tpl_vars['supplier']['supplier_id'] == $this->_tpl_vars['param']['supplier_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['supplier']['supplier_name']; ?>
</option>
      <?php endforeach; endif; unset($_from); ?>
   </select>
    产品名称：<input name="goods_name" type="text"  size="18" value="<?php echo $this->_tpl_vars['param']['goods_name']; ?>
"/>
    产品编号：<input name="goods_sn" type="text"  size="8" value="<?php echo $this->_tpl_vars['param']['goods_sn']; ?>
"/>
    订单号：<input name="batch_sn" type="text"  size="20" value="<?php echo $this->_tpl_vars['param']['batch_sn']; ?>
"/>
    毛利率小于：<input name="rate" type="text"  style="width:20px" value="<?php echo $this->_tpl_vars['param']['rate']; ?>
"/>%
    <input type="button" name="dosearch" value="开始统计" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('todo'=>'search',)));?>','ajax_search')"/>
    </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
<?php endif; ?>

<div id="ajax_search">

<div class="title">订单商品列表 [<a href="<?php echo $this -> callViewHelper('url', array(array('todo'=>'export',)));?>" target="_blank">导出信息</a>]  </div>
	<div class="content">
	    <a href="javascript:;void(0);" onclick="if (document.getElementById('hint').style.display == '')document.getElementById('hint').style.display = 'none';else document.getElementById('hint').style.display = '';" title="字段说明"><img src="/images/admin/help.gif"></a>
	    <div id="hint" style="display:none">
	    <font color="666666">
	    　* 毛利率 = (订单金额-产品成本)/订单金额
	    </font>
	    </div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>订单号</td>
				<td>店铺</td>
				<td>下单时间</td>
				<td>支付方式</td>
				<td>发货时间</td>
				<td>订单金额</td>
				<td>产品成本</td>
				<td>毛利率</td>
			  </tr>
		</thead>
		<tbody>
		<?php if ($this->_tpl_vars['datas']): ?>
		<?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
		<tr>
		  <td><a href="/admin/order/info/batch_sn/<?php echo $this->_tpl_vars['data']['batch_sn']; ?>
" target="_blank"><?php echo $this->_tpl_vars['data']['batch_sn']; ?>
</a></td>
		  <td><?php echo $this->_tpl_vars['data']['shop_name']; ?>
</td>
		  <td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
		  <td><?php if ($this->_tpl_vars['data']['pay_type'] == 'cod'): ?>货到付款<?php else: ?>款到发货<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['logistic_time']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['logistic_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
<?php endif; ?></td>
		  <td><?php echo $this->_tpl_vars['data']['price_order']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['data']['cost']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['data']['benefit_rate']; ?>
%</td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>
		</tbody>
		</table>
	</div>
	<div style="padding:0 5px;">
	</div>
	<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</div>	