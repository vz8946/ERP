<?php /* Smarty version 2.6.19, created on 2014-10-23 09:11:39
         compiled from data/fix.tpl */ ?>
<form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>" method="post">
<div class="title"><?php echo $this->_tpl_vars['title']; ?>
</div>
<div class="content">

<input type="hidden" name="type" value="" />
<input type="button" name="optbtn" value=" 优化表 " onclick="dosubmit('optimize', this.form)" /> <input type="button" name="repbtn" value=" 修复表 " onclick="dosubmit('repair', this.form)" />

<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table" id="selectTable">
<thead>
<tr>
<td><label><input type="checkbox" name="chkall" title="全选" onclick="checkall($('selectTable'), 'tables', this);" /></label></td>
<td>数据表名</td>
<td>引擎类型</td>
<td>字符编码</td>
<td>记录数</td>
<td>数据大小</td>
<td>索引大小</td>
<td>碎片大小</td>
<td>创建时间</td>
<td>更新时间</td>
<td>状态</td>
</tr>
</thead>
<tbody>
<?php $_from = $this->_tpl_vars['tables']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['table'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['table']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['table']):
        $this->_foreach['table']['iteration']++;
?>
<tr>
<td><input type="checkbox" name="tables[]" value="<?php echo $this->_tpl_vars['table']['Name']; ?>
" /></td>
<td><?php echo $this->_tpl_vars['table']['Name']; ?>
</td>
<td><?php echo $this->_tpl_vars['table']['Engine']; ?>
</td>
<td><?php echo $this->_tpl_vars['table']['Collation']; ?>
</td>
<td><?php echo $this->_tpl_vars['table']['Rows']; ?>
</td>
<td><?php echo $this->_tpl_vars['table']['Data_length']; ?>
</td>
<td><?php echo $this->_tpl_vars['table']['Index_length']; ?>
</td>
<td><?php echo $this->_tpl_vars['table']['Data_free']; ?>
</td>
<td><?php echo $this->_tpl_vars['table']['Create_time']; ?>
</td>
<td><?php echo $this->_tpl_vars['table']['Update_time']; ?>
</td>
<td><?php echo $this->_tpl_vars['table']['Status']; ?>
</td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</tbody>
</table>

<input type="hidden" name="type" value="" />
<input type="button" name="optbtn" value=" 优化表 " onclick="dosubmit('optimize', this.form)" /> <input type="button" name="repbtn" value=" 修复表 " onclick="dosubmit('repair', this.form)" />

</div>
</form>
<br />
<script>
function dosubmit(type, form)
{
    var checked = false;
    var checkbox = form.getElements('input[type=checkbox]');
	for (var i = 0; i < checkbox.length; i++) {
		var e = checkbox[i];
		if (e.name.match('tables') && e.checked == true) {
			checked = true;
		}
	}
	if (checked == false) {
	    top.alertBox.init("msg='请选择数据表!'");
	    exit;
	}
    form.type.value=type;
    form.set('send', {
        url: '<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>',
        method: 'post',
        evalScripts: true,
        onRequest: loading,
        onSuccess: function(data)
        {
            loaded(url, false);
            mainAddEvent();
        },
        onFailure: function()
        {
            alert('error');
        }
    }).send();
}
</script>