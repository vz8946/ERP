<?php /* Smarty version 2.6.19, created on 2014-10-27 14:36:56
         compiled from product/batch-list.tpl */ ?>
<form name="searchForm" id="searchForm">
<div class="search">
<?php echo $this->_tpl_vars['catSelect']; ?>

状态：
<select name="p_status">
  <option value="" selected>请选择</option>
  <option value="0" <?php if ($this->_tpl_vars['param']['p_status'] == '0'): ?>selected<?php endif; ?>>正常</option>
  <option value="1" <?php if ($this->_tpl_vars['param']['p_status'] == '1'): ?>selected<?php endif; ?>>冻结</option>
</select>
供应商：<select name="supplier_id" id="supplier_id">
          <option value="">请选择...</option>
          <?php $_from = $this->_tpl_vars['supplierData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['s']):
?>
 		  <option value="<?php echo $this->_tpl_vars['s']['supplier_id']; ?>
" <?php if ($this->_tpl_vars['param']['supplier_id'] == $this->_tpl_vars['s']['supplier_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['s']['supplier_name']; ?>
</option>
          <?php endforeach; endif; unset($_from); ?>
        </select>
<br>
产品编码：<input type="text" name="product_sn" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['param']['product_sn']; ?>
"/>
产品名称：<input type="text" name="product_name" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['product_name']; ?>
"/>
批次号：<input type="text" name="batch_no" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['batch_no']; ?>
"/>
条形码：<input type="text" name="barcode" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['barcode']; ?>
"/>
<input type="submit" name="dosearch" id="dosearch" value="查询"/>
<input type="reset" name="reset" value="清除">

</div>
</form>
<form name="myForm" id="myForm">
<div class="title">产品管理 -&gt; 产品批次列表</div>
<div class="content">
<div class="sub_title">
  [ <a href="javascript:fGo()" onclick="G('/admin/product/add-batch');">添加新批次</a> ] 
</div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td>批次号</td>
            <td>产品编码</td>
            <td width="200px">产品名称（规格）</td>
            <td>系统分类</td>
            <td>条形码</td>
            <td>供应商</td>
            <td>状态</td>
            <td>排序</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['batch_id']; ?>
">
        <td><?php echo $this->_tpl_vars['data']['batch_no']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['product_sn']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['product_name']; ?>
<font color="#FF0000"> (<?php echo $this->_tpl_vars['data']['goods_style']; ?>
) </font></td>
        <td><?php echo $this->_tpl_vars['data']['cat_name']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['barcode']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['supplier_name']; ?>
</td>
        <td>
          <?php if ($this->_tpl_vars['data']['status']): ?><font color="red">冻结</font>
          <?php else: ?>正常
          <?php endif; ?>
        </td>
        <td>
          <input type="text" name="sort" id="sort" size="1" style="text-align:center" value="<?php echo $this->_tpl_vars['data']['sort']; ?>
" onchange="setSort(<?php echo $this->_tpl_vars['data']['batch_id']; ?>
, this.value)">
        </td>
        <td>
	      <a href="javascript:fGo()" onclick="G('/admin/product/edit-batch/batch_id/<?php echo $this->_tpl_vars['data']['batch_id']; ?>
')">编辑</a> 
        </td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</form>
<script language="JavaScript">
function setSort(batch_id, value)
{
    if (!/^\d{0,4}$/.test(value)) {
        alert('请输入四位以内的数字!');
        return;
    }

    if (batch_id != '' && value != '') {
        new Request({
            url: '/admin/product/batch-change-sort/batch_id/' + batch_id + '/sort/' + value,
            onRequest: loading,
            onSuccess: loadSucess,
            onFailure: function(){
        	    alert('error');
            }
        }).send();
    }
}
</script>