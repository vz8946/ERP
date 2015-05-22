<?php /* Smarty version 2.6.19, created on 2014-10-24 17:19:03
         compiled from article/taglist.tpl */ ?>
<div class="title">文章标签管理   [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"add-tag",)));?>')">添加新标签</a> ]</div>
<form name="searchForm" id="searchForm" action="/admin/article/taglist">
<div class="search">
标题：<input type="text" name="title" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['param']['title']; ?>
"/>
标签名：<input type="text" name="tag" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['param']['tag']; ?>
"/>
<input type="submit" name="dosearch" value="查询"/>
</div>
</form>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>标签ID</td>
            <td>标题</td>
            <td>标签名</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['taglist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['data']['goods_id']; ?>
">
        <td><?php echo $this->_tpl_vars['data']['tag_id']; ?>
</td>
        <td>  
		<input type="text" name="update" size="30" value="<?php echo $this->_tpl_vars['data']['title']; ?>
"  onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdatetag',)));?>',<?php echo $this->_tpl_vars['data']['tag_id']; ?>
,'title',this.value)">
		 </td>
        <td>
		<input type="text" name="update" size="30" value="<?php echo $this->_tpl_vars['data']['tag']; ?>
"  onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdatetag',)));?>',<?php echo $this->_tpl_vars['data']['tag_id']; ?>
,'tag',this.value)">
		</td>
        <td>
		 <a href="javascript:fGo()" onclick="G('/admin/article/tag/id/<?php echo $this->_tpl_vars['data']['tag_id']; ?>
')">编辑添加文章</a> 
        </td>
        </td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>