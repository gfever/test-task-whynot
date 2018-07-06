@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @foreach ($posts as $post)
                    <div class="panel panel-default">
                        <div class="panel-heading"><a href="{{action('PostController@show', $post->id)}}">{{$post->title}}</a></div>
                        <div class="panel-body">
                            {{mb_substr(strip_tags($post->body), 0, 500)}}...
                        </div>
                        @if($page === 'user')
                            <div class="panel-footer">
                                @if(!empty($post->published))
                                    <span class="label label-success">Published</span>
                                @else
                                    <span class="label label-danger">Not published</span>
                                @endif
                            </div>
                        @endif
                        <div class="panel-footer">
                        @if($page === 'main' && \Auth::user()->isAdmin())
                            
                                <form action="{{action('PostController@publishToggle', $post->id)}}" method="post">
                                    {{ csrf_field() }}
                                    @if(!empty($post->published))
                                        <input type="submit" value="unpublish" class="btn btn-danger"/>
                                    @else
                                        <input type="submit" value="publish" class="btn btn-success"/>
                                    @endif
                                </form>

                                <form action="{{action('PostController@destroy', $post->id)}}" method="post">
                                    <input type="hidden" name="_method" value="DELETE"/>
                                    {{ csrf_field() }}
                                    <input type="submit" value="Delete" class="btn btn-danger"/>
                                </form>
                            
                        @endif
                            @if($page === 'user' || \Auth::user()->isAdmin())
                            <button onclick="location.href = '{{action('PostController@edit', $post->id)}}';" id="editButton" class="btn btn-success" >Edit</button>
                                @endif
                        </div>
                    </div>
                @endforeach
                {{$posts->links()}}
            </div>
        </div>
    </div>
@endsection