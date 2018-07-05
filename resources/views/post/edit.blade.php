@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Edit post</div>

                    <div class="panel-body">
                        <form action="{{action('PostController@update')}}" method="PUT">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                            <p>
                                <label for="title">Title:</label>
                                <input type="text" class="form-control" name="title" id="title" value="{{$post->title}}" required/>
                            </p>

                            <p>
                                <label for="body">Body:</label>
                                <textarea class="form-control" name="body" id="body" cols="30" rows="10">{{$post->body}}</textarea>
                            </p>

                            <hr/>

                            <p>
                                <button class="btn btn-success form-control">Edit Post</button>
                            </p>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection