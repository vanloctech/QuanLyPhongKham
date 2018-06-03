@extends('index.layout.index')
@section('title')
    <title>Trang chủ - Quản lý phòng mạch tư</title>
@endsection
@section('style')
    <link rel="stylesheet" href="assets/plugins/morris/morris.css">
    <style>
        .button-cus:hover h3{
            color: #7E57C2;
        }
    </style>
@endsection
@section('content')

    @if (count($errors) > 0 || session('error'))
        <div class="alert alert-danger" role="alert">
            <strong>Cảnh báo!</strong><br>
            @foreach($errors->all() as $err)
                {{$err}}<br/>
            @endforeach
            {{session('error')}}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            <strong>Thành công!</strong>
            <button type="button" class="close" data-dismiss="alert">×</button>
            <br/>
            {{session('success')}}
        </div>
    @endif
    <!--end duong dan nho-->
    {{--<div class="row">--}}
        {{--<div class="col-sm-12">--}}
            {{--<h4 class="page-title">Quản lý phòng mạch tư</h4>--}}
            {{--<p class="text-muted page-title-alt">Xin chào {{$user->name}}</p>--}}
        {{--</div>--}}
    {{--</div>--}}

    <div class="row">
        <div class="col-md-6 col-lg-3">
            <div class="widget-bg-color-icon card-box">
                <div class="bg-icon bg-icon-info pull-left">
                    <i class="md md-accessibility text-info"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-dark"><b class="counter">{{$tongBN}}</b></h3>
                    <p class="text-muted">Tổng bệnh nhân đã khám trong tháng</p>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="widget-bg-color-icon card-box">
                <div class="bg-icon bg-icon-custom pull-left">
                    <i class="md md-add-shopping-cart text-custom"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-dark"><b class="counter">{{$tongPK}}</b></h3>
                    <p class="text-muted">Tổng phiếu khám bệnh trong tháng</p>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="widget-bg-color-icon card-box">
                <div class="bg-icon bg-icon-warning pull-left">
                    <i class="md md-attach-money text-warning"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-dark"><b class="counter">{{number_format($tongTK)}}</b></h3>
                    <p class="text-muted">Tổng tiền khám trong tháng</p>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="widget-bg-color-icon card-box">
                <div class="bg-icon bg-icon-danger pull-left">
                    <i class="md md-attach-money text-danger"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-dark"><b class="counter">{{number_format($tongDT)}}</b></h3>
                    <p class="text-muted">Tổng doanh thu trong tháng</p>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <h4 class="text-dark header-title m-t-0">Doanh thu 7 ngày gần nhất</h4>

                <div class="row">
                    <div class="col-md-12" style="height: 350px">
                        <canvas id="myChart"></canvas>
                        {{--height="40vw" width="80vw"--}}
                    </div>
                </div>

                <!-- end row -->

            </div>

        </div>

    </div>

    <div class="row">

        <div class="col-lg-6">
            <div class="card-box">
                {{--<a href="#" class="pull-right btn btn-default btn-sm waves-effect waves-light">Xem tất cả</a>--}}
                <h4 class="text-dark header-title m-t-0">Top 3 thuốc có <b style="color: red">số lượng</b> sử dụng nhiều nhất</h4>

                <div class="table-responsive">
                    <table class="table table-actions-bar">
                        <thead>
                        <tr>
                            <th>Thuốc</th>
                            <th style="color: red">Số lượng dùng</th>
                            <th>Số lần dùng</th>
                            <th>Đơn vị</th>
                            {{--<th>Cách dùng</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($TopThuocSoLuong as $value)
                        <tr>
                            <td>{{$value->thuoc->TenThuoc}}</td>
                            <td style="color: red">{{$value->SoLuongDung}}</td>
                            <td>{{$value->SoLanDung}}</td>
                            <td>{{$value->thuoc->donvi->TenDonVi}}</td>
                            {{--<td>Uống</td>--}}
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <!-- col -->

        <div class="col-lg-6">
            <div class="card-box">
                {{--<a href="#" class="pull-right btn btn-default btn-sm waves-effect waves-light">Xem tất cả</a>--}}
                <h4 class="text-dark header-title m-t-0">Top 3 thuốc có <b style="color: red">số lần</b> sử dụng nhiều nhất</h4>

                <div class="table-responsive">
                    <table class="table table-actions-bar">
                        <thead>
                        <tr>
                            <th>Thuốc</th>
                            <th>Số lượng dùng</th>
                            <th style="color: red">Số lần dùng</th>
                            <th>Đơn vị</th>
                            {{--<th>Cách dùng</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($TopThuocSoLan as $thuoc)
                            <tr>
                                <td>{{$thuoc->thuoc->TenThuoc}}</td>
                                <td>{{$thuoc->SoLuongDung}}</td>
                                <td style="color: red">{{$thuoc->SoLanDung}}</td>
                                <td>{{$value->thuoc->donvi->TenDonVi}}</td>
                                {{--<td>Uống</td>--}}
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <!-- end col -->

    </div>

@endsection
@section('script-ori')
@endsection
@section('script')
    <script src="assets/plugins/waypoints/lib/jquery.waypoints.js"></script>
    <script src="assets/plugins/counterup/jquery.counterup.min.js"></script>
    <script src="assets/Chart.min.js"></script>
    <script type="text/javascript">
        var ctx = document.getElementById('myChart').getContext('2d');
        ctx.height = 200;
        var chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'line',

            // The data for our dataset
            data: {
                labels: [
                        @for($i = date('d')-7;$i< date('d');$i++)
                        "{{$i}}/{{date('m')}}",
                        @endfor
                ],
                datasets: [{
                    label: "Doanh thu (VND)",
                    borderColor: 'rgb(126, 87, 194)',
                    data: [
                        @for($i = date('d')-7;$i< date('d');$i++)
                        {{$doanhThu[$i]}},
                        @endfor
                    ],
                    fill: false,
                    pointBorderWidth: 5
                }]
            },

            // Configuration options go here
            options: {
                maintainAspectRatio: false,
                title: {
                    display: true,
                    text: 'Biểu đồ biểu thị doanh thu 7 ngày gần nhất của phòng mạch'
                }
            }
        });
        jQuery(document).ready(function($) {
            $('.counter').counterUp({
                delay: 100,
                time: 1000
            });

        });
    </script>
@endsection