@extends('front.template')
@section('title')
    <title>{!! config('blog.title') !!}</title>
@stop

@section('main')

    <h5>共 {!! $posts->total() !!} 篇</h5>
    <div class="divider"></div>
    <ul class="collapsible popout" data-collapsible="accordion" id="article">
        @foreach($posts as $post)
            <li>
                <div class="collapsible-header no-seen" id="post-{!! $post->id !!}" pageY="">
                    <h5 id="title">{!! $post->title !!}</h5>
                    <div class="label">published at {!! substr($post->created_at, 0, 10) !!}
                        <i class="material-icons">visibility</i>{!! $post->view_count !!}
                        <i class="material-icons">comment</i>{!! $post->comment_count !!}
                    </div>
                    <p class="summary">{!! $post->summary !!}</p>
                    <div class="row">
                        <div class="col s12 m12 l12">
                            @foreach($post->tags as $tag)
                                <div class="chip">{!! $tag->name !!}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
    {!! $posts->links() !!}
@stop

@section('script')
    <script>
        $('nav').addClass('blue');
        $('.top-tags > a').addClass('blue');
        $('.side-nav').addClass('blue');
        $('.collapsible-according').addClass('blue');

        $(document).on('click', '.no-seen', function () {
            var id = $(this).attr('id');
            var header = $('#' + id);
            var parentLi = $(header).parent();
            $(parentLi).append($(progressDiv));

            $.ajax({
                url: '{!! url('/body') !!}' + '/' + id.substr(5),
                type: 'get'
            }).done(function (data) {
                header.removeClass('no-seen').addClass('seen');
                $('#progressDiv').remove();
                parentLi.append('<div class="collapsible-body markdown-body">' + data.body + '</div>')
                        .children('.collapsible-body').slideDown(500);
            }).fail(function () {
                Materialize.toast('Fetch article body failed!', 3000);
                $('#progressDiv').remove();
                hideActiveHeader(header);
            });


        });
    </script>
@stop