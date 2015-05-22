<?php /* Smarty version 2.6.19, created on 2014-10-24 15:06:23
         compiled from data-analysis/finance-sales.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'data-analysis/finance-sales.tpl', 102, false),array('modifier', 'string_format', 'data-analysis/finance-sales.tpl', 103, false),)), $this); ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm">
    <span style="float:left;line-height:18px;">
      <select name="entry" id="entry" onchange="changeEntry(this.value)">
        <option value="">请选择...</option>
        <option value="self" <?php if ($this->_tpl_vars['param']['entry'] == 'self'): ?>selected<?php endif; ?>>官网自营</option>
        <option value="call" <?php if ($this->_tpl_vars['param']['entry'] == 'call'): ?>selected<?php endif; ?>>呼叫中心</option>
        <option value="channel" <?php if ($this->_tpl_vars['param']['entry'] == 'channel'): ?>selected<?php endif; ?>>渠道店铺</option>
        <option value="distribution" <?php if ($this->_tpl_vars['param']['entry'] == 'distribution'): ?>selected<?php endif; ?>>分销</option>
        <option value="new_distribution" <?php if ($this->_tpl_vars['param']['entry'] == 'new_distribution'): ?>selected<?php endif; ?>>直供</option>
        <option value="tuan" <?php if ($this->_tpl_vars['param']['entry'] == 'tuan'): ?>selected<?php endif; ?>>团购</option>
      </select>
      <select name="type" id="type">
        <option value="">请选择...</option>
      </select>
	  &nbsp;&nbsp;
    </span>
    <span style="float:left;line-height:18px;">发货日期从：</span>
    <span style="float:left;line-height:18px;width:120px;"><input type="text" class="Wdate" onClick="WdatePicker()" name="send_fromdate" id="send_fromdate" size="15" value="<?php echo $this->_tpl_vars['param']['send_fromdate']; ?>
" /></span>
    <span style="float:left;line-height:18px;">到：</span>
    <span style="float:left;line-height:18px;width:120px;"><input type="text" class="Wdate" onClick="WdatePicker()" name="send_todate" id="send_todate" size="15" value="<?php echo $this->_tpl_vars['param']['send_todate']; ?>
" /></span>
    结算状态:
    <select name="is_settle">
      <option value="">请选择...</option>  
      <option value="1" <?php if ($this->_tpl_vars['param']['is_settle'] == '1'): ?>selected<?php endif; ?>>已结算</option>
	  <option value="0" <?php if ($this->_tpl_vars['param']['is_settle'] == '0'): ?>selected<?php endif; ?>>未结算</option>
	</select>
    <input type="button" name="dosearch" value="按条件搜索" onclick="ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('todo'=>'search',)));?>','ajax_search')"/>
  </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
<?php endif; ?>

<div id="ajax_search">

<div class="title">财务销售报表 [<a href="<?php echo $this -> callViewHelper('url', array(array('todo'=>'export',)));?>" target="_blank">导出信息</a>]</div>
<div class="content">
        <a href="javascript:;void(0);" onclick="if (document.getElementById('hint').style.display == '')document.getElementById('hint').style.display = 'none';else document.getElementById('hint').style.display = '';" title="字段说明"><img src="/images/admin/help.gif"></a>
	    <div id="hint" style="display:none">
	    <font color="666666">
	    　* 总订单数 = 有效单/分销单/刷单 订单数量<br>
	    　* 总销售额 = 有效单/分销单/刷单 订单总金额<br>
	    　* 刷单数 = 刷单 订单数量<br>
	    　* 刷单金额 = 刷单 订单总金额<br>
	    　* 实际销售单数 = 有效单/分销单 订单数量<br>
	    　* 实际销售额 = 有效单/分销单 订单总金额<br>
	    　* 销售成本 = 有效单/分销单 产品总成本<br>
	    　* 毛利率 = (实际销售额-销售成本)/实际销售额<br>
	    </font>
	    </div>
  <table cellpadding="0" cellspacing="0" border="0" class="table">
	<thead>
	  <tr>
	    <td>类型</td>
		<td>总订单数</td>
		<td>总销售额</td>
		<td>刷单数</td>
		<td>刷单金额</td>
		<td>实际销售单数</td>
		<td>实际销售额</td>
		<td>销售成本</td>
		<td>销售成本(未税)</td>
		<td>毛利率</td>
      </tr>
	</thead>
	<tbody>
	  <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
	  <tr>
	    <td>
	      <?php if ($this->_tpl_vars['key'] == 'self'): ?>官网自营
	      <?php elseif ($this->_tpl_vars['key'] == 'call_in'): ?>呼入
	      <?php elseif ($this->_tpl_vars['key'] == 'call_out'): ?>呼出
	      <?php elseif ($this->_tpl_vars['key'] == 'call_tq'): ?>咨询
	      <?php elseif ($this->_tpl_vars['key'] == 'channel'): ?>渠道店铺
	      <?php elseif ($this->_tpl_vars['key'] == 'distribution'): ?>分销
	      <?php elseif ($this->_tpl_vars['key'] == 'tuan'): ?>团购
	      <?php elseif ($this->_tpl_vars['key'] == 'jiankang'): ?>垦丰
          <?php elseif ($this->_tpl_vars['key'] == 'call'): ?>呼叫中心
          <?php elseif ($this->_tpl_vars['key'] == 'internal'): ?>内购
          <?php elseif ($this->_tpl_vars['key'] == 'gift'): ?>客情
          <?php elseif ($this->_tpl_vars['key'] == 'other'): ?>其他
          <?php elseif ($this->_tpl_vars['key'] == 'batch_channel'): ?>购销
          <?php elseif ($this->_tpl_vars['key'] == 'new_distribution'): ?>直供
	      <?php elseif ($this->_tpl_vars['item']['shop_name']): ?><?php echo $this->_tpl_vars['item']['shop_name']; ?>

	      <?php else: ?>
	        <?php $_from = $this->_tpl_vars['areas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['areaID'] => $this->_tpl_vars['areaName']):
?>
	          <?php if ($this->_tpl_vars['areaID'] == $this->_tpl_vars['distributionUsername'][$this->_tpl_vars['key']]): ?>
	            <?php echo $this->_tpl_vars['areaName']; ?>

	          <?php endif; ?>
	        <?php endforeach; endif; unset($_from); ?>
	      <?php endif; ?>
	    </td>
		<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['order_count'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
		<td><?php if ($this->_tpl_vars['item']['amount']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['amount'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
<?php else: ?>0<?php endif; ?></td>
		<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['order_count_fake'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
		<td><?php if ($this->_tpl_vars['item']['amount_fake']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['amount_fake'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
<?php else: ?>0<?php endif; ?></td>
		<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['order_count_real'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
 </td>
		<td><?php if ($this->_tpl_vars['item']['amount_real']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['amount_real'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
<?php else: ?>0<?php endif; ?></td>
		<td><?php if ($this->_tpl_vars['item']['cost_amount']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['cost_amount'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
<?php else: ?>0<?php endif; ?></td>
		<td><?php if ($this->_tpl_vars['item']['no_tax_cost_amount']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['no_tax_cost_amount'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
<?php else: ?>0<?php endif; ?></td>
		<td><?php echo $this->_tpl_vars['item']['benefit_rate']; ?>
%</td>
	  </tr>
	  <?php endforeach; endif; unset($_from); ?>
	  <?php if ($this->_tpl_vars['total']): ?>
	  <tr>
	    <td><b>合计</b></td>
	    <td><b><?php echo $this->_tpl_vars['total']['order_count']; ?>
</b></td>
	    <td><b><?php if ($this->_tpl_vars['total']['amount']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['total']['amount'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
<?php else: ?>0<?php endif; ?></b></td>
	    <td><b><?php echo $this->_tpl_vars['total']['order_count_fake']; ?>
</b></td>
	    <td><b><?php if ($this->_tpl_vars['total']['amount_fake']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['total']['amount_fake'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
<?php else: ?>0<?php endif; ?></b></td>
	    <td><b><?php echo $this->_tpl_vars['total']['order_count_real']; ?>
</b></td>
	    <td><b><?php if ($this->_tpl_vars['total']['amount_real']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['total']['amount_real'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
<?php else: ?>0<?php endif; ?></b></td>
	    <td><b><?php if ($this->_tpl_vars['total']['cost_amount']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['total']['cost_amount'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
<?php else: ?>0<?php endif; ?></b></td>
	    <td><b><?php if ($this->_tpl_vars['total']['no_tax_cost_amount']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['total']['no_tax_cost_amount'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.2f") : smarty_modifier_string_format($_tmp, "%.2f")); ?>
<?php else: ?>0<?php endif; ?></b></td>
	    <td><b><?php echo ((is_array($_tmp=@$this->_tpl_vars['total']['benefit_rate'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
%</b></td>
	  </tr>
	  <?php endif; ?>
	</tbody>
  </table>
</div>
<div style="padding:0 5px;"></div>
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
            if (shop[0] == 'jiankang' || shop[0] == 'tuan' || shop[0] == 'credit' || shop[0] == 'distribution')   continue;
            
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
    else if (val == 'new_distribution') {
        for (i = 0; i < shopData.length; i++) {
            shop = shopData[i].split('_');
            if (shop[0] != 'distribution')   continue;
            
            if (type == shop[1]) {
                $('type').options.add(new Option(shop[2], shop[1], true, true));
            }
            else    $('type').options.add(new Option(shop[2], shop[1]));
        }
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