<?php /* Smarty version 2.6.19, created on 2014-11-07 17:59:08
         compiled from stock-report/add-inventory-xls.tpl */ ?>
<div class="title">库存管理 -&gt; 初始库存盘点导入</div>
<form name="searchForm" id="searchForm" method="post" action="<?php echo $this -> callViewHelper('url', array());?>" enctype="multipart/form-data">
<div class="content">
&nbsp;选择仓库
<select name="logic_area" id="logic_area">
<?php $_from = $this->_tpl_vars['areas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
<option value="<?php echo $this->_tpl_vars['key']; ?>
"><?php echo $this->_tpl_vars['item']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</select>
&nbsp;选择库存状态
<select name="status_id" id="status_id">
<option value="">全部状态</option>
<?php $_from = $this->_tpl_vars['status']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
<option value="<?php echo $this->_tpl_vars['key']; ?>
"><?php echo $this->_tpl_vars['item']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</select>
<font color="red">如果是“全部盘点”，导入文件前，请先确认选择了正确的仓库和库存状态！</font>
<br><br><br><br>
<input type="submit" name="export" value="导出待盘点库存">
<font color="red">导出的文件格式是XML的XLS文件，请先将文件另存为2003 Excel！</font>
<br><br><br><br>
&nbsp;<select name="type">
<option value="part">部分盘点</option>
<option value="full">全部盘点</option>
</select>
&nbsp;导入文件 <input type="file" name="import_file" id="import_file">
<input type="submit" name="run" value="开始初始盘点" onclick="if ($('import_file').value == ''){alert('选择导入文件!');return false;}return true;">
</form>
<br><br>
&nbsp;<font color="red">盘点前必须先导出待盘点的库存文件，在该文件基础上修改实际盘点库存并导入，如果选择“全部盘点”，该文件中没有出现的其它所有产品库存（选择范围内）将会清0！</font>
</div>
</form>