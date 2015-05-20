<?php /* Smarty version 2.6.19, created on 2014-10-22 23:10:25
         compiled from logistic/list-logistic-area-price.tpl */ ?>
<div class="search">
<form method="get" action="<?php echo $this -> callViewHelper('url', array(array('action'=>"list-logistic-area-price",)));?>">
    <table>
        <tr>
            <td width=50>物流公司</td>
            <td width=200>
                <select name="logistic_code">
                    <?php $_from = $this->_tpl_vars['logistic']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
                    <option value=<?php echo $this->_tpl_vars['data']['logistic_code']; ?>
 <?php if ($this->_tpl_vars['data']['logistic_code'] == $this->_tpl_vars['logisticCode']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['data']['name']; ?>
</option>
                    <?php endforeach; endif; unset($_from); ?>
                </select>
            </td>
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
                <input type="submit" value="搜索" />
            </td>
        </tr>
    </table>
</form>
</div>

<div id="ajax_search">
<div class="title">物流管理 -&gt; 配送价格管理</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
    <tr>
        <td width=100>操作</td>
        <td width=100>国家</td>
        <td width=150>省份</td>
        <td width=150>城市</td>
        <td width=150>区县</td>
        <?php $_from = $this->_tpl_vars['title']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['label']):
?>
        <td><?php echo $this->_tpl_vars['label']; ?>
</td>
        <?php endforeach; endif; unset($_from); ?>
    </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['logisticArea']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr>
        <td>
        <input type="button" value="编辑" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"edit-logistic-area-price",'logistic_area_id'=>$this->_tpl_vars['data']['logistic_area_id'],'logistic_code'=>$this->_tpl_vars['logisticCode'],'province_id'=>$this->_tpl_vars['provinceID'],'city_id'=>$this->_tpl_vars['cityID'],'area_id'=>$this->_tpl_vars['areaID'],)));?>')" />
        </td>
        <td><?php echo $this->_tpl_vars['data']['country']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['province']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['city']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['area']; ?>
</td>
        <?php $_from = $this->_tpl_vars['data']['price']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['price']):
?>
        <td><?php echo $this->_tpl_vars['price']; ?>
</td>
        <?php endforeach; endif; unset($_from); ?>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
</table>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
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