<?php

namespace App\Http\Controllers;

use App\Thuoc;
use Illuminate\Http\Request;
use App\DonVi;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rule;

class DonViController extends Controller
{
    //

    public function getDSDonVi()
    {
        $dsdonvi = DonVi::all()->sortByDesc('created_at');
        return view('index.donvi.danhsach', compact('dsdonvi'));
    }

    public function getThemDonVi()
    {
        return redirect()->route('ds-donvi.get');
    }

    public function postThemDonVi(Request $request)
    {
        $messages = [
            'tendonvi.required' => 'Chưa nhập tên đơn vị.',
            'tendonvi.between' => 'Tên phải từ :min đến :max kí tự.',
            'tendonvi.unique' => 'Tên đơn vị này đã có vui lòng xem lại.',
        ];
        $rules = [
            'tendonvi' => 'required|between:1,20|unique:donvi,TenDonVi',
        ];
        $errors = Validator::make($request->all(), $rules, $messages);
        if ($errors->fails()) {
            return redirect()
                ->route('ds-donvi.get')
                ->withErrors($errors)
                ->withInput();
        }
        $donvi = new DonVi();
        $donvi->TenDonVi = $request->tendonvi;
        $donvi->save();
        return redirect()->route('ds-donvi.get')->with('success','Thêm đơn vị '.$donvi->TenDonVi.' thành công.');
    }

    public function getSuaDonVi($id)
    {
        $errors = new MessageBag();
        $dem_donvi = DonVi::where('MaDonVi', $id)->count();
        if ($dem_donvi < 1) {
            $errors->add('err', 'Đơn vị này không tồn tại.');
            return redirect()->route('ds-donvi.get')->withErrors($errors);
        }
        else {
            $donvi = DonVi::find($id);
            return view('index.donvi.sua', compact('donvi'));
        }
    }

    public function postSuaDonVi(Request $request,$id)
    {
        $errors = new MessageBag();
        $dem_donvi = DonVi::where('MaDonVi', $id)->count();
        if ($dem_donvi < 1) {
            $errors->add('err', 'Đơn vị này không tồn tại.');
            return redirect()->route('ds-donvi.get')->withErrors($errors);
        }
        else {
            $donvi = DonVi::find($id);
            $messages = [
                'tendonvi.required' => 'Chưa nhập tên đơn vị.',
                'tendonvi.between' => 'Tên phải từ :min đến :max kí tự.',
                'tendonvi.unique' => 'Tên đơn vị này đã có vui lòng xem lại.',
            ];
            $rules = [
                'tendonvi' => [
                    'required',
                    'between:1,20',
                    Rule::unique('donvi','TenDonVi')->ignore($id,'MaDonVi')
                ]
            ];
            $errors = Validator::make($request->all(), $rules, $messages);
            if ($errors->fails()) {
                return redirect()
                    ->route('sua-donvi.get',[$id])
                    ->withErrors($errors)
                    ->withInput();
            }
            $donvi->TenDonVi = $request->tendonvi;
            $donvi->save();
            return redirect()->route('sua-donvi.get',[$id])->with('success','Sửa đơn vị thành công.');
        }
    }

    public function getXoaDonVi($id)
    {
        $errors = new MessageBag();
        $dem_donvi = DonVi::where('MaDonVi', $id)->count();
        if ($dem_donvi == 0) {
            $errors->add('err', 'Đơn vị này không tồn tại.');
            return redirect()->route('ds-donvi.get')->withErrors($errors);
        }
        else {
            $dem_donvi_dung = Thuoc::where('MaDonVi',$id)->count();
            if ($dem_donvi_dung != 0) {
                $errors->add('err', 'Không thể xóa đơn vị này.');
                return redirect()->route('ds-donvi.get')->withErrors($errors);
            }
            $donvi = DonVi::find($id);
            $donvi->delete();
            return redirect()->route('ds-donvi.get')->with('success','Xóa thành công.');
        }
    }
}
