<?php /* Smarty version 2.6.19, created on 2014-10-23 10:42:55
         compiled from order/returnorder.tpl */ ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm" >
<span style="float:left;line-height:18px;">
退货起止日期:<input type="text" name="fromdate" id="fromdate" size="11" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
" class="Wdate" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})"/></span>
<span style="float:left;line-height:18px;">结束日期<input type="text" name="todate" id="todate" size="11" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
" class="Wdate" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})" /></span>
&nbsp;&nbsp;&nbsp;&nbsp;
退货理由：<select name="reasons" id="reasons">
<option value="0">请选择</option>
<?php $_from = $this->_tpl_vars['reasons']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?><option value="<?php echo $this->_tpl_vars['v']['reason_id']; ?>
" <?php if ($this->_tpl_vars['param']['reasons'] == $this->_tpl_vars['v']['reason_id']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['v']['label']; ?>
</option><?php endforeach; endif; unset($_from); ?>
</select>
商品编码：<input type="text" name="goods_sn" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['goods_sn']; ?>
"/>
商品名称：<input type="text" name="goods_name" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['goods_name']; ?>
"/>
<input type="submit" name="dosearch" value="查询"/>
<hr />
<input type="text" id="re_reasons"  class="text"/> <input type="button" value="添加退货理由"  class="button" onclick="add_returnreasons()" />(*添加成功后，会自动添加你填写的退货理由。已经存在的理由请不要重复添加)
</form>
</div>
<?php endif; ?>
<div id="ajax_search">
<div class="title">产品退货汇总</div>    
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('/admin/operation/reason')">退货原因管理</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>订单批次号</td>
            <td>退货时间</td>
			<td>订单商品</td>
            <td>备注</td>
            <td>原因</td>
			
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['data']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['goods_id']; ?>
">
        <td><?php echo $this->_tpl_vars['data']['batch_sn']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['return_time']; ?>
</td>
        <td><?php $_from = $this->_tpl_vars['product']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['goods']):
?>
					<?php if ($this->_tpl_vars['goods']['batch_sn'] == $this->_tpl_vars['data']['batch_sn']): ?>
						<?php echo $this->_tpl_vars['goods']['goods_name']; ?>
 (<font color="#FF3333"><?php echo $this->_tpl_vars['goods']['goods_style']; ?>
</font>)  
                         <font color="#336633"><?php echo $this->_tpl_vars['goods']['product_sn']; ?>
 </font><br />
					<?php endif; ?>
				<?php endforeach; endif; unset($_from); ?></td>
		<td><?php echo $this->_tpl_vars['data']['details']; ?>
</td>
		<td><?php echo $this->_tpl_vars['data']['reasons']; ?>
</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>
 <div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
 <script>
 	function add_returnreasons(){
			var res=$('re_reasons');
			var sel=$('reasons');
			if(res.value!=''||res.value!=null||res.value.length>1){
				new Request({
					url:'/admin/order/reasons',
					data:'res='+res.value,
					onSuccess:function(datas){
						if(datas !='fail'){
							var resdata=JSON.decode(datas);
							var msg="添加成功";
							alertBox.init("msg='"+msg+"',MS=1250");
							sel.options.add(new Option(resdata[0].label,resdata[0].reason_id));
						}
					}
					
					}).send();
				}
		}
 
 </script>
</div>