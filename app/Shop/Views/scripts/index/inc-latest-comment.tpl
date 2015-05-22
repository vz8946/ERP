<div class="list_comment">
        <div class="title"><h2>最新评论</h2></div>
        <div id="comment-box" style="overflow:hidden;"> 
        <ul>
        {{foreach from=$comlist item=vo key=k}}
        <li>
        <dl {{if $k>0 && (($k+1)%5 eq 0)}}class="last"{{/if}}>
            <dt><a href="/b-{{$vo.as_name}}/detail{{$vo.goods_id}}.html" title="{{$vo.goods_name}}" target="_blank"><img width="60" height="60" src="{{$imgBaseUrl}}/{{$vo.goods_img|replace:'.':'_60_60.'}}"></a></dt>
            <dd><a href="/b-{{$vo.as_name}}/detail{{$vo.goods_id}}.html" title="{{$vo.goods_name}}" target="_blank">{{$vo.goods_name|cut_str:24:0:''}}</a></dd>
            <dd title="{{$vo.content}}">{{$vo.content|cut_str:56:''}}</dd>
        </dl>
        </li>
       {{/foreach}}
       </ul>
       </div>
 </div>
<script>
$(function(){
   $("#comment-box").Scroll({line:4,speed:500,timer:3000,up:"",down:""})
});
</script>