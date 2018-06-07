<?php

namespace App\Http\Controllers;

use App\BenhNhan;
use App\LoaiBenh;
use App\PhieuKhamBenh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class BenhNhanController extends Controller
{
    //
    public function getDSBenhNhan()
    {
        $dsbenhnhan = BenhNhan::all();
        return view('index.benhnhan.danhsach', compact('dsbenhnhan'));
    }

    public function getThemBenhNhan()
    {
        return view('index.benhnhan.them');
    }

    public function postThemBenhNhan(Request $request)
    {
        $messages = [
            'hoten.required' => 'Chưa nhập họ & tên.',
            'hoten.min' => 'Họ & tên quá ngắn.',
            'hoten.max' => 'Họ & tên quá dài.',
            'gioitinh.required' => 'Chưa chọn giới tính.',
            'namsinh.required' => 'Chưa chọn năm sinh.',
            'namsinh.numeric' => 'Năm sinh phải là số.',
            'diachi.required' => 'Chưa nhập địa chỉ.',
            'diachi.min' => 'Địa chỉ quá ngắn.',
            'diachi.max' => 'Địa chỉ quá dài.',
        ];
        $rules = [
            'hoten' => 'required|min:5|max:50',
            'gioitinh' => 'required|numeric',
            'namsinh' => 'required|numeric',
            'diachi' => 'required|min:5|max:50',
        ];
        $errors = Validator::make($request->all(), $rules, $messages);
        if ($errors->fails()) {
            return redirect()
                ->route('them-benhnhan.get')
                ->withErrors($errors)
                ->withInput();
        }
        $benhnhan = new BenhNhan();
        $benhnhan->HoTen = $request->hoten;
        $benhnhan->NamSinh = $request->namsinh;
        $benhnhan->GioiTinh = $request->gioitinh;
        $benhnhan->DiaChi = $request->diachi;
        $benhnhan->save();
        return redirect()->route('them-benhnhan.get')->with('success', 'Thêm bệnh nhân thành công.');
    }

    public function getSuaBenhNhan($id)
    {
        $errors = new MessageBag();
        $dem_benhnhan = BenhNhan::where('MaBN', $id)->count();
        if ($dem_benhnhan == 0) {
            $errors->add('err', 'Bệnh nhân không tồn tại.');
            return redirect()->route('ds-benhnhan.get')->withErrors($errors);
        } else {
            $benhnhan = BenhNhan::find($id);
            return view('index.benhnhan.sua', compact('benhnhan'));
        }
    }

    public function postSuaBenhNhan(Request $request, $id)
    {
        $errors = new MessageBag();
        $dem_benhnhan = BenhNhan::where('MaBN', $id)->count();
        if ($dem_benhnhan == 0) {
            $errors->add('err', 'Bệnh nhân không tồn tại.');
            return redirect()->route('ds-benhnhan.get')->withErrors($errors);
        } else {
            $messages = [
                'hoten.required' => 'Chưa nhập họ & tên.',
                'hoten.min' => 'Họ & tên quá ngắn.',
                'hoten.max' => 'Họ & tên quá dài.',
                'gioitinh.required' => 'Chưa chọn giới tính.',
                'namsinh.required' => 'Chưa chọn năm sinh.',
                'namsinh.numeric' => 'Năm sinh phải là số.',
                'diachi.required' => 'Chưa nhập địa chỉ.',
                'diachi.min' => 'Địa chỉ quá ngắn.',
                'diachi.max' => 'Địa chỉ quá dài.',
            ];
            $rules = [
                'hoten' => 'required|min:5|max:50',
                'gioitinh' => 'required|numeric',
                'namsinh' => 'required|numeric',
                'diachi' => 'required|min:5|max:50',
            ];
            $errors = Validator::make($request->all(), $rules, $messages);
            if ($errors->fails()) {
                return redirect()
                    ->route('sua-benhnhan.get', [$id])
                    ->withErrors($errors)
                    ->withInput();
            }
            $benhnhan = BenhNhan::find($id);
            $benhnhan->HoTen = $request->hoten;
            $benhnhan->NamSinh = $request->namsinh;
            $benhnhan->GioiTinh = $request->gioitinh;
            $benhnhan->DiaChi = $request->diachi;
            $benhnhan->save();
            return redirect()->route('sua-benhnhan.get', [$id])->with('success', 'Sửa bệnh nhân thành công.');
        }
    }

    public function getXoaBenhNhan($id)
    {
        $errors = new MessageBag();
        $dem_benhnhan = BenhNhan::where('MaBN', $id)->count();
        if ($dem_benhnhan == 0) {
            $errors->add('err', 'Bệnh nhân không tồn tại.');
            return redirect()->route('ds-benhnhan.get')->withErrors($errors);
        } else {
            $dem_phieukham_dung = PhieuKhamBenh::where('MaBN', $id)->count();
            if ($dem_phieukham_dung > 0) {
                $errors->add('err', 'Không thể xóa bệnh nhân này.');
                return redirect()->route('ds-benhnhan.get')->withErrors($errors);
            } else {
                $benhnhan = BenhNhan::find($id);
                $benhnhan->delete();
                return redirect()->route('ds-benhnhan.get')->with('success', 'Xóa thành công.');
            }
        }

    }

    public function getTraCuuBenhNhan()
    {
        $dsloaibenh = LoaiBenh::all();
        return view('index.benhnhan.tracuu', compact('dsloaibenh'));
    }

    public function getAjaxTraCuuBenhNhan(Request $request)
    {
        if ($request->ajax()) {
            $hoten = "";
            $ngay = "";
            $trieuchung = "";
            $loaibenh = "";

            if ($request->hoten != "")
                $hoten = $request->hoten;
            if ($request->ngay != "")
                $ngay = $request->ngay;
            if ($request->trieuchung != "")
                $trieuchung = $request->trieuchung;
            if ($request->loaibenh != "")
                $loaibenh = $request->loaibenh;

            $dsBenhNhan = PhieuKhamBenh::whereHas('benhnhan', function ($query) use ($hoten) {
                $query->where('HoTen', 'like', '%' . $hoten . '%');
            })->where('TrieuChung', 'like', '%' . $trieuchung . '%')
                ->where('NgayKham', 'like', '%' . $ngay . '%')
                ->where('DuDoanLoaiBenh', 'like', '%' . $loaibenh . '%')
                ->get();

            if (count($dsBenhNhan) == 0)
                echo "<tr><td colspan='6'>Không tìm thấy bệnh nhân</td></tr>";
            else {
                $i = 0;
                sleep(2);

                foreach ($dsBenhNhan as $detail) {
                    $mang_kiemtra[$i] = $detail->MaPKB;
                    echo "<tr>";
                    echo "<td>" . ++$i . "</td>";
                    echo "<td>" . $detail->benhnhan->HoTen . "</td>";
                    echo "<td>" . date_format(date_create($detail->NgayKham), 'd/m/Y') . "</td>";
                    echo "<td>" . $detail->loaibenh->TenLoaiBenh . "</td>";
                    echo "<td>" . $detail->TrieuChung . "</td>";
                    echo "<td class='hidden-print'>
                                <a href=\"" . route('them-phieukham.get', [$detail->MaBN]) . "\"
                                   class=\"btn btn-icon waves-effect waves-light btn-success\" title=\"Thêm phiếu khám bệnh cho bệnh nhân này\"> Thêm PKB</a>
                                &nbsp;            
                                <a href=\"" . route('sua-phieukham.get', [$detail->MaPKB]) . "\"
                                   class=\"btn btn-icon waves-effect waves-light btn-warning\" title=\"Sửa\"> <i
                                            class=\"fa fa-wrench\"></i></a>
                                &nbsp;
                                &nbsp;
                                <a onclick=\"del($detail->MaPKB)\"
                                   class=\"btn btn-icon waves-effect waves-light btn-danger\" title=\"Xóa\"> <i
                                            class=\"fa fa-remove\"></i></a></td>";
                    echo "</tr>";
                }
                if ($i == 0) {
                    echo "<tr><td colspan='6'>Không tìm thấy bệnh nhân</td></tr>";
                }

            }
        }
    }
}
