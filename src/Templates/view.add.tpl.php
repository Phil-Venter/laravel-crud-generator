@extends('[[custom_master]]')

@section('content')


<h2 class="page-header">[[model_uc]]</h2>

<div class="panel panel-default">
    <div class="panel-heading">
        Add/Modify [[model_uc]]
    </div>

    <div class="panel-body">
        <form action="{{ url('/[[route_path]]'.( isset($model) ? "/" . $model->id : "")) }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            @if (isset($model))
            <input type="hidden" name="_method" value="PATCH">
            @endif

            [[foreach:columns]]
            [[if:i.type=='id']]
            <div class="form-group">
                <label for="[[i.name]]" class="col-sm-3 control-label">[[i.display]]</label>
                <div class="col-sm-6">
                    <input type="text" name="[[i.name]]" id="[[i.name]]" class="form-control" value="{{isset($model) ? $model['[[i.name]]']?? '' : ''}}" readonly="readonly">
                </div>
            </div>
            [[endif]]
            [[if:i.type=='string']]
            <div class="form-group">
                <label for="[[i.name]]" class="col-sm-3 control-label">[[i.display]]</label>
                <div class="col-sm-6">
                    <input type="text" name="[[i.name]]" id="[[i.name]]" class="form-control" value="{{isset($model) ? $model['[[i.name]]']?? '' : ''}}">
                </div>
            </div>
            [[endif]]
            [[if:i.type=='text']]
            <div class="form-group">
                <label for="[[i.name]]" class="col-sm-3 control-label">[[i.display]]</label>
                <div class="col-sm-3">
                    <textarea name="[[i.name]]" id="[[i.name]]" class="form-control" rows="5">{{isset($model) ? $model['[[i.name]]']?? '' : ''}}</textarea>
                </div>
            </div>
            [[endif]]
            [[if:i.type=='number']]
            <div class="form-group">
                <label for="[[i.name]]" class="col-sm-3 control-label">[[i.display]]</label>
                <div class="col-sm-2">
                    <input type="number" name="[[i.name]]" id="[[i.name]]" class="form-control" value="{{isset($model) ? $model['[[i.name]]']?? '' : ''}}">
                </div>
            </div>
            [[endif]]
            [[if:i.type=='date']]
            <div class="form-group">
                <label for="[[i.name]]" class="col-sm-3 control-label">[[i.display]]</label>
                <div class="col-sm-3">
                    <input type="date" name="[[i.name]]" id="[[i.name]]" class="form-control" value="{{isset($model) ? $model['[[i.name]]']?? '' : ''}}">
                </div>
            </div>
            [[endif]]
            [[if:i.type=='boolean']]
            <div class="col-sm-9 col-sm-offset-3">
                <label>
                    <input name="[[i.name]]" id="[[i.name]]" type="checkbox" {{ isset($model) ? $model['[[i.name]]'] ? 'checked' : '' : '' }}> [[i.display]]
                </label>
            </div>
            [[endif]]
            [[if:i.type=='unknown']]
            <div class="form-group">
                <label for="[[i.name]]" class="col-sm-3 control-label">[[i.display]]</label>
                <div class="col-sm-6">
                    <input type="text" name="[[i.name]]" id="[[i.name]]" class="form-control" value="{{isset($model) ? $model['[[i.name]]']?? '' : ''}}">
                </div>
            </div>
            [[endif]]
            [[endforeach]]

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <button type="submit" class="btn btn-success pull-right">
                        <i class="fa fa-plus"></i> Save
                    </button>
                    <a class="btn btn-default" href="{{ url('/[[route_path]]') }}"><i class="glyphicon glyphicon-chevron-left"></i> Back</a>
                </div>
            </div>
        </form>

    </div>
</div>

@endsection
