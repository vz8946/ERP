<?php /* Smarty version 2.6.19, created on 2014-11-23 10:28:07
         compiled from warehouse/add.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'warehouse/add.tpl', 19, false),)), $this); ?>
<form name="myForm" id="myForm" action="/admin/warehouse/add" method="post">
<div class="title">添加仓库</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr> 
      <td width="15%"><strong>仓库编码</strong> * </td>
      <td><input type="text" name="warehouse_sn" id="warehouse_sn" size="20" value="" msg="请填写仓库编码" class="required" /></td>
    </tr>
    <tr> 
      <td width="15%"><strong>仓库名称</strong> * </td>
      <td><input type="text" name="warehouse_name" id="warehouse_name" size="20" value="" msg="请填写仓库名称" class="required" /></td>
    </tr>
    <tr>
	<td>地址</td>
	<td>
	<select name="province_id" onchange="getArea(this)">
	    <option value="">请选择省</option>
		<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['search_option']['province']), $this);?>

	</select>
	<select name="city_id" onchange="getArea(this)">
	    <option value="">请选择市</option>
		
	</select>
	<select name="district_id">
	    <option value="">请选择区</option>
		
	</select>
	<input type="text" name="address" id="address" size="40" value="<?php echo $this->_tpl_vars['data']['shop_name']; ?>
" msg="地址" class="required" />
	</td>
</tr>
</tbody>
</table>

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