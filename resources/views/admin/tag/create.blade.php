@extends('layouts.admin')
@section('content')
@inject('tagPresenter','App\Presenters\Admin\TagPresenter')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>{!!trans('admin/tag.title')!!}</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('admin/dash')}}">{!!trans('admin/breadcrumb.home')!!}</a>
        </li>
        <li>
            <a href="{{url('admin/tag')}}">{!!trans('admin/breadcrumb.tag.list')!!}</a>
        </li>
        <li class="active">
            <strong>{!!trans('admin/breadcrumb.tag.create')!!}</strong>
        </li>
    </ol>
  </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>{!!trans('admin/tag.create')!!}</h5>
          <div class="ibox-tools">
              <a class="collapse-link">
                  <i class="fa fa-chevron-up"></i>
              </a>
              <a class="close-link">
                  <i class="fa fa-times"></i>
              </a>
          </div>
        </div>
        <div class="ibox-content">
          <form method="post" action="{{url('admin/tag')}}" class="form-horizontal">
            {{csrf_field()}}
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">{{trans('admin/tag.model.name')}}</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="name" value="{{old('name')}}" placeholder="{{trans('admin/tag.model.name')}}">
                @if ($errors->has('name'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('name') }}</span>
                @endif
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">{{trans('admin/tag.model.slug')}}</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="slug" value="{{old('slug')}}" placeholder="{{trans('admin/tag.model.slug')}}">
                @if ($errors->has('slug'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('slug') }}</span>
                @endif
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('icon') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">{{trans('admin/tag.model.icon')}}</label>
              <div class="col-sm-10">
                <input type="icon" class="form-control" name="icon" value="{{old('icon')}}" placeholder="{{trans('admin/tag.model.icon')}}">
                <span class="help-block m-b-none">更多图标请查看 <a href="http://fontawesome.io/icons/" target="_black">Font Awesome</a></span>
                @if ($errors->has('icon'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('icon') }}</span>
                @endif
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <div class="col-sm-4 col-sm-offset-2">
                  <a class="btn btn-white" href="{{url()->previous()}}">{!!trans('admin/action.actionButton.cancel')!!}</a>
                  <button class="btn btn-primary" type="submit">{!!trans('admin/action.actionButton.submit')!!}</button>
              </div>
            </div>
          </form>
        </div>
    </div>
    </div>
  </div>
</div>
@endsection
@section('js')
<script type="text/javascript" src="{{asset('admin/js/tag/tag.js')}}"></script>
@endsection