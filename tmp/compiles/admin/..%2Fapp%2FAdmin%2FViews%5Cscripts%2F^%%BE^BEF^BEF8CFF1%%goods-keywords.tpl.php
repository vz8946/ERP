<?php /* Smarty version 2.6.19, created on 2014-11-05 08:49:42
         compiled from goods/goods-keywords.tpl */ ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm" action="/admin/goods/goods-keywords/">
<div class="search">

<span style="float:left;line-height:18px;">添加开始日期：</span>
<span style="float:left;width:150px;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="15" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
"  class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">添加结束日期：</span>
<span>
<input  type="text" name="todate" id="todate" size="15" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
"  class="Wdate"  onClick="WdatePicker()"/>
<input type="button" value="清除日期" onclick="$('fromdate').value='';$('todate').value=''"/></span>

<?php if ($this->_tpl_vars['angle_id'] == 1): ?> 系统分类: <?php else: ?>展示分类:  <?php endif; ?><?php echo $this->_tpl_vars['catSelect']; ?>

上下架：<select name="onsale"><option value="" selected>请选择</option><option value="on" <?php if ($this->_tpl_vars['param']['onsale'] == 'on'): ?>selected<?php endif; ?>>上架</option><option value="off" <?php if ($this->_tpl_vars['param']['onsale'] == 'off'): ?>selected<?php endif; ?>>下架</option></select>
&nbsp;&nbsp;
<select name="orderby" onchange="searchForm.submit()">
  <option value="">排序方式</option>
  <option value="goods_add_time" <?php if ($this->_tpl_vars['param']['orderby'] == 'goods_add_time'): ?>selected<?php endif; ?>>添加时间(升序)</option>
  <option value="price" <?php if ($this->_tpl_vars['param']['orderby'] == 'price'): ?>selected<?php endif; ?>>本店价(升序)</option>
  <option value="price desc" <?php if ($this->_tpl_vars['param']['orderby'] == 'price desc'): ?>selected<?php endif; ?>>本店价(降序)</option>
</select>
<br>

商品名称：<input type="text" name="goods_name" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['param']['goods_name']; ?>
"/>
编码：<input type="text" name="goods_sn" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['param']['goods_sn']; ?>
"/>
品牌：<input type="text" name="brand_name" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['param']['brand_name']; ?>
"/>
本店价：<input type="text" name="fromprice" size="5" maxLength="6" value="<?php echo $this->_tpl_vars['param']['fromprice']; ?>
"/>
- <input type="text" name="toprice" size="5" maxLength="6" value="<?php echo $this->_tpl_vars['param']['toprice']; ?>
"/>

<input type="submit" name="dosearch" id="dosearch" value="查询"/>
</div>
</form>
<div class="title">商品关键字管理<!--&nbsp;&nbsp;（<a href="/admin/goods/genkeywords">生成keywords</a>）-->（<a style="cursor:pointer;" onclick="javascript: G('/admin/goods/dict');" href="javascript:fGo()" >词库管理</a>）</div>

   
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>排序</td>
            <td>ID</td>
            <td>商品编码</td>
			<td>商品分类</td>
            <td>商品名称</td>
            <td>状态</td>
            <td>关键字</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['goods_id']; ?>
">
        <td><input type="text" name="update" size="2" value="<?php echo $this->_tpl_vars['data']['goods_sort']; ?>
" style="text-align:center;" onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate',)));?>',<?php echo $this->_tpl_vars['data']['goods_id']; ?>
,'goods_sort',this.value)"></td>
        <td><?php echo $this->_tpl_vars['data']['goods_id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['goods_sn']; ?>
</td>
		<td><?php echo $this->_tpl_vars['data']['cat_name']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['goods_name']; ?>
 (<font color="#FF3333"><?php echo $this->_tpl_vars['data']['goods_style']; ?>
</font>)</td>
        <td><?php echo $this->_tpl_vars['data']['goods_status']; ?>
</td>
        <td>
			<a href="javascript:fGo()" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('controller'=>'goods','action'=>'viewkeywords','goods_id'=>$this->_tpl_vars['data']['goods_id'],)));?>','ajax','<?php echo $this->_tpl_vars['data']['goods_name']; ?>
|关键字',550,300)">修改</a>
		</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>