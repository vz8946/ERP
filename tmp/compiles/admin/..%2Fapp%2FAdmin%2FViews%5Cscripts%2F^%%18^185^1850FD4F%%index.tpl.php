<?php /* Smarty version 2.6.19, created on 2014-10-23 10:54:13
         compiled from customer/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'customer/index.tpl', 14, false),)), $this); ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm" onsubmit="return check();" action="/admin/customer/index">
<div>
    <span style="float:left">开始日期：
        <input type="text"  value="<?php echo $this->_tpl_vars['params']['start_ts']; ?>
" id="start_ts"  name="start_ts"   class="Wdate"   onClick="WdatePicker()" >
    </span>
    <span style="margin-left:10px">
        截止日期：<input  type="text"  value="<?php echo $this->_tpl_vars['params']['end_ts']; ?>
" id="end_ts"  name="end_ts"   class="Wdate"   onClick="WdatePicker()" >
    </span>
    <span style="margin-left:10px">店铺：
        <select name="shop_id">
        <option value="">请选择</option>
        <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['search_option']['shop_info'],'selected' => $this->_tpl_vars['params']['shop_id']), $this);?>

        </select>
    </span>
</div>
<input type="submit" name="dosearch" value="查询" />
</div>
</form>
</div>
<div class="title">信息列表</div>
<div class="content">
    <div class="sub_title">
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
        <thead>
        <tr>
            <td>客户ID</td>
            <td>店铺</td>
            <th>客户姓名</th>
            <td>电话</td>
            <td>手机</td>
            <td>第一次购买时间</td>
            <td>最后一次购买时间</td>
            <td>省份</td>
            <td>订单总数</td>
            <td>总消费额</td>
            <td>商品总数</td>
            <td>创建时间</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['infos']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['info']):
?>
        <tr>
            <td><?php echo $this->_tpl_vars['info']['customer_id']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['shop_name']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['real_name']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['telphone']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['mobile']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['first_order_time']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['last_order_time']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['province_name']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['order_count']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['price_order']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['number']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['created_ts']; ?>
</td>
            <td><a href="/admin/customer/customer-product/customer_id/<?php echo $this->_tpl_vars['info']['customer_id']; ?>
" >订购产品详情</a> | <a href="/admin/customer/customer-order/customer_id/<?php echo $this->_tpl_vars['info']['customer_id']; ?>
" >订购订单详情</a></td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        </tbody>
    </table>
    <div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</div>