<div class="topbar">

    <!-- LOGO -->
    <div class="topbar-left">
        <div class="text-center">
            <a href="" class="logo"><img src="favicon.ico" width="32px" height="32px"><span> QL Phòng Mạch</span></a>
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
                    <li><a class="waves-effect waves-light">Số bệnh nhân có thể khám còn lại là: <strong
                                    style=" font-size: 1.25em">{{$SoBNConLai}}</strong></a></li>
                    {{--<li><h3 style="font-weight: bold; color: #ff0e10;">{{$sobnconlai}}</h3></li>--}}
                </ul>

                <ul class="nav navbar-nav navbar-right pull-right">
                    {{--thong bao--}}
                    @if(count($CanhBaoThuoc) > 0)
                        <li class="dropdown top-menu-item-xs">
                            <a href="#" data-target="#" class="dropdown-toggle waves-effect waves-light"
                               data-toggle="dropdown" aria-expanded="true">
                                <i class="icon-bell"></i>
                                <span class="badge badge-xs badge-success">{{count($CanhBaoThuoc)}}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-lg">
                                <li class="notifi-title">
                                    {{--<span class="label label-default pull-right">Mới {{count($CanhBaoThuoc)}}</span>--}}
                                    Cảnh báo hết thuốc
                                </li>
                                <li class="list-group slimscroll-noti notification-list">

                                    <!-- list item-->
                                    @foreach($CanhBaoThuoc as $detail)
                                        <a class="list-group-item">
                                            <div class="media">
                                                <div class="pull-left p-r-10">
                                                    <em class="fa fa-bell-o noti-custom"></em>
                                                </div>
                                                <div class="media-body">
                                                    <h5 class="media-heading">Thuốc {{$detail->TenThuoc}}</h5>
                                                    <p class="m-0">
                                                        <small>
                                                            Số lượng còn lại:
                                                            <span class="text-custom font-600"> {{$detail->SoLuongConLai}} {{$detail->donvi->TenDonVi}} </span>
                                                        </small>
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach

                                </li>
                            </ul>
                        </li>
                    @endif
                    {{--end thong bao--}}
                    <li class="dropdown top-menu-item-xs">
                        <a href="" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown"
                           aria-expanded="true"><img src="assets/images/avt.png" alt="user-img" class="img-circle"> </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{route('hoso.get')}}"><i class="ti-user m-r-10 text-custom"></i> Hồ sơ </a>
                            </li>
                            <li><a href="{{route('doimatkhau.get')}}"><i class="ti-settings m-r-10 text-custom"></i> Đổi
                                    mật khẩu </a></li>
                            <li class="divider"></li>
                            <li><a href="{{route('dangxuat.get')}}"><i class="ti-power-off m-r-10 text-danger"></i> Đăng
                                    xuất </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </div>
</div>