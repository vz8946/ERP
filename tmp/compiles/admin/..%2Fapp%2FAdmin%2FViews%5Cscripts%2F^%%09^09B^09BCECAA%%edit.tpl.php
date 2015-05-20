<?php /* Smarty version 2.6.19, created on 2014-11-22 22:55:50
         compiled from warehouse/edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'warehouse/edit.tpl', 19, false),)), $this); ?>
<form name="myForm" id="myForm" action="/admin/warehouse/edit" method="post">
<div class="title">编辑仓库</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr> 
      <td width="15%"><strong>仓库编码</strong> * </td>
      <td><input type="text" name="warehouse_sn" id="warehouse_sn" size="20" value="<?php echo $this->_tpl_vars['info']['warehouse_sn']; ?>
" msg="请填写仓库编码" class="required" /></td>
    </tr>
    <tr> 
      <td width="15%"><strong>仓库名称</strong> * </td>
      <td><input type="text" name="warehouse_name" id="warehouse_name" size="20" value="<?php echo $this->_tpl_vars['info']['warehouse_name']; ?>
" msg="请填写仓库名称" class="required" /></td>
    </tr>
    <tr>
	<td>地址</td>
	<td>
	<select name="province_id" onchange="getArea(this)">
	    <option value="">请选择省</option>
		<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['address']['province_option'],'selected' => $this->_tpl_vars['info']['province_id']), $this);?>

	</select>
	<select name="city_id" onchange="getArea(this)">
	    <option value="">请选择市</option>
	    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['address']['city_option'],'selected' => $this->_tpl_vars['info']['city_id']), $this);?>

	</select>
	<select name="district_id">
	    <option value="">请选择区</option>
	    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['address']['district_option'],'selected' => $this->_tpl_vars['info']['district_id']), $this);?>

		
	</select>
	<input type="text" name="address" id="address" size="40" value="<?php echo $this->_tpl_vars['info']['address']; ?>
" msg="地址" class="required" />
	</td>
</tr>
</tbody>
</table>
<input type="hidden" name="warehouse_id" value="<?php echo $this->_tpl_vars['info']['warehouse_id']; ?>
" />
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>
<script>
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