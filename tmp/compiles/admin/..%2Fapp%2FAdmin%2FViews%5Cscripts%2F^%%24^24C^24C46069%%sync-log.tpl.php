<?php /* Smarty version 2.6.19, created on 2014-11-22 18:58:06
         compiled from shop/sync-log.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'shop/sync-log.tpl', 79, false),)), $this); ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
  <form id="searchForm" method="get">
  <span style="float:left;line-height:18px;">开始时间：</span>
      <span style="float:left;width:150px;line-height:18px;">
        <input type="text" name="fromdate" id="fromdate" size="15" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
"  class="Wdate" onClick="WdatePicker()"/>
      </span>
      <span style="float:left;line-height:18px;">结束时间：</span>
      <span style="float:left;width:150px;line-height:18px;">
        <input  type="text" name="todate" id="todate" size="15" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
"  class="Wdate"  onClick="WdatePicker()"/>
      </span>
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
    <option value="distribution" <?php if ($this->_tpl_vars['data']['shop_type'] == 'distribution'): ?>selected<?php endif; ?>>直供</option>
  </select>
  店铺名称：
  <select name="shop_id">
    <option value="">请选择...</option>
    <?php $_from = $this->_tpl_vars['shopDatas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <?php if ($this->_tpl_vars['data']['shop_type'] != 'jiankang' && $this->_tpl_vars['data']['shop_type'] != 'credit' && $this->_tpl_vars['data']['shop_type'] != 'tuan' && $this->_tpl_vars['data']['shop_type'] != 'distribution'): ?>
      <option value="<?php echo $this->_tpl_vars['data']['shop_id']; ?>
" <?php if ($this->_tpl_vars['data']['shop_id'] == $this->_tpl_vars['param']['shop_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['data']['shop_name']; ?>
</option>
    <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
  </select>
  动作：
  <select name="action_name">
    <option value="">请选择...</option>
    <option value="goods" <?php if ($this->_tpl_vars['param']['action_name'] == 'goods'): ?>selected<?php endif; ?>>下载商品</option>
    <option value="order" <?php if ($this->_tpl_vars['param']['action_name'] == 'order'): ?>selected<?php endif; ?>>下载订单</option>
    <option value="stock" <?php if ($this->_tpl_vars['param']['action_name'] == 'stock'): ?>selected<?php endif; ?>>上传商品库存</option>
    <option value="comment" <?php if ($this->_tpl_vars['param']['action_name'] == 'comment'): ?>selected<?php endif; ?>>下载商品评论</option>
    <option value="sync" <?php if ($this->_tpl_vars['param']['action_name'] == 'sync'): ?>selected<?php endif; ?>>双向同步订单</option>
    <option value="tuan" <?php if ($this->_tpl_vars['param']['action_name'] == 'tuan'): ?>selected<?php endif; ?>>同步团购订单</option>
  </select>
  <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('dosearch'=>'search',)));?>','ajax_search')"/>
  </form>
</div>
<form name="myForm" id="myForm">
	<div class="title">同步日志</div>
	<div class="content">
<div style="padding:0 5px">
</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
			    <td>ID</td>
				<td>店铺名称</td>
				<td>动作</td>
				<td>开始时间</td>
				<td>结束时间</td>
				<td>耗时</td>
				<td>操作员</td>
				<td>查看</td>
			  </tr>
		</thead>
		<tbody>
		<?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
		  <tr>
		    <td valign="top"><?php echo $this->_tpl_vars['data']['id']; ?>
</td>
		    <td valign="top"><?php echo $this->_tpl_vars['data']['shop_name']; ?>
</td>
		    <td valign="top">
		      <?php if ($this->_tpl_vars['data']['action_name'] == 'goods'): ?>下载商品
		      <?php elseif ($this->_tpl_vars['data']['action_name'] == 'order'): ?>下载订单
		      <?php elseif ($this->_tpl_vars['data']['action_name'] == 'stock'): ?>上传商品库存
		      <?php elseif ($this->_tpl_vars['data']['action_name'] == 'comment'): ?>下载商品评论
		      <?php elseif ($this->_tpl_vars['data']['action_name'] == 'sync'): ?>双向同步订单
		      <?php elseif ($this->_tpl_vars['data']['action_name'] == 'tuan'): ?>同步团购订单
		      <?php endif; ?>
		    </td>
		    <td valign="top"><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['start_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
			<td valign="top"><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['end_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
			<td valign="top"><?php echo $this->_tpl_vars['data']['second']; ?>
 秒</td>
			<td valign="top"><?php echo $this->_tpl_vars['data']['admin_name']; ?>
</td>
			<td valign="top"><a href="/admin/shop/sync-log-detail/id/<?php echo $this->_tpl_vars['data']['id']; ?>
" target="_blank">详细</a></td>
		  </tr>
		<?php endforeach; endif; unset($_from); ?>
		</tbody>
		</table>
	</div>
	<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</form>