{{include file="news/inc-header.tpl"}}
    <div class="content">
    	<div class="news_recommend">
        	<div class="lunbo">
        		{{html type="wdt" id="news_focus"}}
            </div>
            <div class="zixun">
            	<table width="100%" border="1"  >
                  <tr>
            		{{foreach from=$list_index_tag1 item=v key=k}}
            		{{if $k>=0 and $k<=1}}
                    <td width="300"><a title="{{$v.title}}" target="_blank" href="/news-{{$v.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html">{{$v.title}}</a></td>
                    {{/if}}
	                 {{/foreach}}
                  </tr>
                  <tr>
            		{{foreach from=$list_index_tag1 item=v key=k}}
            		{{if $k>=2 and $k<=3}}
                    <td><a target="_blank" title="{{$v.title}}" href="/news-{{$v.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html">{{$v.title}}</a></td>
                    {{/if}}
	                {{/foreach}}
                  </tr>
                </table>
			  <div class="article fl">
               	<h2>{{$index_tag2.title}}</h2>
                <ul>
            		{{foreach from=$index_tag2.articles item=v key=k}}
            		{{if $k == 0}}
                	<li class="f">
                		<a title="{{$v.title}}" target="_blank" href="/news-{{$v.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html">
                			{{html type="img" src=$v.img_url w=285 h=130 alt=$v.title}}
                		</a></li>
                	{{else}}
                	<li><a title="{{$v.title}}" target="_blank" href="/news-{{$v.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html">{{$v.title}}</a></li>
                	{{/if}}
                  	{{/foreach}}
                </ul>
              </div>
              <div class="article fr">
               	<h2>{{$index_tag3.title}}</h2>
                <ul>
            		{{foreach from=$index_tag3.articles item=v key=k}}
            		{{if $k == 0}}
                	<li class="f"><a title="{{$v.title}}" target="_blank" href="/news-{{$v.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html">{{html type="img" src=$v.img_url w=285 h=130 alt=$v.title}}</a></li>
                	{{else}}
                	<li><a title="{{$v.title}}" title="{{$v.title}}" target="_blank" href="/news-{{$v.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html">{{$v.title}}</a></li>
                	{{/if}}
                  	{{/foreach}}
                </ul>
              </div>
            </div>
        </div>
      <div class="news_sort">
      	
      	
      		{{foreach from=$list_index_cat item=v key=k}}
       	  <div class="article_sort {{if $k%2 != 0}}mr0{{/if}}">
            	<div class="subnav">
                	<img src="/newstatic/images/{{$conf_cat_icon[$v.as_name]}}" width="176" height="33" />
                	<div class="link">
                		{{foreach from=$v.tags item=vv key=kk}}
                    	<a title="{{$vv.title}}" target="_blank" href="/chanel-{{$vv.name}}">{{$vv.title}}</a>&nbsp;|&nbsp;
                    	{{/foreach}}
                    	<a target="_blank" href="/news-{{$v.as_name}}">更多文章>></a>
                    </div>
               </div> 
                <div class="hot">
                	{{html type="img" src=$v.img_url w=175 h=175 alt=$v.title}}
                    <div class="fr" style="height: 175px;overflow: hidden;line-height: 21px;">
                    	<h2 style="padding-top: 12px;">
                    		<a title="{{$v.latest_article.0.title}}" target="_blank" href="/news-{{$v.latest_article.0.as_name}}/detail-{{$v.latest_article.0.article_id}}.html">{{$v.latest_article.0.title}}</a>
                    	</h2>
                        <div style="padding-top: 5px;line-height: 22px;color: #666;">
                        	{{$v.latest_article.0.abstract}}
                        </div>
                    </div>
                </div>
                <ul>
                	{{foreach from=$v.latest_article item=vv key=kk}}
                	{{if $kk != 0}}
                	<li><a title="{{$vv.title}}" target="_blank" href="/news-{{$vv.as_name|default:'jiankang'}}/detail-{{$vv.article_id}}.html">{{$vv.title}}</a></li>
                	{{/if}}
                	{{/foreach}}
                </ul>
            </div>
      		{{/foreach}}

      </div>
    </div>

{{include file="news/inc-footer.tpl"}}
