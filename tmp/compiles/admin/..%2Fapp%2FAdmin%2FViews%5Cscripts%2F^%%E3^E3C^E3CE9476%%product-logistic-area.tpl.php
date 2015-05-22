<?php /* Smarty version 2.6.19, created on 2014-10-24 15:06:20
         compiled from data-analysis/product-logistic-area.tpl */ ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>

<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>"logistic-delivery",)));?>">
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
    <span style="float:left;line-height:18px;">发货日期从：</span><span style="float:left;width:100px;line-height:18px;">
    <input type="text" name="fromdate" id="fromdate" size="12"   value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
" class="Wdate" onClick="WdatePicker()" /></span>
    <span style="float:left;line-height:18px;">到：</span><span style="float:left;width:120px;line-height:18px;">
    <input type="text" name="todate" id="todate" size="12"  value="<?php echo $this->_tpl_vars['param']['todate']; ?>
" class="Wdate" onClick="WdatePicker()" /></span>
    产品名称：<input name="product_name" id="product_name" type="text"  size="18" value="<?php echo $this->_tpl_vars['param']['product_name']; ?>
"/>
    产品编号：<input name="product_sn" id="product_sn" type="text"  size="8" value="<?php echo $this->_tpl_vars['param']['product_sn']; ?>
"/>
    <input type="button" name="dosearch" value="开始统计" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('todo'=>'search',)));?>','ajax_search')"/>
    <br>
    地区：
    <select name="province_id">
      <option value="">请选择地区...</option>
      <?php $_from = $this->_tpl_vars['provinceData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['province_name'] => $this->_tpl_vars['province_id']):
?>
        <option value="<?php echo $this->_tpl_vars['province_id']; ?>
" <?php if ($this->_tpl_vars['param']['province_id'] == $this->_tpl_vars['province_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['province_name']; ?>
</option>
      <?php endforeach; endif; unset($_from); ?>
    </select>
    </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
<?php endif; ?>

<div id="ajax_search">

<div class="title">产品发货区域列表   </div>
	<div class="content">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
			    <td>产品编号</td>
			    <td>产品名称</td>
				<td>地区</td>
				<td>发货单数</td>
			  </tr>
		</thead>
		<tbody>
		<?php if ($this->_tpl_vars['datas']): ?>
		<?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
		<tr>
		  <td><?php echo $this->_tpl_vars['data']['product_sn']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['data']['goods_name']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['data']['area_name']; ?>
</td>
		  <td><?php if ($this->_tpl_vars['data']['count']): ?><?php echo $this->_tpl_vars['data']['count']; ?>
<?php else: ?>0<?php endif; ?></td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
		<thead>
		<tr>
		  <td>合计</td>
		  <td></td>
		  <td></td>
		  <td><?php echo $this->_tpl_vars['totalData']['count']; ?>
</td>
		</tr>
		</thead>
		<?php endif; ?>
		</tbody>
		</table>
	</div>
	<div style="padding:0 5px;">
	</div>
</div>	

<script type="text/javascript">

</script>