@extends('index.layout.index')
@section('title')
    <title>Tra cứu bệnh nhân - Quản lý phòng mạch tư</title>
@endsection
@section('style')
    <link href="assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/datatables/dataTables.colVis.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/datatables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/datatables/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet"/>

    <link href="assets/css/cssdate.css" rel="stylesheet" type="text/css">
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <ol class="breadcrumb">
                <li>
                    <a href=""><i class="ti-home"></i></a>
                </li>
                <li class="active">
                    Tra cứu bệnh nhân
                </li>
            </ol>
        </div>
    </div>

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
    <div class="row hidden-print">
        <div class="col-sm-12">
            <div class="card-box">
                <h4 class="m-t-0 header-title"><b>Tra cứu bệnh nhân</b></h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="p-l-r-10">

                            <div class="form-group">
                                <label class="control-label">Họ & tên bệnh nhân</label>
                                <input name="hoten" type="text" class="form-control" value="{{old('hoten')}}"
                                       placeholder="Họ & tên">
                            </div>


                            <div class="form-group">
                                <label class="control-label">Triệu chứng</label>
                                <input name="trieuchung" type="text" class="form-control" value="{{old('trieuchung')}}"
                                       placeholder="Triệu chứng">
                            </div>

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-l-r-10">
                            <div class="form-group date">
                                <label>Ngày khám</label><br>
                                <input class="input-small datepicker hasDatepicker" id="ngaykham" type="date"
                                       name="ngaykham"> &#160;
                                <button class="ladda-button btn btn-purple" data-style="expand-right" onclick="xoangay()"><i class="fa fa-refresh"></i>
                                </button>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Chuẩn đoán loại bệnh</label>
                                <select class="selectpicker" data-style="btn-default btn-custom" id="loaibenh"
                                        name="loaibenh">
                                    <option value="">--- Chọn loại bệnh ---</option>
                                    @foreach($dsloaibenh as $lb)
                                        <option value="{{ $lb->MaLoaiBenh }}"
                                                @if (old('loaibenh') == $lb->MaLoaiBenh) selected @endif>
                                            {{ $lb->TenLoaiBenh }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-5">
                        <div class="form-group">
                            <button class="ladda-button btn btn-default" data-style="expand-right" onclick="timkiem()">Tìm kiếm
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <h4 class="m-t-0 header-title"><b>Kết quả tra cứu bệnh nhân</b></h4>

                <div class="p-10">
                    <table class="table table-striped table-bordered m-0">
                        <thead>
                        <tr>
                            <th class="text-center">STT</th>
                            <th class="text-center">Họ tên</th>
                            <th class="text-center">Ngày Khám</th>
                            <th class="text-center">Loại bệnh</th>
                            <th class="text-center">Triệu chứng</th>
                            <th class="text-center hidden-print">Hành động</th>
                        </tr>
                        </thead>
                        <tbody style="text-align: center" id="tbodydskhambenh">
                        </tbody>
                    </table>
                </div>
                <div class="hidden-print p-t-10">
                    <div class="pull-right">
                        <a href="javascript:window.print()" class="btn btn-inverse waves-effect waves-light"><i
                                    class="fa fa-print"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script-ori')
@endsection
@section('script')
    <script>
        function del(id) {
            $.confirm({
                text: "Hành động này sẽ xóa dữ liệu của phiếu khám bệnh này <br/><a style='color: red'>(bao gồm đơn thuốc và hóa đơn)</a>.<br/>Bạn có chắc muốn xóa không?",
                title: "Xác nhận xóa",
                confirmButton: "Có, hãy xóa",
                cancelButton: "Không, đừng xóa",
                post: false,
                submitForm: false,
                confirmButtonClass: "btn-danger",
                cancelButtonClass: "btn-default",
                dialogClass: "modal-dialog",
                confirm: function (button) {
                    window.location.assign("phieukham/xoa/" + id);
                },
                cancel: function (button) {
                }
            });
        }
    </script>
    <script>
        function xoangay() {
            $("#ngaykham").val("");
        }

        function randloading() {
            var rand;
            var myArray = ['giphy.gif', 'loading.gif', 'loading1.gif', 'loading2.gif', 'loading3.gif'];
            return rand = myArray[Math.floor(Math.random() * myArray.length)];
        }

        function timkiem() {
            var hoten = $("input[name='hoten']").val();
            var ngay = $("input[name='ngaykham']").val();
            var trieuchung = $("input[name='trieuchung']").val();
            var loaibenh = $('select[name=loaibenh]').val();
            var token = $("input[name='_token']").val();
//            alert(value);
//            alert(loaibenh);
            if (hoten == "" && trieuchung == "" && loaibenh == "" && ngay == "") {
                alert("Bạn chưa nhập từ khóa cần tìm.");
            }
            else {
                $.ajax({
                    url: "{{route('ajax-tracuu-benhnhan.get')}}",
                    type: "GET",
                    async: true,
                    data: {
//                        "_token": token,
                        "hoten": hoten,
                        "ngay" : ngay,
                        "trieuchung" : trieuchung,
                        "loaibenh" : loaibenh
                    },
                    beforeSend: function() {
                        $("#tbodydskhambenh").html("<tr><td colspan='6'><img src='assets/images/"+randloading()+"' width='150' height='150'></td></tr>");
                    },
                    success: function (data) {
                        $("#tbodydskhambenh").html(data);
//                    alert(value);
                    }
                });
            }
        }
    </script>
@endsection