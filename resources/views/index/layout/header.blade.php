<div class="topbar">

    <!-- LOGO -->
    <div class="topbar-left">
        <div class="text-center">
            <a href="" class="logo"><i class="icon-fire"></i><span> QL Phòng Mạch</span></a>
            <!--Image Logo here-->
            <!--<a href="index.html" class="logo">-->
            <!--<i class="icon-c-logo"> <img src="../assets/images/favicon.png" height="42"/> </i>-->
            <!--<span><img src="../assets/images/favicon.png" height="20"/></span>-->
            <!--</a>-->
        </div>
    </div>

    <!-- Button mobile view to collapse sidebar menu -->
    <div class="navbar navbar-default" role="navigation">
        <div class="container">
            <div class="">
                <div class="pull-left">
                    <button class="button-menu-mobile open-left waves-effect waves-light">
                        <i class="md md-menu"></i>
                    </button>
                    <span class="clearfix"></span>
                </div>

                <ul class="nav navbar-nav hidden-xs">
                    <li><a class="waves-effect waves-light">Số bệnh nhân có thể khám còn lại là: <strong style=" font-size: 1.25em">{{$sobnconlai}}</strong></a></li>
                    {{--<li><h3 style="font-weight: bold; color: #ff0e10;">{{$sobnconlai}}</h3></li>--}}
                </ul>

                <ul class="nav navbar-nav navbar-right pull-right">
                    {{--thong bao--}}
                    {{--end thong bao--}}
                    <li class="dropdown top-menu-item-xs">
                        <a href="" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown" aria-expanded="true"><img src="assets/images/avt.png" alt="user-img" class="img-circle"> </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{route('hoso.get')}}"><i class="ti-user m-r-10 text-custom"></i> Hồ sơ </a></li>
                            <li><a href="{{route('doimatkhau.get')}}"><i class="ti-settings m-r-10 text-custom"></i> Đổi mật khẩu </a></li>
                            <li class="divider"></li>
                            <li><a href="{{route('dangxuat.get')}}"><i class="ti-power-off m-r-10 text-danger"></i> Đăng xuất </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </div>
</div>