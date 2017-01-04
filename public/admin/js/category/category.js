var categoryList = function() {
  var categoryInit = function(){
    $('#nestable').nestable({
      "maxDepth":2
    }).on('change',function () {
      var list = window.JSON.stringify($('#nestable').nestable('serialize'));
      console.log(list);
      $.ajax({
        url:'/admin/category/orderable',
        data:{
          nestable:list
        },
        dataType:'json',
        success:function (response) {
          if (response.status) {
            layer.msg(response.message);
          }
        }
      });
    });
    var category = {
      box:'.categoryBox',
      createcategory:'.create_category',
      close:'.close-link',
      createForm:'#createBox',
      middleBox:'.middle-box',
      createButton:'.createButton',
    };
    /**
     * 添加分类
     * @author 晚黎
     * @date   2016-11-04T10:07:56+0800               [description]
     */
    $(category.box).on('click',category.createcategory,function () {
      $.ajax({
        url:'/admin/category/create',
        dataType:'html',
        success:function (htmlData) {
          $(category.middleBox).removeClass('fadeInRightBig').addClass('bounceOut').hide();
          $(category.box).append(htmlData);
        }
      });
    });

    $(category.box).on('click',category.close,function () {
      $('.formBox').removeClass('bounceIn').addClass('bounceOut').remove();
      $(category.middleBox).removeClass('bounceOut').addClass('bounceIn').show();
    });

    $('.tooltips').tooltip();
    /**
     * 添加分类
     * @author 晚黎
     * @date   2016-11-04T16:12:58+0800
     */
    $(category.box).on('click','.createButton',function () {
      var l = $(this).ladda();
      var _item = $(this);
      var _form = $('#createForm');
      $.ajax({
        url:'/admin/category',
        type:'post',
        dataType: 'json',
        data:_form.serializeArray(),
        headers : {
          'X-CSRF-TOKEN': $("input[name='_token']").val()
        },
        beforeSend : function(){
          l.ladda( 'start' );
          _item.attr('disabled','true');
        },
        success:function (response) {
          layer.msg(response.message);
          setTimeout(function(){
            window.location.href = '/admin/category';
          }, 1000);
        }
      }).fail(function(response) {
        if(response.status == 422){
          var data = $.parseJSON(response.responseText);
          var layerStr = "";
          for(var i in data){
            layerStr += "<div>"+data[i]+"</div>";
          }
          layer.msg(layerStr);
        }
      }).always(function () {
        l.ladda('stop');
        _item.removeAttr('disabled');
      });;
    });
    /**
     * 修改分类表单
     * @author 晚黎
     * @date   2016-11-04T16:13:20+0800
     */
    $('#nestable').on('click','.editcategory',function () {
      var _item = $(this);
      $.ajax({
        url:_item.attr('data-href'),
        dataType:'html',
        success:function (htmlData) {
          var box = $(category.middleBox);
          if (box.is(':visible')) {
            $(category.middleBox).removeClass('fadeInRightBig').addClass('bounceOut').hide();
          }else{
            var _createForm = $('.formBox');
            // 创建表单存在的情况下
            if (_createForm.length > 0) {
              _createForm.removeClass('bounceIn').addClass('bounceOut').remove();
            }
          }
          $(category.box).append(htmlData);
        }
      });
    });
    /**
     * 修改分类数据
     * @author 晚黎
     * @date   2016-11-04T16:51:00+0800
     */
    $(category.box).on('click','.editButton',function () {
      var l = $(this).ladda();
      var _item = $(this);
      var _form = $('#editForm');

      $.ajax({
        url:_form.attr('action'),
        type:'post',
        dataType: 'json',
        data:_form.serializeArray(),
        headers : {
          'X-CSRF-TOKEN': $("input[name='_token']").val()
        },
        beforeSend : function(){
          l.ladda( 'start' );
          _item.attr('disabled','true');
        },
        success:function (response) {
          layer.msg(response.message);
          setTimeout(function(){
            window.location.href = '/admin/category';
          }, 1000);
        }
      }).fail(function(response) {
        if(response.status == 422){
          var data = $.parseJSON(response.responseText);
          var layerStr = "";
          for(var i in data){
            layerStr += "<div>"+data[i]+"</div>";
          }
          layer.msg(layerStr);
        }
      }).always(function () {
        l.ladda('stop');
        _item.removeAttr('disabled');
      });;
    });
    /**
     * 查看分类详细信息
     * @author 晚黎
     * @date   2016-11-04
     */
    $('#nestable').on('click','.showInfo',function () {
      var _item = $(this);
      $.ajax({
        url:_item.attr('data-href'),
        dataType:'html',
        success:function (htmlData) {
          var box = $(category.middleBox);
          if (box.is(':visible')) {
            $(category.middleBox).removeClass('fadeInRightBig').addClass('bounceOut').hide();
          }else{
            var _createForm = $('.formBox');
            // 创建表单存在的情况下
            if (_createForm.length > 0) {
              _createForm.removeClass('bounceIn').addClass('bounceOut').remove();
            }
          }
          $(category.box).append(htmlData);
        }
      });
    });
  };

  return {
    init : categoryInit
  }
}();
$(function () {
  categoryList.init();
});