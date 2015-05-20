<?php /* Smarty version 2.6.19, created on 2014-10-30 10:43:29
         compiled from index/baokuan.tpl */ ?>
<div class="wrap" style="width: 990px;">
	<div class="adv1200"><img border="0" height="200" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/in_ad.jpg" alt="首banner">
	</div>
	<div class="bakkuan_menu">
		<ul>
			<li>
				<a  rel="nofollow" <?php if (empty ( $this->_tpl_vars['pidcode'] )): ?> class="cur" <?php endif; ?> href="javascript:void(0);" attrid="0">全部</a>
			</li>

			<?php $_from = $this->_tpl_vars['catelist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['vo']):
?>
			<li>
				<a  rel="nofollow" href="javascript:void(0);" <?php if ($this->_tpl_vars['vo']['code'] == $this->_tpl_vars['pidcode']): ?> class="cur" <?php endif; ?>   attrid="<?php echo $this->_tpl_vars['vo']['code']; ?>
"><?php echo $this->_tpl_vars['vo']['name']; ?>
</a>
			</li>
			<?php endforeach; endif; unset($_from); ?>
		</ul>
	</div>
	<div class="baokuan_list">
		<ul class="active">
			<?php $_from = $this->_tpl_vars['baokuanlist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['vo']):
?>
			<li columnid="c_150023">
				<div class="product">
					<div class="pic">
						<a target="_blank" href="/b-<?php echo $this->_tpl_vars['vo']['as_name']; ?>
/detail<?php echo $this->_tpl_vars['vo']['goodsid']; ?>
.html" title="<?php echo $this->_tpl_vars['vo']['goods_name']; ?>
"><img width="720" height="240" alt="<?php echo $this->_tpl_vars['vo']['goods_name']; ?>
" src="<?php echo $this->_tpl_vars['vo']['imgurl']; ?>
"></a>
					</div>
				</div>
				<div class="intro">
					<h2></h2>
					<h3><a target="_blank" title="<?php echo $this->_tpl_vars['vo']['goods_name']; ?>
" href="/b-<?php echo $this->_tpl_vars['vo']['as_name']; ?>
/detail<?php echo $this->_tpl_vars['vo']['goodsid']; ?>
.html" class="tlt"><?php echo $this->_tpl_vars['vo']['goods_name']; ?>
 </a>
					</h3>
                    <p class="price"><span>市场价：</span><em style="text-decoration:line-through;"><?php echo $this->_tpl_vars['vo']['market_price']; ?>
 </em><span style="margin-left:15px;">垦丰价：</span><span style="color:red;display: inline;font-weight: bold;"><?php echo $this->_tpl_vars['vo']['price']; ?>
 </span></p>
					<p align="center">
						<a target="_blank" title="<?php echo $this->_tpl_vars['vo']['goods_name']; ?>
" href="/b-<?php echo $this->_tpl_vars['vo']['as_name']; ?>
/detail<?php echo $this->_tpl_vars['vo']['goodsid']; ?>
.html" class="tlt"> <img border="0"  src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/submit.jpg" width="110px" alt="立即购买"></a>
					</p>
					<div class="reason">
						<p class="reason_title">
							<em class="e1"></em><span>推荐理由</span><em class="e2"></em>
						</p>
						<div class="reason_txt">
							<p class="txt" title="<?php echo $this->_tpl_vars['vo']['act_notes']; ?>
">
								<?php echo $this->_tpl_vars['vo']['act_notes']; ?>

							</p>
							<div class="ri"></div>
						</div>
					</div>
				</div>
			</li>
			<?php endforeach; endif; unset($_from); ?>
		</ul>
	</div>
</div>
<!-- foot start-->
<!-- foot end-->
<script src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/Public/js/otherPd.js" type="text/javascript"></script>