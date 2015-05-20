<?php /* Smarty version 2.6.19, created on 2014-10-23 10:36:01
         compiled from transport/split-order.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'transport/split-order.tpl', 47, false),)), $this); ?>
<div style="height:400px;">
<form name="myForm2" id="myForm2">
<input type="hidden" name="batch_sn" value="<?php echo $this->_tpl_vars['order']['batch_sn']; ?>
">
<table cellpadding="5" cellspacing="5" border="0">
<tr>
<td width="30px"><br><br><br></td>
<td width="80px"><b>订单号：</b></td>
<td>
  <?php echo $this->_tpl_vars['order']['batch_sn']; ?>
<br>
</td>
</tr>
<tr>
<td></td>
<td><b>收货地址：</b></td>
<td><?php echo $this->_tpl_vars['order']['addr_province']; ?>
 <?php echo $this->_tpl_vars['order']['addr_city']; ?>
 <?php echo $this->_tpl_vars['order']['addr_area']; ?>
 <?php echo $this->_tpl_vars['order']['addr_address']; ?>
</td>
</tr>
<tr>
<td></td>
<td><b>收货人：</b></td>
<td><?php echo $this->_tpl_vars['order']['addr_consignee']; ?>
 <?php echo $this->_tpl_vars['order']['addr_mobile']; ?>
 <?php echo $this->_tpl_vars['order']['addr_tel']; ?>
</td>
</tr>
</table>
<br>
<div style="overflow:scroll;height: 220px;">
<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td width="30px">&nbsp;</td>
  <td>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
      <thead>
        <tr>
          <td width="300px"><b>产品名称</b></td>
          <td width="60px"><b>产品编号</b></td>
          <td width="40px"><b>数量</b></td>
          <td><b>操作</b></td>
        </tr>
      </thead>
    </table>
    <table cellpadding="0" cellspacing="0" border="5" class="table" id="area_0">
      <?php $_from = $this->_tpl_vars['product']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['goods']):
        $this->_foreach['goods']['iteration']++;
?>
      <input type="hidden" name="base_number" id="number_<?php echo $this->_tpl_vars['goods']['product_sn']; ?>
" value="<?php echo $this->_tpl_vars['goods']['number']; ?>
_<?php echo $this->_tpl_vars['goods']['goods_name']; ?>
_<?php echo $this->_tpl_vars['goods']['goods_style']; ?>
">
      <tr>
        <td width="300px"><?php echo $this->_tpl_vars['goods']['goods_name']; ?>
 (<font color="#FF3333"><?php echo $this->_tpl_vars['goods']['goods_style']; ?>
</font>) </td>
        <td width="60px"><?php echo $this->_tpl_vars['goods']['product_sn']; ?>
*<?php echo $this->_tpl_vars['goods']['number']; ?>
</td>
        <td width="40px"><input type="text" name="number[]" id="number_<?php echo $this->_tpl_vars['goods']['product_sn']; ?>
" style="text-align:center;width:80%" value="<?php echo $this->_tpl_vars['goods']['number']; ?>
" onkeypress="return NumOnly(event)"><input type="hidden" name="product[]" value="0_<?php echo $this->_tpl_vars['goods']['product_sn']; ?>
"></td>
        <?php if ($this->_foreach['goods']['iteration'] == 1): ?>
        <td rowspan="<?php echo count($this->_tpl_vars['product']); ?>
"><input type="button" name="split" value="拆分" onclick="splitOrder()"></td>
        <?php endif; ?>
      </tr>
      <?php endforeach; endif; unset($_from); ?>
    </table>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
      <thead>
        <tr>
          <td><b>新生成订单</b></td>
        </tr>
      </thead>
    </table>
    <div id="newArea">
      
    </div>
  </td>
</tr>
</table>
</div>
<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td width="30px">&nbsp;</td>
  <td>
    <br>
    <input type="button" name="submit" value="配货" onclick="ajax_submit($('myForm2'), '<?php echo $this -> callViewHelper('url', array());?>')">
  </td>
</tr>
</table>
</form>
</div>