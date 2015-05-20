<?php /* Smarty version 2.6.19, created on 2014-10-23 09:45:46
         compiled from member/account.tpl */ ?>
<div style="width:600px; float:left; background-color:#FFFFFF; border:0px; overflow:auto; padding: 0 5px">
<form name="accountForm" id="accountForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>" onsubmit="return submitAccountForm(this, '<?php echo $this->_tpl_vars['accountType']; ?>
')" method="post" target="ifrmSubmit">
    <table cellpadding="0" cellspacing="0" border="0" width="590px" class="table_form">
    <tbody>
        <tr>
            <td width="20%" style="text-align:right; font-weight:bold">当前会员</td>
            <td width="80%"><?php echo $this->_tpl_vars['member']['user_name']; ?>
</td>
        </tr>
        <tr>
            <td width="20%" style="text-align:right; font-weight:bold">当前<?php echo $this->_tpl_vars['accountName']; ?>
</td>
            <td width="80%"><?php echo $this->_tpl_vars['accountValue']; ?>
</td>
        </tr>
        <tr>
            <td width="20%" style="text-align:right; font-weight:bold"><?php echo $this->_tpl_vars['accountName']; ?>
 *</td>
            <td width="80%">
                <input type="radio" name="accountType" value="1" checked /><span style="padding-left:5px;padding-right:5px">增加</span>
                <input type="radio" name="accountType" value="2" /><span style="padding-left:5px;padding-right:10px">减少</span>
                <input type="text" name="accountValue" maxlength="10" size="15" />
                <input type="hidden" id="accountTotalValue" name="accountTotalValue" value="<?php echo $this->_tpl_vars['accountValue']; ?>
" />
            </td>
        </tr>
        <tr>
            <td width="20%" style="text-align:right; font-weight:bold">账户变动原因 *</td>
            <td width="80%"><textarea style="width: 400px;height: 50px" name="note"></textarea></td>
        </tr>
    <tbody>
    </table>
    <div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /> <input type="button" onclick="closeWin()" value=" 关闭 " /></div>
</form>
</div>