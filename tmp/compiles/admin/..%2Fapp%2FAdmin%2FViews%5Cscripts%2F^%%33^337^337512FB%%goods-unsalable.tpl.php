<?php /* Smarty version 2.6.19, created on 2014-10-24 15:06:17
         compiled from data-analysis/goods-unsalable.tpl */ ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm">
    商品编码：<input name="product_sn" id="product_sn" type="text"  size="10" value="<?php echo $this->_tpl_vars['param']['product_sn']; ?>
" />
    商品名称：<input name="product_name" id="product_name" type="text"  size="18" value="<?php echo $this->_tpl_vars['param']['product_name']; ?>
" />
    滞销天数小于：<input name="days" id="days" type="text"  size="2" value="<?php echo $this->_tpl_vars['param']['days']; ?>
" />
    <input type="checkbox" name="onlyShowHaveStockNumber" value="1" <?php if ($this->_tpl_vars['param']['onlyShowHaveStockNumber']): ?>checked<?php endif; ?>/>只显示当前库存大于0的产品
    <input type="button" name="dosearch" value="按条件搜索" onclick="if (document.getElementById('days').value == ''){alert('滞销天数必须输入!');return false;} ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('todo'=>'search',)));?>','ajax_search')"/>
  </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
<?php endif; ?>

<div id="ajax_search">

<div class="title">产品滞销列表 [<a href="<?php echo $this -> callViewHelper('url', array(array('todo'=>'export',)));?>" target="_blank">导出信息</a>]</div>
	<div class="content">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td >产品编码</td>
				<td >产品名称</td>
				<td >规格</td>
				<td >滞销天数</td>
				<td >当前库存</td>
			  </tr>
		</thead>
		<tbody>
		<?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
		<tr>
			<td><?php echo $this->_tpl_vars['item']['product_sn']; ?>
 </td>
			<td><?php echo $this->_tpl_vars['item']['product_name']; ?>
 </td>
			<td><?php echo $this->_tpl_vars['item']['goods_style']; ?>
 </td>
			<td><?php echo $this->_tpl_vars['item']['days']; ?>
 </td>
			<td><?php echo $this->_tpl_vars['item']['stock_number']; ?>
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