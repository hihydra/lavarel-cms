@inject('categoryPresenter','App\Presenters\Admin\categoryPresenter')
<div class="ibox float-e-margins animated bounceIn formBox" id="createBox">
  <div class="ibox-title">
    <h5>{{trans('admin/category.create')}}</h5>
    <div class="ibox-tools">
      <a class="close-link">
          <i class="fa fa-times"></i>
      </a>
    </div>
  </div>
  <div class="ibox-content">
    <div class="tabs-container">
      <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true">分类信息</a>
          </li>
          <li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="false">分类简介</a>
          </li>
      </ul>
      <form method="post" action="{{url('admin/category')}}" class="form-horizontal" id="createForm">
      {!!csrf_field()!!}
        <div class="tab-content">
              <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                      <div class="form-group">
                        <label class="col-sm-2 control-label">{{trans('admin/category.model.name')}}</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" placeholder="{{trans('admin/category.model.name')}}" name="name">
                        </div>
                      </div>
                      <div class="hr-line-dashed"></div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label">{{trans('admin/category.model.pid')}}</label>
                        <div class="col-sm-10">
                          <select class="form-control" name="pid">
                            {!!$categoryPresenter->topCategoryList($categorys)!!}
                          </select>
                        </div>
                      </div>
                      <div class="hr-line-dashed"></div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label">{{trans('admin/category.model.icon')}}</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" placeholder="{{trans('admin/category.model.icon')}}" name="icon">
                          <span class="help-block m-b-none">{!!trans('admin/category.moreIcon')!!}</span>
                        </div>
                      </div>
                      <div class="hr-line-dashed"></div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label">{{trans('admin/category.model.url')}}</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" placeholder="{{trans('admin/category.model.url')}}" name="url">
                        </div>
                      </div>
                      <div class="hr-line-dashed"></div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label">{{trans('admin/category.model.sort')}}</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" placeholder="{{trans('admin/category.model.sort')}}" name="sort">
                        </div>
                      </div>
                      <div class="hr-line-dashed"></div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label">{{trans('admin/category.model.type')}}</label>
                        <div class="col-sm-10">
                            <div class="i-checks checkbox-inline">
                                <label>
                                    <input type="radio" value="{{config('admin.global.categoryType.cover')}}" name="type">
                                    <i></i> {{trans('admin/category.type.cover')}}</label>
                            </div>
                            <div class="radio i-checks checkbox-inline">
                                <label>
                                    <input type="radio" value="{{config('admin.global.categoryType.column')}}" name="type" checked=""><i></i> {{trans('admin/category.type.column')}}</label>
                            </div>
                        </div>
                      </div>
                      <div class="hr-line-dashed"></div>
                      <div class="form-group">
                      	 <label class="col-sm-2 control-label">{{trans('admin/category.model.status')}}</label>
                       	 <div class="col-sm-10">
                             <input type="checkbox" class="js-switch" name="status" checked />
                         </div>
                      </div>
                </div>
              </div>
              <div id="tab-2" class="tab-pane">
                  <div class="panel-body">
                      <div id="editor"><textarea style="display: none;">{!!old('editor-markdown-doc')!!}</textarea></div>
                  </div>
              </div>
        </div>
        <div class="hr-line-dashed"></div>
          <div class="form-group">
              <div class="col-sm-4 col-sm-offset-1">
                <a class="btn btn-white close-link">{!!trans('admin/action.actionButton.close')!!}</a>
                <button class="btn btn-primary createButton ladda-button"  data-style="zoom-in">{!!trans('admin/action.actionButton.submit')!!}</button>
              </div>
        </div>
      </form>
    </div>
  </div>
</div>
<link href="{{asset('vendors/iCheck/custom.css')}}" rel="stylesheet" type="text/css" />
<script src="{{asset('vendors/iCheck/icheck.min.js')}}" type="text/javascript"></script>
<link href="{{asset('vendors/switchery/switchery.css')}}" rel="stylesheet" type="text/css" />
<script src="{{asset('vendors/switchery/switchery.js')}}" type="text/javascript"></script>
<link href="{{asset('vendors/editor/css/editormd.min.css')}}" rel="stylesheet" type="text/css" />
<script src="{{asset('vendors/editor/js/editormd.min.js')}}" type="text/javascript"></script>
<script type="text/javascript">
  $(function() {
    $(".i-checks").iCheck({
      checkboxClass:"icheckbox_square-green",
      radioClass:"iradio_square-green"
    });
    //ios7风格按钮
    new Switchery(document.querySelector(".js-switch"),{color:"#1AB394"});
    //编辑器
    var editor = editormd('editor',{
      width   : "100%",
      height  : 540,
      syncScrolling : "single",
      toolbarAutoFixed: false,
      gotoLine:false,
      emoji:true,
      path    : "{{asset('vendors/editor/lib')}}/",
      imageUpload : true,
      imageUploadURL : '/admin/article/upload'
    });
  });
</script>