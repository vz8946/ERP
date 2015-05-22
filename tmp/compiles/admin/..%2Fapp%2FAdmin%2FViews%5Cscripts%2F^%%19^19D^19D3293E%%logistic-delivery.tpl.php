<?php /* Smarty version 2.6.19, created on 2014-11-11 08:38:22
         compiled from data-analysis/logistic-delivery.tpl */ ?>
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
    <span style="float:left;line-height:18px;">选择日期从：</span><span style="float:left;width:150px;line-height:18px;">
    <input type="text" name="fromdate" id="fromdate" size="12"   value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
" class="Wdate" onClick="WdatePicker()" /></span>
    <span style="float:left;line-height:18px;">到：</span><span style="float:left;width:150px;line-height:18px;">
    <input type="text" name="todate" id="todate" size="12"  value="<?php echo $this->_tpl_vars['param']['todate']; ?>
" class="Wdate" onClick="WdatePicker()" /></span>
    <input type="button" name="dosearch" value="开始统计" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('todo'=>'search',)));?>','ajax_search')"/>
    <br>
    收货地址(省)：
    <input type="checkbox" name="chkprovinceall" title="全选/全不选" onclick="checkprovinceall(this)"/>全选/全不选
    <?php $_from = $this->_tpl_vars['provinceData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['province'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['province']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['province_name'] => $this->_tpl_vars['province_id']):
        $this->_foreach['province']['iteration']++;
?>
    <?php if ($this->_tpl_vars['province_id'] != 3880): ?><input type="checkbox" name="province" value="<?php echo $this->_tpl_vars['province_id']; ?>
" <?php if ($this->_tpl_vars['param']['province'][$this->_tpl_vars['province_id']]): ?>checked<?php endif; ?>><?php echo $this->_tpl_vars['province_name']; ?>
<?php endif; ?>
    <?php if ($this->_foreach['province']['iteration'] == 16): ?><br>　　　　　　　　　　　　　　&nbsp;&nbsp;<?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
    </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
<?php endif; ?>

<div id="ajax_search">

<div class="title">物流单数量列表   </div>
	<div class="content">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>物流公司</td>
				<td><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'TotalCount','sortType'=>$this->_tpl_vars['sortType'],)));?>">总单数</a></td>
				<td><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'SignCount','sortType'=>$this->_tpl_vars['sortType'],)));?>">已签收</a></td>
				<td><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'NotSignCount','sortType'=>$this->_tpl_vars['sortType'],)));?>">未签收</a></td>
				<td><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'RefuseCount','sortType'=>$this->_tpl_vars['sortType'],)));?>">拒收</a></td>
				<td><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'MatchCount','sortType'=>$this->_tpl_vars['sortType'],)));?>">匹配总单数</a></td>
				<td><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'TotalPrice','sortType'=>$this->_tpl_vars['sortType'],)));?>">总运费</a></td>
			  </tr>
		</thead>
		<tbody>
		<?php if ($this->_tpl_vars['datas']): ?>
		<?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
		<tr>
		  <td><?php echo $this->_tpl_vars['data']['logistic_name']; ?>
</td>
		  <td><?php if ($this->_tpl_vars['data']['TotalCount']): ?><?php echo $this->_tpl_vars['data']['TotalCount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['SignCount']): ?><?php echo $this->_tpl_vars['data']['SignCount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['NotSignCount']): ?><?php echo $this->_tpl_vars['data']['NotSignCount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['RefuseCount']): ?><?php echo $this->_tpl_vars['data']['RefuseCount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['MatchCount']): ?><?php echo $this->_tpl_vars['data']['MatchCount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['TotalPrice']): ?><?php echo $this->_tpl_vars['data']['TotalPrice']; ?>
<?php else: ?>0<?php endif; ?></td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
		<thead>
		<tr>
		  <td>合计</td>
		  <td><?php echo $this->_tpl_vars['totalData']['TotalCount']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['totalData']['SignCount']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['totalData']['NotSignCount']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['totalData']['RefuseCount']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['totalData']['MatchCount']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['totalData']['TotalPrice']; ?>
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

<script type="text/javascript">
function checkprovinceall(current)
{
    var province = document.getElementsByName('province');
    for ( i = 0; i < province.length; i++ ) {
        province[i].checked = current.checked;
    }
}
</script>