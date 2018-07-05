@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @foreach ($posts as $post)
                    <div class="panel panel-default">
                        <div class="panel-heading"><a href="{{action('PostController@show', $post->id)}}">{{$post->title}}</a>Panel heading without title</div>
                        <div class="panel-body">
                            {{mb_substr($post->text, 0, 500)}}...
                        </div>
                    </div>
                @endforeach
                {{$posts->links()}}
            </div>
        </div>
    </div>
@endsection