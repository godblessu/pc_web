@php
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getTime;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\formatList;
    use function Zhiyi\Component\ZhiyiPlus\PlusComponentPc\getAvatar;
@endphp
@if (!$data->isEmpty())
<ul>
    @foreach ($data as $post)
    <li>
        <h3 class="m-tt">
            <a href="{{ route('pc:questionread', $post->id) }}"> {{ $post->subject }} </a>
            @if($post->excellent == 1 && !isset($post->excellent_show))
                <span class="u-exc">精</span>
            @endif
        </h3>
        @if ($post->answer)
        <div class="m-subtt">
            @if ($post->answer->anonymity && !($post->answer->user_id == $TS['id']))
                <img src="{{ asset('assets/pc/images/ico_anonymity_60.png') }}" width="24" height="24" />
                <span class="u-name">（匿名）</span>
            @else
                <div class="f-pr fl">
                    <img class="avatar lazy" data-original="{{ getAvatar($post->answer->user, 24) }}" width="24" height="24" />
                    @if ($post->answer->user->verified)
                        <img class="role" src="{{ $post->answer->user->verified['icon'] or asset('assets/pc/images/vip_icon.svg') }}">
                    @endif
                    <span class="u-name">{{ $post->answer->user->name or '（匿名）'}}</span>
                    @if(!$post->answer->user->tags->isEmpty())
                    <div class="m-tags">
                        <span class="u-ll">·</span>
                        @foreach ($post->answer->user->tags as $tag)
                            <span class="u-tag">{{$tag->name}}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
            @endif
            <span class="u-tm">{{ getTime($post->created_at) }}</span>
        </div>
        <div class="m-ct f-cb">
            @php preg_match('/\@\!\[(.*?)\]\((\d+)\)/i', $post->answer->body, $imgs); @endphp
            @if (count($imgs))
                <div class="m-img">
                    <img class="lazy" data-original="{{ $routes['storage'].$imgs[2] }}" width="200" height="90" />
                </div>
            @endif
            <div class="u-txt">
                @if(
                    !$post->answer->invited ||
                    !$post->look ||
                    (
                        $post->answer->invited &&
                        (
                            $post->answer->could ||
                            in_array($TS['id'], [$post->user_id,$post->answer->user_id])
                        )
                    )
                )
                    {!! str_limit(formatList($post->answer->body), 250, '...') !!}
                    <a class="u-more" href="{{ route('pc:answeread', $post->answer->id) }}">查看详情</a>
                @else
                    <span class="fuzzy" onclick="QA.look({{ $post->answer->id }}, '{{ sprintf("%.2f", $config['bootstrappers']['question:onlookers_amount'] * ($config['bootstrappers']['wallet:ratio'])) }}' , {{ $post->id }}, this)">
                        @php
                            for ($i = 0; $i < 250; $i ++) { echo 'T'; } @endphp
                    </span>
                @endif
            </div>
        </div>
        @endif
        <div class="m-tool">
            <span class="u-btn">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-follow"></use></svg>
                {{ $post->watchers_count }} 关注
            </span>
            <span class="u-btn">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-huida"></use></svg>
                {{ $post->answers_count }} 条回答
            </span>
            @if($post->amount)
            <span class="u-btn">
                <svg class="icon" aria-hidden="true"><use xlink:href="#icon-money"></use></svg>
                {{ $post->amount * ($config['bootstrappers']['wallet:ratio']) }}
            </span>
            @endif
        </div>
    </li>
    @endforeach
</ul>
@elseif(isset($search) && $search)
<script>
if (!$('.no_data_div').length) {
    var box = $('#content_list');
    box.append('<div class="no_data_div"><div class="no_data"><img src="{{ asset('assets/pc/images/pic_default_content.png') }}"><p><div class="search-button"><a href="{{ route('pc:createquestion') }}">去提问</a></div></div></div>');
}
</script>
@endif
