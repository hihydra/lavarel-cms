@extends('layouts.admin')
@section('css')
<link href="{{asset('vendors/bootstrap-fileinput/bootstrap-fileinput.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('vendors/editor/css/editormd.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('vendors/bootstrap-select/css/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('vendors/bootstrap-articlesinput/bootstrap-articlesinput.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>{!!trans('admin/article.title')!!}</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('admin/dash')}}">{!!trans('admin/breadcrumb.home')!!}</a>
        </li>
        <li>
            <a href="{{url('admin/article')}}">{!!trans('admin/breadcrumb.article.list')!!}</a>
        </li>
        <li class="active">
            <strong>{!!trans('admin/breadcrumb.article.create')!!}</strong>
        </li>
    </ol>
  </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>{!!trans('admin/article.create')!!}</h5>
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
          <form method="post" action="{{url('admin/article')}}" class="form-horizontal">
            {{csrf_field()}}
            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">{{trans('admin/article.model.title')}}</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="title" value="{{old('title')}}" placeholder="{{trans('admin/article.model.title')}}">
                @if ($errors->has('title'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('title') }}</span>
                @endif
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">{{trans('admin/article.model.category')}}</label>
              <div class="col-sm-10">
                <select class="bs-select form-control form-filter" data-live-search="true" data-show-subtext="true" name="category">
                    @if($categories)
                      @foreach($categories as $v)
                        <option value="{{$v['id']}}">{{$v['name']}}</option>
                        @if($v['child'])
                        @foreach($v['child'] as $val)
                          <option value="{{$val['id']}}">{{'|-- '.$val['name']}}</option>
                        @endforeach
                        @endif
                      @endforeach
                    @endif
                </select>
                @if ($errors->has('category'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('category') }}</span>
                @endif
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('img') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">{{trans('admin/article.model.img')}}</label>
              <div class="col-sm-10">
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                        <img src="{{asset('admin/img/no-image.png')}}" alt="" /> </div>
                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                    <div>
                        <span class="btn purple btn-file">
                            <span class="fileinput-new"> Select image </span>
                            <span class="fileinput-exists"> Change </span>
                            <input type="file" name="img"> </span>
                        <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                    </div>
                </div>
                @if ($errors->has('img'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('img') }}</span>
                @endif
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('intro') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">{{trans('admin/article.model.intro')}}</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="intro" value="{{old('intro')}}" placeholder="{{trans('admin/article.model.intro')}}">
                @if ($errors->has('intro'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('intro') }}</span>
                @endif
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">{{trans('admin/article.model.content')}}</label>
              <div class="col-sm-10">
                <div id="editor"><textarea style="display: none;">{!!old('editor-markdown-doc')!!}</textarea></div>
                @if ($errors->has('content'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('content') }}</span>
                @endif
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('tags') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">{{trans('admin/article.model.tags')}}</label>
              <div class="col-sm-10">
                <select class="bs-select form-control" name="tags" data-live-search="true" data-show-subtext="true" multiple="multiple" value="">
                    @if($tags)
                      @foreach($tags as $v)
                        <option value="{{$v['id']}}">{{$v['name']}}</option>
                      @endforeach
                    @endif
                </select>
                @if ($errors->has('tags'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('tags') }}</span>
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
<script src="{{asset('vendors/bootstrap-fileinput/bootstrap-fileinput.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/editor/js/editormd.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/bootstrap-select/js/bootstrap-select.min.js')}}" type="text/javascript" ></script>
<script src="{{asset('vendors/bootstrap-articlesinput/bootstrap-articlesinput.min.js')}}" type="text/javascript"></script>
<script>
  $(function() {
    var editor = editormd('editor',{
      width   : "100%",
      height  : 640,
      syncScrolling : "single",
      toolbarAutoFixed: false,
      gotoLine:false,
      emoji:true,
      saveHTMLToTextarea:true,
      path    : "{{asset('vendors/editor/lib')}}/",
      imageUpload : true,
      imageUploadURL : '/admin/article/upload'
    });

    $(".bs-select").selectpicker({
      iconBase: "fa",
      tickIcon: "fa-check"
    });
  });
</script>
@endsection