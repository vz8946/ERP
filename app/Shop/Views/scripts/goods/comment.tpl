<div class="goods_comment">
	<div class="goods_comment_head">
		<ul>
			<li class="goods_comment_head_left">
				<ul>
					<li>
						好评度
					</li>
					<li>
						<span>{{$level_data_bf.level1}}</span>%
					</li>
				</ul>
			</li>
			<li class="goods_comment_head_center">
				<ul>
					<li>
						<dl>
							<dd class="goods_comment_head_center_dd">
								好评（{{$level_data.level1}}）
							</dd>
							<dd>
								<div class="comm-scrollbar-off">
									<div class="comm-scrollbar-on" style="width:{{$level_data_bf.level1}}%"></div>
								</div>
							</dd>
							<dd>
								{{$level_data_bf.level1}}%
							</dd>
						</dl>
					</li>
					<li>
						<dl>
							<dd class="goods_comment_head_center_dd">
								中评（{{$level_data.level2}}）
							</dd>
							<dd>
								<div class="comm-scrollbar-off">
									<div class="comm-scrollbar-on" style="width:{{$level_data_bf.level2}}%"></div>
								</div>
							</dd>
							<dd>
								{{$level_data_bf.level2}}%
							</dd>
						</dl>
					</li>
					<li>
						<dl>
							<dd class="goods_comment_head_center_dd">
								差评（{{$level_data.level3}}）
							</dd>
							<dd>
								<div class="comm-scrollbar-off">
									<div class="comm-scrollbar-on" style="width:{{$level_data_bf.level3}}%"></div>
								</div>
							</dd>
							<dd>
								{{$level_data_bf.level3}}%
							</dd>
						</dl>
					</li>
				</ul>
			</li>
			<li class="goods_comment_head_end">
				<ul>
					<li>
						<a href="#content" class="goods_comment_head_end_but">我要评论</a>
					</li>
					<li>
						<a href="javascript:getCommentListnew(0,1)" class="goods_comment_head_end_link" >查看所有评论</a>
					</li>
				</ul>
			</li>
		</ul>
	</div>
	<div class="goods_comment_title">
		<ul>
			<li>
				<a href="javascript:getCommentListnew(0,1)" class="goods_comment_title_one" >所有评论</a>
			</li>
			<li>
				<ul style="margin-top:4px;">
					<li>
						<a href="javascript:getCommentListnew(1,1)" class="goods_comment_title_list" >好评</a>
					</li>
					<li>
						<a href="javascript:getCommentListnew(2,1)" class="goods_comment_title_list">中评</a>
					</li>
					<li>
						<a href="javascript:getCommentListnew(3,1)" class="goods_comment_title_list" >差评</a>
					</li>
					<li class="goods_comment_title_list_end">
						<ul>
							<li style="width:168px;height: 27px;"></li>
							<li style="line-height: 27px;">
								<input type="checkbox" checked="checked" value="1"/>
							</li>
							<li style="margin-left:5px;">
								只显示有内容的评价
							</li>
						</ul>
					</li>
				</ul>
			</li>
		</ul>
	</div>
	<div class="goods_comment_conlist">
		{{if $datas}}
		{{foreach from=$datas item=item}}
		<div class="goods_comment_obj">
			<ul class="goods_comment_obj_firstul">
				<li class="goods_comment_obj_firstul_img"></li>
				<li class="goods_comment_obj_firstul_name">
					{{$item.user_name}}
				</li>
			</ul>
			<ul class="goods_comment_content">
				<li>
					<ul class="goods_comment_content_top">
						<li class="goods_comment_content_com">
							<ul>
								<li>
									评分
								</li>
								<li>
									<div class="comm-contentbar-off">
										<div class="comm-contentbar-on" style="width:{{math equation='(( x / y )* z )' x=$item.cnt2 y=5 z=100}}%"></div>
									</div>
								</li>
							</ul>
						</li>
						<li>
							{{$item.add_time}}
						</li>
					</ul>
				</li>
				<li>
					<ul class="goods_comment_content_detail">
						<li style="width: 553px;height: 42px;overflow: hidden;">
							<span style="color: #000;">评价:</span>
							{{$item.content}}
						</li>
					</ul>
				</li>
			</ul>
		</div>
		{{/foreach}}
		<ol style="width:740px;">
			{{$pageNav}}
		</ol>
		{{/if}}
	</div>

	<p class="sh" style="clear: both;">
		<form action="javascript:;" onsubmit="submitGoodsComment(this)" method="post" name="commentForm" id="commentForm">
			<input type="hidden" name="score" id="score">
			<input type="hidden" name="title" value="产品评论" />
			<input type="hidden" name="goods_id" value="{{$id}}" />
			<input type="hidden" name="goods_name" value="{{$goods.goods_name}}" />
			{{if !$login}}
			<div style="height: 30px;clear: both;overflow: hidden; font-weight:bold;">
				商品评论
			</div>
			<img id="star0_1" src="{{$imgBaseUrl}}/images/shop/try/star_gray.jpg" onmouseover="imgChange(0,1)" onclick="imgClick(0,1)">
			<img id="star0_2" src="{{$imgBaseUrl}}/images/shop/try/star_gray.jpg" onmouseover="imgChange(0,2)" onclick="imgClick(0,2)">
			<img id="star0_3" src="{{$imgBaseUrl}}/images/shop/try/star_gray.jpg" onmouseover="imgChange(0,3)" onclick="imgClick(0,3)">
			<img id="star0_4" src="{{$imgBaseUrl}}/images/shop/try/star_gray.jpg" onmouseover="imgChange(0,4)" onclick="imgClick(0,4)">
			<img id="star0_5" src="{{$imgBaseUrl}}/images/shop/try/star_gray.jpg" onmouseover="imgChange(0,5)" onclick="imgClick(0,5)">
			<span id="star_message"></span>

			<div class="grayborder" style="width:745px;height: 100px;">
				<textarea name="content" id="content" cols="" rows=""  style="width:710px;height: 80px;"></textarea>
			</div>			
			<div class="tj">
				<input name="" type="submit"  value="我要评论"/>
			</div>
			{{else}}
			<div style="height: 50px;text-align: center;padding-top:20px;border:1px solid #ddd;">
				<a style="color: #317EE7;" href="/login.html">登录</a>垦丰电商帐号对商品评论
			</div>
			{{/if}}

		</form>
	</p>
</div>

<script type="text/javascript">
	var isClick0 = false;
	var messages = Array('很差', '比较差', '一般', '很好', '强烈推荐');
	function imgChange(id, tag) {
		if ((id == 0) && isClick0)
			return false;

		if (id == 0)
			document.getElementById('score').value = tag;

		for ( i = 1; i <= 5; i++) {
			var star = document.getElementById('star' + id + '_' + i);
			star.src = "{{$imgBaseUrl}}/images/shop/try/star_gray.jpg";
		}
		for ( i = 1; i <= tag; i++) {
			var star = document.getElementById('star' + id + '_' + i);
			star.src = "{{$imgBaseUrl}}/images/shop/try/star_orange.jpg";
		}

		if (id == 0)
			document.getElementById('star_message').innerHTML = messages[tag - 1];
	}

	function imgClick(id, tag) {
		if (id == 0)
			document.getElementById('score').value = tag;

		for ( i = 1; i <= 5; i++) {
			var star = document.getElementById('star' + id + '_' + i);
			star.src = "{{$imgBaseUrl}}/images/shop/try/star_gray.jpg";
		}
		for ( i = 1; i <= tag; i++) {
			var star = document.getElementById('star' + id + '_' + i);
			star.src = "{{$imgBaseUrl}}/images/shop/try/star_orange.jpg";
		}

		if (id == 0)
			document.getElementById('star_message').innerHTML = messages[tag - 1];

		if (id == 0)
			isClick0 = true;
		else if (id == 1)
			isClick1 = true;
		else if (id == 2)
			isClick2 = true;
		else if (id == 3)
			isClick3 = true;
		else if (id == 4)
			isClick4 = true;
	}

	/**
	 * 提交评论信息
	 */
	var cmt_empty_content = "评论的内容不能小于2个字符";
	var cmt_large_content = "您输入的评论内容超过了250个字符";

	function submitGoodsComment() {
		var uname = $.trim($("#commentForm input[name='user_name']").val());
		var content = $.trim($("#content").val());

		if (content.length < 2) {
			alert(cmt_empty_content);
			return false;
		}
		if (content.length > 250) {
			alert(cmt_large_content);
			return false;
		}

		$.ajax({
			url : '/goods/msg',
			type : 'post',
			data : $('#commentForm').serialize(),
			success : function(msg) {
				if (msg != '') {
					alert(msg);
				} else {
					alert('您的评论已成功提交，请等待管理员审核');
					$("#content").val('');
				}
			},
			error : function(msg, err) {
				alert(err);
			}
		})

		return false;
	}

	function getCommentListnew(conf, page) {
		$.ajax({
			url : '/goods/comment',
			data : {
				id : '{{$id}}',
				conf : conf,
				page : page
			},
			type : 'get',
			success : function(msg) {
				$('#comment_list').html(msg);
			}
		})
	}
</script>