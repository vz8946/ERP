<?php /* Smarty version 2.6.19, created on 2014-10-23 09:55:59
         compiled from transport/confirm-list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'transport/confirm-list.tpl', 10, false),array('modifier', 'date_format', 'transport/confirm-list.tpl', 85, false),)), $this); ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm" method="get">
开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
"  class="Wdate" onClick="WdatePicker()"/>
结束日期：<input  type="text" name="todate" id="todate" size="15" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
"  class="Wdate"  onClick="WdatePicker()"/>
单据类型：
<select name="bill_type">
    <option value="">请选择</option>
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['billType'],'selected' => $this->_tpl_vars['param']['bill_type']), $this);?>

</select>
物流公司：
<select name="logistic_code">
    <option value="">请选择</option>
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['logisticList'],'selected' => $this->_tpl_vars['param']['logistic_code']), $this);?>

</select>
付款方式：<select name="is_cod"><option value="">请选择</option><option value="0" <?php if ($this->_tpl_vars['param']['is_cod'] == '0'): ?>selected<?php endif; ?>>非货到付款</option><option value="1" <?php if ($this->_tpl_vars['param']['is_cod'] == '1'): ?>selected<?php endif; ?>>货到付款</option></select>
是否开票：<select name="invoice"><option value="">请选择</option><option value="1" <?php if ($this->_tpl_vars['param']['invoice'] == '1'): ?>selected<?php endif; ?>>是</option><option value="0" <?php if ($this->_tpl_vars['param']['invoice'] == '0'): ?>selected<?php endif; ?>>否</option></select>
<div class="line">
店铺：
  <select name="shop_id">
    <option value="">请选择...</option>
    <?php $_from = $this->_tpl_vars['shopDatas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['shop']):
?>
      <?php if ($this->_tpl_vars['shop']['shop_type'] != 'tuan'): ?>
      <option value="<?php echo $this->_tpl_vars['shop']['shop_id']; ?>
" <?php if ($this->_tpl_vars['shop']['shop_id'] == $this->_tpl_vars['param']['shop_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['shop']['shop_name']; ?>
</option>
      <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
  </select>
收货人：<input type="text" name="consignee" size="10" maxLength="20" value="<?php echo $this->_tpl_vars['param']['consignee']; ?>
"/>
单据编号：<input type="text" name="bill_no" size="30" maxLength="50" value="<?php echo $this->_tpl_vars['param']['bill_no']; ?>
"/>
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('do'=>'search',)));?>','ajax_search')"/>
<input type="reset" name="reset" value="清除">
<input type="button" name="export" value="导出" onclick="this.form.method='post';this.form.target='_blank';this.form.action='<?php echo $this -> callViewHelper('url', array(array('controller'=>'transport','action'=>'export','act'=>"confirm-list",)));?>';this.form.submit()"">
</div>
<input type="button" name="dosearch2" value="所有被我锁定的入库单" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('do'=>'search','is_lock'=>yes,)));?>','ajax_search')"/>
<input type="button" name="dosearch3" value="所有没有锁定的入库单" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('do'=>'search','is_lock'=>no,)));?>','ajax_search')"/>
</div>
</form>
<?php endif; ?>
<div class="title">配送管理 -&gt; <?php echo $this->_tpl_vars['actions'][$this->_tpl_vars['action']]; ?>
</div>
<form name="myForm" id="myForm">
<div class="content">
<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/>
<input type="button" value="锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/1','G(\'<?php echo $this -> callViewHelper('url', array());?>\')')">
<input type="button" value="解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/0','G(\'<?php echo $this -> callViewHelper('url', array());?>\')')">
<input type="button" value="打印运输单" onclick="<?php if ($this->_tpl_vars['param']['bill_type'] == '' || $this->_tpl_vars['param']['logistic_code'] == '' || $this->_tpl_vars['param']['is_cod'] == ''): ?>alert('请先选择单据类型/物流公司/付款方式');return;<?php endif; ?>this.form.method='post';this.form.target='_blank';this.form.action='<?php echo $this -> callViewHelper('url', array(array('action'=>'prints',)));?>';this.form.submit()">
请输入初始物流单号(必须连号)：<input type="text" name="logistic_no" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['param']['logistic_no']; ?>
"/>
<input type="button" value="填充单号" onclick="<?php if ($this->_tpl_vars['param']['bill_type'] == '' || $this->_tpl_vars['param']['logistic_code'] == '' || $this->_tpl_vars['param']['is_cod'] == ''): ?>alert('请先选择单据类型/物流公司/付款方式');return;<?php endif; ?>ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"fill-no",)));?>','Gurl(\'<?php echo $this -> callViewHelper('url', array());?>\')')">
<input type="button" value="导入单号" onclick="openDiv('/admin/transport/import-no','ajax','批量导入单号',780,400);">
<input type="button" value="打印销售单" onclick="<?php if ($this->_tpl_vars['param']['bill_type'] == '' || $this->_tpl_vars['param']['is_cod'] == ''): ?>alert('请先选择单据类型/物流公司/付款方式');return;<?php endif; ?>this.form.method='post';this.form.target='_blank';this.form.action='<?php echo $this -> callViewHelper('url', array(array('controller'=>"logic-area-out-stock",'action'=>'prints',)));?>';this.form.submit()">
<input type="button" value="打印拣货单" onclick="this.form.method='post';this.form.target='_blank';this.form.action='<?php echo $this -> callViewHelper('url', array(array('controller'=>"logic-area-out-stock",'action'=>"prints-pickorders",)));?>';this.form.submit()">
<input type="button" value="确认运输单" onclick="<?php if ($this->_tpl_vars['param']['bill_type'] == '' || $this->_tpl_vars['param']['logistic_code'] == '' || $this->_tpl_vars['param']['is_cod'] == ''): ?>alert('请先选择单据类型/物流公司/付款方式');return;<?php endif; ?>ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'confirms',)));?>','Gurl(\'<?php echo $this -> callViewHelper('url', array());?>\')')">
<input type="button" value="返回派单" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"back-assign",)));?>','Gurl(\'<?php echo $this -> callViewHelper('url', array());?>\')')">
</div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="30">全选</td>
            <td>操作</td>
            <td>单据编号</td>
            <td>店铺</td>
            <td>收货人</td>
            <td>单据类型</td>
            <td>付款方式</td>
            <td>承运商</td>
            <td>运单号</td>
            <td>制单日期</td>
            <td>是否锁定</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['tid']; ?>
">
        <td><input type="checkbox" name="ids[<?php echo $this->_tpl_vars['data']['tid']; ?>
]" value="<?php echo $this->_tpl_vars['data']['tid']; ?>
"/><input type="hidden" name="bill_no[<?php echo $this->_tpl_vars['data']['tid']; ?>
]" value="<?php echo $this->_tpl_vars['data']['bill_no']; ?>
"/></td>
        <td>
			<input type="button" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>'confirm','id'=>$this->_tpl_vars['data']['tid'],)));?>','ajax','查看单据')" value="查看">
        </td>
        <td><?php echo $this->_tpl_vars['data']['bill_no_str']; ?>
<?php if ($this->_tpl_vars['data']['remark']): ?><br><b><?php echo $this->_tpl_vars['data']['remark']; ?>
</b><?php endif; ?></td>
        <td><?php echo $this->_tpl_vars['data']['shop_name']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['consignee']; ?>
</td>
        <td><?php echo $this->_tpl_vars['billType'][$this->_tpl_vars['data']['bill_type']]; ?>
<input type="hidden" name="bill_type[<?php echo $this->_tpl_vars['data']['tid']; ?>
]" value="<?php echo $this->_tpl_vars['data']['bill_type']; ?>
"><input type="hidden" name="info[<?php echo $this->_tpl_vars['data']['tid']; ?>
][bill_type]" value="<?php echo $this->_tpl_vars['data']['bill_type']; ?>
"><input type="hidden" name="info[<?php echo $this->_tpl_vars['data']['tid']; ?>
][bill_no]" value="<?php echo $this->_tpl_vars['data']['bill_no']; ?>
"></td>
        <td><?php if ($this->_tpl_vars['data']['is_cod']): ?>货到付款<?php else: ?>非货到付款<?php endif; ?></td>
        <td><?php echo $this->_tpl_vars['data']['logistic_name']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['logistic_no']; ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
</td>
        <td><?php if ($this->_tpl_vars['data']['lock_name']): ?>已被<font color="red"><?php echo $this->_tpl_vars['data']['lock_name']; ?>
</font><?php else: ?>未<?php endif; ?>锁定</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>

<?php if ($this->_tpl_vars['param']['bill_type'] == '' || $this->_tpl_vars['param']['logistic_code'] == '' || $this->_tpl_vars['param']['is_cod'] == ''): ?>
<p style="color:red">请选择单据类型、物流公司及付款方式<br><br></p>
<?php endif; ?>

<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/>
<input type="button" value="锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/1','G(\'<?php echo $this -> callViewHelper('url', array());?>\')')">
<input type="button" value="解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/0','Gurl(\'<?php echo $this -> callViewHelper('url', array());?>\')')">
<input type="button" value="打印运输单" onclick="<?php if ($this->_tpl_vars['param']['bill_type'] == '' || $this->_tpl_vars['param']['logistic_code'] == '' || $this->_tpl_vars['param']['is_cod'] == ''): ?>alert('请先选择单据类型/物流公司/付款方式');return;<?php endif; ?>this.form.method='post';this.form.target='_blank';this.form.action='<?php echo $this -> callViewHelper('url', array(array('action'=>'prints',)));?>';this.form.submit()">
请输入初始物流单号(必须连号)：<input type="text" name="logistic_no" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['param']['logistic_no']; ?>
"/>
<input type="button" value="填充单号" onclick="<?php if ($this->_tpl_vars['param']['bill_type'] == '' || $this->_tpl_vars['param']['logistic_code'] == '' || $this->_tpl_vars['param']['is_cod'] == ''): ?>alert('请先选择单据类型/物流公司/付款方式');return;<?php endif; ?>ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"fill-no",)));?>','Gurl(\'<?php echo $this -> callViewHelper('url', array());?>\')')">
<input type="button" value="导入单号" onclick="openDiv('/admin/transport/import-no','ajax','批量导入单号',780,400);">
<input type="button" value="打印销售单" onclick="<?php if ($this->_tpl_vars['param']['bill_type'] == '' || $this->_tpl_vars['param']['logistic_code'] == '' || $this->_tpl_vars['param']['is_cod'] == ''): ?>alert('请先选择单据类型/物流公司/付款方式');return;<?php endif; ?>this.form.method='post';this.form.target='_blank';this.form.action='<?php echo $this -> callViewHelper('url', array(array('controller'=>"logic-area-out-stock",'action'=>'prints',)));?>';this.form.submit()">
<input type="button" value="打印拣货单" onclick="this.form.method='post';this.form.target='_blank';this.form.action='<?php echo $this -> callViewHelper('url', array(array('controller'=>"logic-area-out-stock",'action'=>"prints-pickorders",)));?>';this.form.submit()">
<input type="button" value="确认运输单" onclick="<?php if ($this->_tpl_vars['param']['bill_type'] == '' || $this->_tpl_vars['param']['logistic_code'] == '' || $this->_tpl_vars['param']['is_cod'] == ''): ?>alert('请先选择单据类型/物流公司/付款方式');return;<?php endif; ?>ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'confirms',)));?>','Gurl(\'<?php echo $this -> callViewHelper('url', array());?>\')')">
<input type="button" value="返回派单" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"back-assign",)));?>','Gurl(\'<?php echo $this -> callViewHelper('url', array());?>\')')">
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</form>