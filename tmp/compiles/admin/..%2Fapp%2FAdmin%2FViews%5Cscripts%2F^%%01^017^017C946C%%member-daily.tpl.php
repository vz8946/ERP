<?php /* Smarty version 2.6.19, created on 2014-10-24 15:06:05
         compiled from data-analysis/member-daily.tpl */ ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>"member-daily",)));?>">
    <span style="float:left;line-height:18px;">选择日期从：</span><span style="float:left;width:100px;line-height:18px;">
    <input type="text" name="fromdate" id="fromdate" size="12"   value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
" class="Wdate" onClick="WdatePicker()"  /></span>
    <span style="float:left;line-height:18px;">到：</span><span style="float:left;width:150px;line-height:18px;">
    <input type="text" name="todate" id="todate" size="12"  value="<?php echo $this->_tpl_vars['param']['todate']; ?>
" class="Wdate" onClick="WdatePicker()"  /></span>
    <input type="radio" name="dateFormat" value="Y-m-d" <?php if ($this->_tpl_vars['param']['dateFormat'] == 'Y-m-d'): ?>checked<?php endif; ?>>按天
    <input type="radio" name="dateFormat" value="Y-m"  <?php if ($this->_tpl_vars['param']['dateFormat'] == 'Y-m'): ?>checked<?php endif; ?>>按月
    <input type="button" name="dosearch" value="开始统计" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('todo'=>'search',)));?>','ajax_search')"/>
    </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
<?php endif; ?>

<div id="ajax_search">

<div class="title">会员数量列表   </div>
	<div class="content">
	    <a href="javascript:;void(0);" onclick="if (document.getElementById('hint').style.display == '')document.getElementById('hint').style.display = 'none';else document.getElementById('hint').style.display = '';" title="字段说明"><img src="/images/admin/help.gif"></a>
	    <div id="hint" style="display:none">
	    <font color="666666">
	    　* 下单会员数 = 正常单 前台下单/电话下单 会员数量<br>
	    　* 复购会员人数 = 正常单 前台下单/电话下单 每月下单超过一次的会员数量<br>
	    　* 订单复购率 = 复购会员人数 / 下单会员数<br>
	    </font>
	    </div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td >日期</td>
				<td><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'RegUserCount','sortType'=>$this->_tpl_vars['sortType'],)));?>">新增注册会员数</a></td>
				<td><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'FrontRegUserCount','sortType'=>$this->_tpl_vars['sortType'],)));?>">自然注册人数</a></td>
				<td><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'CPSRegUserCount','sortType'=>$this->_tpl_vars['sortType'],)));?>">CPS注册人数</a></td>
				<td><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'UserOrderCount','sortType'=>$this->_tpl_vars['sortType'],)));?>">下单会员数</a></td>
				<td><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'UserMoreOrderCount','sortType'=>$this->_tpl_vars['sortType'],)));?>">复购会员人数</a></td>
				<td><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'MoreUserOrderRate','sortType'=>$this->_tpl_vars['sortType'],)));?>">订单复购率</a></td>
			  </tr>
		</thead>
		<tbody>
		<?php if ($this->_tpl_vars['datas']): ?>
		<?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
		<tr>
		  <td><?php echo $this->_tpl_vars['data']['date']; ?>
</td>
		  <td><?php if ($this->_tpl_vars['data']['RegUserCount']): ?><?php echo $this->_tpl_vars['data']['RegUserCount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['FrontRegUserCount']): ?><?php echo $this->_tpl_vars['data']['FrontRegUserCount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['CPSRegUserCount']): ?><?php echo $this->_tpl_vars['data']['CPSRegUserCount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['UserOrderCount']): ?><?php echo $this->_tpl_vars['data']['UserOrderCount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['UserMoreOrderCount']): ?><?php echo $this->_tpl_vars['data']['UserMoreOrderCount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['MoreUserOrderRate']): ?><?php echo $this->_tpl_vars['data']['MoreUserOrderRate']; ?>
<?php else: ?>0<?php endif; ?>%</td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
		<thead>
		<tr>
		  <td>合计</td>
		  <td><?php echo $this->_tpl_vars['totalData']['RegUserCount']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['totalData']['FrontRegUserCount']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['totalData']['CPSRegUserCount']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['totalData']['UserOrderCount']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['totalData']['UserMoreOrderCount']; ?>
</td>
		  <td></td>
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