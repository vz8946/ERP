		
		<!-- 资讯  -->
		<div class="info">
			<dl>
				<dt>
					新闻报道
				</dt>
				<dd class="arrange">
					<div class="pic">
						<a href="{{$newsBaseUrl}}/{{$HXINWENBAODAOTU.0.asName}}/news-{{$HXINWENBAODAOTU.0.id}}.html" title="{{$HXINWENBAODAOTU.0.title}}" target="_blank"><img width="80" height="80" lazy_src="{{$imgBaseUrl}}/{{$HXINWENBAODAOTU.0.ztpic}}"></a>
					</div>
					<div class="con">
						<h4><a href="{{$newsBaseUrl}}/{{$HXINWENBAODAOTU.0.asName}}/news-{{$HXINWENBAODAOTU.0.id}}.html" title="{{$HXINWENBAODAOTU.0.title}}" target="_blank">{{$HXINWENBAODAOTU.0.title}}</a></h4>
						<p>
							{{$HXINWENBAODAOTU.0.seoDescription}}
						</p>
					</div>
				</dd>
				<dd class="infolist">
					<ul>
						{{foreach from=$HXINWENBAODAO item=vo}}
							<li>
								· <a href="{{$newsBaseUrl}}/{{$vo.asName}}/news-{{$vo.id}}.html" title="{{$vo.title}}" target="_blank">{{$vo.title}}</a>
							</li>
						{{/foreach}}
					</ul>
				</dd>
			</dl>
			<dl>
				<dt>
					行业报道
				</dt>
				{{if $HHANGYEBAODAO}}
				<dd class="news">
					<h3><a href="{{$newsBaseUrl}}/{{$HHANGYEBAODAO.0.asName}}/news-{{$HHANGYEBAODAO.0.id}}.html" title="{{$HHANGYEBAODAO.0.title}}" target="_blank">{{$HHANGYEBAODAO.0.title}}</a></h3>
					<p>
						{{$HHANGYEBAODAO.0.seoDescription}}
					</p>
				</dd>
				{{/if}}
				<dd class="infolist">
					<ul>
						{{foreach from=$HHANGYEBAODAO key=key item=vo}}
							{{if $key > 0}}
							<li>
								· <a href="{{$newsBaseUrl}}/{{$vo.asName}}/news-{{$vo.id}}.html" title="{{$vo.title}}" target="_blank">{{$vo.title}}</a>
							</li>
							{{/if}}
						{{/foreach}}
					</ul>
				</dd>
			</dl>
			<dl>
				<dt>
					垦丰资讯
				</dt>
				<dd class="infolist list2">
					<ul>
						{{foreach from=$HJIANKANGZIXUN key=key item=vo}}
							{{if $key eq 0}}
								<li>
									· <a href="{{$newsBaseUrl}}/{{$vo.asName}}/news-{{$vo.id}}.html" class="cur" title="{{$vo.title}}" target="_blank">{{$vo.title}}</a>
								</li>
							{{else}}
								<li>
									· <a href="{{$newsBaseUrl}}/{{$vo.asName}}/news-{{$vo.id}}.html" title="{{$vo.title}}" target="_blank">{{$vo.title}}</a>
								</li>
							{{/if}}
						{{/foreach}}
					</ul>
				</dd>
			</dl>
			<dl class="last fast-msg">
				<dt>
					垦丰快迅
				</dt>
				<dd>
					<ul>
						{{foreach from=$HGUOYAOZX item=vo}}
						<li>
							· <a href="{{$newsBaseUrl}}/{{$vo.asName}}/news-{{$vo.id}}.html" title="{{$vo.title}}" target="_blank">{{$vo.title|truncate:16:'...'}}</a>
						</li>
						{{/foreach}}
					</ul>
				</dd>
			</dl>
		</div>
		<!--end 资讯  -->
