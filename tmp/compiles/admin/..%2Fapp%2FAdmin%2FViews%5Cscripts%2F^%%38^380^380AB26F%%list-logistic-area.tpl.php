<?php /* Smarty version 2.6.19, created on 2014-11-12 17:04:13
         compiled from logistic/list-logistic-area.tpl */ ?>
<div class="search">
<form method="get" action="<?php echo $this -> callViewHelper('url', array(array('action'=>"list-logistic-area",)));?>">
    <table>
        <tr>
            <td>地区</td>
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
                <select name="open">
                    <option value=''>是否开通...</option>
                    <option value='0' <?php if ($this->_tpl_vars['open'] === '0'): ?>selected<?php endif; ?>>否</option>
                    <option value='1' <?php if ($this->_tpl_vars['open'] === '1'): ?>selected<?php endif; ?>>是</option>
                </select>
            </td>
            <td>
                <select name="delivery">
                    <option value=''>能否上门派送...</option>
                    <option value='0' <?php if ($this->_tpl_vars['delivery'] === '0'): ?>selected<?php endif; ?>>否</option>
                    <option value='1' <?php if ($this->_tpl_vars['delivery'] === '1'): ?>selected<?php endif; ?>>是</option>
                </select>
            </td>
            <td>
                <select name="pickup">
                    <option value=''>能否上门取件...</option>
                    <option value='0' <?php if ($this->_tpl_vars['pickup'] === '0'): ?>selected<?php endif; ?>>否</option>
                    <option value='1' <?php if ($this->_tpl_vars['pickup'] === '1'): ?>selected<?php endif; ?>>是</option>
                </select>
            </td>
            <td>
                <select name="cod">
                    <option value=''>能否货到付款...</option>
                    <option value='0' <?php if ($this->_tpl_vars['cod'] === '0'): ?>selected<?php endif; ?>>否</option>
                    <option value='1' <?php if ($this->_tpl_vars['cod'] === '1'): ?>selected<?php endif; ?>>是</option>
                </select>
            </td>
            <td>
                <input type="submit" value="搜索" />
            </td>
        </tr>
    </table>
</form>
</div>

<div id="ajax_search">
<div class="title">物流管理 -&gt; 操作区域管理</div>
<form name="myForm" id="myForm">
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
    <tr>
        <td width=10></td>
        <td width=50>操作</td>
        <td width=100>物流公司</td>
        <td>省份</td>
        <td>城市</td>
        <td>区县</td>
        <td>是否开通</td>
        <td>是否上门派送</td>
        <td>是否上门取件</td>
        <td>是否代收货款</td>
        <td>锁定状态</td>
    </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tmp']):
?>
    <tr>
        <td><input type='checkbox' name="ids[]" value="<?php echo $this->_tpl_vars['tmp']['logistic_area_id']; ?>
"></td>
        <td> <input type="button" value="查看" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"edit-logistic-area",'logistic_area_id'=>$this->_tpl_vars['tmp']['logistic_area_id'],)));?>')" /></td>
        <td>
            <?php $_from = $this->_tpl_vars['logisticPlugin']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['code'] => $this->_tpl_vars['name']):
?>
            <?php if ($this->_tpl_vars['tmp']['logistic_code'] == $this->_tpl_vars['code']): ?><?php echo $this->_tpl_vars['name']; ?>
<?php endif; ?>
            <?php endforeach; endif; unset($_from); ?>
        </td>
        <td><?php echo $this->_tpl_vars['tmp']['province']; ?>
</td>
        <td><?php echo $this->_tpl_vars['tmp']['city']; ?>
</td>
        <td><?php echo $this->_tpl_vars['tmp']['area']; ?>
</td>
        <td><?php if ($this->_tpl_vars['tmp']['open']): ?>是<?php else: ?>否<?php endif; ?></td>
        <td><?php if ($this->_tpl_vars['tmp']['delivery']): ?>是<?php else: ?>否<?php endif; ?></td>
        <td><?php if ($this->_tpl_vars['tmp']['pickup']): ?>是<?php else: ?>否<?php endif; ?></td>
        <td><?php if ($this->_tpl_vars['tmp']['cod']): ?>是<?php else: ?>否<?php endif; ?></td>
        <td><?php if ($this->_tpl_vars['tmp']['lock_name']): ?>被<font color="red"><?php echo $this->_tpl_vars['tmp']['lock_name']; ?>
</font><?php else: ?>未<?php endif; ?>锁定</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
</table>
<div style="padding:0 5px;">
	<input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/> 
	<input type="button" value="锁定" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/lock/1','Gurl(\'refresh\',\'ajax_search\')')">
	<input type="button" value="解锁" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>'lock',)));?>/lock/0','Gurl(\'refresh\',\'ajax_search\')')"></div>
	
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</form>
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

function update(area_strategy_id,code,name,value){
	var url = filterUrl('<?php echo $this -> callViewHelper('url', array(array('action'=>"update-area-strategy-by-ajax",)));?>','id');
	new Request({
			url: url+'/area_strategy_id/'+area_strategy_id+'/code/'+code+'/name/'+name+'/value/'+value,
			method: 'get',
			evalScripts: true,
			onRequest: '',
			onSuccess: function(data){
                console.info(data);
			},
			onFailure: function(){
				alert('error');
			}
		}).send();
}
</script>