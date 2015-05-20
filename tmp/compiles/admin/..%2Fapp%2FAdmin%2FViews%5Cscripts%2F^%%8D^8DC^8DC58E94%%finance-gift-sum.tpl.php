<?php /* Smarty version 2.6.19, created on 2014-11-11 08:39:53
         compiled from data-analysis/finance-gift-sum.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'data-analysis/finance-gift-sum.tpl', 68, false),)), $this); ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm">
    <span style="float:left;line-height:18px;">开始日期从：</span>
    <span style="float:left;line-height:18px;width:120px;"><input type="text" class="Wdate" onClick="WdatePicker()" name="fromdate" id="fromdate" size="15" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
" /></span>
    <span style="float:left;line-height:18px;">到：</span>
    <span style="float:left;line-height:18px;width:120px;"><input type="text" class="Wdate" onClick="WdatePicker()" name="todate" id="todate" size="15" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
" /></span>
    卡号：<input type="text" name="card_sn" id="card_sn" value="<?php echo $this->_tpl_vars['param']['card_sn']; ?>
" size="15">
    状态：
    <select name="status">
      <option value="">请选择...</option>
      <option value="0" <?php if ($this->_tpl_vars['param']['status'] == '0'): ?>selected<?php endif; ?>>有效</option>
	  <option value="1" <?php if ($this->_tpl_vars['param']['status'] == '1'): ?>selected<?php endif; ?>>无效</option>
	  <option value="2" <?php if ($this->_tpl_vars['param']['status'] == '2'): ?>selected<?php endif; ?>>未激活</option>
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

<div class="title">礼品卡汇总 [<a href="<?php echo $this -> callViewHelper('url', array(array('todo'=>'export',)));?>" target="_blank">导出信息</a>]</div>
<div class="content">
        <a href="javascript:;void(0);" onclick="if (document.getElementById('hint').style.display == '')document.getElementById('hint').style.display = 'none';else document.getElementById('hint').style.display = '';" title="字段说明"><img src="/images/admin/help.gif"></a>
	    <div id="hint" style="display:none">
	    <font color="666666">
	    　* 面值 = 其它是特殊面值，主要是呼叫中心10/24导入的卡<br>
	    　* 期初卡内余额 = 开始日期的卡内余额<br>
	    　* 本期售卡数 = 发货日期内的销售数量<br>
	    　* 本期卡内消费1 = 发货日期在选择日期内的抵扣卡金额<br>
	    　* 本期卡内消费2 = 发货日期在选择日期后的抵扣卡金额<br>
	    　* 卡内退货退回金额 = 发货后的退回卡内金额<br>
	    　* 期末卡内余额 = 结束日期的卡内余额<br>
	    </font>
	    </div>
  <table cellpadding="0" cellspacing="0" border="0" class="table">
	<thead>
	  <tr>
	    <td>面值</td>
		<td>期初卡内余额</td>
		<td>本期售卡数</td>
		<td>预售面值</td>
		<td>预售总金额(激活)</td>
		<td>预售总金额(在途)</td>
		<td>本期卡内消费1</td>
		<td>本期卡内消费2</td>
		<td>卡内退货退回金额</td>
		<td>期末卡内余额</td>
      </tr>
	</thead>
	<tbody>
	  <?php if ($this->_tpl_vars['datas']): ?>
	  <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
	  <tr>
	    <td>
	      <?php if ($this->_tpl_vars['key'] == 'other'): ?>其它<?php else: ?><?php echo $this->_tpl_vars['key']; ?>
<?php endif; ?>
	    </td>
		<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['from_price'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
		<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['count'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
		<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['card_amount'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
		<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['active_amount'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
		<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['plan_amount'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
		<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['use_amount1'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
		<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['use_amount2'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
		<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['return_amount'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
		<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['to_price'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</td>
	  </tr>
	  <?php endforeach; endif; unset($_from); ?>
	  <?php if ($this->_tpl_vars['total']): ?>
	  <tr>
	    <td><b>合计</b></td>
	    <td><b><?php echo $this->_tpl_vars['total']['from_price']; ?>
</b></td>
	    <td><b><?php echo $this->_tpl_vars['total']['count']; ?>
</b></td>
	    <td><b><?php echo $this->_tpl_vars['total']['card_amount']; ?>
</b></td>
	    <td><b><?php echo $this->_tpl_vars['total']['active_amount']; ?>
</b></td>
	    <td><b><?php echo $this->_tpl_vars['total']['plan_amount']; ?>
</b></td>
	    <td><b><?php echo $this->_tpl_vars['total']['use_amount1']; ?>
</b></td>
	    <td><b><?php echo $this->_tpl_vars['total']['use_amount2']; ?>
</b></td>
	    <td><b><?php echo $this->_tpl_vars['total']['return_amount']; ?>
</b></td>
	    <td><b><?php echo $this->_tpl_vars['total']['to_price']; ?>
</b></td>
	  </tr>
	  <?php endif; ?>
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
        $('type').options.add(new Option('呼叫中心', 'call'<?php if ($this->_tpl_vars['param']['type'] == 'call'): ?>, true, true<?php endif; ?>));
        $('type').options.add(new Option('客情', 'gift'<?php if ($this->_tpl_vars['param']['type'] == 'gift'): ?>, true, true<?php endif; ?>));
        $('type').options.add(new Option('内购', 'internal'<?php if ($this->_tpl_vars['param']['type'] == 'internal'): ?>, true, true<?php endif; ?>));
        $('type').options.add(new Option('其他', 'other'<?php if ($this->_tpl_vars['param']['type'] == 'other'): ?>, true, true<?php endif; ?>));
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