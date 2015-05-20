<?php /* Smarty version 2.6.19, created on 2014-11-12 16:45:51
         compiled from logistic/list-area.tpl */ ?>
<div class="search">
<form method="get" action="<?php echo $this -> callViewHelper('url', array(array('action'=>"list-area",)));?>">
    <table>
        <tr>
            <td width=50>区号</td>
            <td width=200><input type="input" name="code" value="<?php echo $this->_tpl_vars['code']; ?>
"></td>
            <td width=50>邮编</td>
            <td width=200><input type="input" name="zip" value="<?php echo $this->_tpl_vars['zip']; ?>
"></td>
            <td width=50>地区</td>
            <td>
                <select name="province_id" onchange="getArea(this)">
                    <option value="">请选择省份...</option>
                    <?php $_from = $this->_tpl_vars['province']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['p']):
?>
                    <option value="<?php echo $this->_tpl_vars['p']['area_id']; ?>
" <?php if ($this->_tpl_vars['p']['area_id'] == $this->_tpl_vars['provinceID']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['p']['area_name']; ?>
</option>
                    <?php endforeach; endif; unset($_from); ?>            
                </select>
                <select name="city_id" onchange="getArea(this)">
                    <option value="">请选择城市...</option>
                   <?php if ($this->_tpl_vars['province']): ?>
                    <?php $_from = $this->_tpl_vars['city']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
?>
                    <option value="<?php echo $this->_tpl_vars['c']['area_id']; ?>
" <?php if ($this->_tpl_vars['c']['area_id'] == $this->_tpl_vars['cityID']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['c']['area_name']; ?>
</option>
                    <?php endforeach; endif; unset($_from); ?>            
                    <?php endif; ?>
                </select>
                <select name="area_id">
                    <option value="">请选择地区...</option>
                    <?php if ($this->_tpl_vars['city']): ?>
                    <?php $_from = $this->_tpl_vars['area']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['a']):
?>
                    <option value="<?php echo $this->_tpl_vars['a']['area_id']; ?>
" <?php if ($this->_tpl_vars['a']['area_id'] == $this->_tpl_vars['areaID']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['a']['area_name']; ?>
</option>
                    <?php endforeach; endif; unset($_from); ?>            
                    <?php endif; ?>
                </select>
            </td>
            <td>
                <input type="submit" value="搜索"/>
            </td>
        </tr>
    </table>
</form>
</div>

<div id="ajax_search">
<div class="title">物流管理 -&gt; 配送地区管理</div>
<div class="content">
<form name="upForm" id="upForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>"import-logistic-area",)));?>" method="post" enctype="multipart/form-data"  target="ifrmSubmit">
    <input type="hidden" name="submit" value="submit" />
    <input type="file" name="logistic" />
    <input type="submit" value="导入邮编和区号">
</form>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
    <tr>
        <td width=100>国家</td>
        <td width=150>省份</td>
        <td width=150>城市</td>
        <td width=150>区县</td>
        <td width=100>区号</td>
        <td>邮政编码</td>
    </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['areaList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr>
        <td><?php echo $this->_tpl_vars['data']['country']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['province']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['city']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['area']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['code']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['zip']; ?>
</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
</table>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
<div>
    <input type="button" value="管理" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"list-manage-area",)));?>')" />
</div>

</div>

<script>
function getArea(obj)
{
    var areaID = obj.value;
    var select = obj.getNext();
    var url = filterUrl('<?php echo $this -> callViewHelper('url', array(array('action'=>"list-area-by-json",)));?>','area_id');
	new Request({
            url: url + '/area_id/' + areaID,
			onSuccess: function(json){
                select.options.length = 1;
                if (!obj.getPrevious()) {
                    select.getNext().options.length = 1;
                }
                if (json != '') {
                    data = JSON.decode(json);
                    $each(data, function(item, index){
                        var option = document.createElement("OPTION");
                        option.value = item.area_id;
                        option.text  = item.area_name;
                        select.options.add(option);
                    });
                }
            },
			onFailure: function(){
				alert('error');
			}
		}).send();
}
</script>