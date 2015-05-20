<?php /* Smarty version 2.6.19, created on 2014-11-11 09:59:18
         compiled from product-apply/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'product-apply/index.tpl', 14, false),array('modifier', 'cn_truncate', 'product-apply/index.tpl', 60, false),)), $this); ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm" onsubmit="return check();" action="/admin/product-apply/index">
<div>
    <span style="float:left">活动开始日期：
        <input type="text"  value="<?php echo $this->_tpl_vars['params']['start_ts']; ?>
" id="start_ts"  name="start_ts"   class="Wdate"   onClick="WdatePicker()" >
    </span>
    <span style="margin-left:10px">
        活动截止日期：<input  type="text"  value="<?php echo $this->_tpl_vars['params']['end_ts']; ?>
" id="end_ts"  name="end_ts"   class="Wdate"   onClick="WdatePicker()" >
    </span>
    <span style="margin-left:10px">店铺：
        <select name="shop_id">
        <option value="">请选择</option>
        <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['search_option']['shop_info'],'selected' => $this->_tpl_vars['params']['shop_id']), $this);?>

        </select>
    </span>
    <span style="margin-left:10px">商品类型：
        <select name="type">
        <option value="">请选择</option>
        <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['search_option']['type'],'selected' => $this->_tpl_vars['params']['type']), $this);?>

        </select>
    </span>
    <span> 产品编码：
        <input type="text" value="<?php echo $this->_tpl_vars['params']['product_sn']; ?>
" maxlength="10" size="10" name="product_sn">
    </span>
    <span>
     产品名称：
    <input type="text" value="<?php echo $this->_tpl_vars['params']['product_name']; ?>
" maxlength="50" size="20" name="product_name">
    </span>
</div>
<input type="submit" name="dosearch" value="查询" />
</div>
</form>
</div>
<div class="title">产品保护价申请列表</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>店铺</td>
            <td>编码</td>
            <th>名称</th>
            <td>类型</td>
            <td>保护价格</td>
            <td>活动开始时间</td>
            <td>活动结束时间</td>
            <td>备注</td>
            <td>创建人</td>
			<td>创建时间</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['infos']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['info']):
?>
        <tr>
            <td><?php echo $this->_tpl_vars['info']['product_apply_id']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['shop_name']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['product_sn']; ?>
</td>
            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['product_name'])) ? $this->_run_mod_handler('cn_truncate', true, $_tmp, 40, '...') : smarty_modifier_cn_truncate($_tmp, 40, '...')); ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['type']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['price_limit']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['start_ts']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['end_ts']; ?>
</td>
            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['remark'])) ? $this->_run_mod_handler('cn_truncate', true, $_tmp, 40, '...') : smarty_modifier_cn_truncate($_tmp, 40, '...')); ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['created_by']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['created_ts']; ?>
</td>
            <td><a href="/admin/product-apply/edit/apply_id/<?php echo $this->_tpl_vars['info']['product_apply_id']; ?>
">编辑</a></td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        </tbody>
    </table>
    <div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</div>