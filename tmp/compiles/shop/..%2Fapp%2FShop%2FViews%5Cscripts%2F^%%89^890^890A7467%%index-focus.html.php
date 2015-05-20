<?php /* Smarty version 2.6.19, created on 2014-10-30 12:38:58
         compiled from D:%5Cwamp%5Cwww%5Cjiankang%5Clib%5CWidget%5CAdvertWidget%5Chtml%5Cindex-focus.html */ ?>
<!--首焦图-->
<div id="ad_<?php echo $this->_tpl_vars['ad_id']; ?>
" class="slideadv fl" style="height:<?php echo $this->_tpl_vars['board']['height']; ?>
px;width:<?php echo $this->_tpl_vars['board']['width']; ?>
px;overflow:hidden;">
<?php $_from = $this->_tpl_vars['adlist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
		<div style="display:block; " class="adv">			
			<a target="_blank"  href="<?php echo $this->_tpl_vars['item']['url']; ?>
">
			 <img  aid="<?php echo $this->_tpl_vars['item']['id']; ?>
" alt="<?php echo $this->_tpl_vars['item']['desc']; ?>
" title="<?php echo $this->_tpl_vars['item']['desc']; ?>
" <?php if ($this->_tpl_vars['key'] == 0): ?>src="<?php echo $this->_tpl_vars['item']['content']; ?>
"<?php else: ?> _src="<?php echo $this->_tpl_vars['item']['content']; ?>
"<?php endif; ?>  width="671"   height="370"  style="display: inline; opacity: 1; ">
			</a>                       
                        
           <div class="index-focus-small" style="float: left;width: 295px;height: 358px;overflow: hidden;border: 1px solid #eee;">	
           	<?php $_from = $this->_tpl_vars['item']['extconfig']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sub']):
?>	
		    <div>			
			<a target="_blank"  href="<?php echo $this->_tpl_vars['sub']['link']; ?>
" class="small_focus">
			 <img  width="295"  height="120" <?php if ($this->_tpl_vars['key'] == 0): ?> src="<?php echo $this->_tpl_vars['sub']['pic']; ?>
"  <?php else: ?>  _src="<?php echo $this->_tpl_vars['sub']['pic']; ?>
" <?php endif; ?> >
			 <em></em>
			 </a>
		    </div>	
		   <?php endforeach; endif; unset($_from); ?>
	      </div>
	</div>            
<?php endforeach; endif; unset($_from); ?>     
<div class="num">
		<ul>
		<?php $_from = $this->_tpl_vars['adlist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
			<li <?php if ($this->_tpl_vars['key'] == 0): ?> class="cur" <?php endif; ?>></li>
		<?php endforeach; endif; unset($_from); ?>
		</ul>
</div>

</div>

<script>
   
	//图片轮换
	len = $(".slideadv .adv").length;
	$(".num ul li").mouseover(function(){
		index  =   $(".num ul li").index(this);
		showImg(index);
		clearInterval(adTimer);
		adTimer=null;
	});
	$(".num ul li").mouseout(function(){
		go();
	})
	// 图片轮播---滑入 停止动画，滑出开始动画.
	$('.slideadv a').hover(function(){
			 clearInterval(adTimer);
			 adTimer=null;
		 },function(){
			 go();
	});
	go();
   
	$("a.small_focus").hover(function(){
		$("a.small_focus em").show();
		$(this).find("em").hide();
	},function(){
		$("a.small_focus em").hide();
	})
</script>