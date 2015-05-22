<?php /* Smarty version 2.6.19, created on 2014-10-24 18:42:25
         compiled from offers/assign-goods.tpl */ ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%" id="discountTable" class="table_form">
<tbody>
<tr>
<td width="10%">
   商品编号*
 </td>
<td width="20%"><input type="text" value="<?php echo $this->_tpl_vars['offers']['config']['product_sn']; ?>
" name="product_sn"></td>
<td></td></tr>
<tr>
<td width="10%">
数量区间设置
 </td>
<td colspan="2">
<table style="width:100%" class="table">
<thead>
<tr>
<th width="150">数量（整数*）</th> <th width="150">单价（数字*）</th> <th width="350"> 赠品*   </th>
<th width="*">操作  
<a href="#" onclick="return add_row_goods();" style="color:blue;" onfocus="this.blur();">+增加一行</a></th>
</tr>
</thead>
<tbody id="tmp_body">
<?php if ($this->_tpl_vars['offers']['config']['goods']): ?>
<?php $_from = $this->_tpl_vars['offers']['config']['goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
<tr>
		<td><input type="text" style="width:30px" name="goods[<?php echo $this->_tpl_vars['key']; ?>
][start_num]" value="<?php echo $this->_tpl_vars['item']['start_num']; ?>
"> -
		 <input type="text" style="width:30px" name="goods[<?php echo $this->_tpl_vars['key']; ?>
][end_num]" value="<?php echo $this->_tpl_vars['item']['end_num']; ?>
"> </td>
		<td><input type="text" name="goods[<?php echo $this->_tpl_vars['key']; ?>
][price]" value="<?php echo $this->_tpl_vars['item']['price']; ?>
"> </td>
		<td>
		<input type="button" onclick="openGoodsWin('text', 'assign-goods',this,<?php echo $this->_tpl_vars['key']; ?>
)" value="添加 ">
		<div style="padding-left:5px; align:left" class="gifbox">	
	      <?php $this->assign('t_index', $this->_tpl_vars['key']); ?>
		  <?php $_from = $this->_tpl_vars['item']['gift']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['gk'] => $this->_tpl_vars['gnum']):
?>
		  <p> <a title="删除" onclick="this.parentNode.parentNode.removeChild(this.parentNode);" href="javascript:fGo()"><img border="0" src="/images/admin/delete.png"></a> 赠品ID：<?php echo $this->_tpl_vars['gk']; ?>
  <input type="text" size="15" value="<?php echo $this->_tpl_vars['gnum']; ?>
" name="goods[<?php echo $this->_tpl_vars['key']; ?>
][gift][<?php echo $this->_tpl_vars['gk']; ?>
]"> </p>
		  <?php endforeach; endif; unset($_from); ?>  
		</div>
		</td>
		<td><a href="javascript:;" onclick="return removeTr(this);" title="删除">删除</a></td>
</tr>
<?php endforeach; endif; unset($_from); ?>
<?php else: ?>
<tr>
		<td><input type="text" style="width:30px" name="goods[0][start_num]"> - <input type="text" style="width:30px" name="goods[0][end_num]"> </td>
		<td><input type="text" name="goods[0][price]"> </td>
		<td>
		<input type="button" onclick="openGoodsWin('text', 'assign-goods',this,0)" value="添加 ">
		<div style="padding-left:5px; align:left" class="gifbox"></div>
		</td>
		<td><a href="javascript:;" onclick="return removeTr(this);" title="删除">删除</a></td>
</tr>
<?php endif; ?>	
</tbody>
</table>
</td>
</tr>
</table>

<script>
var t_num = <?php echo $this->_tpl_vars['t_index']+1; ?>
;
function add_row_goods()
{	
      var td1 = new Element('td');
	  var td2 = new Element('td');
	  var td3 = new Element('td');
	  var td4 = new Element('td');
	  
	 td1.innerHTML="<input type=\"text\" style=\"width:30px\" name=\"goods["+t_num+"][start_num]\"> - <input type=\"text\" style=\"width:30px\" name=\"goods["+t_num+"][end_num]\">";
	 td2.innerHTML="<input type=\"text\" name=\"goods["+t_num+"][price]\">";
	 td3.innerHTML="<input type=\"button\" onclick=\"openGoodsWin(\'text\', \'assign-goods\',this,"+t_num+")\" value=\"添加 \"><div style=\"padding-left:5px; align:left\" class=\"gifbox\"></div>";
	 td4.innerHTML="<a href=\"javascript:;\" onclick=\"return removeTr(this);\" title=\"删除\">删除</a>";
		
	 var tr = new Element('tr');	 
	 td1.inject(tr);   
	 td2.inject(tr);   
	 td3.inject(tr); 
	 td4.inject(tr);		 
	$("tmp_body").grab(tr); 
	
	t_num++;
	return false;
}

function removeTr(e)
{
	e.parentNode.parentNode.parentNode.removeChild(e.parentNode.parentNode);
	return false;
}
</script>