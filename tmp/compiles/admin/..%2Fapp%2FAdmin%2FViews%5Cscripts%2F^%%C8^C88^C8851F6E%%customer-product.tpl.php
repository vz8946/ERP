<?php /* Smarty version 2.6.19, created on 2014-10-30 11:49:24
         compiled from customer/customer-product.tpl */ ?>
<div class="title">客户订购产品详情&nbsp;&nbsp;&nbsp;
<br /><br />
<table cellpadding="0" cellspacing="0" border="0" class="table">
    <tr>
        <td width="100">客户ID：</td>
        <td><?php echo $this->_tpl_vars['customer_info']['customer_id']; ?>
</td>
    </tr>
    <tr>
        <td width="100">客户名：</td>
        <td><?php echo $this->_tpl_vars['customer_info']['real_name']; ?>
</td>
    </tr>
    <tr>
        <td width="100">电话：</td>
        <td><?php echo $this->_tpl_vars['customer_info']['telphone']; ?>
</td>
    </tr>
    <tr>
        <td width="100">手机：</td>
        <td><?php echo $this->_tpl_vars['customer_info']['mobile']; ?>
</td>
    </tr>
</table>
<br /><br />
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>产品ID</td>
            <td>产品编码</td>
        	<td>产品名</td>
            <td>规格</td>
            <td>数量</td>
            <td>单号</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['infos']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['info']):
?>
    <tr>
        <td><?php echo $this->_tpl_vars['info']['product_id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['info']['product_sn']; ?>
</td>
        <td><?php echo $this->_tpl_vars['info']['goods_name']; ?>
</td>
        <td><?php echo $this->_tpl_vars['info']['goods_style']; ?>
</td>
        <td><?php echo $this->_tpl_vars['info']['number']; ?>
</td>
        <td><?php echo $this->_tpl_vars['info']['batch_sn']; ?>
</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>