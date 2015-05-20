<?php /* Smarty version 2.6.19, created on 2014-11-12 16:44:42
         compiled from customer/customer-buy.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'customer/customer-buy.tpl', 14, false),)), $this); ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm" action="/admin/customer/customer-buy">
<div>
    <span style="float:left">下单开始日期：
        <input type="text"  value="<?php echo $this->_tpl_vars['params']['start_ts']; ?>
" id="start_ts"  name="start_ts"   class="Wdate"   onClick="WdatePicker()" >
    </span>
    <span style="margin-left:10px">
        下单截止日期：<input  type="text"  value="<?php echo $this->_tpl_vars['params']['end_ts']; ?>
" id="end_ts"  name="end_ts"   class="Wdate"   onClick="WdatePicker()" >
    </span>
    <span style="margin-left:10px">店铺：
        <select name="shop_id">
        <option value="">请选择</option>
        <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['search_option']['shop_info'],'selected' => $this->_tpl_vars['params']['shop_id']), $this);?>

        </select>
    </span>
    <span style="margin-left:10px">
        购买次数介于：<input  type="text"  value="<?php echo $this->_tpl_vars['params']['times_start']; ?>
" id="times_start"  name="times_start" size=10> <input  type="text"  value="<?php echo $this->_tpl_vars['params']['times_end']; ?>
" id="times_end"  name="times_end" size=10>

    </span>
    <span style="margin-left:10px">
        购买金额介于：<input  type="text"  value="<?php echo $this->_tpl_vars['params']['amount_start']; ?>
" id="amount_start"  name="amount_start" size=10> <input  type="text"  value="<?php echo $this->_tpl_vars['params']['amount_end']; ?>
" id="amount_end"  name="amount_end" size=10>
    </span>
</div>
<input type="button" name="dosearch" value="查询" onclick="check();" />
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
            <th>客户姓名</th>
            <td>电话</td>
            <td>手机</td>
            <td>下单时间</td>
            <td>省份</td>
            <td>订单总数</td>
            <td>总消费额</td>
            <td>商品总数</td>
        </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['infos']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['info']):
?>
        <tr>
            <td><?php echo $this->_tpl_vars['info']['customer_id']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['real_name']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['telphone']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['mobile']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['order_time']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['province_name']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['order_count']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['price_order']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['number']; ?>
</td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        </tbody>
    </table>
    <div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</div>
<script language=javascript>
    function check()
    {
        var times_start = $("times_start").value;
        var times_end   = $("times_end").value;

        if (times_start.trim() == '' && times_end.trim() == '') {
            alert('购买次数不能为空');
            return false;
        }

        $("searchForm").submit();
    }
</script>