<?php /* Smarty version 2.6.19, created on 2014-10-24 15:06:09
         compiled from data-analysis/order-daily.tpl */ ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>"order-daily",)));?>">
    <span style="float:left;line-height:18px;">
      <select name="entry" id="entry" onchange="changeEntry(this.value)">
        <option value="">请选择...</option>
        <option value="self" <?php if ($this->_tpl_vars['param']['entry'] == 'self'): ?>selected<?php endif; ?>>官网自营</option>
        <option value="call" <?php if ($this->_tpl_vars['param']['entry'] == 'call'): ?>selected<?php endif; ?>>呼叫中心</option>
        <option value="channel" <?php if ($this->_tpl_vars['param']['entry'] == 'channel'): ?>selected<?php endif; ?>>渠道店铺</option>
        <option value="distribution" <?php if ($this->_tpl_vars['param']['entry'] == 'distribution'): ?>selected<?php endif; ?>>分销</option>
        <option value="tuan" <?php if ($this->_tpl_vars['param']['entry'] == 'tuan'): ?>selected<?php endif; ?>>团购</option>
      </select>
      <select name="type" id="type">
        <option value="">请选择...</option>
      </select>
	  &nbsp;&nbsp;
    </span>
    <span style="float:left;line-height:18px;">下单日期从：</span>
    <span style="float:left;width:100px;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="12" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
" class="Wdate" onClick="WdatePicker()"/></span>
    <span style="float:left;line-height:18px;">到：</span>
    <span style="float:left;width:100px;line-height:18px;"><input type="text" name="todate" id="todate" size="12" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
" class="Wdate" onClick="WdatePicker()" /></span>
    <span style="float:left;line-height:18px;">付款日期从：</span>
    <span style="float:left;width:100px;line-height:18px;"><input type="text" name="pay_fromdate" id="pay_fromdate" size="12" value="<?php echo $this->_tpl_vars['param']['pay_fromdate']; ?>
" class="Wdate" onClick="WdatePicker()"/></span>
    <span style="float:left;line-height:18px;">到：</span>
    <span style="float:left;width:100px;line-height:18px;"><input type="text" name="pay_todate" id="pay_todate" size="12" value="<?php echo $this->_tpl_vars['param']['pay_todate']; ?>
" class="Wdate" onClick="WdatePicker()" /></span>
    <input type="radio" name="dateFormat" value="Y-m-d" <?php if ($this->_tpl_vars['param']['dateFormat'] == 'Y-m-d'): ?>checked<?php endif; ?>>按天
    <input type="radio" name="dateFormat" value="Y-m"  <?php if ($this->_tpl_vars['param']['dateFormat'] == 'Y-m'): ?>checked<?php endif; ?>>按月
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

<div class="title">订单列表 [<a href="<?php echo $this -> callViewHelper('url', array(array('todo'=>'export',)));?>" target="_blank">导出信息</a>] </div>
	<div class="content">
	    <a href="javascript:;void(0);" onclick="if (document.getElementById('hint').style.display == '')document.getElementById('hint').style.display = 'none';else document.getElementById('hint').style.display = '';" title="字段说明"><img src="/images/admin/help.gif"></a>
	    <div id="hint" style="display:none">
	    <font color="666666">
	    　* 总单数 = 所有订单数量 包含取消单/刷单/分销单<br>
	    　* 订单总金额 = 有效订单总金额 + 退款金额<br>
	    　* 有效单数 = 正常单/分销单 订单数量<br>
	    　* 有效订单总金额 = 正常单/分销单 订单总金额<br>
	    　* 运费金额 = 正常单/不发货单 运费总金额<br>
	    　* 每单平均金额 = 有效订单总金额 / 有效单数<br>
	    　* 发货单数 = 正常单/不发货单 已发货 订单数量<br>
	    　* 退货单数 = 所有订单 退货单数量<br>
	    　* 退款金额 = 所有订单 退款总金额
	    </font>
	    </div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td ><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'date','sortType'=>$this->_tpl_vars['sortType'],)));?>">日期</a></td>
				<td >店铺</td>
				<td ><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'TotalCount','sortType'=>$this->_tpl_vars['sortType'],)));?>">总单数</a></td>
				<td ><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'TotalAmount','sortType'=>$this->_tpl_vars['sortType'],)));?>">订单总金额</a></td>
				<td ><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'ValidCount','sortType'=>$this->_tpl_vars['sortType'],)));?>">有效单数</a></td>
				<td ><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'Amount','sortType'=>$this->_tpl_vars['sortType'],)));?>">有效订单总金额</a></td>
				<td ><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'LogisticAmount','sortType'=>$this->_tpl_vars['sortType'],)));?>">运费金额</a></td>
				<td ><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'AvgAmount','sortType'=>$this->_tpl_vars['sortType'],)));?>">每单平均金额</a></td>
				<td ><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'SentCount','sortType'=>$this->_tpl_vars['sortType'],)));?>">发货单数</a></td>
				<td ><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'ReturnCount','sortType'=>$this->_tpl_vars['sortType'],)));?>">退货单数</a></td>
				<td ><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'ReturnAmount','sortType'=>$this->_tpl_vars['sortType'],)));?>">退款金额</a></td>
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
		  <td>
		    <?php if ($this->_tpl_vars['data']['shop_name']): ?><?php echo $this->_tpl_vars['data']['shop_name']; ?>

		    <?php else: ?>
		      <?php if ($this->_tpl_vars['param']['entry'] == 'channel'): ?>渠道运营
		      <?php elseif ($this->_tpl_vars['param']['entry'] == 'call'): ?>呼叫中心
		      <?php else: ?>其它下单
		      <?php endif; ?>
		    <?php endif; ?>
		  </td>
		  <td><?php if ($this->_tpl_vars['data']['TotalCount']): ?><?php echo $this->_tpl_vars['data']['TotalCount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['TotalAmount']): ?><?php echo $this->_tpl_vars['data']['TotalAmount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['ValidCount']): ?><?php echo $this->_tpl_vars['data']['ValidCount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['Amount']): ?><?php echo $this->_tpl_vars['data']['Amount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['LogisticAmount']): ?><?php echo $this->_tpl_vars['data']['LogisticAmount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['AvgAmount']): ?><?php echo $this->_tpl_vars['data']['AvgAmount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['SentCount']): ?><?php echo $this->_tpl_vars['data']['SentCount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['ReturnCount']): ?><?php echo $this->_tpl_vars['data']['ReturnCount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['ReturnAmount']): ?><?php echo $this->_tpl_vars['data']['ReturnAmount']; ?>
<?php else: ?>0<?php endif; ?></td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
		<thead>
		<tr>
		  <td>合计</td>
		  <td></td>
		  <td ><?php echo $this->_tpl_vars['totalData']['TotalCount']; ?>
</td>
		  <td ><?php echo $this->_tpl_vars['totalData']['TotalAmount']; ?>
</td>
		  <td ><?php echo $this->_tpl_vars['totalData']['ValidCount']; ?>
</td>
		  <td ><?php echo $this->_tpl_vars['totalData']['Amount']; ?>
</td>
		  <td ><?php echo $this->_tpl_vars['totalData']['LogisticAmount']; ?>
</td>
		  <td ><?php echo $this->_tpl_vars['totalData']['AvgAmount']; ?>
</td>
		  <td ><?php echo $this->_tpl_vars['totalData']['SentCount']; ?>
</td>
		  <td ><?php echo $this->_tpl_vars['totalData']['ReturnCount']; ?>
</td>
		  <td ><?php echo $this->_tpl_vars['totalData']['ReturnAmount']; ?>
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

<script>
function changeEntry(val)
{
    $('type').options.length = 0;
    $('type').options.add(new Option('请选择...', ''));
    if (val == 'self') {
        $('type').options.add(new Option('垦丰', 'jiankang'<?php if ($this->_tpl_vars['param']['type'] == 'jiankang'): ?>, true, true<?php endif; ?>));
        $('type').options.add(new Option('客情', 'gift'<?php if ($this->_tpl_vars['param']['type'] == 'gift'): ?>, true, true<?php endif; ?>));
        $('type').options.add(new Option('内购', 'internal'<?php if ($this->_tpl_vars['param']['type'] == 'internal'): ?>, true, true<?php endif; ?>));
        $('type').options.add(new Option('其他', 'other'<?php if ($this->_tpl_vars['param']['type'] == 'other'): ?>, true, true<?php endif; ?>));
    }
    else if (val == 'call') {
        $('type').options.add(new Option('呼入', 'call_in'<?php if ($this->_tpl_vars['param']['type'] == 'call_in'): ?>, true, true<?php endif; ?>));
        $('type').options.add(new Option('呼出', 'call_out'<?php if ($this->_tpl_vars['param']['type'] == 'call_out'): ?>, true, true<?php endif; ?>));
        $('type').options.add(new Option('咨询', 'call_tq'<?php if ($this->_tpl_vars['param']['type'] == 'call_tq'): ?>, true, true<?php endif; ?>));
    }
    else if (val == 'channel') {
        for (i = 0; i < shopData.length; i++) {
            shop = shopData[i].split('_');
            if (shop[0] == 'jiankang' || shop[0] == 'tuan' || shop[0] == 'credit')   continue;
            
            if (type == shop[1]) {
                $('type').options.add(new Option(shop[2], shop[1], true, true));
            }
            else    $('type').options.add(new Option(shop[2], shop[1]));
        }
    }
    else if (val == 'distribution') {
        $('type').options.add(new Option('购销', 'batch_channel'<?php if ($this->_tpl_vars['param']['type'] == 'batch_channel'): ?>, true, true<?php endif; ?>));
        <?php $_from = $this->_tpl_vars['areas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
          <?php if ($this->_tpl_vars['key'] > 20): ?>
          $('type').options.add(new Option('<?php echo $this->_tpl_vars['item']; ?>
', '<?php echo $this->_tpl_vars['distributionArea'][$this->_tpl_vars['key']]; ?>
'<?php if ($this->_tpl_vars['param']['type'] == $this->_tpl_vars['distributionArea'][$this->_tpl_vars['key']]): ?>, true, true<?php endif; ?>));
          <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
    }
    else if (val == 'tuan') {
        for (i = 0; i < shopData.length; i++) {
            shop = shopData[i].split('_');
            if (shop[0] != 'tuan' && shop[0] != 'credit')   continue;
            
            if (type == shop[1]) {
                $('type').options.add(new Option(shop[2], shop[1], true, true));
            }
            else    $('type').options.add(new Option(shop[2], shop[1]));
        }
    }
}

function checkprovinceall(current)
{
    var province = document.getElementsByName('province');
    for ( i = 0; i < province.length; i++ ) {
        province[i].checked = current.checked;
    }
}

var type = '<?php echo $this->_tpl_vars['param']['type']; ?>
';
var shopData = new Array();
<?php $_from = $this->_tpl_vars['shopDatas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['shop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['shop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['shop']):
        $this->_foreach['shop']['iteration']++;
?>
<?php $this->assign('index', $this->_foreach['shop']['iteration']-1); ?>
shopData[<?php echo $this->_tpl_vars['index']; ?>
] = '<?php echo $this->_tpl_vars['shop']['shop_type']; ?>
_<?php echo $this->_tpl_vars['shop']['shop_id']; ?>
_<?php echo $this->_tpl_vars['shop']['shop_name']; ?>
';
<?php endforeach; endif; unset($_from); ?>

changeEntry($('entry').value);

</script>