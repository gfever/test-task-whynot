@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create post</div>

                    <div class="panel-body">
                        <form action="{{action('PostController@store')}}" method="post">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                            <p>
                                <label for="title">Title:</label>
                                <input type="text" class="form-control" name="title" id="title" required/>
                            </p>

                            <p>
                                <label for="body">Body:</label>
                                <textarea class="form-control" name="body" id="body" cols="30" rows="10"></textarea>
                            </p>

                            <hr/>

                            <p>
                                <button class="btn btn-success form-control">Create Post</button>
                            </p>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection