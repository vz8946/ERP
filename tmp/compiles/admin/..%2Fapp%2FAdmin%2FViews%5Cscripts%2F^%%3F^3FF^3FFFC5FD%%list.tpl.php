<?php /* Smarty version 2.6.19, created on 2014-11-11 08:39:05
         compiled from op-log/list.tpl */ ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm" action="/admin/op-log/list/">
<div class="search">
<span style="float:left;line-height:18px;">开始时间：</span>
<span style="float:left;width:150px;line-height:18px;"><input type="text" name="ctime" id="fromdate" size="11" value="<?php echo $this->_tpl_vars['param']['ctime']; ?>
" class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">结束时间：</span>
<span style="float:left;width:150px;line-height:18px;"><input type="text" name="ltime" id="todate" size="11" value="<?php echo $this->_tpl_vars['param']['ltime']; ?>
" class="Wdate" onClick="WdatePicker()"/></span>

<select name="bill_type">
  <option value=""  <?php if ($this->_tpl_vars['param']['bill_type'] == ''): ?>selected<?php endif; ?>>日志类型</option>
  <option value="1" <?php if ($this->_tpl_vars['param']['bill_type'] == 1): ?>selected<?php endif; ?>>订单</option>
  <option value="2" <?php if ($this->_tpl_vars['param']['bill_type'] == 2): ?>selected<?php endif; ?>>运单</option>
  <option value="3" <?php if ($this->_tpl_vars['param']['bill_type'] == 3): ?>selected<?php endif; ?>>导出</option>
  <option value="3" <?php if ($this->_tpl_vars['param']['bill_type'] == 4): ?>selected<?php endif; ?>>会员</option>
</select>
<input type="submit" name="dosearch" value="查询"/>
</div>
</form>
<div id="ajax_search">
<?php endif; ?>
	<div class="title">日志管理</div>
	<div class="content">

		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>ID</td>
				<td>类型</td>
				<td>订单号</td>
				<td>操作模块</td>
				<td>IP</td>
				<td>操作管理员</td>
				<td>时间</td>
			</tr>
		</thead>
		<tbody>
		<?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
		
			<td><?php echo $this->_tpl_vars['item']['log_id']; ?>
</td>
			
				<td>
							<?php if ($this->_tpl_vars['item']['bill_type'] == 1): ?>
				订单日志
			<?php elseif ($this->_tpl_vars['item']['bill_type'] == 2): ?>
				运单日志
			<?php elseif ($this->_tpl_vars['item']['bill_type'] == 3): ?>
				导出日志
			<?php else: ?>
				会员操作日志
			<?php endif; ?>
				</td>
				
				<td><?php echo $this->_tpl_vars['item']['bill_sn']; ?>
</td>
				<td><?php echo $this->_tpl_vars['item']['url']; ?>
</td>
				<td><?php echo $this->_tpl_vars['item']['ip']; ?>
</td>
				<td><?php echo $this->_tpl_vars['item']['admin_name']; ?>
</td>
				<td><?php echo $this->_tpl_vars['item']['optdata']; ?>
</td>
			
		</tr>
		<?php endforeach; endif; unset($_from); ?>
		</tbody>
		</table>
	</div>
	<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</div>
<script type="text/javascript">
function changHot(articleID, st){
	articleID = parseInt(articleID);
	st = parseInt(st);
	if(st!=1 && st!=0){ st = 0; }
	new Request({
		url:'/admin/article/is-hot/article_id/'+articleID+'/st/'+st,
		onSuccess:function(msg){
			if(msg == 'ok'){
				location.reload();
			}else{
				alert(msg);
			}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}
</script>