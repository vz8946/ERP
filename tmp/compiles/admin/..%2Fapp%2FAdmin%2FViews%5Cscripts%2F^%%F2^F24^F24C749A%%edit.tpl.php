<?php /* Smarty version 2.6.19, created on 2014-11-19 16:32:01
         compiled from shop/edit.tpl */ ?>
<form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>" method="post">
<div class="title"><?php if ($this->_tpl_vars['action'] == 'edit'): ?>编辑店铺<?php else: ?>添加店铺<?php endif; ?></div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr> 
      <td width="15%"><strong>店铺名称</strong> * </td>
      <td><input type="text" name="shop_name" id="shop_name" size="20" value="<?php echo $this->_tpl_vars['data']['shop_name']; ?>
" msg="请填写店铺名称" class="required" /></td>
    </tr>
    <tr> 
      <td><strong>店铺类型</strong> * </td>
      <td>
        <select name="shop_type" onchange="showConfigArea(this.value, '<?php echo $this->_tpl_vars['data']['shop_id']; ?>
')">
          <option value="jiankang" <?php if ($this->_tpl_vars['data']['shop_type'] == 'jiankang'): ?>selected<?php endif; ?>>官网B2C</option>
          <option value="taobao" <?php if ($this->_tpl_vars['data']['shop_type'] == 'taobao'): ?>selected<?php endif; ?>>淘宝</option>
          <option value="jingdong" <?php if ($this->_tpl_vars['data']['shop_type'] == 'jingdong'): ?>selected<?php endif; ?>>京东</option>
          <option value="yihaodian" <?php if ($this->_tpl_vars['data']['shop_type'] == 'yihaodian'): ?>selected<?php endif; ?>>一号店</option>
          <option value="dangdang" <?php if ($this->_tpl_vars['data']['shop_type'] == 'dangdang'): ?>selected<?php endif; ?>>当当网</option>
          <option value="qq" <?php if ($this->_tpl_vars['data']['shop_type'] == 'qq'): ?>selected<?php endif; ?>>QQ商城</option>
          <option value="alibaba" <?php if ($this->_tpl_vars['data']['shop_type'] == 'alibaba'): ?>selected<?php endif; ?>>阿里巴巴</option>
          <option value="tuan" <?php if ($this->_tpl_vars['data']['shop_type'] == 'tuan'): ?>selected<?php endif; ?>>团购</option>
		  <option value="credit" <?php if ($this->_tpl_vars['data']['shop_type'] == 'credit'): ?>selected<?php endif; ?>>赊销</option>
		  <option value="distribution" <?php if ($this->_tpl_vars['data']['shop_type'] == 'distribution'): ?>selected<?php endif; ?>>直供</option>
        </select>
      </td>
    </tr>
    <tr> 
      <td><strong>所属公司</strong> * </td>
      <td>
        <select name="company">
          <option value="1" <?php if ($this->_tpl_vars['data']['company'] == '1'): ?>selected<?php endif; ?>>垦丰</option>
          <option value="2" <?php if ($this->_tpl_vars['data']['company'] == '2'): ?>selected<?php endif; ?>>御网</option>
        </select>
      </td>
    </tr>
    <tr> 
      <td><strong>佣金类型</strong> * </td>
      <td>
        <input type="radio" name="commission_type" value="1" <?php if ($this->_tpl_vars['data']['commission_type'] == 1 || $this->_tpl_vars['action'] == 'add'): ?>checked<?php endif; ?> onclick="document.getElementById('commission_rate_row').style.display='none'">差价
        <input type="radio" name="commission_type" value="2" <?php if ($this->_tpl_vars['data']['commission_type'] == 2): ?>checked<?php endif; ?> onclick="document.getElementById('commission_rate_row').style.display=''">提成
      </td>
    </tr>
    <tr id="commission_rate_row" <?php if ($this->_tpl_vars['data']['commission_type'] == '1' || $this->_tpl_vars['action'] == 'add'): ?>style="display:none"<?php endif; ?>> 
      <td><strong>佣金率</strong> * </td>
      <td>
        <input type="text" name="commission_rate" id="commission_rate" value="<?php echo $this->_tpl_vars['data']['commission_rate']; ?>
" size="2">%
      </td>
    </tr>
    <tr> 
      <td><strong>店铺地址</strong></td>
      <td>
		<input type="text" name="shop_url" id="shop_url" size="50" value="<?php echo $this->_tpl_vars['data']['shop_url']; ?>
"/>
	  </td>
    </tr>
    <tr> 
      <td><strong>自动下载订单时间间隔</strong></td>
      <td>
		<input type="text" name="sync_order_interval" id="sync_order_interval" size="2" value="<?php echo $this->_tpl_vars['data']['sync_order_interval']; ?>
"/>分钟 <font color="999999">(0表示不自动同步)</font>
	  </td>
    </tr>
    <tr> 
      <td><strong>是否启用</strong> * </td>
      <td>
	   <input type="radio" name="status" value="0" <?php if ($this->_tpl_vars['data']['status'] == 0 && $this->_tpl_vars['action'] == 'edit'): ?>checked<?php endif; ?>/> 是
	   <input type="radio" name="status" value="1" <?php if ($this->_tpl_vars['data']['status'] == 1 || $this->_tpl_vars['action'] == 'add'): ?>checked<?php endif; ?>/> 否
	  </td>
    </tr>
</tbody>
</table>
<div id="configArea">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
  <?php if ($this->_tpl_vars['config']): ?>
  <?php $_from = $this->_tpl_vars['config']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field'] => $this->_tpl_vars['name']):
?>
  <tr>
    <td width="15%"><strong><?php echo $this->_tpl_vars['name']; ?>
</strong></td>
    <td><input type="text" name="config[<?php echo $this->_tpl_vars['field']; ?>
]" value="<?php echo $this->_tpl_vars['data']['config'][$this->_tpl_vars['field']]; ?>
" size="30"></td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  <?php endif; ?>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>

<script>
function showConfigArea(shopType, shopID) {
    new Request({
        url: '/admin/shop/show-config-area/shopType/' + shopType + '/shopID/' + shopID + '/r/' + Math.random(),
        onRequest: loading,
        onSuccess:function(data){
            document.getElementById('configArea').innerHTML = '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">' + data + '</table>';
        }
    }).send();
}
</script>