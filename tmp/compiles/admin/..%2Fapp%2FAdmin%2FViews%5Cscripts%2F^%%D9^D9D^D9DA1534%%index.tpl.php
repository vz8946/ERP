<?php /* Smarty version 2.6.19, created on 2014-10-23 13:43:37
         compiled from brand/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html', 'brand/index.tpl', 7, false),)), $this); ?>
<div class="title">品牌管理</div>
<form name="searchForm" id="searchForm" action="/admin/brand">
<div class="search">
品牌名称：<input type="text" name="brand_name" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['brand_name']; ?>
"/>
品牌别名：<input type="text" name="as_name" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['as_name']; ?>
"/>
&nbsp;
是否品牌馆：<?php echo smarty_function_html(array('type' => 'slt','opt' => $this->_tpl_vars['opt_yn'],'label' => "不限",'name' => 'bluk','value' => $this->_tpl_vars['param']['bluk']), $this);?>

&nbsp;
是否品牌城：<?php echo smarty_function_html(array('type' => 'slt','opt' => $this->_tpl_vars['opt_yn'],'label' => "不限",'name' => 'ispinpaicheng','value' => $this->_tpl_vars['param']['ispinpaicheng']), $this);?>

&nbsp;
<input type="submit" name="dosearch" value="查询"/>
</div>
</form>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'add',)));?>')">添加品牌</a> ]
        [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"refresh-char-cache",)));?>')">刷新品牌首字母缓存</a> ]
        [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"refresh-asname",)));?>')">更新别名到商品表</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="40">ID</td>
            <td>品牌名称</td>
			 <td>品牌别名</td>
            <td>商品数量</td>
            <td>是否品牌馆</td>
            <td>是否品牌城</td>
			<td>Big图片</td>
            <td>Small图片</td>
            <td>状态</td>
		    <td>排序</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
        <tr id="ajax_list<?php echo $this->_tpl_vars['data']['brand_id']; ?>
">
            <td><?php echo $this->_tpl_vars['data']['brand_id']; ?>
</td>
            <td><a href="/b-<?php echo $this->_tpl_vars['data']['as_name']; ?>
" target="_blank"><?php echo $this->_tpl_vars['data']['brand_name']; ?>
</a></td>
			 <td><?php echo $this->_tpl_vars['data']['as_name']; ?>
</td>
            <td>
            <a onclick="openDiv('/admin/goods/goods-status/brand_id/<?php echo $this->_tpl_vars['data']['brand_id']; ?>
','ajax','查看  <?php echo $this->_tpl_vars['data']['brand_name']; ?>
 商品信息',900,500)" >
            <font color="#FF3300"><?php echo $this->_tpl_vars['data']['brand_goods_num']; ?>
</font></a>
            </td>
            <td>
            	<a href="javascript:void(0);"  id="label_bluk<?php echo $this->_tpl_vars['data']['brand_id']; ?>
" 
            		onclick="ajax_status('/admin/brand/toggle-bluk','<?php echo $this->_tpl_vars['data']['brand_id']; ?>
','<?php echo $this->_tpl_vars['data']['bluk']; ?>
','label_bluk');"><?php if ($this->_tpl_vars['data']['bluk'] == 1): ?>是<?php else: ?>否<?php endif; ?></a>
            </td>
            <td>
            	<a href="javascript:void(0);"  id="label_ispinpaicheng<?php echo $this->_tpl_vars['data']['brand_id']; ?>
" 
            		onclick="ajax_status('/admin/brand/toggle-ispinpaicheng','<?php echo $this->_tpl_vars['data']['brand_id']; ?>
','<?php echo $this->_tpl_vars['data']['ispinpaicheng']; ?>
','label_ispinpaicheng');"><?php if ($this->_tpl_vars['data']['ispinpaicheng'] == 1): ?>是<?php else: ?>否<?php endif; ?></a>
            </td>
			<td> <?php if ($this->_tpl_vars['data']['big_logo']): ?>   <img  src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['data']['big_logo']; ?>
" height="50" width="100"/> <?php endif; ?></td>
            <td> <?php if ($this->_tpl_vars['data']['small_logo']): ?>   <img  src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['data']['small_logo']; ?>
" height="50" width="100"/> <?php endif; ?></td>
            <td id="ajax_status<?php echo $this->_tpl_vars['data']['brand_id']; ?>
"><?php echo $this->_tpl_vars['data']['status']; ?>
</td>
			<td ><input type="text" name="update" size="3" value="<?php echo $this->_tpl_vars['data']['band_sort']; ?>
" onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate',)));?>',<?php echo $this->_tpl_vars['data']['brand_id']; ?>
,'band_sort',this.value)">
			</td>
	        <td>
				<a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'edit','id'=>$this->_tpl_vars['data']['brand_id'],)));?>')">编辑</a>
				<a href="javascript:fGo()" onclick="G('/admin/brand/tag/id/<?php echo $this->_tpl_vars['data']['brand_id']; ?>
')">设置推荐商品</a>
	        </td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        </tbody>
    </table>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</di>
