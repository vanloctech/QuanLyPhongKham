<?php

namespace App\Http\Controllers;

use App\ThamSo;
use App\ThongTinPhongKham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuyDinhController extends Controller
{
    //
    public function getQuyDinh()
    {
        $sobntoida = ThamSo::where('ThamSo','SoBenhNhanToiDa')->first();
        $tienkham = ThamSo::where('ThamSo','TienKham')->first();
        return view('index.quydinh.quydinh',compact('sobntoida','tienkham'));
    }

    public function postQuyDinh(Request $request)
    {
        $messages = [
            'sobntoida.required' => 'Chưa nhập số bệnh nhân tối đa.',
            'sobntoida.numeric' => 'Số bệnh nhân phải là số',
            'tienkham.require' => 'Chưa nhập tiền khám.',
            'tienkham.numeric' => 'Tiền khám phải là số.',
        ];
        $rules = [
            'sobntoida' => 'required|numeric',
            'tienkham' => 'required|numeric'
        ];
        $errors = Validator::make($request->all(), $rules, $messages);
        if ($errors->fails()) {
            return redirect()
                ->route('quydinh.get')
                ->withErrors($errors)
                ->withInput();
        }
        $sobntoida = ThamSo::where('ThamSo','SoBenhNhanToiDa')->first();
        $tienkham = ThamSo::where('ThamSo','TienKham')->first();

        $sobntoida->GiaTri = $request->sobntoida;
        $tienkham->GiaTri = $request->tienkham;

        $sobntoida->save();
        $tienkham->save();

        return redirect()->route('quydinh.get')->with('success','Sửa quy định thành công');

    }

    public function getTTPK()
    {
        $tenpk = ThongTinPhongKham::find(1);
        $tenbs = ThongTinPhongKham::find(2);
        $diachi = ThongTinPhongKham::find(3);
        $sdt = ThongTinPhongKham::find(4);
        return view('index.quydinh.thongtin',compact('tenpk','tenbs','diachi','sdt'));
    }

    public function postTTPK(Request $request)
    {
        $messages = [
            'tenpk.max' => 'Tên phòng khám quá dài.',
            'tenbs.max' => 'Tên bác sĩ quá dài.',
            'diachi.max' => 'Địa chỉ quá dài.',
            'sdt.max' => 'Số điện thoại không tồn tại.',
        ];
        $rules = [
            'tenpk' => 'nullable|max:50',
            'tenbs' => 'nullable|max:50',
            'diachi' => 'nullable|max:50',
            'sdt' => 'nullable|max:11',
        ];
        $errors = Validator::make($request->all(), $rules, $messages);
        if ($errors->fails()) {
            return redirect()
                ->route('ttpk.get')
                ->withErrors($errors)
                ->withInput();
        }
        $tenpk = ThongTinPhongKham::find(1);
        $tenbs = ThongTinPhongKham::find(2);
        $diachi = ThongTinPhongKham::find(3);
        $sdt = ThongTinPhongKham::find(4);

        $tenpk->GiaTri = $request->tenpk;
        $tenbs->GiaTri = $request->tenbs;
        $diachi->GiaTri = $request->diachi;
        $sdt->GiaTri = $request->sdt;

        $tenpk->save();
        $tenbs->save();
        $diachi->save();
        $sdt->save();

        return redirect()->route('ttpk.get')->with('success','Sửa thông tin phòng khám thành công');
    }
}
