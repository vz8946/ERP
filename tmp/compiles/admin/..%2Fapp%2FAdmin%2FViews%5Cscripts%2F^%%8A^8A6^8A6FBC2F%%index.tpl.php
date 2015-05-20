<?php /* Smarty version 2.6.19, created on 2014-10-27 14:33:38
         compiled from sale-result/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'sale-result/index.tpl', 31, false),)), $this); ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm"  action="/admin/sale-result/index" onsubmit="return check();">
<div>
    <span style="float:left">开始日期：
        <select name="start_month" id="start_month">
        <option value=''>请选择</option>
        <?php $_from = $this->_tpl_vars['search_option']['year_month']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['info']):
?>
            <option value='<?php echo $this->_tpl_vars['key']; ?>
' <?php if ($this->_tpl_vars['params']['start_month'] == $this->_tpl_vars['key']): ?> selected=selected<?php endif; ?>><?php echo $this->_tpl_vars['info']; ?>
</option>
        <?php endforeach; endif; unset($_from); ?>
        </select>
    </span>
    <span style="margin-left:10px">
        截止日期：<select name="end_month" id="end_month">
        <option value=''>请选择</option>
        <?php $_from = $this->_tpl_vars['search_option']['year_month']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['info']):
?>
            <option value='<?php echo $this->_tpl_vars['key']; ?>
' <?php if ($this->_tpl_vars['params']['end_month'] == $this->_tpl_vars['key']): ?> selected=selected<?php endif; ?>><?php echo $this->_tpl_vars['info']; ?>
</option>
        <?php endforeach; endif; unset($_from); ?>
        </select>
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
</div>
<input type="submit" name="dosearch" value="查询" />
<input type="hidden" name="collect" id="collect" value="0" />
<input type="button" name="collect" value="汇总查询" onclick="query()"/>
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
            <?php if ($this->_tpl_vars['params']['collect'] != '1'): ?>
            <td>操作</td>
            <td>ID</td>
            <?php else: ?>
            <td>状态</td>
            <?php endif; ?>
            <th>产品编码</th>
            <td>产品名称</td>
            <td>供应商</td>
            <td>需处理数量</td>
			<td>已处理数量</td>
            <?php if ($this->_tpl_vars['params']['collect'] != '1'): ?>
            <td>操作年月</td>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['infos']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['info']):
?>
        <tr>
            <?php if ($this->_tpl_vars['params']['collect'] != '1'): ?>
            <td>
                <?php if ($this->_tpl_vars['info']['deal_number'] == $this->_tpl_vars['info']['number']): ?>
                    <input type="button" onclick="openDiv('/admin/sale-result/clear-sale/sale_result_id/<?php echo $this->_tpl_vars['info']['sale_result_id']; ?>
','ajax','销售产品数量结算',400,400,true)" value="查看">
                <?php else: ?>
                <input type="button" onclick="openDiv('/admin/sale-result/clear-sale/sale_result_id/<?php echo $this->_tpl_vars['info']['sale_result_id']; ?>
','ajax','销售产品数量结算',400,400,true)" value="数量结算">
                <?php endif; ?>
            </td>

            <td><?php echo $this->_tpl_vars['info']['sale_result_id']; ?>
</td>
            <?php else: ?>
            <td>
                <?php if ($this->_tpl_vars['info']['deal_number'] == $this->_tpl_vars['info']['number']): ?>
                    结算完毕
                <?php else: ?>
                    <span style="color:#ff0000">结算中</span>
                <?php endif; ?>
            </td>
            <?php endif; ?>
            <td><?php echo $this->_tpl_vars['info']['product_sn']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['product_name']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['supplier_name']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['number']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['deal_number']; ?>
</td>
            <?php if ($this->_tpl_vars['params']['collect'] != '1'): ?>
            <td><?php echo $this->_tpl_vars['info']['created_month']; ?>
</td>
            <?php endif; ?>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        <?php if ($this->_tpl_vars['total_counts']): ?>
        <tr>
            <td height="30" colspan="4">总计：</td>
            <td><?php echo $this->_tpl_vars['total_counts']['number']; ?>
</td>
            <td colspan="2"><?php echo $this->_tpl_vars['total_counts']['deal_number']; ?>
</td>
        </tr>
        <?php endif; ?>
        </tbody>
    </table>
    <div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</div>
<script>
    function check()
    {
        var start_month = $("start_month").value;
        var end_month   = $("end_month").value;

        if (typeof(start_month) != 'undefined' && typeof(end_month) != 'undefined' && end_month < start_month) {
            alert('结束日期不能小于开始日期');
            return false;
        }
    }

    function query()
    {
        var start_month = $("start_month").value;
        var end_month   = $("end_month").value;
        if (typeof(start_month) != 'undefined' && typeof(end_month) != 'undefined' && end_month < start_month) {
            alert('结束日期不能小于开始日期');
            return false;
        }
        
        $("collect").value = 1;
        document.getElementById("searchForm").submit();
    }
</script>