<?php

namespace App\Http\Controllers;

use App\BenhNhan;
use App\ChiTietPKB;
use App\HoaDon;
use App\LoaiBenh;
use App\ThamSo;
use App\Thuoc;
use Illuminate\Http\Request;
use App\PhieuKhamBenh;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use App\BaoCaoDoanhThu;
use App\ChiTietBCDT;
use Maatwebsite\Excel\Facades\Excel;

class PhieuKhamController extends Controller
{
    //
    public function getDSKhamBenh()
    {
        $dskhambenh = PhieuKhamBenh::where('NgayKham', date('Y-m-d'))->get();
        return view('index.phieukhambenh.dskhambenh', compact('dskhambenh'));
    }

    public function getAjaxDSKhamBenh(Request $request)
    {
        if ($request->ajax()) {
            $key = $request->key;
            $dskhambenh = PhieuKhamBenh::where('NgayKham', $key)->get();
            $i = 0;
            foreach ($dskhambenh as $bn) {
                echo "<tr>";
                echo "<td>" . ++$i . "</td>";
                echo "<td>" . $bn->benhnhan->HoTen . "</td>";
                if ($bn->benhnhan->GioiTinh == 1)
                    echo "<td>Nữ</td>";
                elseif ($bn->benhnhan->GioiTinh == 2)
                    echo "<td>Nam</td>";
                else echo "<td>Khác</td>";
                echo "<td>" . $bn->benhnhan->NamSinh . "</td>";
                echo "<td>" . $bn->benhnhan->DiaChi . "</td>";
                echo "</tr>";
            }
        }
    }

    public function getXuatExcel($ngay)
    {
        $nameCompany = "Phòng Mạch Tư";
        $ngayFormat = date_format(date_create($ngay), 'd_m_Y');
        $dskhambenh = PhieuKhamBenh::where('NgayKham', $ngay)->get();
        if (count($dskhambenh) == 0)
            return redirect()->route('ds-khambenh.get')->with('error', 'Không có gì để xuất');
        Excel::create('Danh sách khám bệnh ngày ' . $ngayFormat, function ($excel) use ($dskhambenh, $nameCompany, $ngayFormat) {
            $excel->setCreator('Phần mềm quản lý phòng mạch tư')
                ->setCompany($nameCompany)
                ->setTitle('Danh sach kham benh')
                ->setDescription('Đây là danh sách khám bệnh được backup từ hệ thống');
            $excel->sheet('DSKB_' . $ngayFormat, function ($sheet) use ($dskhambenh) {
                //set font and size
                $sheet->setStyle(array(
                    'font' => array(
                        'name' => 'Times New Roman',
                        'size' => 13,
                        'bold' => false
                    )
                ));
                $sheet->row(1, array(
                    'Họ & Tên', 'Giới tính', 'Năm sinh', 'Địa chỉ'
                ));
                $sheet->row(1, function ($row) {
                    $row->setFontWeight('bold');
                });
                foreach ($dskhambenh as $i => $value) {
                    if ($value->benhnhan->GioiTinh == 1)
                        $sheet->row($i + 2, array(
                            $value->benhnhan->HoTen,
                            'Nữ',
                            $value->benhnhan->NamSinh,
                            $value->benhnhan->DiaChi
                        ));
                    elseif ($value->benhnhan->GioiTinh == 2)
                        $sheet->row($i + 2, array(
                            $value->benhnhan->HoTen,
                            'Nam',
                            $value->benhnhan->NamSinh,
                            $value->benhnhan->DiaChi
                        ));
                    else
                        $sheet->row($i + 2, array(
                            $value->benhnhan->HoTen,
                            'Khác',
                            $value->benhnhan->NamSinh,
                            $value->benhnhan->DiaChi
                        ));
                }
            });
        })->download('xlsx');
//        return redirect()->back();
    }

    public function getDSPhieuKham()
    {
        $dsphieukham = PhieuKhamBenh::all()->sortByDesc('created_at');
        return view('index.phieukhambenh.danhsach', compact('dsphieukham'));
    }

    protected function ThemBaoCaoDT()
    {
        $month = date('m/Y');
        $day = date('j');
        $dem_baocaodt = BaoCaoDoanhThu::where('ThangNam', $month)->count();
        if ($dem_baocaodt == 0) {
            $baocaodt = new BaoCaoDoanhThu();
            $baocaodt->ThangNam = $month;
            $baocaodt->TongDoanhThu = 0;
            $baocaodt->save();
            $bcdt = new ChiTietBCDT();
            $bcdt->MaBCDT = $baocaodt->MaBCDT;
            $bcdt->Ngay = $day;

            $pkb = PhieuKhamBenh::where('NgayKham', date('Y-m-d'))->get();
            $sobenhnhan = count($pkb);
            $doanhthu = 0;
            foreach ($pkb as $detail) {
                $hoadon = HoaDon::where('MaPKB', $detail->MaPKB)->first();
                $doanhthu += ($hoadon->TienKham + $hoadon->TienThuoc);
            }
            $bcdt->SoBenhNhan = $sobenhnhan;
            $bcdt->DoanhThu = $doanhthu;
            $bcdt->save();
            $baocaodt->TongDoanhThu = $doanhthu;
            $baocaodt->save();

        } else {
            $baocaodt = BaoCaoDoanhThu::where('ThangNam', $month)->first();
            $dem_ctbcdt = ChiTietBCDT::where('MaBCDT', $baocaodt->MaBCDT)->where('Ngay', $day)->count();
            if ($dem_ctbcdt == 0) {
                $bcdt = new ChiTietBCDT();
                $bcdt->MaBCDT = $baocaodt->MaBCDT;
                $bcdt->Ngay = $day;

                $pkb = PhieuKhamBenh::where('NgayKham', date('Y-m-d'))->get();
                $sobenhnhanngay = count($pkb);
                $doanhthungay = 0;
                foreach ($pkb as $detail) {
                    $hoadon = HoaDon::where('MaPKB', $detail->MaPKB)->first();
                    $doanhthungay += ($hoadon->TienKham + $hoadon->TienThuoc);
                }
                $bcdt->SoBenhNhan = $sobenhnhanngay;
                $bcdt->DoanhThu = $doanhthungay;
                $bcdt->save();

                $baocaodt->TongDoanhThu = $baocaodt->TongDoanhThu + $doanhthungay;
                $baocaodt->save();
            } else {
                $bcdt = ChiTietBCDT::where('MaBCDT', $baocaodt->MaBCDT)->where('Ngay', $day)->first();
                $pkb = PhieuKhamBenh::where('NgayKham', date('Y-m-d'))->get();
                $pkbthang = PhieuKhamBenh::where('NgayKham', 'like', date('Y-m') . '%')->get();
                $sobenhnhanngay = count($pkb);
                $doanhthuthang = 0;
                $doanhthungay = 0;
                foreach ($pkb as $detail) {
                    $hoadon = HoaDon::where('MaPKB', $detail->MaPKB)->first();
                    $doanhthungay += ($hoadon->TienKham + $hoadon->TienThuoc);
                }
                foreach ($pkbthang as $detail) {
                    $hoadon = HoaDon::where('MaPKB', $detail->MaPKB)->first();
                    $doanhthuthang += ($hoadon->TienKham + $hoadon->TienThuoc);
                }
                echo count($pkbthang);
                $bcdt->SoBenhNhan = $sobenhnhanngay;
                $bcdt->DoanhThu = $doanhthungay;
                $bcdt->save();

                $baocaodt->TongDoanhThu = $doanhthuthang;
                $baocaodt->save();
            }
        }
    }

    public function getThemPhieuKham($id = 0)
    {
        $benhnhan = BenhNhan::where('MaBN', $id)->count() != 0 ? BenhNhan::find($id)->MaBN : "";
        $sobndakhamtrongngay = PhieuKhamBenh::where('NgayKham', date('Y-m-d'))->count();
        $sobntoida = ThamSo::where('ThamSo', 'SoBenhNhanToiDa')->first()->GiaTri;
        if ($sobndakhamtrongngay - $sobntoida == 0)
            $canhbao = 'Không thể thêm phiếu khám bệnh mới do đã khám quá số bệnh nhân tối đa được khám trong ngày.';
        else $canhbao = "";
        $dsbenhnhan = BenhNhan::all()->sortByDesc('created_at');
        $dsloaibenh = LoaiBenh::all();
        $dsthuoc = Thuoc::all();
        return view("index.phieukhambenh.them", compact('dsbenhnhan', 'dsloaibenh', 'dsthuoc', 'sobndakhamtrongngay', 'sobntoida', 'canhbao','benhnhan'));
    }

    public function postThemPhieuKham(Request $request)
    {
        $errors = new MessageBag();
        $sobndakhamtrongngay = PhieuKhamBenh::where('NgayKham', date('Y-m-d'))->count();
        $sobntoida = ThamSo::where('ThamSo', 'SoBenhNhanToiDa')->first()->GiaTri;
        if ($sobndakhamtrongngay >= $sobntoida) {
            $errors->add('err', 'Không thể thêm phiếu khám bệnh mới do đã khám quá số bệnh nhân tối đa được khám trong ngày.');
            return redirect()->route('them-phieukham.get')->withErrors($errors);
        } else {
            $dsthuoc = Thuoc::all();
            $messages = [
                'mabn.required' => 'Chưa chọn bệnh nhân.',
                'ngaykham.required' => 'Chưa có ngày khám.',
                'trieuchung.required' => 'Chưa nhập triệu chứng.',
                'trieuchung.min' => 'Triệu chứng quá ngắn.',
                'trieuchung.max' => 'Triệu chứng quá dài.',
                'loaibenh.required' => 'Chưa chọn loại bệnh.',
            ];
            $rules = [
                'mabn' => 'required',
                'ngaykham' => 'required',
                'trieuchung' => 'required|min:3|max:50',
                'loaibenh' => 'required',
            ];
            $errors = Validator::make($request->all(), $rules, $messages);
            if ($errors->fails()) {
                return redirect()
                    ->route('them-phieukham.get')
                    ->withErrors($errors)
                    ->withInput();
            }
            $phieukham = new PhieuKhamBenh();
            $phieukham->NgayKham = $request->ngaykham;
            $phieukham->MaBN = $request->mabn;
            $phieukham->TrieuChung = $request->trieuchung;
            $phieukham->DuDoanLoaiBenh = $request->loaibenh;
            $phieukham->save();

            $tienkham = ThamSo::where('ThamSo', 'TienKham')->first();

            $hoadon = new HoaDon();
            $hoadon->MaPKB = $phieukham->MaPKB;
            $hoadon->TienKham = $tienkham->GiaTri;

            $tienthuoc = 0;
            foreach ($dsthuoc as $thuoc) {
                $idthuoc = $thuoc->MaThuoc;
                $soluong = $request->$idthuoc;
                if ($soluong != 0) {
                    $ctpkb = new ChiTietPKB();
                    $ctpkb->MaPKB = $phieukham->MaPKB;
                    $ctpkb->MaThuoc = $thuoc->MaThuoc;
                    $ctpkb->DonGia = $thuoc->DonGia;
                    $ctpkb->SoLuong = $soluong * 1;
                    $thanhtien = $thuoc->DonGia * $soluong * 1;
                    $ctpkb->ThanhTien = $thanhtien;
                    $tienthuoc += $thanhtien;
                    $ctpkb->save();
                }
            }

            $hoadon->TienThuoc = $tienthuoc;
            $hoadon->save();
            $this->ThemBaoCaoDT();
            return redirect()->route('them-phieukham.get')->with('success', 'Thêm phiếu khám bệnh thành công');
        }
    }

    protected function SuaBaoCaoDT($id)
    {
        $phieukb = PhieuKhamBenh::find($id);
        $ngay = explode('-', $phieukb->NgayKham);
        $month = $ngay[1] . "/" . $ngay[0];
        $day = $ngay[2] * 1;
        $namthang = $ngay[0] . "-" . $ngay[1];

        $baocaodt = BaoCaoDoanhThu::where('ThangNam', $month)->first();

        $bcdt = ChiTietBCDT::where('MaBCDT', $baocaodt->MaBCDT)->where('Ngay', $day)->first();
        $pkb = PhieuKhamBenh::where('NgayKham', $phieukb->NgayKham)->get();
        $pkbthang = PhieuKhamBenh::where('NgayKham', 'like', $namthang . '%')->get();
        $sobenhnhanngay = count($pkb);
        $doanhthuthang = 0;
        $doanhthungay = 0;
        foreach ($pkb as $detail) {
            $hoadon = HoaDon::where('MaPKB', $detail->MaPKB)->first();
            $doanhthungay += ($hoadon->TienKham + $hoadon->TienThuoc);
        }
        foreach ($pkbthang as $detail) {
            $hoadon = HoaDon::where('MaPKB', $detail->MaPKB)->first();
            $doanhthuthang += ($hoadon->TienKham + $hoadon->TienThuoc);
        }
        echo count($pkbthang);
        $bcdt->SoBenhNhan = $sobenhnhanngay;
        $bcdt->DoanhThu = $doanhthungay;
        $bcdt->save();

        $baocaodt->TongDoanhThu = $doanhthuthang;
        $baocaodt->save();
    }

    public function getSuaPhieuKham($id)
    {
        $errors = new MessageBag();
        $dem_pkb = PhieuKhamBenh::where('MaPKB', $id)->count();
        if ($dem_pkb == 0) {
            $errors->add('err', 'Phiếu khám bệnh không tồn tại.');
            return redirect()->route('ds-phieukham.get')->withErrors($errors);
        } else {
            $pk = PhieuKhamBenh::find($id);
            $ctpkb = ChiTietPKB::where('MaPKB', $pk->MaPKB)->get();
            $arr = array();
            foreach ($ctpkb as $item => $value) {
                $arr[$item] = $value->MaThuoc;
            }
            $dsbenhnhan = BenhNhan::all()->sortByDesc('created_at');
            $dsloaibenh = LoaiBenh::all();
            $dsthuoc = Thuoc::whereNotIn('MaThuoc', $arr)->get();
            return view("index.phieukhambenh.sua", compact('pk', 'dsbenhnhan', 'dsloaibenh', 'dsthuoc', 'ctpkb'));
        }
    }

    public function postSuaPhieuKham(Request $request, $id)
    {
        $errors = new MessageBag();
        $dem_pkb = PhieuKhamBenh::where('MaPKB', $id)->count();
        if ($dem_pkb == 0) {
            $errors->add('err', 'Phiếu khám bệnh không tồn tại.');
            return redirect()->route('ds-phieukham.get')->withErrors($errors);
        } else {
            $messages = [
//                'mabn.required' => 'Chưa chọn bệnh nhân.',
                'ngaykham.required' => 'Chưa có ngày khám.',
                'trieuchung.required' => 'Chưa nhập triệu chứng.',
                'trieuchung.min' => 'Triệu chứng quá ngắn.',
                'trieuchung.max' => 'Triệu chứng quá dài.',
                'loaibenh.required' => 'Chưa chọn loại bệnh.',
            ];
            $rules = [
//                'mabn' => 'required',
                'ngaykham' => 'required',
                'trieuchung' => 'required|min:3|max:50',
                'loaibenh' => 'required',
            ];
            $errors = Validator::make($request->all(), $rules, $messages);
            if ($errors->fails()) {
                return redirect()
                    ->route('sua-phieukham.get', [$id])
                    ->withErrors($errors)
                    ->withInput();
            }

            $phieukham = PhieuKhamBenh::find($id);
            $phieukham->TrieuChung = $request->trieuchung;
            $phieukham->DuDoanLoaiBenh = $request->loaibenh;
            $phieukham->save();

            $dsctpkb = ChiTietPKB::where('MaPKB', $phieukham->MaPKB)->get();

            $hoadon = HoaDon::where('MaPKB', $id)->first();

            $tienthuoc = 0;
            foreach ($dsctpkb as $ctpkb) {
                $idthuoc = $ctpkb->MaThuoc;
                $soluong = $request->$idthuoc;
                if ($soluong != 0) {
                    $ctpkb->SoLuong = $soluong * 1;
                    $thanhtien = $ctpkb->DonGia * $soluong * 1;
                    $ctpkb->ThanhTien = $thanhtien;
                    $tienthuoc += $thanhtien;
                    $ctpkb->save();
                } else {
//                    $soluong = 0;
//                    $ctpkb->SoLuong = $soluong * 1;
//                    $thanhtien = $ctpkb->DonGia * $soluong * 1;
//                    $ctpkb->ThanhTien = $thanhtien;
//                    $tienthuoc += $thanhtien;
//                    $ctpkb->save();
                    $ctpkb->delete();
                }
            }

            $arr = array();
            foreach ($dsctpkb as $item => $value) {
                $arr[$item] = $value->MaThuoc;
            }

            //danh sach thuoc dung sau khi sua
            $dsthuoc = Thuoc::whereNotIn('MaThuoc', $arr)->get();

            foreach ($dsthuoc as $thuoc) {
                $idthuoc = $thuoc->MaThuoc;
                $soluong = $request->$idthuoc;
                if ($soluong != 0) {
                    $ctpkb = new ChiTietPKB();
                    $ctpkb->MaPKB = $phieukham->MaPKB;
                    $ctpkb->MaThuoc = $thuoc->MaThuoc;
                    $ctpkb->DonGia = $thuoc->DonGia;
                    $ctpkb->SoLuong = $soluong * 1;
                    $thanhtien = $thuoc->DonGia * $soluong * 1;
                    $ctpkb->ThanhTien = $thanhtien;
                    $tienthuoc += $thanhtien;
                    $ctpkb->save();
                }
            }

            $hoadon->TienThuoc = $tienthuoc;
            $hoadon->save();
            $this->SuaBaoCaoDT($id);
            return redirect()->route('sua-phieukham.get', [$id])->with('success', 'Sửa phiếu khám bệnh thành công');
        }
    }

    public function getXoaPhieuKham($id)
    {
        $errors = new MessageBag();
        $dem_pkb = PhieuKhamBenh::where('MaPKB', $id)->count();
        if ($dem_pkb == 0) {
            $errors->add('err', 'Phiếu khám bệnh không tồn tại.');
            return redirect()->route('ds-phieukham.get')->withErrors($errors);
        } else {
            $pkb = PhieuKhamBenh::find($id);
            $pkb->delete();
            return redirect()->route('ds-phieukham.get')->with('success', 'Xóa thành công.');
        }
    }

    public function getCTPhieuKham($id)
    {
        $pkb = PhieuKhamBenh::find($id);
        return view('index.phieukhambenh.chitiet', compact('pkb'));
    }

    public function getHDPhieuKham($id)
    {
        $pkb = PhieuKhamBenh::find($id);
        return view('index.phieukhambenh.hoadon', compact('pkb'));
    }
}
