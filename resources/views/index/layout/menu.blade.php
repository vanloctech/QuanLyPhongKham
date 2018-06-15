<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>

                {{--<li class="text-muted menu-title">Quản lý phòng mạch tư v1.0</li>--}}

                <li class="has_sub">
                    <a href="" class="waves-effect"><i class="ti-home"></i><span> Trang chủ </span></a>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-user"></i>
                        <span> Quản lý bệnh nhân </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('ds-benhnhan.get')}}"> Danh sách bệnh nhân </a></li>
                        <li><a href="{{route('them-benhnhan.get')}}"> Thêm bệnh nhân </a></li>
                        <li><a href="{{route('tracuu-benhnhan.get')}}"> Tra cứu bệnh nhân </a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-layers-alt"></i>
                        <span> Quản lý phiếu khám </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('ds-phieukham.get')}}"> Danh sách phiếu khám </a></li>
                        <li><a href="{{route('them-phieukham.get')}}"> Thêm phiếu khám </a></li>
                        <li><a href="{{route('ds-khambenh.get')}}"> Danh sách khám bệnh </a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-view-list"></i>
                        <span> Báo cáo </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('cronjobthem-bcdt.get')}}"> Lập báo cáo doanh thu </a></li>
                        <li><a href="{{route('bcdt.get')}}"> Báo cáo doanh thu </a></li>
                        <li><a href="{{route('bcsdt.get')}}"> Báo cáo sử dụng thuốc </a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-layers-alt"></i>
                        <span> Danh mục </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('ds-loaibenh.get')}}"> Quản lý loại bệnh </a></li>
                        <li><a href="{{route('ds-thuoc.get')}}"> Quản lý thuốc </a></li>
                        <li><a href="{{route('ds-donvi.get')}}"> Quản lý đơn vị </a></li>
                        <li><a href="{{route('ds-cachdung.get')}}"> Quản lý cách dùng </a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="{{route('quydinh.get')}}" class="waves-effect"><i class="ti-settings"></i><span> Quy định </span></a>
                </li>

                {{--<li class="has_sub">--}}
                    {{--<a href="{{route('ttpk.get')}}" class="waves-effect"><i class="ti-info"></i><span> Thông tin phòng khám </span></a>--}}
                {{--</li>--}}

                <li class="has_sub">
                    <a href="{{route('dangxuat.get')}}" class="waves-effect"><i class="ti-power-off"></i><span> Đăng xuất </span></a>
                </li>

                <li class="text-muted menu-title">Tác giả</li>

                <li class="has_sub">
                    <a href="{{route('aboutus.get')}}" class="waves-effect"><i class="ti-window"></i><span> Về chúng tôi </span></a>
                </li>

            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>