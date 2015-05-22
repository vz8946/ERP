<?php /* Smarty version 2.6.19, created on 2014-10-30 11:50:49
         compiled from config/edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'config/edit.tpl', 17, false),)), $this); ?>
<form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>" method="post" enctype="multipart/form-data">
<div class="title"><?php echo $this->_tpl_vars['title']; ?>
</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'index',)));?>')">设置列表</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
    <tbody>
    <tr>
        <td width="10%">变量名称 * </td>
        <td width="40%"><input type="text" name="name" id="name" msg="请填写变量名称" class="required" size="30" maxlength="40" value="<?php echo $this->_tpl_vars['config']['name']; ?>
" <?php if ($this->_tpl_vars['action'] == 'edit'): ?>readonly<?php else: ?>onchange="ajax_check('<?php echo $this -> callViewHelper('url', array(array('action'=>'check',)));?>','name')"<?php endif; ?> /></td>
        <td width="10%">变量显示 * </td>
        <td width="40%"><input type="text" name="title" msg="请填写变量显示" class="required" size="30" maxlength="80" value="<?php echo $this->_tpl_vars['config']['title']; ?>
" /></td>
    </tr>
    <tr>
        <td width="10%">变量类型</td>
        <td width="40%"><select name="type" onchange="showOptions(this.value)"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['typeOptions'],'selected' => $this->_tpl_vars['config']['type']), $this);?>
</select></td>
        <td width="10%">隶属分类</td>
        <td width="40%"><select name="parent_id"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['catOptions'],'selected' => $this->_tpl_vars['config']['parent_id']), $this);?>
</select></td>
    </tr>
    <tr id="options" style="display:<?php if ($this->_tpl_vars['config']['type'] != 'radio' && $this->_tpl_vars['config']['type'] != 'checkbox' && $this->_tpl_vars['config']['type'] != 'select'): ?>none<?php endif; ?>">
        <td width="10%">变量选项</td>
        <td colspan="3">
            <div id="type_options">
            <?php if ($this->_tpl_vars['config']['type_options']): ?>
                <?php $_from = $this->_tpl_vars['config']['type_options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['options'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['options']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['name'] => $this->_tpl_vars['title']):
        $this->_foreach['options']['iteration']++;
?>
                    <?php if ($this->_foreach['options']['iteration'] == 1): ?>
                        <p><a onclick="addOption(this,'type_options')" href="javascript:fGo();">[+]</a> 值 <input type="text" name="type_key[]" size="20" value="<?php echo $this->_tpl_vars['name']; ?>
" /> 显示文字 <input type="text" name="type_value[]" size="20" value="<?php echo $this->_tpl_vars['title']; ?>
" /></p>
                    <?php else: ?>
                        <p><a onclick="removeOption(this,'type_options')" href="javascript:fGo();">[- ]</a> 值 <input type="text" name="type_key[]" size="20" value="<?php echo $this->_tpl_vars['name']; ?>
" /> 显示文字 <input type="text" name="type_value[]" size="20" value="<?php echo $this->_tpl_vars['title']; ?>
" /></p>
                    <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?>
            <?php else: ?>
                <p><a onclick="addOption(this,'type_options')" href="javascript:fGo();">[+]</a> 值 <input type="text" name="type_key[]" size="20" /> 显示文字 <input type="text" name="type_value[]" size="20" /></p>
            <?php endif; ?>
            </div>
        </td>
    </tr>
    <tr>
        <td width="10%">变量说明</td>
        <td colspan="3"><textarea name="notice" style="width:500px; height:50px"><?php echo $this->_tpl_vars['config']['notice']; ?>
</textarea></td>
    </tr>
    </tbody>
    </table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>
<script>
function showOptions(type)
{
    if (type == 'radio' || type == 'checkbox' || type == 'select') {
        $('options').style.display = '';
    } else {
        $('options').style.display = 'none';
    }
}

function addOption(obj, div)
{
	var p = document.createElement("p");
	p.innerHTML = obj.parentNode.innerHTML.replace(/(.*)(addOption)(.*)(\[)(\+)/i, "$1removeOption$3$4- ");
	$(div).appendChild(p);
}

function removeOption(obj, div)
{
    $(div).removeChild(obj.parentNode);
}
</script>