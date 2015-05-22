<?php /* Smarty version 2.6.19, created on 2014-11-19 16:31:54
         compiled from shop/list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'shop/list.tpl', 75, false),)), $this); ?>
<div class="search">
  <form id="searchForm" method="get">
  店铺类型：
  <select name="shop_type">
    <option value="">请选择...</option>
    <option value="taobao" <?php if ($this->_tpl_vars['param']['shop_type'] == 'taobao'): ?>selected<?php endif; ?>>淘宝</option>
    <option value="jingdong" <?php if ($this->_tpl_vars['param']['shop_type'] == 'jingdong'): ?>selected<?php endif; ?>>京东</option>
    <option value="yihaodian" <?php if ($this->_tpl_vars['param']['shop_type'] == 'yihaodian'): ?>selected<?php endif; ?>>一号店</option>
    <option value="dangdang" <?php if ($this->_tpl_vars['param']['shop_type'] == 'dangdang'): ?>selected<?php endif; ?>>当当网</option>
    <option value="qq" <?php if ($this->_tpl_vars['param']['shop_type'] == 'qq'): ?>selected<?php endif; ?>>QQ商城</option>
    <option value="alibaba" <?php if ($this->_tpl_vars['param']['shop_type'] == 'alibaba'): ?>selected<?php endif; ?>>阿里巴巴</option>
    <option value="tuan" <?php if ($this->_tpl_vars['param']['shop_type'] == 'tuan'): ?>selected<?php endif; ?>>团购</option>
    <option value="credit" <?php if ($this->_tpl_vars['param']['shop_type'] == 'credit'): ?>selected<?php endif; ?>>赊销</option>
    <option value="distribution" <?php if ($this->_tpl_vars['param']['shop_type'] == 'distribution'): ?>selected<?php endif; ?>>直供</option>
  </select>
  店铺名称：<input type="text" name="shop_name" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['param']['shop_name']; ?>
">
  佣金分成类型：
	<select name="commission_type">
		<option value="">请选择...</option>
		<option value="1" <?php if ($this->_tpl_vars['param']['commission_type'] == '1'): ?>selected<?php endif; ?>>差价</option>
		<option value="2" <?php if ($this->_tpl_vars['param']['commission_type'] == '2'): ?>selected<?php endif; ?>>提成</option>
	</select>
  店铺状态：
	<select name="status">
		<option value="">请选择...</option>
		<option value="0" <?php if ($this->_tpl_vars['param']['status'] == '0'): ?>selected<?php endif; ?>>正常</option>
		<option value="1" <?php if ($this->_tpl_vars['param']['status'] == '1'): ?>selected<?php endif; ?>>冻结</option>
	</select>
  <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('dosearch'=>'search',)));?>','ajax_search')"/>
  </form>
</div>
<form name="myForm" id="myForm">
	<div class="title">店铺列表 [<a href="/admin/shop/add">添加店铺</a>]</div>
	<div class="content">
<div style="padding:0 5px">
</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
			    <td>ID</td>
				<td>店铺名称</td>
				<td>店铺类型</td>
				<td>所属公司</td>
				<td>佣金分成类型</td>
				<td>状态</td>
				<td>自动下载订单</td>
				<td>最后下载订单时间</td>
				<td>添加时间</td>
				<td >操作</td>
			  </tr>
		</thead>
		<tbody>
		<?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
		<tr >
		    <td valign="top"><?php echo $this->_tpl_vars['data']['shop_id']; ?>
</td>
		    <td valign="top"><?php echo $this->_tpl_vars['data']['shop_name']; ?>
</td>
			<td valign="top"><?php echo $this->_tpl_vars['data']['shop_type']; ?>
</td>
			<td valign="top">
			  <?php if ($this->_tpl_vars['data']['company'] == 1): ?>自家
			  <?php elseif ($this->_tpl_vars['data']['company'] == 2): ?>合作
			  <?php endif; ?>
			</td>
			<td valign="top">
			  <?php if ($this->_tpl_vars['data']['commission_type'] == 1): ?>差价
			  <?php elseif ($this->_tpl_vars['data']['commission_type'] == 2): ?>提成
			  <?php endif; ?>
			</td>
			<td id="ajax_status<?php echo $this->_tpl_vars['data']['shop_id']; ?>
"><?php echo $this->_tpl_vars['data']['status']; ?>
</td>
			<td valign="top">
			  <?php if ($this->_tpl_vars['data']['sync_order_interval']): ?>是
			  <?php else: ?>否
			  <?php endif; ?>
			</td>
			<td valign="top"><?php echo $this->_tpl_vars['data']['sync_order_time']; ?>
</td>
			<td valign="top"><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
</td>
			<td valign="top">
			  <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'edit','id'=>$this->_tpl_vars['data']['shop_id'],)));?>')">编辑</a>
			  <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'sync','id'=>$this->_tpl_vars['data']['shop_id'],)));?>')">同步</a> 
			  <?php if ($this->_tpl_vars['data']['shop_type'] == 'jingdong' || $this->_tpl_vars['data']['shop_type'] == 'taobao' || $this->_tpl_vars['data']['shop_type'] == 'alibaba' || $this->_tpl_vars['data']['shop_type'] == 'dangdang'): ?>
			  <a href="/admin/shop/oauth/shop_id/<?php echo $this->_tpl_vars['data']['shop_id']; ?>
" target="_blank">授权</a>
			  <?php endif; ?>
			  <?php if ($this->_tpl_vars['data']['shop_url']): ?><a href="<?php echo $this->_tpl_vars['data']['shop_url']; ?>
" target="_blank">前往店铺</a><?php endif; ?>
			</td>
		  </tr>
		<?php endforeach; endif; unset($_from); ?>
		</tbody>
		</table>
	</div>
	<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</form>