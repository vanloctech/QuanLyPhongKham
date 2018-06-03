<?php

namespace App\Http\Controllers;

use App\CachDung;
use App\ChiTietPKB;
use App\DonVi;
use App\Thuoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rule;

class ThuocController extends Controller
{
    //
    public function getDSThuoc()
    {
        $dsthuoc = Thuoc::all();
        return view('index.thuoc.danhsach', compact('dsthuoc'));
    }

    public function getThemThuoc()
    {
        $dsdonvi = DonVi::all();
        $dscachdung = CachDung::all();
        return view("index.thuoc.them", compact('dsdonvi','dscachdung'));
    }

    public function postThemThuoc(Request $request)
    {
        $messages = [
            'tenthuoc.required' => 'Chưa nhập tên thuốc.',
            'tenthuoc.min' => 'Tên thuốc quá ngắn.',
            'tenthuoc.max' => 'Tên thuốc quá dài.',
            'tenthuoc.unique' => 'Tên thuốc đã tồn tại.',
            'dongia.required' => 'Chưa nhập đơn giá.',
            'dongia.numeric' => 'Đơn giá phải là số.',
            'donvi.required' => 'Chưa chọn đơn vị.',
            'donvi.numeric' => 'Đơn vị không tồn tại.',
            'cachdung.required' => 'Chưa chọn cách dùng.',
            'cachdung.numeric' => 'Cách dùng không tồn tại.',
        ];
        $rules = [
            'tenthuoc' => 'required|min:5|max:50|unique:thuoc,TenThuoc',
            'dongia' => 'required|numeric',
            'donvi' => 'required|numeric',
            'cachdung' => 'required|numeric',
        ];
        $errors = Validator::make($request->all(), $rules, $messages);
        if ($errors->fails()) {
            return redirect()
                ->route('them-thuoc.get')
                ->withErrors($errors)
                ->withInput();
        }
        $thuoc = new Thuoc();
        $thuoc->TenThuoc = $request->tenthuoc;
        $thuoc->DonGia = $request->dongia;
        $thuoc->MaDonVi = $request->donvi;
        $thuoc->MaCachDung = $request->cachdung;
        $thuoc->save();
        return redirect()->route('them-thuoc.get')->with('success','Thêm thuốc mới thành công.');
    }

    public function getSuaThuoc($id)
    {
        $errors = new MessageBag();
        $dem_thuoc = Thuoc::where('MaThuoc', $id)->count();
        if ($dem_thuoc == 0) {
            $errors->add('err', 'Thuốc này không tồn tại.');
            return redirect()->route('ds-thuoc.get')->withErrors($errors);
        }
        else {
            $dsdonvi = DonVi::all();
            $dscachdung = CachDung::all();
            $thuoc = Thuoc::find($id);
            return view('index.thuoc.sua', compact('dsdonvi','dscachdung','thuoc'));
        }
    }

    public function postSuaThuoc(Request $request, $id)
    {
        $errors = new MessageBag();
        $dem_thuoc = Thuoc::where('MaThuoc', $id)->count();
        if ($dem_thuoc == 0) {
            $errors->add('err', 'Thuốc này không tồn tại.');
            return redirect()->route('ds-thuoc.get')->withErrors($errors);
        }
        else {
            $thuoc = Thuoc::find($id);
            $messages = [
                'tenthuoc.required' => 'Chưa nhập tên thuốc.',
                'tenthuoc.min' => 'Tên thuốc quá ngắn.',
                'tenthuoc.max' => 'Tên thuốc quá dài.',
                'tenthuoc.unique' => 'Tên thuốc đã tồn tại.',
                'dongia.required' => 'Chưa nhập đơn giá.',
                'dongia.numeric' => 'Đơn giá phải là số.',
                'donvi.required' => 'Chưa chọn đơn vị.',
                'donvi.numeric' => 'Đơn vị không tồn tại.',
                'cachdung.required' => 'Chưa chọn cách dùng.',
                'cachdung.numeric' => 'Cách dùng không tồn tại.',
            ];
            $rules = [
                'tenthuoc' => [
                    'required',
                    'min:5',
                    'max:50',
                    Rule::unique('thuoc','TenThuoc')->ignore($id,'MaThuoc')
                    ],
                'dongia' => 'required|numeric',
                'donvi' => 'required|numeric',
                'cachdung' => 'required|numeric',
            ];
            $errors = Validator::make($request->all(), $rules, $messages);
            if ($errors->fails()) {
                return redirect()
                    ->route('sua-thuoc.get',[$id])
                    ->withErrors($errors)
                    ->withInput();
            }
            $thuoc->TenThuoc = $request->tenthuoc;
            $thuoc->DonGia = $request->dongia;
            $thuoc->MaDonVi = $request->donvi;
            $thuoc->MaCachDung = $request->cachdung;
            $thuoc->save();
            return redirect()->route('sua-thuoc.get',[$id])->with('success','Sửa thuốc thành công.');
        }
    }

    public function getXoaThuoc($id)
    {
        $errors = new MessageBag();
        $dem_thuoc = Thuoc::where('MaThuoc', $id)->count();
        if ($dem_thuoc == 0) {
            $errors->add('err', 'Thuốc này không tồn tại.');
            return redirect()->route('ds-thuoc.get')->withErrors($errors);
        }
        else {
            $dem_thuoc_dung = ChiTietPKB::where('MaThuoc',$id)->count();
            if ($dem_thuoc_dung != 0) {
                $errors->add('err', 'Không thể xóa thuốc này.');
                return redirect()->route('ds-thuoc.get')->withErrors($errors);
            }
            $thuoc = Thuoc::find($id);
            $thuoc->delete();
            return redirect()->route('ds-thuoc.get')->with('success','Xóa thành công.');
        }
    }
}
