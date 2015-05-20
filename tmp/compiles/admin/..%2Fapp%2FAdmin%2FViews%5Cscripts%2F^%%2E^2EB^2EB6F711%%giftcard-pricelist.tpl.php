<?php /* Smarty version 2.6.19, created on 2014-10-28 14:18:47
         compiled from product/giftcard-pricelist.tpl */ ?>
<form name="searchForm" id="searchForm">
<div class="search">
产品编码：<input type="text" name="product_sn" size="15" maxLength="50" value="<?php echo $this->_tpl_vars['param']['product_sn']; ?>
"/>
产品名称：<input type="text" name="goods_name" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['goods_name']; ?>
"/>
<input type="submit" name="dosearch" id="dosearch" value="查询"/>
</div>
</form>
<form name="myForm" id="myForm">
<div class="title">产品管理 -&gt; 礼品卡金额管理       </div>
<div class="content">
   <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td>产品ID</td>
            <td>产品编码</td>
            <td>产品名称</td>
			<td>金额</td>
			<td>操作人</td>
			<td>添加时间</td>
            <td>修改时间</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['infos']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['info']):
?>
    <tr id="<?php echo $this->_tpl_vars['info']['product_id']; ?>
">
        <td><?php echo $this->_tpl_vars['info']['product_id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['info']['product_sn']; ?>
</td>
        <td><?php echo $this->_tpl_vars['info']['product_name']; ?>
</td>
		<td><input type="text" name="amount"  size="8" value="<?php echo $this->_tpl_vars['info']['amount']; ?>
" style="text-align:center;" onchange="changePrice('<?php echo $this->_tpl_vars['info']['product_id']; ?>
', this, '<?php echo $this->_tpl_vars['info']['amount']; ?>
')"></td>
        <td><?php echo $this->_tpl_vars['info']['admin_name']; ?>
</td>
        <td><?php echo $this->_tpl_vars['info']['created_ts']; ?>
</td>
        <td><?php echo $this->_tpl_vars['info']['update_ts']; ?>
</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</form>
<script>
    function changePrice(product_id,obj, origin_amount)
    {
        if (!confirm("确认更改价格吗?")) {
            return false;
        }
        if (parseInt(product_id) < 1) {
            alert('产品ID不正确');
            return false;
        }

        var amount = obj.value;

        if (isNaN(amount)) {
            alert('金额不正确');
            obj.value = origin_amount;
            obj.focus();
            return false;
        }

        if (Math.ceil(amount) <= 0) {
            alert('金额不能小于0');
            obj.value = origin_amount;
            return false;
        }
        new Request({
		url:'/admin/product/change-ajax-giftproduct/product_id/'+product_id+'/amount/'+ amount,
		onSuccess:function(data){
			data = JSON.decode(data);
            if (data.success == 'false') {
                alert(data.message);
                return false;
            } else {
                data = data.data;
                obj.value = data.amount;
                var tr = document.getElementById(product_id);
                tr.cells[4].innerHTML = data.admin_name;
                tr.cells[5].innerHTML = data.created_ts;
                tr.cells[6].innerHTML = data.update_ts;
            }
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
    }
</script>