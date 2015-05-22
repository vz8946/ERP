<?php /* Smarty version 2.6.19, created on 2014-10-22 23:10:36
         compiled from logistic/list-area-strategy.tpl */ ?>
<div class="search">
<form method="get" action="<?php echo $this -> callViewHelper('url', array(array('action'=>"list-area-strategy",)));?>">
    <table>
        <tr>
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
<div class="title">物流管理 -&gt; 配送策略管理</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
    <tr>
        <td width=150>省份</td>
        <td width=150>城市</td>
        <td width=150>区县</td>
        <td width=150>物流公司</td>
        <td width=150>优先级</td>
        <td width=150>指定</td>
        <td width=150>开启</td>
    </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['strategy']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr>
        <td><?php echo $this->_tpl_vars['data']['province']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['city']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['area']; ?>
</td>
        <td>
            <?php if ($this->_tpl_vars['data']['strategy']): ?>
            <?php $_from = $this->_tpl_vars['data']['strategy']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['code_1'] => $this->_tpl_vars['tmp']):
?>
            <?php $_from = $this->_tpl_vars['logisticPlugin']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['code_2'] => $this->_tpl_vars['label']):
?>
            <?php if ($this->_tpl_vars['code_1'] == $this->_tpl_vars['code_2']): ?><?php echo $this->_tpl_vars['label']; ?>
<?php endif; ?>
            <?php endforeach; endif; unset($_from); ?><br>
            <?php endforeach; endif; unset($_from); ?>
            <?php endif; ?>
        </td>
        <td>
            <?php if ($this->_tpl_vars['data']['strategy']): ?>
            <?php $_from = $this->_tpl_vars['data']['strategy']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['code_1'] => $this->_tpl_vars['tmp']):
?>
            <input type='text' size=3 value='<?php echo $this->_tpl_vars['tmp']['rank']; ?>
' 
            onchange="update(<?php echo $this->_tpl_vars['data']['area_id']; ?>
,'<?php echo $this->_tpl_vars['code_1']; ?>
','rank',this.value)"><br>
            <?php endforeach; endif; unset($_from); ?>
            <?php endif; ?>
        </td>
        <td>
            <?php if ($this->_tpl_vars['data']['strategy']): ?>
            <?php $_from = $this->_tpl_vars['data']['strategy']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['code_1'] => $this->_tpl_vars['tmp']):
?>
            <input type='radio' name='radio_<?php echo $this->_tpl_vars['data']['area_id']; ?>
' value=1 <?php if ($this->_tpl_vars['tmp']['use']): ?>checked="checked"<?php endif; ?>
            onchange="update(<?php echo $this->_tpl_vars['data']['area_id']; ?>
,'<?php echo $this->_tpl_vars['code_1']; ?>
','use',this.value)"><br>
            <?php endforeach; endif; unset($_from); ?>
            <?php endif; ?>
        </td>
        <td>
            <?php if ($this->_tpl_vars['data']['strategy']): ?>
            <?php $_from = $this->_tpl_vars['data']['strategy']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['code_1'] => $this->_tpl_vars['tmp']):
?>
            <input type='checkbox' value=1 <?php if ($this->_tpl_vars['tmp']['open']): ?>checked='checked'<?php endif; ?>
            onchange="if(this.checked){var value=1;}else{var value=0}update(<?php echo $this->_tpl_vars['data']['area_id']; ?>
,'<?php echo $this->_tpl_vars['code_1']; ?>
','open',value)"><br>
            <?php endforeach; endif; unset($_from); ?>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
</table>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
<div>
    <input type="button" value="管理" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"list-manage-area-strategy",)));?>')" />
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

function update(area_id,code,name,value){
	var url = filterUrl('<?php echo $this -> callViewHelper('url', array(array('action'=>"update-area-strategy-by-ajax",)));?>','id');
	new Request({
			url: url+'/area_id/'+area_id+'/code/'+code+'/name/'+name+'/value/'+value,
			method: 'get',
			evalScripts: true,
			onRequest: '',
			onSuccess: function(data){
			},
			onFailure: function(){
				alert('error');
			}
		}).send();
}
</script>