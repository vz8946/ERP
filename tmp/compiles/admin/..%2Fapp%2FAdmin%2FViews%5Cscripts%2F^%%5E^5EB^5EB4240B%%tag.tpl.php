<?php /* Smarty version 2.6.19, created on 2014-12-05 10:22:00
         compiled from brand/tag.tpl */ ?>
<form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>"brand-tag",)));?>" method="post" target="ifrmSubmit"/>
<div class="title">设置推荐商品 -&gt; <?php echo $this->_tpl_vars['data']['title']; ?>
</div>
<div class="content">
    <div>
   <input type="button" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('controller'=>'goods','action'=>'sel',)));?>','ajax','查询商品',750,450,true,'sel');" value="查询添加<?php if ($this->_tpl_vars['type']): ?>组合<?php endif; ?>商品">
        排序：<input type="text" id="m_id" size="5">前置于<input type="text" id="to_id" size="5">
        <input type="button" onclick="sort();" value="排序">
    </div>
<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
	<tr>
	<td width="80">删除</td>
	<td>ID</td>
	<td>商品编码</td>
	<td>商品名称</td>
	<td>状态</td>
	</tr>
</thead>
<tbody id="list">
<?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['d']):
?>
<tr id="sid<?php echo $this->_tpl_vars['d']['goods_id']; ?>
">
	<td><input type="button" onclick="removeRow('<?php echo $this->_tpl_vars['d']['goods_id']; ?>
')" value="删除"><input type="hidden" name="goods_id[]" value="<?php echo $this->_tpl_vars['d']['goods_id']; ?>
" ></td>
	<td><?php echo $this->_tpl_vars['d']['goods_id']; ?>
</td>
	<td><?php echo $this->_tpl_vars['d']['goods_sn']; ?>
</td>
	<td><?php echo $this->_tpl_vars['d']['goods_name']; ?>
</td>
	<td><?php if ($this->_tpl_vars['d']['onsale'] == 0): ?>上架<?php else: ?><font color="red">下架</font><?php endif; ?></td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</tbody>
</table>
</div>
<div class="submit">
该标签下共有商品<?php echo $this->_tpl_vars['num']; ?>
件
<input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>
<script language="JavaScript">
function addRow()
{
	var el = $('source_select').getElements('input[type=checkbox]');
	var obj = $('list');
	for (i = 1; i < el.length; i++)
	{
		if (el[i].checked)
		{
			var goods_id = el[i].value;
			var str = $('ginfo' + goods_id).value;
			var ginfo = JSON.decode(str);
			if ($('sid' + goods_id))
			{
				continue;
			}
			else
			{
			    var tr = obj.insertRow(0);
			    tr.id = 'sid' + goods_id;
			    for (var j = 0;j <= 5; j++)
				{
				  	 tr.insertCell(j);
				}
				tr.cells[0].innerHTML = '<input type="button" value="删除" onclick="removeRow('+ goods_id +')"><input type="hidden" name="goods_id[]" value="'+goods_id+'" >';
				tr.cells[1].innerHTML = ginfo.goods_id;
				tr.cells[2].innerHTML = ginfo.goods_sn;
				tr.cells[3].innerHTML = ginfo.goods_name;
				tr.cells[4].innerHTML = ginfo.goods_status;
				obj.appendChild(tr);
			}
		}
	}
}

function removeRow(id)
{
	$('sid' + id).destroy();
}

function sort()
{
	var m_id = $('m_id').value;
	var to_id = $('to_id').value;
	if (m_id == '' || to_id == '') return false;
	var html = $('sid' + m_id);
	$('sid' + m_id).injectBefore($('sid' + to_id));
}
</script>