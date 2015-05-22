<?php /* Smarty version 2.6.19, created on 2014-10-24 18:54:49
         compiled from topics/editform.tpl */ ?>
<script type="text/javascript" src="/scripts/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="/scripts/kindeditor/lang/zh_CN.js"></script>
<form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>" method="post" enctype="multipart/form-data">
<input type='hidden' name='article[id]' value='<?php echo $this->_tpl_vars['article']['id']; ?>
'>
<div class="title">编辑文章</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="10%"><strong>标题</strong> * </td>
      <td><input type="text" name="article[name]" size="30" value="<?php echo $this->_tpl_vars['article']['name']; ?>
" msg="请填写标题" class="required" /></td>
    </tr>

    <tr>
      <td width="10%"><strong>内容</strong></td>
      <td>
		<textarea name="article[content]" id="content" rows="20" style="width:680px; height:260px;"><?php echo $this->_tpl_vars['article']['content']; ?>
</textarea>
		<script type="text/javascript">
			KindEditor.ready(function(K) {
				K.create('textarea[name="content"]', {
					        filterMode : false,
							allowFileManager : true
						});
			});
		</script>
	  </td>
    </tr>
    <tr>
      <td width="10%"><strong>是否显示</strong></td>
      <td><input name="article[isDisplay]" type="radio" value="1"  <?php if ($this->_tpl_vars['article']['isDisplay'] == 1): ?> checked="checked"  <?php endif; ?>/>
      显示 　　　　　
        <input name="article[isDisplay]" type="radio" value="0" <?php if ($this->_tpl_vars['article']['isDisplay'] == 0): ?> checked="checked"  <?php endif; ?> />
        隐藏</td>
    </tr>
	 <tr>
      <td width="10%"><strong>是否显示下单板块</strong></td>
      <td><input name="article[sort]" type="radio" value="1"  <?php if ($this->_tpl_vars['article']['sort'] == 1): ?> checked="checked"  <?php endif; ?>/>
      显示 　　　　　
        <input name="article[sort]" type="radio" value="0" <?php if ($this->_tpl_vars['article']['sort'] == 0): ?> checked="checked"  <?php endif; ?> />
        隐藏</td>
    </tr>
    <tr>
      <td width="10%"><strong>显示优先级（值越大显示越靠前）</strong> * </td>
      <td><input type="text" name="article[grade]" size="10" value="<?php echo $this->_tpl_vars['article']['grade']; ?>
" /></td>
    </tr>
      <tr>
      <td width="10%"><strong>产品标签</strong> * </td>
      <td><input type="text" name="article[flagUrl]" size="10" value="<?php echo $this->_tpl_vars['article']['flagUrl']; ?>
"/></td>
    </tr>
    <tr>
      <td width="10%"><strong>meta标题</strong> * </td>
      <td><input type="text" name="article[title]" size="30" value="<?php echo $this->_tpl_vars['article']['title']; ?>
" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>meta关键字</strong> * </td>
      <td><input type="text" name="article[keyword]" size="30" value="<?php echo $this->_tpl_vars['article']['keyword']; ?>
" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>meta描述</strong> * </td>
      <td><textarea name="article[desc]" rows="6" cols="50" id="meta_description" style="width:330px; height:45px;"><?php echo $this->_tpl_vars['article']['desc']; ?>
</textarea></td>
    </tr>
	<tr>
	  <td><strong>代表图片</strong></td>
	  <td><input type="file" name="img_url" /></td>
	</tr>
	<?php if ($this->_tpl_vars['article']['imgUrl']): ?>
	<tr>
	  <td></td>
	  <td>原图：<img src="/<?php echo $this->_tpl_vars['article']['imgUrl']; ?>
" width="100" height="100" /></td>
	</tr>
	<?php endif; ?>
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>