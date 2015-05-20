<?php /* Smarty version 2.6.19, created on 2014-10-30 10:40:47
         compiled from goods/consultation.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'goods/consultation.tpl', 24, false),)), $this); ?>
<!--新加产品咨询-->
<p class="sh">共有<?php echo $this->_tpl_vars['csl']['total']; ?>
 人进行了提问 |  您的咨询，营养师将在10分钟内回复您，请您稍等。<!-- <a href="#" class="blue">查看所有问题>></a>--> </p>

<form action="javascript:;" onsubmit="submitComment(this)" method="post" name="ommenttForm" id="ommenttForm">
<input type="hidden" name="goods_id" value="<?php echo $this->_tpl_vars['id']; ?>
" />
<input type="hidden" name="title" value="产品咨询" />
<input type="hidden" name="type" value="2" />
<input type="hidden" name="goods_name" value="<?php echo $this->_tpl_vars['goods']['goods_name']; ?>
" />

<?php if ($this->_tpl_vars['login']): ?>
	<div class="grayborder"><a href="/login.html" class="blue">登录</a>垦丰电商帐号进行提问</div>
<?php else: ?>
	<div class="grayborder"><textarea id="contentt" name="content"></textarea></div>
	<div class="tj"><input type="submit" value="我要提问" name=""></div> 
<?php endif; ?>

</form>

<h4>产品咨询 <span>共有<em class="red"> <?php echo $this->_tpl_vars['csl']['total']; ?>
 </em>条咨询 已解决<em class="red"> <?php echo $this->_tpl_vars['csl']['ctotal']; ?>
 </em>条</span></h4>

<ul>
<?php if ($this->_tpl_vars['datas']): ?>
<?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>	
    <li><div class="one"><strong class="orange"><?php echo $this->_tpl_vars['item']['user_name']; ?>
</strong> 说：<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>

    <div><?php echo $this->_tpl_vars['item']['content']; ?>
</div></div>
    <div class="two clear">
    <div class="fl">
        <?php if ($this->_tpl_vars['item']['dietitian'] == 1): ?>
            <img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/shop/dietitian_1.jpg" />彭老师
            <?php elseif ($this->_tpl_vars['item']['dietitian'] == 2): ?>
            <img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/shop/dietitian_2.jpg" />白老师
            <?php elseif ($this->_tpl_vars['item']['dietitian'] == 3): ?>
            <img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/shop/dietitian_3.jpg" />杜老师
            <?php elseif ($this->_tpl_vars['item']['dietitian'] == 4): ?>
            <img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/shop/dietitian_4.jpg" />肖老师
            <?php elseif ($this->_tpl_vars['item']['dietitian'] == 5): ?>
            <img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/shop/dietitian_5.jpg" />王老师
            <?php elseif ($this->_tpl_vars['item']['dietitian'] == 6): ?>
            <img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/shop/dietitian_6.jpg" />白老师
        <?php endif; ?>
    </div>
    <div class="fr"><strong class="orange">营养师回复解答：</strong>
    <div><?php echo $this->_tpl_vars['item']['reply']; ?>
</div></div><!--fr end-->
    </div></li>
<?php endforeach; endif; unset($_from); ?>
<?php endif; ?>	

</ul>
<div class="fy"> </div>
<!--新加产品咨询 end-->	
<script type="text/javascript">
/**
 * 提交问题
 */
var cmt_empty_content = "提问不能小于2个字符";
var cmt_large_content = "您输入的内容超过了250个字符";
function submitComment(){
	var uname=$.trim($("#ommenttForm input[name='user_name']").val());
	var content = $("#contentt").val();
	if(content.length<2){
		alert(cmt_empty_content);
		return false;
	}
	if(content.length>250){
		alert(cmt_large_content);
		return false;
	}
	$.ajax({
		url:'/goods/msg',
		type:'post',
		data:$('#ommenttForm').serialize(),
		success:function(msg){
			if(msg!=''){alert(msg);}
			else{
				alert('您的问题已成功提交，请等待专家回复。');
                $("#contentt").val('');
			}
		},
		error:function(msg,err){
			alert(err);
		}
	})
	return false;
}
</script>