<?php /* Smarty version 2.6.19, created on 2014-10-24 15:06:12
         compiled from data-analysis/goods-daily.tpl */ ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>"goods-daily",)));?>">
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
    <span style="float:left;line-height:18px;">发货日期从：</span>
    <span style="float:left;width:100px;line-height:18px;">
      <input type="text" name="fromdate" id="fromdate" size="12" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
" class="Wdate" onClick="WdatePicker()"/>
    </span>
    <span style="float:left;line-height:18px;">到：</span>
    <span style="float:left;width:100px;line-height:18px;">
      <input type="text" name="todate" id="todate" size="12"  value="<?php echo $this->_tpl_vars['param']['todate']; ?>
" class="Wdate" onClick="WdatePicker()"/>
    </span>
    <br><br>
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
    产品名称：<input name="product_name" type="text"  size="18" value="<?php echo $this->_tpl_vars['param']['product_name']; ?>
"/>
    产品编号：<input name="product_sn" type="text"  size="8" value="<?php echo $this->_tpl_vars['param']['product_sn']; ?>
"/>
    <input type="button" name="dosearch" value="开始统计" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('todo'=>'search',)));?>','ajax_search')"/>
    [<a href="<?php echo $this -> callViewHelper('url', array(array('action'=>"goods-daily-export",)));?>?<?php echo $_SERVER['QUERY_STRING']; ?>
" target="_blank">导出查询结果</a>]
    </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
<?php endif; ?>

<div id="ajax_search">

<div class="title">销售商品列表   </div>
	<div class="content">
	    <a href="javascript:;void(0);" onclick="if (document.getElementById('hint').style.display == '')document.getElementById('hint').style.display = 'none';else document.getElementById('hint').style.display = '';" title="字段说明"><img src="/images/admin/help.gif"></a>
	    <div id="hint" style="display:none">
	    <font color="666666">
	    　* 平均售价 = 销售总金额 / 实际出库数量<br>
	    　* 出库数量 = 正常单/分销单 已发货 商品总数量<br>
	    　* 退货数量 = 正常单/分销单 已发货 退货商品总数量<br>
	    　* 实际出库数量 = 出库数量 - 退货数量<br>
	    </font>
	    </div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>产品编号</td>
				<td>产品名称</td>
				<td>平均售价</td>
				<td><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'OutStockCount','sortType'=>$this->_tpl_vars['sortType'],)));?>">发货数量</a></td>
				<td><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'ReturnCount','sortType'=>$this->_tpl_vars['sortType'],)));?>">退货数量(发货中的退货)</a></td>
				<td><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'RealOutStockCount','sortType'=>$this->_tpl_vars['sortType'],)));?>">实际发货数量</a></td>
				<td><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'TotalCost','sortType'=>$this->_tpl_vars['sortType'],)));?>">总成本</a></td>
				<td><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'TotalNoTaxCost','sortType'=>$this->_tpl_vars['sortType'],)));?>">总成本(未税)</a></td>
				<td><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'BenefitAmount','sortType'=>$this->_tpl_vars['sortType'],)));?>">毛利</a></td>
				<td><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'BenefitRate','sortType'=>$this->_tpl_vars['sortType'],)));?>">毛利率</a></td>
				<td><a href="<?php echo $this -> callViewHelper('url', array(array('sortField'=>'TotalAmount','sortType'=>$this->_tpl_vars['sortType'],)));?>">销售总金额</a></td>
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
		  <td><?php echo $this->_tpl_vars['data']['product_name']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['data']['AveragePrice']; ?>
</td>
		  <td><?php if ($this->_tpl_vars['data']['OutStockCount']): ?><?php echo $this->_tpl_vars['data']['OutStockCount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['ReturnCount']): ?><?php echo $this->_tpl_vars['data']['ReturnCount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['RealOutStockCount']): ?><?php echo $this->_tpl_vars['data']['RealOutStockCount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['TotalCost']): ?><?php echo $this->_tpl_vars['data']['TotalCost']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['TotalNoTaxCost']): ?><?php echo $this->_tpl_vars['data']['TotalNoTaxCost']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['BenefitAmount']): ?><?php echo $this->_tpl_vars['data']['BenefitAmount']; ?>
<?php else: ?>0<?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['BenefitRate']): ?><?php echo $this->_tpl_vars['data']['BenefitRate']; ?>
%<?php else: ?><?php endif; ?></td>
		  <td><?php if ($this->_tpl_vars['data']['TotalAmount']): ?><?php echo $this->_tpl_vars['data']['TotalAmount']; ?>
<?php else: ?>0<?php endif; ?></td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
		<thead>
		<tr>
		  <td>合计</td>
		  <td>*</td>
		  <td>*</td>
		  <td><?php echo $this->_tpl_vars['totalData']['OutStockCount']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['totalData']['ReturnCount']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['totalData']['RealOutStockCount']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['totalData']['TotalCost']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['totalData']['TotalNoTaxCost']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['totalData']['BenefitAmount']; ?>
</td>
		  <td><?php if ($this->_tpl_vars['totalData']['BenefitRate']): ?><?php echo $this->_tpl_vars['totalData']['BenefitRate']; ?>
%<?php endif; ?></td>
		  <td><?php echo $this->_tpl_vars['totalData']['TotalAmount']; ?>
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