<?php /* Smarty version 2.6.19, created on 2014-11-12 16:45:47
         compiled from logistic/edit-logistic-area-price.tpl */ ?>
<form>
<input type='hidden' name='logistic_code' value='<?php echo $this->_tpl_vars['logisticCode']; ?>
'>
<input type='hidden' name='province_id' value='<?php echo $this->_tpl_vars['provinceID']; ?>
'>
<input type='hidden' name='city_id' value='<?php echo $this->_tpl_vars['cityID']; ?>
'>
<input type='hidden' name='area_id' value='<?php echo $this->_tpl_vars['areaID']; ?>
'>
<input type="hidden" name="submit" value="submit" />
<div class="title">编辑配送价格</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
    <tr>
        <td width=120>物流公司</td>
        <td>
		    <?php echo $this->_tpl_vars['logistic']['name']; ?>

        </td>
    </tr>
    <tr>
        <td>区域</td>
        <td>
		    <?php echo $this->_tpl_vars['logisticArea']['province']; ?>
 - <?php echo $this->_tpl_vars['logisticArea']['city']; ?>
 - <?php echo $this->_tpl_vars['logisticArea']['area']; ?>

        </td>
    </tr>
    <?php $_from = $this->_tpl_vars['logisticAreaPrice']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
        <tr>
            <td><?php echo $this->_tpl_vars['data']['min']; ?>
&lt;X&lt;=<?php echo $this->_tpl_vars['data']['max']; ?>
</td>
            <td>
                <input type='text' size="6" name='price[<?php echo $this->_tpl_vars['data']['logistic_area_price_id']; ?>
]' value='<?php echo $this->_tpl_vars['data']['price']; ?>
'>元
            </td>
        </tr>
    <?php endforeach; endif; unset($_from); ?>
    <tr>
        <td>&nbsp;</td>
        <td><input type="button" value="编辑" onclick="ajax_submit(this.form,'<?php echo $this -> callViewHelper('url', array(array('action'=>"edit-logistic-area-price",)));?>')" /></td>
    </tr>
    </table>
</div>
</form>