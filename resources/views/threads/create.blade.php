@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create a new Thread</div>

                <div class="card-body">
                      
                    <form method="post" action="/threads">
                        @csrf
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" value="{{old('title')}}">
                        </div>

                        <div class="form-group">
                            <label>Choose Channel</label>
                            <select name="channel_id" class="form-control">
                                <option value="">Choose One</option>
                                @foreach($channels as $channel)
                                <option value="{{$channel->id}}" {{old('channel_id') == $channel->id?'selected':''}}>{{$channel->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Body</label>
                            <textarea name="body" class="form-control" rows="8">
                                {{old('body')}}
                            </textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                    @if(count($errors)>0)
                     <div class="alert alert-danger" role="alert">
                        @foreach($errors->all() as $error)
                        <li>
                           {{$error}} 
                        </li>
                        @endforeach
                    </div>

                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
