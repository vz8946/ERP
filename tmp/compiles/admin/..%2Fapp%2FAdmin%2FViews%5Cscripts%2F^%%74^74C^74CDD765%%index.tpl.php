<?php /* Smarty version 2.6.19, created on 2014-10-22 22:28:35
         compiled from category/index.tpl */ ?>
<div class="title">分类管理 ---> 前台展示分类 </div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'add','angle_id'=>$this->_tpl_vars['angle_id'],),""));?>')">添加分类</a> ] 
	 
        [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'relation','id'=>0,'limit_type'=>'global','ttype'=>'view',)));?>')">设置全局浏览关联</a> ] 
        [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'relation','id'=>0,'limit_type'=>'global','ttype'=>'buy',)));?>')">设置全局购买关联</a> ] 
        [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'relation','id'=>0,'limit_type'=>'global','ttype'=>'similar',)));?>')">设置全局分类关联</a> ] 
	
    [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"reflash-cache",)));?>')">刷新分类缓存</a> ] 
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>排序</td>
            <td>ID</td>
            <td>名称</td>          
            <td>URL别名</td>         
            <td>属性</td>
            <td>状态</td>
            <td>是否显示</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['cat_id']; ?>
">
        <td><input type="text" name="update" size="2" value="<?php echo $this->_tpl_vars['data']['cat_sort']; ?>
" style="text-align:center;" onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate',)));?>',<?php echo $this->_tpl_vars['data']['cat_id']; ?>
,'cat_sort',this.value)"></td>
        <td><?php echo $this->_tpl_vars['data']['cat_id']; ?>
</td>
        <td style="padding-left:<?php echo $this->_tpl_vars['data']['step']*20; ?>
px"><?php echo $this->_tpl_vars['data']['depth']; ?>
<input type="text" name="update" size="30" value="<?php echo $this->_tpl_vars['data']['cat_name']; ?>
"  onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate',)));?>',<?php echo $this->_tpl_vars['data']['cat_id']; ?>
,'cat_name',this.value)"></td>
    
   
        <td><input type="text" name="url_alias" size="15" value="<?php echo $this->_tpl_vars['data']['url_alias']; ?>
" style="text-align:left;" onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate',)));?>','<?php echo $this->_tpl_vars['data']['cat_id']; ?>
','url_alias',this.value)"></td>
     
        <td>
			<a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'add','pid'=>$this->_tpl_vars['data']['cat_id'],'angle_id'=>$this->_tpl_vars['angle_id'],),""));?>')">添加子分类</a>
        </td>
        <td id="ajax_status<?php echo $this->_tpl_vars['data']['cat_id']; ?>
"><?php echo $this->_tpl_vars['data']['status']; ?>
</td>
        <td id="ajax_display<?php echo $this->_tpl_vars['data']['cat_id']; ?>
"><?php echo $this->_tpl_vars['data']['display']; ?>
</td>
        <td>
			<a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'edit','id'=>$this->_tpl_vars['data']['cat_id'],)));?>')">编辑</a>			
			<a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'relation','id'=>$this->_tpl_vars['data']['cat_id'],'limit_type'=>'cat','ttype'=>'view',)));?>')">浏联</a>
			<a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'relation','id'=>$this->_tpl_vars['data']['cat_id'],'limit_type'=>'cat','ttype'=>'buy',)));?>')">购联</a>
			<a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'relation','id'=>$this->_tpl_vars['data']['cat_id'],'limit_type'=>'cat','ttype'=>'similar',)));?>')">类联</a>
		  </td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>