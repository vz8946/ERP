<?php /* Smarty version 2.6.19, created on 2014-10-23 15:00:31
         compiled from goods/price-list.tpl */ ?>
<?php if ($this->_tpl_vars['message']): ?>
<script type="text/javascript">
alert('<?php echo $this->_tpl_vars['message']; ?>
');
window.location='/admin/goods/price-list';
</script>
<?php endif; ?>
<form name="searchForm" id="searchForm" action="/admin/goods/price-list/">
<div class="search">
<?php echo $this->_tpl_vars['catSelect']; ?>

上下架：<select name="onsale"><option value="" selected>请选择</option><option value="on" <?php if ($this->_tpl_vars['param']['onsale'] == 'on'): ?>selected<?php endif; ?>>上架</option><option value="off" <?php if ($this->_tpl_vars['param']['onsale'] == 'off'): ?>selected<?php endif; ?>>下架</option></select>
商品名称：<input type="text" name="goods_name" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['goods_name']; ?>
"/> 编码：<input type="text" name="goods_sn" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['goods_sn']; ?>
"/>
&nbsp;&nbsp;
<select name="orderby" onchange="searchForm.submit()">
  <option value="">排序方式</option>
  <option value="price" <?php if ($this->_tpl_vars['param']['orderby'] == 'price'): ?>selected<?php endif; ?>>本店价(升序)</option>
  <option value="price desc" <?php if ($this->_tpl_vars['param']['orderby'] == 'price desc'): ?>selected<?php endif; ?>>本店价(降序)</option>
  <option value="staff_price" <?php if ($this->_tpl_vars['param']['orderby'] == 'staff_price'): ?>selected<?php endif; ?>>员工价(升序)</option>
  <option value="staff_price desc" <?php if ($this->_tpl_vars['param']['orderby'] == 'staff_price desc'): ?>selected<?php endif; ?>>员工价(降序)</option>
</select>
<br>
本店价：<input type="text" name="fromprice" size="5" maxLength="6" value="<?php echo $this->_tpl_vars['param']['fromprice']; ?>
"/> - <input type="text" name="toprice" size="5" maxLength="6" value="<?php echo $this->_tpl_vars['param']['toprice']; ?>
"/>
市场价：<input type="text" name="fromprice_market" size="5" maxLength="6" value="<?php echo $this->_tpl_vars['param']['fromprice_market']; ?>
"/> - <input type="text" name="toprice_market" size="5" maxLength="6" value="<?php echo $this->_tpl_vars['param']['toprice_market']; ?>
"/>
员工价：<input type="text" name="fromprice_staff" size="5" maxLength="6" value="<?php echo $this->_tpl_vars['param']['fromprice_staff']; ?>
"/> - <input type="text" name="toprice_staff" size="5" maxLength="6" value="<?php echo $this->_tpl_vars['param']['toprice_staff']; ?>
"/>
限价：<input type="checkbox" name="price_limit" value="1" <?php if ($this->_tpl_vars['param']['price_limit'] == '1'): ?>checked='true'<?php endif; ?>/>
<input type="submit" name="dosearch" id="dosearch" value="查询"/>
</div>

</form>
<div class="title">商品管理</div>
<div class="content">
    <div class="sub_title">
        
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>商品编码</td>
            <td width="280px">商品名称</td>
			<?php if ($this->_tpl_vars['viewcost'] == '1'): ?><td>成本价</td><?php endif; ?>
			<?php if ($this->_tpl_vars['viewcost'] == '1'): ?><td>税后成本价</td><?php endif; ?>
			<?php if ($this->_tpl_vars['viewcost'] == '1'): ?><td>成本价税率</td><?php endif; ?>
            <td>市场价</td>
            <td>本店价</td>
            <td>保护价</td>
            <!--<td>员工价</td>-->
            <td>状态</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['goods_id']; ?>
">
        <td><?php echo $this->_tpl_vars['data']['goods_id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['goods_sn']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['goods_name']; ?>
 (<font color="#FF3333"><?php echo $this->_tpl_vars['data']['goods_style']; ?>
</font>)</td>
        <?php if ($this->_tpl_vars['viewcost'] == '1'): ?><td><?php echo $this->_tpl_vars['data']['cost']; ?>
</td><?php endif; ?>
        <?php if ($this->_tpl_vars['viewcost'] == '1'): ?><td><?php echo $this->_tpl_vars['data']['cost_tax']; ?>
</td><?php endif; ?>
        <?php if ($this->_tpl_vars['viewcost'] == '1'): ?><td><?php echo $this->_tpl_vars['data']['invoice_tax_rate']; ?>
%</td><?php endif; ?>
        <td><?php echo $this->_tpl_vars['data']['market_price']; ?>
</td>
        <td <?php if ($this->_tpl_vars['data']['price'] < $this->_tpl_vars['data']['price_limit']): ?> style="color:#ff0000;"<?php endif; ?>><?php echo $this->_tpl_vars['data']['price']; ?>
</td>
        <td><?php if ($this->_tpl_vars['data']['price_limit'] == 0): ?>无限价<?php else: ?><?php echo $this->_tpl_vars['data']['price_limit']; ?>
<?php endif; ?></td>
        <!--<td><?php echo $this->_tpl_vars['data']['staff_price']; ?>
</td>-->
        <td><?php echo $this->_tpl_vars['data']['goods_status']; ?>
</td>
        <td>
			<a href="javascript:fGo()" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>'price','id'=>$this->_tpl_vars['data']['goods_id'],)));?>','ajax','<?php echo $this->_tpl_vars['data']['goods_name']; ?>
 <?php echo $this->_tpl_vars['data']['goods_color']; ?>
',750,400)">管理价格</a>
        </td>
        </td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>