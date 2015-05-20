<?php /* Smarty version 2.6.19, created on 2014-10-23 09:58:26
         compiled from msg/goodsbuylog.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'msg/goodsbuylog.tpl', 5, false),)), $this); ?>
<form name="myForm1" id="myForm1">
<div class="search">
    该商品评论个数：<?php echo $this->_tpl_vars['commentcnt']; ?>
，购买总记录：<input type="text" size="5" name="buy_total" value="<?php echo $this->_tpl_vars['info']['buy_total']; ?>
"> 
    <font color="red">为增强信息可信性，请参考评论个数填写。</font><br>
    最后更新时间：<?php echo ((is_array($_tmp=$this->_tpl_vars['info']['update_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>

</div>


    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>序号</td>
            <td>用户名称</td>
            <td>会员等级</td>
			<td>购买时间</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['item']['msg_id']; ?>
">
        <td><?php echo $this->_tpl_vars['key']+1; ?>
</td>
        <td><input type="text" size="30" name="buy_log[<?php echo $this->_tpl_vars['key']; ?>
][]" value="<?php echo $this->_tpl_vars['item']['user_name']; ?>
"></td>
        <td><input type="text" size="15" name="buy_log[<?php echo $this->_tpl_vars['key']; ?>
][]" value="<?php echo $this->_tpl_vars['item']['rank_name']; ?>
"></td>
        <td><input type="text" size="18" name="buy_log[<?php echo $this->_tpl_vars['key']; ?>
][]" value="<?php echo $this->_tpl_vars['item']['add_time']; ?>
"></td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
<div class="submit"><input type="button" name="dosubmit1" id="dosubmit1" value="提交" onclick="dosubmit()"/>
<input type="reset" name="reset" value="重置" /></div>
</form>

<script language="JavaScript">
function dosubmit()
{
    if(confirm('确认内容无误吗？')){
		$('dosubmit1').value = '处理中';
		$('dosubmit1').disabled = true;
		ajax_submit($('myForm1'),'<?php echo $this -> callViewHelper('url', array());?>');
	}
}
</script>