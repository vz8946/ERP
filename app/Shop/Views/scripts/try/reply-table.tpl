<div class="con" id="reply_area">
    {{foreach from=$reply_data item=reply}}
    <dl class="clear">
      <dt>{{$reply.user_name|truncate:15}}</dt>
        {{if $type == 1}}
          <dd>
            {{$reply.reason}}
            <div class="date">于 {{$reply.reply_time}} 提交申请理由</div>
          </dd>
        {{elseif $type == 2}}
          <dd class="clear">
            <h6>{{$reply.report_title}}</h6>
            <ul class="clear">
              <li class="other">综合评分：
                <span>
                  {{foreach from=$reply.score_array item=i}}
                    <img src="{{$imgBaseUrl}}/images/shop/try/star_orange.jpg">
                  {{/foreach}}
                  {{if $reply.score == 1}}很差
                  {{elseif $reply.score == 2}}比较差
                  {{elseif $reply.score == 3}}一般
                  {{elseif $reply.score == 4}}很好
                  {{elseif $reply.score == 5}}强烈推荐
                  {{/if}}
                </span>
              </li>
              <li>服务态度：
                {{foreach from=$reply.score1_array item=i}}
                  <img src="{{$imgBaseUrl}}/images/shop/try/star_orange.jpg" width="6px" height="6px">
                {{/foreach}}
              </li>
              <li>性价比 ：
                {{foreach from=$reply.score2_array item=i}}
                  <img src="{{$imgBaseUrl}}/images/shop/try/star_orange.jpg" width="6px" height="6px">
                {{/foreach}}
              </li>
              <li>包  装 ：
                {{foreach from=$reply.score3_array item=i}}
                  <img src="{{$imgBaseUrl}}/images/shop/try/star_orange.jpg" width="6px" height="6px">
                {{/foreach}}
              </li>
              <li>商品功能：
                {{foreach from=$reply.score4_array item=i}}
                  <img src="{{$imgBaseUrl}}/images/shop/try/star_orange.jpg" width="6px" height="6px">
                {{/foreach}}
              </li>
            </ul>
            <div class="describe">试用描述：
              <p>{{$reply.report}}</p>
            </div>
            <div class="date">于 {{$reply.reply_time}}提交报告</div>
          </dd>
        {{elseif $type == 3}}
          <dd>
            <h6 class="red">于 {{$reply.check_date}} 审核通过 </h6>
            <div class="describe">申请理由：
            <p>{{$reply.reason}}</p></div>
            {{if $user_count[$reply.user_id]}}
              <div class="see" ><a href="#" onclick="switchPage(2,{{$reply.user_id}});return false;">查看TA的试用报告</a></div>
            {{/if}}
          </dd>
        {{/if}}
    </dl>
    {{/foreach}}
    {{$pageNav}}
  </div>
  

