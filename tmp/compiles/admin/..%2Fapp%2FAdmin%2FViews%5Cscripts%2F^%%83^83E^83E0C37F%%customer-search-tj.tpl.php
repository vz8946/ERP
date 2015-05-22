<?php /* Smarty version 2.6.19, created on 2014-11-05 08:49:46
         compiled from goods/customer-search-tj.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'goods/customer-search-tj.tpl', 50, false),)), $this); ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm" action="/admin/goods/customer-search-tj/">
<div class="search">

<span style="float:left;line-height:18px;">创建开始时间：</span>
<span style="float:left;width:150px;line-height:18px;"><input type="text" name="ctime" id="fromdate" size="11" value="<?php echo $this->_tpl_vars['param']['ctime']; ?>
" class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">创建结束时间：</span>
<span style="float:left;width:150px;line-height:18px;"><input type="text" name="ltime" id="todate" size="11" value="<?php echo $this->_tpl_vars['param']['ltime']; ?>
" class="Wdate" onClick="WdatePicker()"/></span>
<br><br>
<select name="orderby">
  <option value="">排序方式</option>
  <option value="desc" <?php if ($this->_tpl_vars['param']['orderby'] == 'desc'): ?>selected<?php endif; ?>>搜索次数降序</option>
  <option value="asc" <?php if ($this->_tpl_vars['param']['orderby'] == 'asc'): ?>selected<?php endif; ?>>搜索次数升序</option>
</select>
<select name="status">
  <option value=""  <?php if ($this->_tpl_vars['param']['status'] == ''): ?>selected<?php endif; ?>>添加到词库</option>
  <option value="1" <?php if ($this->_tpl_vars['param']['status'] == 1): ?>selected<?php endif; ?>>未</option>
  <option value="2" <?php if ($this->_tpl_vars['param']['status'] == 2): ?>selected<?php endif; ?>>已</option>
</select>
搜索次数大于：<input type="text" name="searchcount" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['param']['searchcount']; ?>
"/>
关键词：<input type="text" name="searchword" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['param']['searchword']; ?>
"/>
<input type="submit" name="dosearch" value="查询"/>
</div>
</form>

<div class="content"><form name="myForm" id="myForm">
    <div style="float:left;width:600px;">
            <input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/> 全选/全不选
            <input type="button" value="批量加入词库" onclick="if (confirm('确认执行批量加入词库操作？')) {ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"batch-add-dict",)));?>','Gurl(\'refresh\')');}">
                <input type="button" value="批量删除" onclick="if (confirm('确认执行批量删除操作？')) {ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"batch-del-searchword",)));?>','Gurl(\'refresh\')');}"> 
       </div>
        <table cellpadding="0" cellspacing="0" border="0" class="table">
            <thead>
            <tr>
                <td>ID  </td>
                <td>搜索关键词</td>
                <td>搜索次数</td>
                <td>创建时间</td>
                <td>最后一次搜索时间</td>
                <td>状态</td>
                <td>操作</td>
            </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
        <tr id="row<?php echo $this->_tpl_vars['data']['id']; ?>
">
            <td><input type='checkbox' name="ids[]" value="<?php echo $this->_tpl_vars['data']['id']; ?>
_<?php echo $this->_tpl_vars['data']['searchword']; ?>
"> <?php echo $this->_tpl_vars['data']['id']; ?>
</td>
            <td><b><?php echo $this->_tpl_vars['data']['searchword']; ?>
</b></td>
            <td><?php echo $this->_tpl_vars['data']['searchcount']; ?>
</td>
            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['ctime'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %T") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %T")); ?>
</td>
            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['ltime'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %T") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %T")); ?>
</td>
            <td><?php if ($this->_tpl_vars['data']['status'] == 2): ?><font color="red" title="已经添加到词库">已</font><?php else: ?><font title="未添加到词库">未</font><?php endif; ?></td>
            <td><a href="javascript:fGo()" onclick="addToDict('<?php echo $this->_tpl_vars['data']['searchword']; ?>
',<?php echo $this->_tpl_vars['data']['id']; ?>
);">添加到词库</a> / <a href="javascript:fGo()" onclick="delCustomerSearchword(<?php echo $this->_tpl_vars['data']['id']; ?>
)">删除</a></td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        </tbody>
        </table>
        <div style="float:left;width:600px;">
            <input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/> 全选/全不选
            <input type="button" value="批量加入词库" onclick="if (confirm('确认执行批量加入词库操作？')) {ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"batch-add-dict",)));?>','Gurl(\'refresh\')');}">
            <input type="button" value="批量删除" onclick="if (confirm('确认执行批量删除操作？')) {ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"batch-del-searchword",)));?>','Gurl(\'refresh\')');}"> 
        </div>
    </form>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
<script type="text/javascript">
//添加到词库
function addToDict(val,id){
	if(val==''){alert('参数错误');}
	var id=parseInt(id);if(id<1){alert('参数错误');}
	new Request({
		url:'/admin/goods/add-customer-searchword-to-dict/val/'+val+'/id/'+id,
		onSuccess:function(msg){
			if(msg){alert(msg);}
			else{
				alert('添加成功');
				window.location.reload();
			}
		}
	}).send();
}
//删除
function delCustomerSearchword(id){
	var id=parseInt(id);if(id<1){alert('参数错误');}
	new Request({
		url:'/admin/goods/del-customer-searchword/id/'+id,
		onSuccess:function(msg){
			if(msg){alert(msg);}
			else{
				alert('删除成功');
				window.location.reload();
			}
		}
	}).send();
}
</script>