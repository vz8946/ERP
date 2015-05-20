<?php /* Smarty version 2.6.19, created on 2014-10-23 10:45:20
         compiled from cod/list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'cod/list.tpl', 9, false),)), $this); ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm" method="get">
<div class="search">
开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
"  class="Wdate" onClick="WdatePicker()"/>
结束日期：<input  type="text" name="todate" id="todate" size="15" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
"  class="Wdate"  onClick="WdatePicker()"/>
物流公司：
<select name="logistic_code">
    <option value="">请选择</option>
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['logisticList'],'selected' => $this->_tpl_vars['param']['logistic_code']), $this);?>

</select>
省份：<select name="province_id" onchange="getArea(this)">
    <option value="">请选择省</option>
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['province']), $this);?>

</select>
城市：<select name="city_id" onchange="getArea(this)">
    <option value="">请选择市</option>
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['city']), $this);?>

</select>
区县：<select name="area_id">
    <option value="">请选择区</option>
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['area']), $this);?>

</select>
<br>
单据类型：
<select name="bill_type">
    <option value="">请选择</option>
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['billType'],'selected' => $this->_tpl_vars['param']['bill_type']), $this);?>

</select>
配送状态：
<select name="logistic_status">
    <option value="">请选择</option>
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['logisticStatus'],'selected' => $this->_tpl_vars['param']['logistic_status']), $this);?>

</select>
<select name="is_check">
<option value="">选择单据状态</option>
<option value="0" <?php if ($this->_tpl_vars['param']['is_check'] == '0'): ?>selected<?php endif; ?>>未审核</option>
<option value="1" <?php if ($this->_tpl_vars['param']['is_check'] == '1'): ?>selected<?php endif; ?>>已审核</option>
<option value="2" <?php if ($this->_tpl_vars['param']['is_check'] == '2'): ?>selected<?php endif; ?>>已拒绝</option>
</select>


运单号码：<input type="text" name="logistic_no" size="15" maxLength="50" value="<?php echo $this->_tpl_vars['param']['logistic_no']; ?>
"/>
收货人：<input type="text" name="consignee" size="10" maxLength="20" value="<?php echo $this->_tpl_vars['param']['consignee']; ?>
"/>
单据编号：<input type="text" name="bill_no" size="30" maxLength="50" value="<?php echo $this->_tpl_vars['param']['bill_no']; ?>
"/>
<input type="submit" name="dosearch" value="查询"/>
<input type="reset" name="reset" value="清除">
<br>
<input type="button" name="dosearch2" value="所有被我锁定的入库单" onclick="G('<?php echo $this -> callViewHelper('url', array(array('is_lock'=>yes,)));?>'+location.search)"/>
<input type="button" name="dosearch3" value="所有没有锁定的入库单" onclick="G('<?php echo $this -> callViewHelper('url', array(array('is_lock'=>no,)));?>'+location.search)"/>
</div>
</form>
<div class="title">代收货款变更管理 -&gt; <?php echo $this->_tpl_vars['actions'][$this->_tpl_vars['action']]; ?>
</div>
<form name="myForm" id="myForm">

<div class="content">

<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/0','Gurl(\'refresh\',\'ajax_search\')')">
</div>

    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="30">全选</td>
            <td>操作</td>
            <td>单据编号</td>
            <td>单据类型</td>
            <td>物流公司</td>
            <td>原订单金额</td>
            <td>变更后金额</td>
            <td>审核状态</td>
            <td>运单号</td>
            <td>配送状态</td>
            <td>是否锁定</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['cid']; ?>
">
        <td><input type="checkbox" name="ids[]" value="<?php echo $this->_tpl_vars['data']['cid']; ?>
"/></td>
        <td>
			<input type="button" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['operates'][$this->_tpl_vars['action']],'id'=>$this->_tpl_vars['data']['cid'],)));?>','ajax','查看单据')" value="查看">
        </td>
        <td><?php echo $this->_tpl_vars['data']['bill_no']; ?>
</td>
        <td><?php echo $this->_tpl_vars['billType'][$this->_tpl_vars['data']['bill_type']]; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['logistic_name']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['amount']; ?>
</td>
        <td><?php if ($this->_tpl_vars['data']['is_check'] == 1): ?><?php echo $this->_tpl_vars['data']['amount']+$this->_tpl_vars['data']['change_amount']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['amount']+$this->_tpl_vars['data']['tmp_amount']; ?>
<?php endif; ?></td>
        <td><?php if ($this->_tpl_vars['data']['is_check'] == 1): ?>已审核<?php elseif ($this->_tpl_vars['data']['is_check'] == 2): ?>已拒绝<?php else: ?>未审核<?php endif; ?></td>
        <td><?php echo $this->_tpl_vars['data']['logistic_no']; ?>
</td>
        <td><?php echo $this->_tpl_vars['logisticStatus'][$this->_tpl_vars['data']['logistic_status']]; ?>
</td>
        <td><?php if ($this->_tpl_vars['data']['lock_name']): ?>已被<font color="red"><?php echo $this->_tpl_vars['data']['lock_name']; ?>
</font><?php else: ?>未<?php endif; ?>锁定</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>

<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/> <input type="button" value="锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/1','Gurl(\'refresh\',\'ajax_search\')')"> <input type="button" value="解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/val/0','Gurl(\'refresh\',\'ajax_search\')')">
</div>

<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</form>
<script language="JavaScript">
function getArea(id)
{
    var value = id.value;
    var select = $(id).getNext();
    new Request({
        url: '/admin/member/area/id/' + value,
        onRequest: loading,
        onSuccess:function(data){
            select.options.length = 1;
	        if (data != '') {
	            data = JSON.decode(data);
	            $each(data, function(item, index){
	                var option = document.createElement("OPTION");
                    option.value = index;
                    option.text  = item;
                    select.options.add(option);
	            });
	        }
            loadSucess();
        }
    }).send();
}
</script>