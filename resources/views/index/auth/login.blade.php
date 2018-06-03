<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="favicon.ico" sizes="16x16 32x32">
    <base href="{{asset('')}}">

    <title>Đăng nhập - Quản lý phòng mạch tư</title>

    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/core.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/components.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/pages.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/responsive.css" rel="stylesheet" type="text/css"/>

    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <script src="assets/js/modernizr.min.js"></script>

</head>
<body>

<div class="account-pages"></div>
<div class="clearfix"></div>
<div class="wrapper-page">
    <div class=" card-box">
        <div class="panel-heading">
            <h3 class="text-center"> Đăng nhập</h3>
            <h3 class="text-center"><a><strong class="text-custom">Quản Lý Phòng Mạch Tư</strong></a></h3>
        </div>

        @if (count($errors) > 0 || session('error'))
            <div class="alert alert-danger" role="alert">
                @foreach($errors->all() as $err)
                    {{$err}}<br/>
                @endforeach
                {{session('error')}}
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">
                {{session('success')}}
            </div>
        @endif

        <div class="panel-body">
            <form class="form-horizontal m-t-10" action="{{route('dangnhap.get')}}" method="post">
                {{csrf_field()}}
                <div class="form-group ">
                    <div class="col-xs-12">
                        <input class="form-control" type="email" name="email" required="" placeholder="Email"
                               value="{{ old('email') }}" autocomplete="off">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-12">
                        <input class="form-control" type="password" name="password" required="" placeholder="Mật khẩu"
                               autocomplete="off">
                    </div>
                </div>

                {{--<div class="form-group ">--}}
                {{--<div class="col-xs-12">--}}
                {{--<div class="checkbox checkbox-custom">--}}
                {{--<input id="checkbox-signup" type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>--}}
                {{--<label for="checkbox-signup">--}}
                {{--Nhớ mật khẩu--}}
                {{--</label>--}}
                {{--</div>--}}

                {{--</div>--}}
                {{--</div>--}}

                <div class="form-group text-center m-t-40">
                    <div class="col-xs-12">
                        <button class="btn btn-default btn-block text-uppercase waves-effect waves-light" type="submit">
                            Đăng nhập
                        </button>
                    </div>
                </div>

                {{--<div class="form-group m-t-30 m-b-0">--}}
                {{--<div class="col-sm-12">--}}
                {{--<a href="quenmatkhau" class="text-dark"><i class="fa fa-lock m-r-5"></i> Quên mật khẩu?</a>--}}
                {{--</div>--}}
                {{--</div>--}}
            </form>

        </div>
    </div>
    {{--<div class="row">--}}
    {{--<div class="col-sm-12 text-center">--}}
    {{--<p>Bạn chưa có tài khoản? <a href="dangki" class="text-custom m-l-5"><b>ĐĂNG KÍ</b></a></p>--}}

    {{--</div>--}}
    {{--</div>--}}

</div>


<script>
    var resizefunc = [];
</script>

<!-- jQuery  -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/detect.js"></script>
<script src="assets/js/fastclick.js"></script>
<script src="assets/js/jquery.slimscroll.js"></script>
<script src="assets/js/jquery.blockUI.js"></script>
<script src="assets/js/waves.js"></script>
<script src="assets/js/wow.min.js"></script>
<script src="assets/js/jquery.nicescroll.js"></script>
<script src="assets/js/jquery.scrollTo.min.js"></script>


<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>

</body>
</html>