<?php /* Smarty version 2.6.19, created on 2014-10-24 15:06:07
         compiled from data-analysis/member-goods.tpl */ ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>"member-goods",)));?>">
    <span style="float:left;line-height:18px;">选择日期从：</span><span style="float:left;width:120px;line-height:18px;">
    <input type="text" name="fromdate" id="fromdate" size="12"   value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
" class="Wdate" onClick="WdatePicker()" /></span>
    <span style="float:left;line-height:18px;">到：</span><span style="float:left;width:120px;line-height:18px;">
    <input type="text" name="todate" id="todate" size="12"  value="<?php echo $this->_tpl_vars['param']['todate']; ?>
" class="Wdate" onClick="WdatePicker()" /></span>
    <br><br>
    会员名：<input name="user_name" type="text"  size="18" value="<?php echo $this->_tpl_vars['param']['user_name']; ?>
"/>
    有效单数 >= <input name="order_count" type="text"  size="3" value="<?php echo $this->_tpl_vars['param']['order_count']; ?>
"/>
    消费金额 >= <input name="real_amount" type="text"  size="3" value="<?php echo $this->_tpl_vars['param']['real_amount']; ?>
"/>
  <input type="button" name="dosearch" value="开始统计" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('todo'=>'search',)));?>','ajax_search')"/>
    </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
<?php endif; ?>

<div id="ajax_search">

<div class="title">会员订单商品列表   </div>
	<div class="content">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>会员名</td>
				<td ><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'GoodsCount','sortType'=>$this->_tpl_vars['sortType'],)));?>">购买商品清单</a></td>
				<td ><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'TotalOrderCount','sortType'=>$this->_tpl_vars['sortType'],)));?>">总单数</a></td>
				<td ><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'OrderCount','sortType'=>$this->_tpl_vars['sortType'],)));?>">有效单数</a></td>
				<td ><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'TotalAmount','sortType'=>$this->_tpl_vars['sortType'],)));?>">应收总金额</a></td>
				<td ><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'RealAmount','sortType'=>$this->_tpl_vars['sortType'],)));?>">实际消费总金额</a></td>
			  </tr>
		</thead>
		<tbody>
		<?php if ($this->_tpl_vars['datas']): ?>
		<?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
		<tr>
		  <td><?php echo $this->_tpl_vars['data']['user_name']; ?>
</td>
		  <td>
		    <?php if ($this->_tpl_vars['data']['GoodsCount']): ?>
		      <a href="javascript:void(0)" onclick="this.style.display='none';document.getElementById('Goods_<?php echo $this->_tpl_vars['data']['user_id']; ?>
').style.display='';"><?php echo $this->_tpl_vars['data']['GoodsCount']; ?>
</a>
		      <div id="Goods_<?php echo $this->_tpl_vars['data']['user_id']; ?>
" style="display:none">
		        <?php $_from = $this->_tpl_vars['data']['Goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['goods']):
?>
		          <?php echo $this->_tpl_vars['goods']['goods_name']; ?>
 * 
		          <?php echo $this->_tpl_vars['goods']['number']; ?>
<br>
		        <?php endforeach; endif; unset($_from); ?>
		      </div>
		    <?php else: ?>
		      0
		    <?php endif; ?>
		  </td>
		  <td><?php if ($this->_tpl_vars['data']['TotalOrderCount']): ?><?php echo $this->_tpl_vars['data']['TotalOrderCount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['OrderCount']): ?><?php echo $this->_tpl_vars['data']['OrderCount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['TotalAmount']): ?><?php echo $this->_tpl_vars['data']['TotalAmount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['RealAmount']): ?><?php echo $this->_tpl_vars['data']['RealAmount']; ?>
<?php else: ?>0<?php endif; ?></td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
		<thead>
	    <tr>
		  <td>合计</td>
		  <td ><?php echo $this->_tpl_vars['totalData']['GoodsCount']; ?>
</td>
		  <td ><?php echo $this->_tpl_vars['totalData']['TotalOrderCount']; ?>
</td>
		  <td ><?php echo $this->_tpl_vars['totalData']['OrderCount']; ?>
</td>
		  <td ><?php echo $this->_tpl_vars['totalData']['TotalAmount']; ?>
</td>
	      <td ><?php echo $this->_tpl_vars['totalData']['RealAmount']; ?>
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