<?php /* Smarty version 2.6.19, created on 2014-11-10 09:34:04
         compiled from goods/show.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'goods/show.tpl', 7, false),array('modifier', 'count', 'goods/show.tpl', 112, false),array('modifier', 'default', 'goods/show.tpl', 272, false),array('function', 'math', 'goods/show.tpl', 55, false),array('function', 'widget', 'goods/show.tpl', 349, false),)), $this); ?>
<div  class="wbox990">
<div class="position"><a title="垦丰商城" href="/" style="color: #004ca2;">垦丰商城</a>&nbsp;<?php echo $this->_tpl_vars['ur_here']; ?>
</div>
<div class="wrap">
	<div class="preview">
    	<div class="bigimg">
    	   <a   href="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['data']['goods_img']; ?>
"  class="jqzoom" rel='gal1'  title="triumph">
    	       <img width="378" height="378"  src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_380_380.') : smarty_modifier_replace($_tmp, '.', '_380_380.')); ?>
"  alt="<?php echo $this->_tpl_vars['data']['goods_name']; ?>
">
    	  </a>
    	  <div class="goods_zoom"></div>
    	</div>
        <div class="smallimg" id="pic_small_wrapper">
        	<a class="btn_left btn_left_none" href="javascript:;"  onfocus="this.blur();" ></a>
             <div class="items" id="list_smallpic">
            	<ul id="thumblist">
            		<li>
					  <a onfocus="this.blur();"    class="zoomThumbActive"  href="javascript:void(0)" href='javascript:void(0);' rel="{gallery: 'gal1', smallimage: '<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_380_380.') : smarty_modifier_replace($_tmp, '.', '_380_380.')); ?>
',largeimage: '<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['data']['goods_img']; ?>
'}" >
                        <img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
"  alt="<?php echo $this->_tpl_vars['data']['goods_name']; ?>
" >
                       </a>
					</li>
					<?php if (! empty ( $this->_tpl_vars['img_url'] )): ?>
					<?php $_from = $this->_tpl_vars['img_url']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['r']):
?>
					<?php if ($this->_tpl_vars['r']['img_url']): ?>
					<li>
					<a onfocus="this.blur();"  href="javascript:void(0)"  rel="{gallery: 'gal1', smallimage: '<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['r']['img_url'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_380_380.') : smarty_modifier_replace($_tmp, '.', '_380_380.')); ?>
',largeimage: '<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['r']['img_url']; ?>
'}">
					<img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['r']['img_url'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
"  alt="<?php echo $this->_tpl_vars['data']['goods_name']; ?>
"  ></a>
					</li>					
					<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?>
					<?php endif; ?>					
                </ul>
            </div>
            <a class="btn_right" href="javascript:;" onfocus="this.blur();"  ></a>
        </div>
    </div>
    
  <div class="product_info">
    	<div class="name">
       	  <h2><?php echo $this->_tpl_vars['data']['goods_name']; ?>
</h2>
            <b><?php echo $this->_tpl_vars['data']['goods_alt']; ?>
</b>
        </div>
        <div class="summary">
             <p class="price">市场价:<span>￥<?php echo $this->_tpl_vars['data']['market_price']; ?>
</span></p>
		       <?php if ($this->_tpl_vars['data']['org_price']): ?>		 		
			 		 <p class="price">垦丰价:<span><em><b>￥</b><?php echo $this->_tpl_vars['data']['org_price']; ?>
</em> </span></p>	
			 		 <p class="price_jiankang">
				    <?php if ($this->_tpl_vars['data']['offers_type'] == 'exclusive'): ?>
				       <?php if ($this->_tpl_vars['data']['show_name']): ?><?php echo $this->_tpl_vars['data']['show_name']; ?>
专享价<?php else: ?>专享价<?php endif; ?>:<span>￥<?php echo $this->_tpl_vars['data']['price']; ?>
</span> <?php if ($this->_tpl_vars['data']['excluisv_name'] != ''): ?><i>（<?php echo $this->_tpl_vars['data']['excluisv_name']; ?>
）</i><?php endif; ?>
					<?php elseif ($this->_tpl_vars['data']['offers_type'] == 'price-exclusive'): ?>
						试 用 价:<span>￥<?php echo $this->_tpl_vars['data']['price']; ?>
</span> <?php if ($this->_tpl_vars['data']['excluisv_name'] != ''): ?><i>（<?php echo $this->_tpl_vars['data']['excluisv_name']; ?>
）</i><?php endif; ?>
				    <?php elseif ($this->_tpl_vars['data']['offers_type'] == 'fixed'): ?>	
					   <span><?php if ($this->_tpl_vars['data']['offers_message'] == 'only_new_member'): ?>新会员特价 <?php else: ?>抢购价 <?php endif; ?></span><em><b>￥</b><?php echo $this->_tpl_vars['data']['price']; ?>
</em>				   			
					<?php elseif ($this->_tpl_vars['data']['offers_type'] == 'discount'): ?>
					    <span><?php echo $this->_tpl_vars['data']['discount_title']; ?>
 折价</span><em><b>￥</b><?php echo $this->_tpl_vars['data']['price']; ?>
</em>
					 <?php endif; ?>
				        <i>已优惠￥<?php echo smarty_function_math(array('equation' => "x - y",'x' => $this->_tpl_vars['data']['market_price'],'y' => $this->_tpl_vars['data']['price']), $this);?>
</i>
				    </p>			    	
		  <?php else: ?>
			  <p class="price_jiankang"><span>垦丰价</span><em><b>￥</b><?php echo $this->_tpl_vars['data']['price']; ?>
</em><i>已优惠￥<?php echo smarty_function_math(array('equation' => "x - y",'x' => $this->_tpl_vars['data']['market_price'],'y' => $this->_tpl_vars['data']['price']), $this);?>
</i></p>	
           <?php endif; ?>
					
					
				<?php if ($this->_tpl_vars['groupGoodsAData'] || $this->_tpl_vars['groupGoodsBData'] || $this->_tpl_vars['groupGoodsCData']): ?>
					<div  class="other" style="padding:3px 0px;">
						
						
						<div class="activity">
                        	<span class="fl">促销活动：</span>
							<?php if ($this->_tpl_vars['groupGoodsBData']): ?>
							<dl class="yi">
								<dt>
									总价优惠
								</dt>
								<dd>
									<ol>
										<?php $_from = $this->_tpl_vars['groupGoodsBData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['groupGoods']):
?>
										<li>
											<a href="/groupgoods-<?php echo $this->_tpl_vars['groupGoods']['group_id']; ?>
.html" target="_blank"><?php echo $this->_tpl_vars['groupGoods']['group_goods_name']; ?>
</a><em></em><strong class="red">￥<?php echo $this->_tpl_vars['groupGoods']['group_price']; ?>
</strong><a href="javascript:void(0)" onclick="addGroupCart(<?php echo $this->_tpl_vars['groupGoods']['group_id']; ?>
)" class="buttons">购买</a>
										</li>
										<?php endforeach; endif; unset($_from); ?>
									</ol>
								</dd>
							</dl><!--总价优惠 end-->
							<?php endif; ?>
							<?php if ($this->_tpl_vars['groupGoodsCData']): ?>
							<dl class="yi">
								<dt>
									买送优惠
								</dt>
								<dd>
									<ol>
										<?php $_from = $this->_tpl_vars['groupGoodsCData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['groupGoods']):
?>
										<li>
											<a href="/groupgoods-<?php echo $this->_tpl_vars['groupGoods']['group_id']; ?>
.html" target="_blank"><?php echo $this->_tpl_vars['groupGoods']['group_goods_name']; ?>
</a><em></em><strong class="red">￥<?php echo $this->_tpl_vars['groupGoods']['group_price']; ?>
</strong><a href="javascript:void(0)" onclick="addGroupCart(<?php echo $this->_tpl_vars['groupGoods']['group_id']; ?>
)" class="buttons">购买</a>
										</li>
										<?php endforeach; endif; unset($_from); ?>
									</ol>
								</dd>
							</dl>
					<?php endif; ?>
							<!--买送优惠 end-->
					<?php if ($this->_tpl_vars['groupGoodsAData']): ?>
				     <dl class="er">
								<dt>
                                	组合套装
								</dt>
								<dd>
									<ol>
										<?php $_from = $this->_tpl_vars['groupGoodsAData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['n'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['n']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['groupGoods']):
        $this->_foreach['n']['iteration']++;
?>
										<li <?php if (($this->_foreach['n']['iteration'] == $this->_foreach['n']['total'])): ?>style="border: none;"<?php endif; ?> class="clear">
											<table width="100%">
												<tr>
													<?php $this->assign('c', count($this->_tpl_vars['groupGoods']['group_goods_config'])); ?>
													<?php if ($this->_tpl_vars['c'] == 0): ?>
													<?php $this->assign('w', 50); ?>
													<?php elseif ($this->_tpl_vars['c'] == 1): ?>
													<?php $this->assign('w', 105); ?>
													<?php elseif ($this->_tpl_vars['c'] == 2): ?>
													<?php $this->assign('w', 165); ?>
													<?php endif; ?>
													<td width="<?php echo $this->_tpl_vars['w']; ?>
">
														<a href="/goods-<?php echo $this->_tpl_vars['data']['goods_id']; ?>
.html" target="_blank" title="<?php echo $this->_tpl_vars['data']['goods_name']; ?>
">
														<img width="40" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
" />
														</a>
														<?php if ($this->_tpl_vars['groupGoods']['group_goods_config']): ?>
													  	<span>+</span>
														<?php $_from = $this->_tpl_vars['groupGoods']['group_goods_config']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['goods']):
        $this->_foreach['goods']['iteration']++;
?>
														<?php if ($this->_foreach['goods']['iteration'] <= 2): ?>
														<a href="/goods-<?php echo $this->_tpl_vars['goods']['goods_id']; ?>
.html" target="_blank"  title="<?php echo $this->_tpl_vars['goods']['goods_name']; ?>
">
														<img width="40" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['goods']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
" />
														</a>
														<?php if ($this->_foreach['goods']['iteration'] != count($this->_tpl_vars['groupGoods']['group_goods_config'])): ?>
														<?php if ($this->_foreach['goods']['iteration'] == 1): ?>
														<span>+</span>
														<?php endif; ?>
														<?php endif; ?>
														<?php endif; ?>
														<?php endforeach; endif; unset($_from); ?>
														<?php endif; ?>
													</td>
													<td>
														<a href="/groupgoods-<?php echo $this->_tpl_vars['groupGoods']['group_id']; ?>
.html" target="_blank"><?php echo $this->_tpl_vars['groupGoods']['group_goods_name']; ?>
</a> <strong class="red">￥<?php echo $this->_tpl_vars['groupGoods']['group_price']; ?>
</strong>
													</td>
													<td width="60">
														<a style="display: inline-block;" href="javascript:void(0)" onclick="addGroupCart(<?php echo $this->_tpl_vars['groupGoods']['group_id']; ?>
)" class="buttons">购买</a>
													</td>
												</tr>
											</table>
										</li>
										<?php endforeach; endif; unset($_from); ?>
									</ol>
								</dd>
							</dl><!--组合套装 end-->
							<?php endif; ?>

							
					
							<div style="clear:both;"></div>
						</div><!--activity end-->
					</div>
					<?php endif; ?>		 
          
          
          
          <p>商品编号：<?php echo $this->_tpl_vars['data']['goods_sn']; ?>

		  <?php if ($this->_tpl_vars['data']['needLogin']): ?><font color="#999999">(该商品有活动优惠价，请先<a href="/auth/login/goto/<?php echo $this->_tpl_vars['goto']; ?>
">登录</a>以获得最新价格)</font><?php endif; ?></p>
          <p>品  牌：<?php echo $this->_tpl_vars['brand']['brand_name']; ?>
</p>
          <p>规  格：<?php echo $this->_tpl_vars['data']['goods_style']; ?>
</p>
          <p>
	  <?php if ($this->_tpl_vars['data']['characters'] == '1'): ?>适种积温带：第一积温带<?php endif; ?>
	  <?php if ($this->_tpl_vars['data']['characters'] == '2'): ?>适种积温带：第二积温带<?php endif; ?>
	  <?php if ($this->_tpl_vars['data']['characters'] == '3'): ?>适种积温带：第三积温带<?php endif; ?>
	  <?php if ($this->_tpl_vars['data']['characters'] == '4'): ?>适种积温带：第四积温带<?php endif; ?>
	  <?php if ($this->_tpl_vars['data']['characters'] == '5'): ?>适种积温带：第五积温带<?php endif; ?>
	  <?php if ($this->_tpl_vars['data']['characters'] == '6'): ?>适种积温带：第六积温带<?php endif; ?>
	  </p>
          <!--
          <p class="comment"><b>评  价：</b><i class="star02"><i class="star01"></i></i><span>已有<em>23</em>个人评价，100%好评</span></p>
           -->
        </div>
        
        
       
				  <div class="buyBox">
			       	    <p>我要买<br>
			       	    <a onclick="selNum('less')" href="javascript:;">-</a>
					     <input type="text" name="buy_number" id="buy_number"  value="1" class="text" onblur="if(this.value><?php echo $this->_tpl_vars['data']['limit_number']; ?>
 || this.value<1){this.value=this.defaultValue;}getPrice(<?php echo $this->_tpl_vars['id']; ?>
,this.value)" onkeyup="this.value=this.value.replace(/\D/g,'');" onafterpaste="this.value=this.value.replace(/\D/g,'');"/>
					    <a onclick="selNum('add')"  href="javascript:;">+</a>
					    &nbsp;	
					    							
			       	    <span>赠送<?php echo smarty_function_math(array('equation' => "x * y",'x' => 1,'y' => $this->_tpl_vars['data']['price']), $this);?>
积分</span></p>
			       	     <?php if ($this->_tpl_vars['data']['onsale'] == 0 && $this->_tpl_vars['stock_number'] > 0): ?>
			            <div class="link"><a href="javascript:void(0);" onclick="addToCart('<?php echo $this->_tpl_vars['data']['goods_sn']; ?>
','buy_now','<?php echo $this->_tpl_vars['data']['characters']; ?>
')" ><img width="101" height="35" src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/shop/btn_buy.jpg"></a>
			            <a href="javascript:void(0);" onclick="addToCart('<?php echo $this->_tpl_vars['data']['goods_sn']; ?>
','buy_cart','<?php echo $this->_tpl_vars['data']['characters']; ?>
')" ><img width="130" height="35" src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/shop/btn_cart.jpg"></a>
			            <a href="javascript:void(0);" onclick="favGoods(this,'<?php echo $this->_tpl_vars['data']['goods_id']; ?>
')" ><img width="80" height="26" src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/shop/btn_collect.jpg"></a></div>
			             <?php elseif ($this->_tpl_vars['data']['onsale'] == 0 && $this->_tpl_vars['stock_number'] < 1): ?>
			              <a href="javascript:void(0);" onclick="goods_notice(this,'<?php echo $this->_tpl_vars['data']['goods_id']; ?>
')" ><img width="121" height="35" src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/shop/btn_notice.jpg"></a>
			           
			           <div id="goods_notice_box" style="display:none">
			            <form action="/goods/send-notice/" method="post" onsubmit="return check_notice()" id="frm_notice">
						  <p>
						  	&nbsp;邮箱/手机号码<br><input type="text" name="account" id="account"><br>
						    <input type="hidden" name="goods_id" value="<?php echo $this->_tpl_vars['data']['goods_id']; ?>
">
						  	<a href="javascript:;" onclick="$('#frm_notice').submit();"><img width="155" height="36" src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/shop/btn_take.jpg"></a>
						  </p>
						  <div class="note">
						  	*用户输入邮箱（或手机号）即可订阅<br>*商品到货后自动发送邮件（或短信）给顾客<br>*仅触发一次，发送后自动失效
						  </div>
						   </form>
						</div>     
						 <?php elseif ($this->_tpl_vars['data']['onsale'] == 1): ?>
						    <b>商品已下架</b>
			            <?php endif; ?>			             
			        </div>
		<script>
		function addToCart(goods_sn,buy_type,characters){
		    if(characters != '0' && characters != ''){
			switch(characters){
			    case '1':str = '一';
			    break;
			    case '2':str='二';
			    break;
			    case '3':str='三';
			    break;
			    case '4':str = '四';
			    break;
			    case '5':str = '五';
			    break;
			    case '6':str = '六';
			    break;
			}
			if(confirm('您当前的商品适种于第'+str+'积温带')){
				addCart(goods_sn,buy_type);
			}
		    }else{
			addCart(goods_sn,buy_type);
		    }
		}
		</script>
		        
        <div class="share">分享到：<img width="184" height="31" border="0" usemap="#Map" src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/shop/share.jpg">
          <map id="Map" name="Map">
            <area href="javascript:window.open('http://v.t.sina.com.cn/share/share.php?title='+encodeURIComponent(document.title)+'&url='+encodeURIComponent(window.location.href)+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" class="t-sina" title="分享到新浪微博" coords="5,5,27,24" shape="rect">
            <area href="javascript:(function(){window.open('http://v.t.qq.com/share/share.php?title='+encodeURIComponent(document.title)+'&url='+encodeURIComponent(window.location.href)+'&source=bookmark','_blank','width=610,height=350');})()" class="t-qq" title="分享到QQ微博" coords="38,5,58,24" shape="rect">
            <area href="javascript:window.open('http://www.kaixin001.com/repaste/share.php?rtitle='+encodeURIComponent(document.title)+'&rurl='+encodeURIComponent(window.location.href)+'&rcontent=看中一个好东东，很好看，是垦丰电商的'+encodeURIComponent(document.title)+' 亲爱的您也看下吧'+encodeURIComponent(location.href),'_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" class="kaixin" title="分享到开心网" coords="69,6,87,25" shape="rect">
            <area href="javascript:window.open('http://cang.baidu.com/do/add?it='+encodeURIComponent(document.title)+'&iu='+encodeURIComponent(window.location.href)+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到百度收藏" coords="99,7,117,25" shape="rect">
            <area href="javascript:window.open('http://bai.sohu.com/share/blank/addbutton.do?title='+encodeURIComponent(document.title)+'&link='+encodeURIComponent(window.location.href)+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到搜狐白社会" coords="128,5,147,25" shape="rect">
            <area href="javascript:window.open('http://www.douban.com/recommend?title='+encodeURIComponent(document.title)+'&link='+encodeURIComponent(window.location.href)+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到豆瓣" coords="158,5,174,24" shape="rect">
          </map>
        </div>
        
    </div>
</div>




<div class="main goods mar">

		
		
		<div style="padding-top: 30px;">
			<div class="goods_right list_side">
				<?php if ($this->_tpl_vars['buy_relation']): ?>
				<div class="mod">
					<h2 class="stitle2">浏览本目录的顾客购买过</h2>
					<div class="conts">
						<ul class="prolist">
							<?php $_from = $this->_tpl_vars['buy_relation']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
							<li>
								<div class="pics">
									<div class="wh160 verticalPic">
										<a href="/b-<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['as_name'])) ? $this->_run_mod_handler('default', true, $_tmp, 'jiankang') : smarty_modifier_default($_tmp, 'jiankang')); ?>
/detail<?php echo $this->_tpl_vars['v']['goods_id']; ?>
.html" title="<?php echo $this->_tpl_vars['v']['goods_name']; ?>
"><img alt="<?php echo $this->_tpl_vars['v']['goods_name']; ?>
"
										src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['v']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_380_380.') : smarty_modifier_replace($_tmp, '.', '_380_380.')); ?>
" height="160" width="160" /></a>
									</div>
								</div>
								<div class="txt">
									<a href="/b-<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['as_name'])) ? $this->_run_mod_handler('default', true, $_tmp, 'jiankang') : smarty_modifier_default($_tmp, 'jiankang')); ?>
/detail<?php echo $this->_tpl_vars['v']['goods_id']; ?>
.html" title="<?php echo $this->_tpl_vars['v']['goods_name']; ?>
"><?php echo $this->_tpl_vars['v']['goods_name']; ?>
</a>
								</div>
								<div class="Sprice">
									¥<span><?php echo $this->_tpl_vars['v']['price']; ?>
</span>
								</div>
							</li>
							<?php endforeach; endif; unset($_from); ?>
						</ul>
					</div>
				</div>
				<?php endif; ?>

				<?php if ($this->_tpl_vars['view_relation']): ?>
				<div class="mod">
					<h2 class="stitle2">浏览本目录的顾客浏览过</h2>
					<div class="conts">
						<ul class="prolist">
							<?php $_from = $this->_tpl_vars['view_relation']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
							<li>
								<div class="pics">
									<div class="wh160 verticalPic">
										<a href="/b-<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['as_name'])) ? $this->_run_mod_handler('default', true, $_tmp, 'jiankang') : smarty_modifier_default($_tmp, 'jiankang')); ?>
/detail<?php echo $this->_tpl_vars['v']['goods_id']; ?>
.html" title="<?php echo $this->_tpl_vars['v']['goods_name']; ?>
"><img alt="<?php echo $this->_tpl_vars['v']['goods_name']; ?>
"
										src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['v']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_180_180.') : smarty_modifier_replace($_tmp, '.', '_180_180.')); ?>
" height="160" width="160" /></a>
									</div>
								</div>
								<div class="txt">
									<a href="/b-<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['as_name'])) ? $this->_run_mod_handler('default', true, $_tmp, 'jiankang') : smarty_modifier_default($_tmp, 'jiankang')); ?>
/detail<?php echo $this->_tpl_vars['v']['goods_id']; ?>
.html" title="<?php echo $this->_tpl_vars['v']['goods_name']; ?>
"><?php echo $this->_tpl_vars['v']['goods_name']; ?>
</a>
								</div>
								<div class="Sprice">
									¥<span><?php echo $this->_tpl_vars['v']['price']; ?>
</span>
								</div>
							</li>
							<?php endforeach; endif; unset($_from); ?>
						</ul>
					</div>
				</div>
				<?php endif; ?>

				<?php if ($this->_tpl_vars['similar_relation']): ?>
				<div class="mod">
					<h2 class="stitle2">同类商品推荐</h2>
					<div class="conts">
						<ul class="prolist">
							<?php $_from = $this->_tpl_vars['similar_relation']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
							<li>
								<div class="pics">
									<div class="wh160 verticalPic">
										<a href="/b-<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['as_name'])) ? $this->_run_mod_handler('default', true, $_tmp, 'jiankang') : smarty_modifier_default($_tmp, 'jiankang')); ?>
/detail<?php echo $this->_tpl_vars['v']['goods_id']; ?>
.html" title="<?php echo $this->_tpl_vars['v']['goods_name']; ?>
"><img alt="<?php echo $this->_tpl_vars['v']['goods_name']; ?>
"
										src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['v']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_180_180.') : smarty_modifier_replace($_tmp, '.', '_180_180.')); ?>
" height="160" width="160" /></a>
									</div>
								</div>
								<div class="txt">
									<a href="/b-<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['as_name'])) ? $this->_run_mod_handler('default', true, $_tmp, 'jiankang') : smarty_modifier_default($_tmp, 'jiankang')); ?>
/detail<?php echo $this->_tpl_vars['v']['goods_id']; ?>
.html" title="<?php echo $this->_tpl_vars['v']['goods_name']; ?>
"><?php echo $this->_tpl_vars['v']['goods_name']; ?>
</a>
								</div>
								<div class="Sprice">
									¥<span><?php echo $this->_tpl_vars['v']['price']; ?>
</span>
								</div>
							</li>
							<?php endforeach; endif; unset($_from); ?>
						</ul>
					</div>
				</div>
				<?php endif; ?>

				<div class="mod">
					<h2 class="stitle2"><a onclick="clearCook('/clearhistory.html',this);" href="javascript:void(0);">清空</a>历史浏览记录</h2>
					<div class="conts" id="historyBox">
						
					</div>
				</div>
				
			<div class="mod">
			 <?php echo smarty_function_widget(array('class' => 'AdvertWidget','id' => '16'), $this);?>

			</div>
								
			</div>

			<div class="goods_left mains">
			    <?php if ($this->_tpl_vars['groupGoodsAData']): ?>
			       <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'goods/inc-show-pref-gg.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				<?php endif; ?>
				
				<?php if ($this->_tpl_vars['links']): ?>
				     <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'goods/inc-show-prmt-gg.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				<?php endif; ?>
			
				<ul class="title"  id="ttt">
					<li class="selected">
						<a href="javascript:void(0);">商品详情</a>
					</li>
					<li><a href="javascript:void(0);">商品说明书</a></li>
					<li>
						<a href="javascript:void(0);">商品评论</a>
					</li>
					<li>
						<a href="javascript:void(0);">商品咨询</a>
					</li>
					<li>
						<a href="javascript:void(0);">品牌介绍</a>
					</li>
					<li>
						<a href="javascript:void(0);">关于垦丰</a>
					</li>
					<li  style="margin-right: 0px;float: right;">
						<a href="javascript:void(0);">服务说明</a>
					</li>
					<!--
					<li  class="go_cart" style="margin-right: 0px;float: right;">
						<a class="add-cart"  href="javascript:void(0);" onclick="addCart('<?php echo $this->_tpl_vars['data']['goods_sn']; ?>
');">加入购物车</a>
					</li>
					-->
					<div style="clear:both;"></div>
				</ul>
				
				<div id="goods-detial-tab" class="goods-detial-tab">
				
				<div class="tab-c">
					<div style="height: 10px;overflow: hidden;"></div>
					<h2 class="dtitle"><span>商品详情</span></h2>
					<dl>
						<dd style="text-align:left">
				
						  <?php if ($this->_tpl_vars['id'] == '639'): ?>
						
							  <a href="http://www.1jiankang.com/groupgoods-66.html" target="_blank"><img id="taozu01" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/special/mk0717/taozu01.jpg" width="245" height="279" alt="" /></a> 
							  <a href="http://www.1jiankang.com/groupgoods-62.html" target="_blank"><img id="taozu02" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/special/mk0717/taozu02.jpg" width="245" height="279" alt="" /></a> 
							  <a href="http://www.1jiankang.com/groupgoods-63.html" target="_blank"><img id="taozu03" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/special/mk0717/taozu03.jpg" width="245" height="279" alt="" /></a> 
							 
						 <?php endif; ?>
							
							<?php echo $this->_tpl_vars['data']['description']; ?>

							
							<?php if (! empty ( $this->_tpl_vars['img_ext_url'] )): ?>
							<?php $_from = $this->_tpl_vars['img_ext_url']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['r']):
?>
							<img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['r']['img_url']; ?>
" title="<?php echo $this->_tpl_vars['r']['img_desc']; ?>
" width="650px" alt="<?php echo $this->_tpl_vars['data']['goods_name']; ?>
">
							<br/>
							<?php endforeach; endif; unset($_from); ?>
							<?php endif; ?>
							<?php if (! empty ( $this->_tpl_vars['data']['goods_package'] )): ?>
							<img src="<?php echo $this->_tpl_vars['data']['goods_package']; ?>
" alt="<?php echo $this->_tpl_vars['data']['goods_name']; ?>
">
							<?php endif; ?>
						</dd>				
					</dl>

				</div>
				
				<div class="tab-c">
					<div style="height: 10px;overflow: hidden;"></div>
					<h2 class="dtitle"><span>商品说明书</span></h2>
					<?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['introduction'])) ? $this->_run_mod_handler('default', true, $_tmp, '内容还在完善中，抱歉！') : smarty_modifier_default($_tmp, '内容还在完善中，抱歉！')); ?>

				</div>

				<div class="tab-c">
					<div style="height: 10px;overflow: hidden;"></div>
					<h2 class="dtitle"><span>商品评论</span></h2>
					<div id="comment_list"></div>
				</div>
				
				<!--新加商品咨询-->
				<div class="tab-c">
					<div style="height: 10px;overflow: hidden;"></div>
					<h2 class="dtitle"><span>商品咨询</span></h2>
					<div class="goods_comment ask"  id="consultation_list"></div>
				</div>
				<!--新加商品咨询 end-->

				<div class="tab-c">
					<div style="height: 10px;overflow: hidden;"></div>
					<h2 class="dtitle"><span>品牌介绍</span></h2>
					<div style="padding: 10px 0px;">
						<?php echo $this->_tpl_vars['data']['brand']['brand_desc']; ?>

					</div>
				</div>

				<div class="tab-c">
					<div style="height: 10px;overflow: hidden;"></div>
					<h2 class="dtitle"><span>关于垦丰</span></h2>
					北大荒垦丰种业股份有限公司是集研发、生产、加工、销售、服务及进出口业务于一体，具有完整产业链、多作物经营的现代化大型综合性种业公司。位居中国种业信用明星企业第三位，是中国种子行业首批AAA级信用企业，首批育繁推一体化企业，全国守合同重信用企业，黑龙江省高新技术企业。<br>
    公司注册资本3.3270亿元，主要经营玉米、甜菜、水稻、大豆、麦类等农作物种子，年生产、加工能力超过30万吨 ，2012年销售额汇总合计21.29亿元。拥有农业部颁发的全国种子经营许可证和进出口经营许可权，已通过ISO9001:2008国际质量体系认证，&ldquo;垦丰&rdquo;商标被认定为黑龙江省著名商标，正在申报中国驰名商标。<br>
    <strong>在研发创新方面，</strong>公司全面构建商业化育种体系，向跨国种业企业的商业化育种体系看齐，实行首席育种家制度，逐步实现育种团队化、规模化、机械化、信息化、流程化、自动化。整合现有育种力量和资源，充分利用公益性研究成果，按照市场化、产业化育种模式开展品种研发，建立以市场需求为导向的育种新机制。完善研发管理信息化平台建设，建立了研发中心→研发分中心（育种站）→测试站的组织机构框架。公司现已完成1个研发中心、2个研发分中心、12个育种站和黑龙江省内26个品种测试点建设。未来五年，将在全国内建设25个育种站和150个以上测试站。<br>
    公司现拥有专职科研人员290人,研发投入占产品总销售额5%以上，其中杂交作物研发投入占其销售额8%以上。并与德国KWS公司等国际跨国公司保持和推进紧密的交流与合作，与国内著名科研院所及高等院校建立了全面紧密的科企合作关系。<br>
    <strong>在生产、加工及质量控制方面，</strong>公司科学规划种子生产优势区域布局，建设规模化、机械化、标准化、集约化玉米制种基地和自交作物生产基地。转变传统种子加工理念为种子优化理念，通过优化，提升种子生命活力、成苗率、抗病性等指标。建设现代化种子加工中心，引进国外先进的加工工艺和设备，推进种子加工全程自动化、机械化、数字化，采用国际先进的包衣技术和种衣剂配方，实现种子包衣标准化，确保公司品种100%包衣，种子加工技术达到国际先进水平。实现玉米等种子制种的种、管、收全程机械化管理，采用国内外先进的种子生产技术，确保制种数量和质量安全，实现种子全程可追溯，质量控制精细化。<br>
    <strong>在市场营销及服务方面，</strong>公司逐步完善市场营销体系，在实现黑龙江全垦区范围内直销直供的基础上，加强地方直营店建设，配套财务平台、物流平台和推广技术服务平台建设，以&ldquo;直营直供为主，渠道销售为辅&rdquo;的销售模式，建立、完善全国范围内的市场营销系统，实现产品在全国范围内主要粮食产区的直营直供。同时积极拓展国际市场，建立国际市场销售平台。不断提高对终端用户的服务能力和水平，把我们最好的产品最直接、最及时的交到种植户手中。<br>
    垦丰种业现拥有1470名员工，致力于&ldquo;科技与服务，创造美好生活&rdquo;的企业使命，通过优良品种及解决方案，为种植者创造价值，改善生活环境，提升生活品质！公司将继续秉承&ldquo;品行天下，质创未来&rdquo;的经营理念，努力打造 &ldquo;国内领先、国际知名&rdquo;的具有国际竞争力的大型企业集团，为振兴民族种业，保障国家粮食安全做出新的更大的贡献。
				</div>

				<div class="tab-c">
					<div style="height: 10px;overflow: hidden;"></div>
					<h2 class="dtitle"><span>服务说明</span></h2>
					<div style="padding-top: 20px;">
						<h2 style="font-size: 24px;color: #555;font-weight: bold;">关于包装</h2>
						<div style="padding-bottom: 10px;">所有纸箱，塑料袋等标有"垦丰种业"的外包装均由垦丰为您免费提供，我们会根据您所购商品的具体尺寸提供相应的完整包装，保证您所购商品在投递的过程中完好无损。</div>
						<h2 style="font-size: 24px;color: #555;font-weight: bold;">关于服务</h2>
						<div style="height: 10px;"></div>
						<div style="padding-top: 5px;">垦丰种业向您保证所售商品均为正品，我们旨在用线上的细心服务为您带来超越线下的购物体验和同质商品。为您提供全场包邮的服务，所有商品均由我们合作的物流公司代为投递，为保障物流质量和速度，我们已经与国内多家知名的物流公司建立合作，保证您所购商品顺利投递到您的手中。</div>
						<div>但由于种子自身的特殊性，根据国家相关的法律规定，一经售出非质量问题概不退货。如确有质量问题，我们会免费为您退换货。退换货有效期为七天，请您尽快确认，逾期恕不退换。</div>
					</div>
				</div>
				</div>
			</div>
			<div style="clear:both;"></div>
		</div>
		<!--re_left end-->

		<div class="cleardiv"></div>
	</div><!--main end-->
<script  type="text/javascript">

                 if (!window.navigator.cookieEnabled) alert('请打开您浏览器的Cookie，否则将影响您下单!');			
              			   
	    		    $('.jqzoom').jqzoom({ zoomType: 'standard',lens:true,zoomWidth:400,zoomHeight:400, preloadImages: false, alwaysOn:false});//放大镜                   
	    		   
	    		    function setBtnEnable(jqObj, enable, classname) {
	                    jqObj.removeClass(classname);
	                    if (!enable) jqObj.addClass(classname);
	                }

	                var nowIdx = 0;
	                var moving = false;
	                var pic_num  =$("#thumblist li").length
	                var avgWidth = 67;
	                $("#thumblist").width(avgWidth * pic_num);
	                function moveFn(direction) {
	                    if (moving) return;
	                    moving = true;
	                    if (direction > 0) {
	                        if (nowIdx >= pic_num - 5) return;
	                        nowIdx++;
	                    } else {
	                        if (nowIdx <= 0) return;
	                        nowIdx--;
	                    }
	                    $('#thumblist li').eq(nowIdx).mouseenter();
	                    setBtnEnable($('#pic_small_wrapper .btn_right'), nowIdx < pic_num - 5, 'btn_right_none');
	                    setBtnEnable($('#pic_small_wrapper .btn_left'), nowIdx > 0, 'btn_left_none');
	                    $('#list_smallpic').animate({
	                        scrollLeft: nowIdx * avgWidth
	                    }, 100, '', function() {
	                        moving = false;
	                    });
	                }

	                setBtnEnable($('#pic_small_wrapper .btn_right'), 0 < pic_num - 5, 'btn_right_none');
	                setBtnEnable($('#pic_small_wrapper .btn_left'), false, 'btn_left_none');
	                
	                $('#pic_small_wrapper .btn_left').click(function() {
	                    if ($(this).hasClass('btn_left_none')) {
	                        return;
	                    }
	                    moveFn(-1);
	                });
	    		    
	                $('#pic_small_wrapper .btn_right').click(function() {
	                    if ($(this).hasClass('btn_right_none')) {
	                        return;
	                    }
	                    moveFn(1);
	                });               
	    		
				 $(".activity dl").mouseenter(function() {
				 	$(this).children("dd").show();
				 }).mouseleave(function() {
				 	$(this).children("dd").hide();
				 });;
				 
				 $(".buyform").hover(function() {$(this).addClass("selected")}, function() {$(this).removeClass("selected")});
				var absTop=$('#ttt').offset().top-220;
				 $(".goods_left ul.title li:not(.go_cart)").each(function(index) {
					$(this).click(function() {
						$(window).scrollTop(absTop-100);
						$(this).siblings().removeClass("selected")
						$(this).addClass("selected")
						$(".goods_left .con").hide()
						$(".goods_left .con").eq(index).show()

						if (index > 0) {
							$('#goods-detial-tab').find('.tab-c').hide();
							$('#goods-detial-tab').find('.tab-c').eq(index).show();
						} else {
							$('#goods-detial-tab').find('.tab-c').show();
						}
					});
				
				});		
				
				$(".more_num ol li").hover(function() {$(this).addClass("selected")}, function() {$(this).removeClass("selected")});				
				
				getCommentList(<?php echo $this->_tpl_vars['id']; ?>
,1);
				getZxList(<?php echo $this->_tpl_vars['id']; ?>
);		
				setHistory(<?php echo $this->_tpl_vars['id']; ?>
);
					
				$(window).scroll(function () {
					var $body = $("body");					
			        /*判断窗体高度与竖向滚动位移大小相加 是否 超过内容页高度*/
			        if ($(window).scrollTop() > absTop) {$('#ttt').addClass('fixer'); }else{$('#ttt').removeClass('fixer');}
			    });		
                
				/*选择数量*/
				function selNum(flag){
				    	try{
							var number = $("#buy_number").val();
							if(flag == "add"){
							   if(Number(number) >= <?php echo $this->_tpl_vars['data']['limit_number']; ?>
) return;
							   number =  Number(number) + 1;
							}
							if(flag == "less"){
								if(Number(number)>1){
								    number = Number(number) - 1;
								}
							}						
							$("#buy_number").val(number);
							getPrice(<?php echo $this->_tpl_vars['id']; ?>
, number);
						 }catch(e){}
				}	
				
			   	function getPrice(id, number) {
					 if (($('#org_price1').length>0) || ($('#org_price2').length>0)) {
									$.ajax({
									url: '/goods/get-price/id/'+id+'/number/'+number+'/r/'+Math.random(),
									type: 'get',
									success:function(data){
									if (data) {
										if (data >= <?php echo $this->_tpl_vars['data']['price']; ?>
) {
										 data = <?php echo $this->_tpl_vars['data']['price']; ?>
;
										}
										var savePrice = <?php echo $this->_tpl_vars['data']['market_price']; ?>
 - data;
										 $('#current_price').html(data.toFixed(2));
										 $('#save_price').html( '为您节省' + Math.round(savePrice*100)/100 + '元');
										 $("#point").html('赠送' + Math.round(data * number * 100)/100 + '积分');
									}
								}
							 });
						}
			 }
		
</script>	
</div>