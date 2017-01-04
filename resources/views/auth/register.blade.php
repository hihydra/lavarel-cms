<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <title>用户注册</title>
  <link rel="stylesheet" type="text/css" href="{{asset('admin/css/login.css')}}" />
  <style>
  body{height:100%;background:#16a085;overflow:hidden;}
  canvas{z-index:-1;position:absolute;}
  </style>
</head>
<body>
<form role="form" method="POST" action="{{ url('/register') }}">
  {{ csrf_field() }}
  <dl class="admin_login">
    <dt>
    <strong>用户注册</strong>
    <em>Management System</em>
    </dt>
    <dd class="user_icon">
    <input type="text" name="{{config('admin.global.username')}}" class="login_txtbx" />
    @if ($errors->has(config('admin.global.username')))
    <dt class="error">
      <em>{{ $errors->first(config('admin.global.username')) }}</em>
    </dt>
    @endif
    </dd>
    <dd class="pwd_icon">
    <input type="email" name="email" class="login_txtbx" />
    @if ($errors->has('email'))
    <dt class="error">
      <em>{{ $errors->first('email') }}</em>
    </dt>
    @endif
    </dd>
    <dd class="pwd_icon">
    <input type="password" name="password" class="login_txtbx" />
    @if ($errors->has('password'))
    <dt class="error">
      <em>{{ $errors->first('password') }}</em>
    </dt>
    @endif
    </dd>
    <dd class="pwd_icon">
    <input type="password" name="password_confirmation" class="login_txtbx" />
    @if ($errors->has('password_confirmation'))
    <dt class="error">
      <em>{{ $errors->first('password_confirmation') }}</em>
    </dt>
    @endif
    </dd>
    <dd>
    <input type="submit" value="立即注册" class="submit_btn"/>
    </dd>
    <dd>
    <a href="{{ url('/login') }}">返回登陆</a>
    </dd>
    <dd>
    <p>后台管理系统</p>
    </dd>
  </dl>
</form>
  <script src="{{asset('vendors/jquery/jquery-2.1.1.js')}}"></script>
  <script src="{{asset('vendors/Particleground.js')}}"></script>
  <script>
  $(document).ready(function() {
  //粒子背景特效
  $('body').particleground({
  dotColor: '#5cbdaa',
  lineColor: '#5cbdaa'
  });
  });
  </script>
</body>
</html>