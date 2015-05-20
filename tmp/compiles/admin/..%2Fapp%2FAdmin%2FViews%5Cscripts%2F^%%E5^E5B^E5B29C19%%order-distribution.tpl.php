<?php /* Smarty version 2.6.19, created on 2014-10-24 15:06:14
         compiled from data-analysis/order-distribution.tpl */ ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>"order-distribution",)));?>">
    <span style="float:left;line-height:18px;">选择日期从：</span><span style="float:left;width:150px;line-height:18px;">
    <input type="text" name="fromdate" id="fromdate" size="12"   value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
" class="Wdate" onClick="WdatePicker()"/></span>
    <span style="float:left;line-height:18px;">到：</span><span style="float:left;width:150px;line-height:18px;">
    <input type="text" name="todate" id="todate" size="12"  value="<?php echo $this->_tpl_vars['param']['todate']; ?>
" class="Wdate" onClick="WdatePicker()" /></span>
    <input type="button" name="dosearch" value="开始统计" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('todo'=>'search',)));?>','ajax_search')"/>
    </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
<?php endif; ?>

<div id="ajax_search">

<div class="title">订单数量列表   </div>
	<div class="content">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>订单来源</td>
				<td><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'TotalCount','sortType'=>$this->_tpl_vars['sortType'],)));?>">订单总数</a></td>
				<td><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'ValidCount','sortType'=>$this->_tpl_vars['sortType'],)));?>">有效单数量</a></td>
				<td><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'TotalAmount','sortType'=>$this->_tpl_vars['sortType'],)));?>">订单总金额</a></td>
				<td><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'PaidAmount','sortType'=>$this->_tpl_vars['sortType'],)));?>">实收金额</a></td>
				<td><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'ReturnCount','sortType'=>$this->_tpl_vars['sortType'],)));?>">退货单数</a></td>
				<td><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'ReturnAmout','sortType'=>$this->_tpl_vars['sortType'],)));?>">退货金额</a></td>
			  </tr>
		</thead>
		<tbody>
		<?php if ($this->_tpl_vars['datas']): ?>
		<?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
		<tr>
		  <td>
		    <?php if ($this->_tpl_vars['data']['type'] == 0): ?>
            前台下单
            <?php elseif ($this->_tpl_vars['data']['type'] == 1): ?>
            电话下单
			<?php elseif ($this->_tpl_vars['data']['type'] == 5): ?>
			赠送下单
			<?php elseif ($this->_tpl_vars['data']['type'] == 6): ?>
			非会员下单
			<?php elseif ($this->_tpl_vars['data']['type'] == 7): ?>
			团购下单
			<?php elseif ($this->_tpl_vars['data']['type'] == 13): ?>
			渠道下单
			<?php elseif ($this->_tpl_vars['data']['type'] == 14): ?>
			渠道补单
			<?php elseif ($this->_tpl_vars['data']['type'] == 15): ?>
			其它下单
			<?php endif; ?>
		  </td>
		  <td><?php if ($this->_tpl_vars['data']['TotalCount']): ?><?php echo $this->_tpl_vars['data']['TotalCount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['ValidCount']): ?><?php echo $this->_tpl_vars['data']['ValidCount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['TotalAmount']): ?><?php echo $this->_tpl_vars['data']['TotalAmount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['PaidAmount']): ?><?php echo $this->_tpl_vars['data']['PaidAmount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['ReturnCount']): ?><?php echo $this->_tpl_vars['data']['ReturnCount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['ReturnAmout']): ?><?php echo $this->_tpl_vars['data']['ReturnAmout']; ?>
<?php else: ?>0<?php endif; ?></td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
		<thead>
		<tr>
		  <td>合计</td>
		  <td><?php echo $this->_tpl_vars['totalData']['TotalCount']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['totalData']['ValidCount']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['totalData']['TotalAmount']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['totalData']['PaidAmount']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['totalData']['ReturnCount']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['totalData']['ReturnAmout']; ?>
</td>
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