<?php

namespace App\Http\Controllers;

use App\PhieuKhamBenh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\LoaiBenh;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rule;

class LoaiBenhController extends Controller
{
    //
    public function getDSLoaiBenh()
    {
        $dsloaibenh = LoaiBenh::all()->sortByDesc('created_at');
        return view('index.loaibenh.danhsach', compact('dsloaibenh'));
    }

    public function getThemLoaiBenh()
    {
        return redirect()->route('ds-loaibenh.get');
    }

    public function postThemLoaiBenh(Request $request)
    {
        $messages = [
            'tenloaibenh.required' => 'Chưa nhập loại bệnh.',
            'tenloaibenh.between' => 'Loại bệnh phải từ :min đến :max kí tự.',
            'tenloaibenh.unique' => 'Loại bệnh này đã có vui lòng xem lại.',
        ];
        $rules = [
            'tenloaibenh' => 'required|between:2,20|unique:loaibenh,TenLoaiBenh',
        ];
        $errors = Validator::make($request->all(), $rules, $messages);
        if ($errors->fails()) {
            return redirect()
                ->route('ds-loaibenh.get')
                ->withErrors($errors)
                ->withInput();
        }
        $loaibenh = new LoaiBenh();
        $loaibenh->TenLoaiBenh = $request->tenloaibenh;
        $loaibenh->save();
        return redirect()->route('ds-loaibenh.get')->with('success','Thêm loại bệnh thành công.');
    }

    public function getSuaLoaiBenh($id)
    {
        $errors = new MessageBag();
        $dem_loaibenh = LoaiBenh::where('MaLoaiBenh', $id)->count();
        if ($dem_loaibenh < 1) {
            $errors->add('err', 'Loại bệnh này không tồn tại.');
            return redirect()->route('ds-loaibenh.get')->withErrors($errors);
        }
        else {
            $loaibenh = LoaiBenh::find($id);
            return view('index.loaibenh.sua', compact('loaibenh'));
        }
    }

    public function postSuaLoaiBenh(Request $request,$id)
    {
        $errors = new MessageBag();
        $dem_loaibenh = LoaiBenh::where('MaLoaiBenh', $id)->count();
        if ($dem_loaibenh < 1) {
            $errors->add('err', 'Loại bệnh này không tồn tại.');
            return redirect()->route('ds-loaibenh.get')->withErrors($errors);
        }
        else {
            $loaibenh = LoaiBenh::find($id);
            $messages = [
                'tenloaibenh.required' => 'Chưa nhập loại bệnh.',
                'tenloaibenh.between' => 'Loại bệnh phải từ :min đến :max kí tự.',
                'tenloaibenh.unique' => 'Loại bệnh này đã có vui lòng xem lại.',
            ];
            $rules = [
                'tenloaibenh' => [
                    'required',
                    'between:2,20',
                    Rule::unique('loaibenh','TenLoaiBenh')->ignore($id,'MaLoaiBenh')
                ]
            ];
            $errors = Validator::make($request->all(), $rules, $messages);
            if ($errors->fails()) {
                return redirect()
                    ->route('sua-loaibenh.get',[$id])
                    ->withErrors($errors)
                    ->withInput();
            }
            $loaibenh->TenLoaiBenh = $request->tenloaibenh;
            $loaibenh->save();
            return redirect()->route('sua-loaibenh.get',[$id])->with('success','Sửa loại bệnh thành công.');
        }
    }

    public function getXoaLoaiBenh($id)
    {
        $errors = new MessageBag();
        $dem_loaibenh = LoaiBenh::where('MaLoaiBenh', $id)->count();
        if ($dem_loaibenh == 0) {
            $errors->add('err', 'Loại bệnh này không tồn tại.');
            return redirect()->route('ds-loaibenh.get')->withErrors($errors);
        }
        else {
            $dem_loaibenh_dung = PhieuKhamBenh::where('DuDoanLoaiBenh',$id)->count();
            if ($dem_loaibenh_dung != 0) {
                $errors->add('err', 'Không thể xóa loại bệnh này.');
                return redirect()->route('ds-loaibenh.get')->withErrors($errors);
            }
            $loaibenh = LoaiBenh::find($id);
            $loaibenh->delete();
            return redirect()->route('ds-loaibenh.get')->with('success','Xóa thành công.');
        }
    }
}
