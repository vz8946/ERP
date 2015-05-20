<?php /* Smarty version 2.6.19, created on 2014-10-30 13:30:33
         compiled from index/jpy.tpl */ ?>
<div class="wbox_1200">
	<div class="wrap">
		<!-- 广告-->
		<div class="adv1200">

			<div id="zqj_box" >
				<div style="MARGIN: 0px auto; WIDTH: 980px" id="zqj_box">
					<div class="left_bg1"></div>
					<div class="right_bg1"></div>
					<div >
						<img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/new_banner_ad.jpg " width="991" height="260" />
					</div>
				</div>
			</div>
			<!-- end 广告-->
			<div class="wrap"  style="width: 990px;">

				<div class="giantlist clearfix" id="specialProductDiv">
					<h1 class="t1"></h1>

					<div class="product clearfix">
						<ul>
							<?php $_from = $this->_tpl_vars['jpy']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['vo']):
?>
							<li>
								<div class="pic">
									<div class="wh200 verticalPic">
										<a href="/b-<?php echo $this->_tpl_vars['vo']['as_name']; ?>
/detail<?php echo $this->_tpl_vars['vo']['goods_id']; ?>
.html" title="<?php echo $this->_tpl_vars['vo']['goods_name']; ?>
" target="_blank"><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['vo']['goods_img']; ?>
" alt="<?php echo $this->_tpl_vars['vo']['goods_name']; ?>
" width="200" height="200"></a>
									</div>
								</div>
								<div class="name">
									<a href="/b-<?php echo $this->_tpl_vars['vo']['as_name']; ?>
/detail<?php echo $this->_tpl_vars['vo']['goods_id']; ?>
.html" title="<?php echo $this->_tpl_vars['vo']['goods_name']; ?>
" target="_blank"><?php echo $this->_tpl_vars['vo']['goods_name']; ?>
</a>
								</div>
								<div class="buybox">
									<div class="price">
										特促价：¥<span><?php echo $this->_tpl_vars['vo']['price']; ?>
</span>
									</div>
									<div class="buynow">
										<a href="/b-<?php echo $this->_tpl_vars['vo']['as_name']; ?>
/detail<?php echo $this->_tpl_vars['vo']['goods_id']; ?>
.html" title="立即购买" target="_blank">立即购买</a>
									</div>
								</div>
								<div class="disc">
									<span>市场价
										<br>
										<del><em>¥</em><?php echo $this->_tpl_vars['vo']['market_price']; ?>
</del></span>

									<span>节省
										<br>
										<em>¥</em><?php echo $this->_tpl_vars['vo']['disprice']; ?>
</span>
								</div>
							</li>

							<?php endforeach; endif; unset($_from); ?>

						</ul>
					</div>
				</div>

			</div>
		</div>
	</div>

	<!-- foot start-->
	<!-- foot end-->
	<script src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/Public/js/global.js" type="text/jscript"></script>
	<script src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/Public/js/otherPd.js" type="text/javascript"></script>
</div>