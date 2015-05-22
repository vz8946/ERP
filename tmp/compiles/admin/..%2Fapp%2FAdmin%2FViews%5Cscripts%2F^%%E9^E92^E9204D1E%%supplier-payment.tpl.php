<?php /* Smarty version 2.6.19, created on 2014-11-11 08:39:31
         compiled from data-analysis/supplier-payment.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'data-analysis/supplier-payment.tpl', 89, false),)), $this); ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm">
    <span style="float:left;line-height:18px;">入库日期从：</span>
    <span style="float:left;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="15" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
" class="Wdate" onClick="WdatePicker()" /></span>
    <span style="float:left;line-height:18px;">到：</span>
    <span style="float:left;line-height:18px;"><input type="text" name="todate" id="todate" size="15" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
" class="Wdate" onClick="WdatePicker()" /></span>
    &nbsp;&nbsp;
    供应商：
    <select name="supplier_id">
	  <option value="">请选择...</option>
      <?php $_from = $this->_tpl_vars['supplier']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['s']):
?>
        <?php if ($this->_tpl_vars['s']['status'] == 0): ?>
          <option value="<?php echo $this->_tpl_vars['s']['supplier_id']; ?>
" <?php if ($this->_tpl_vars['param']['supplier_id'] == $this->_tpl_vars['s']['supplier_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['s']['supplier_name']; ?>
</option>
        <?php endif; ?>
      <?php endforeach; endif; unset($_from); ?>
	</select>
	采购类型：
    <select name="purchase_type">
	  <option value="">请选择...</option>
      <option value="1" <?php if ($this->_tpl_vars['param']['purchase_type'] == '1'): ?>selected<?php endif; ?>>经销</option>
      <option value="2" <?php if ($this->_tpl_vars['param']['purchase_type'] == '2'): ?>selected<?php endif; ?>>代销</option>
	</select>
	付款状态：
	<select name="status">
	  <option value="">请选择...</option>
      <option value="0" <?php if ($this->_tpl_vars['param']['status'] == '0'): ?>selected<?php endif; ?>>未付款</option>
      <option value="1" <?php if ($this->_tpl_vars['param']['status'] == '1'): ?>selected<?php endif; ?>>部分付款</option>
      <option value="2" <?php if ($this->_tpl_vars['param']['status'] == '2'): ?>selected<?php endif; ?>>已付款</option>
	</select>
    <br><br>
    产品编码：<input name="product_sn" id="product_sn" type="text"  size="10" value="<?php echo $this->_tpl_vars['param']['product_sn']; ?>
" />
    产品名称：<input name="product_name" id="product_name" type="text"  size="18" value="<?php echo $this->_tpl_vars['param']['product_name']; ?>
" />
   <input type="button" name="dosearch" value="按条件搜索" onclick="ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('todo'=>'search',)));?>','ajax_search')"/>
  </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
<?php endif; ?>

<div id="ajax_search">

<div class="title">供应商付款统计 <!--[<a href="<?php echo $this -> callViewHelper('url', array(array('todo'=>'export',)));?>" target="_blank">导出信息</a>]--> </div>
	<div class="content">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
			  <?php if ($this->_tpl_vars['param']['supplier_id']): ?>
			  <td>供应商</td>
			  <td>单据编号</td>
			  <td>采购类型</td>
			  <td>应付金额(含税)</td>
			  <td>应付金额(不含税）</td>
			  <td>付款状态</td>
			  <td>入库日期</td>
			  <?php else: ?>
			  <td>供应商</td>
			  <td>应付金额(含税)</td>
			  <td>应付金额(不含税）</td>
			  <?php endif; ?>
		    </tr>
		</thead>
		<tbody>
		  <?php if ($this->_tpl_vars['datas']): ?>
		  <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
		  <tr>
		    <?php if ($this->_tpl_vars['param']['supplier_id']): ?>
		    <td><?php echo $this->_tpl_vars['data']['supplier_name']; ?>
</td>
		    <td><?php echo $this->_tpl_vars['data']['bill_no']; ?>
</td>
		    <td><?php if ($this->_tpl_vars['data']['purchase_type'] == 1): ?>经销<?php else: ?>代销<?php endif; ?></td>
		    <td><?php echo $this->_tpl_vars['data']['amount1']; ?>
</td>
		    <td><?php echo $this->_tpl_vars['data']['amount2']; ?>
</td>
		    <td>
		      <?php if ($this->_tpl_vars['data']['status'] == 1): ?>
		      部分付款
		      <?php elseif ($this->_tpl_vars['data']['status'] == 2): ?>
		      已付款
		      <?php else: ?>
		      未付款
		      <?php endif; ?>
		    </td>
		    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['finish_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
</td>
		    <?php else: ?>
		    <td><?php echo $this->_tpl_vars['data']['supplier_name']; ?>
</td>
		    <td><?php echo $this->_tpl_vars['data']['amount1']; ?>
</td>
		    <td><?php echo $this->_tpl_vars['data']['amount2']; ?>
</td>
		    <?php endif; ?>
		  </tr>
		  <?php endforeach; endif; unset($_from); ?>
		  <thead>
		  <tr>
		    <td>合计</td>
		  <?php if ($this->_tpl_vars['param']['supplier_id']): ?>
		    <td>*</td>
		    <td>*</td>
		    <td><?php echo $this->_tpl_vars['totalData']['amount1']; ?>
</td>
		    <td><?php echo $this->_tpl_vars['totalData']['amount2']; ?>
</td>
		    <td>*</td>
		    <td>*</td>
		  <?php else: ?>
		    <td><?php echo $this->_tpl_vars['totalData']['amount1']; ?>
</td>
		    <td><?php echo $this->_tpl_vars['totalData']['amount2']; ?>
</td>
		  <?php endif; ?>
		  </tr>
		  </thead>
		  <?php endif; ?>
		</tbody>
		</table>
	</div>
	<div style="padding:0 5px;">
	</div>
	<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</div>	