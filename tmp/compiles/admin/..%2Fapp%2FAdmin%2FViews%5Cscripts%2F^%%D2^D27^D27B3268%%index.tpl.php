<?php /* Smarty version 2.6.19, created on 2014-10-23 09:44:09
         compiled from config/index.tpl */ ?>
<div class="title">商店参数设置</div>
<form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>" method="post" enctype="multipart/form-data" />
<div class="title" style="height:25px;">
	<ul id="show_tab">
	<?php $_from = $this->_tpl_vars['cate']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['cate'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['cate']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['title']):
        $this->_foreach['cate']['iteration']++;
?>
	   <li onclick="show_tab(<?php echo $this->_tpl_vars['key']; ?>
)" id="show_tab_nav_<?php echo $this->_tpl_vars['key']; ?>
" class="<?php if ($this->_tpl_vars['key'] == 1): ?>bg_nav_current<?php else: ?>bg_nav<?php endif; ?>"><?php echo $this->_tpl_vars['title']; ?>
</li>
    <?php endforeach; endif; unset($_from); ?>
	</ul>
</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'add',)));?>')">添加设置</a> ]
    </div>
    <?php $_from = $this->_tpl_vars['cate']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['cate'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['cate']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['title']):
        $this->_foreach['cate']['iteration']++;
?>
    <div id="show_tab_page_<?php echo $this->_tpl_vars['key']; ?>
" style="display:<?php if ($this->_tpl_vars['key'] == 1): ?>block<?php else: ?>none<?php endif; ?>">
        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
        <tbody>
        <?php $_from = $this->_tpl_vars['optionFrom'][$this->_tpl_vars['key']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['optionForm'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['optionForm']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['option']):
        $this->_foreach['optionForm']['iteration']++;
?>
            <tr id="ajax_list<?php echo $this->_tpl_vars['id']; ?>
">
                <td width="30%" class="desc">
                    <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'edit','id'=>$this->_tpl_vars['id'],)));?>')" title="编辑设置"><img src="/images/admin/edit.png" border="0" /></a> 
                    <a href="javascript:fGo()" onclick="reallydelete('<?php echo $this -> callViewHelper('url', array(array('action'=>'delete',)));?>','<?php echo $this->_tpl_vars['id']; ?>
')" title="删除设置"><img src="/images/admin/delete.png" /></a> 
                    <?php if ($this->_tpl_vars['option']['notice']): ?>
                        <a href="javascript:fGo()" onclick="showNotice('notice_<?php echo $this->_tpl_vars['id']; ?>
')" title="点击此处查看提示信息"><img src="images/notice.gif" width="16" height="16" border="0" alt="点击此处查看提示信息" /></a> 
                    <?php endif; ?>
                    <?php echo $this->_tpl_vars['option']['title']; ?>
</td>
                <td width="70%">
                    <?php echo $this->_tpl_vars['option']['option']; ?>

                    <?php if ($this->_tpl_vars['option']['notice']): ?>
                        <br /><span class="notice" id="notice_<?php echo $this->_tpl_vars['id']; ?>
"><?php echo $this->_tpl_vars['option']['notice']; ?>
</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; endif; unset($_from); ?>
        </tbody>
        </table>
    </div>
    <?php endforeach; endif; unset($_from); ?>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>
<script>
function showNotice(id)
{
    var obj = $(id);

    if (obj) {
        if (obj.style.display != "block") {
            obj.style.display = "block";
        } else {
            obj.style.display = "none";
        }
    }
}
</script>