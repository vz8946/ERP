<?php /* Smarty version 2.6.19, created on 2014-10-27 14:33:39
         compiled from sale-report/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'sale-report/index.tpl', 21, false),)), $this); ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm"  action="/admin/sale-report/index" onsubmit="return check();">
<div>
    <span style="float:left">开始日期：
        <input  type="text" name="start_ts" id="start_ts" size="15" value="<?php echo $this->_tpl_vars['params']['start_ts']; ?>
"  class="Wdate"  onClick="WdatePicker()"/>
    </span>
    <span style="margin-left:10px">
        截止日期：<input  type="text" name="end_ts" id="end_ts" size="15" value="<?php echo $this->_tpl_vars['params']['end_ts']; ?>
"  class="Wdate"  onClick="WdatePicker()"/>(两个月以内)
    </span>

    <span style="margin-left:10px">
        产品编码：<input  type="text"  value="<?php echo $this->_tpl_vars['params']['product_sn']; ?>
" id="product_sn"  name="product_sn" / >
    </span>
    <span style="margin-left:10px">
        产品名称：<input  type="text"  value="<?php echo $this->_tpl_vars['params']['product_name']; ?>
" id="product_name"  name="product_name" / >
    </span>
    <span style="margin-left:10px">供应商：
        <select name="supplier_id">
        <option value="">请选择</option>
        <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['search_option']['supplier_info'],'selected' => $this->_tpl_vars['params']['supplier_id']), $this);?>

        </select>
    </span>
    <input type="hidden" name="query" value="1" />
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
            <th>产品ID</th>
            <th>供应商</th>
            <th>产品编码</th>
            <td>产品名称</td>
            <td>实际销售数量</td>
	<?php if ($this->_tpl_vars['is_view']): ?>
            <td>销售出库数量</td>
            <td>分销出库数量</td>
            <td>退货入库数量</td>
	<?php endif; ?>

        </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['infos']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['info']):
?>
        <tr>
            <td><?php echo $this->_tpl_vars['info']['product_id']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['supplier_name']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['product_sn']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['product_name']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['number']; ?>
</td>
	    <?php if ($this->_tpl_vars['is_view']): ?>
            <td><?php echo $this->_tpl_vars['info']['sale_number']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['fenxiao_number']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['return_number']; ?>
</td>
	    <?php endif; ?>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        
        </tbody>
    </table>
    <div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</div>
<script>
    function check()
    {
        var start_ts = $("start_ts").value;
        var end_ts   = $("end_ts").value;

        if (start_ts == '' || end_ts == '') {
            alert('请选择开始时间结束时间');
            return false;
        }
        if (end_ts < start_ts) {
            alert('结束日期不能小于开始日期');
            return false;
        }
    }
</script>