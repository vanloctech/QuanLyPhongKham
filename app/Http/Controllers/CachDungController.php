<?php

namespace App\Http\Controllers;

use App\CachDung;
use App\Thuoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rule;

class CachDungController extends Controller
{
    //
    public function getDSCachDung()
    {
        $dscachdung = CachDung::all()->sortByDesc('created_at');
        return view('index.cachdung.danhsach', compact('dscachdung'));
    }

    public function getThemCachDung()
    {
        return redirect()->route('ds-cachdung.get');
    }

    public function postThemCachDung(Request $request)
    {
        $messages = [
            'cachdung.required' => 'Chưa nhập cách dùng.',
            'cachdung.between' => 'Cách dùng phải từ :min đến :max kí tự.',
            'cachdung.unique' => 'Cách dùng này đã có vui lòng xem lại.',
        ];
        $rules = [
            'cachdung' => 'required|between:3,20|unique:cachdung,CachDung',
        ];
        $errors = Validator::make($request->all(), $rules, $messages);
        if ($errors->fails()) {
            return redirect()
                ->route('ds-cachdung.get')
                ->withErrors($errors)
                ->withInput();
        }
        $cachdung = new CachDung();
        $cachdung->CachDung = $request->cachdung;
        $cachdung->save();
        return redirect()->route('ds-cachdung.get')->with('success','Thêm cách dùng thành công.');
    }

    public function getSuaCachDung($id)
    {
        $errors = new MessageBag();
        $dem_cachdung = CachDung::where('MaCachDung', $id)->count();
        if ($dem_cachdung < 1) {
            $errors->add('err', 'Cách dùng này không tồn tại.');
            return redirect()->route('ds-cachdung.get')->withErrors($errors);
        }
        else {
            $cachdung = CachDung::find($id);
            return view('index.cachdung.sua', compact('cachdung'));
        }
    }

    public function postSuaCachDung(Request $request,$id)
    {
        $errors = new MessageBag();
        $dem_cachdung = CachDung::where('MaCachDung', $id)->count();
        if ($dem_cachdung < 1) {
            $errors->add('err', 'Cách dùng này không tồn tại.');
            return redirect()->route('ds-cachdung.get')->withErrors($errors);
        }
        else {
            $cachdung = CachDung::find($id);
            $messages = [
                'cachdung.required' => 'Chưa nhập cách dùng.',
                'cachdung.between' => 'Cách dùng phải từ :min đến :max kí tự.',
                'cachdung.unique' => 'Cách dùng này đã có vui lòng xem lại.',
            ];
            $rules = [
                'cachdung' => [
                    'required',
                    'between:3,20',
                    Rule::unique('cachdung','CachDung')->ignore($id,'MaCachDung')
                    ]
            ];
            $errors = Validator::make($request->all(), $rules, $messages);
            if ($errors->fails()) {
                return redirect()
                    ->route('sua-cachdung.get',[$id])
                    ->withErrors($errors)
                    ->withInput();
            }
            $cachdung->CachDung = $request->cachdung;
            $cachdung->save();
            return redirect()->route('sua-cachdung.get',[$id])->with('success','Sửa cách dùng thành công.');
        }
    }

    public function getXoaCachDung($id)
    {
        $errors = new MessageBag();
        $dem_cachdung = CachDung::where('MaCachDung', $id)->count();
        if ($dem_cachdung == 0) {
            $errors->add('err', 'Cách dùng này không tồn tại.');
            return redirect()->route('ds-cachdung.get')->withErrors($errors);
        }
        else {
            $dem_cachdung_dung = Thuoc::where('MaCachDung',$id)->count();
            if ($dem_cachdung_dung != 0) {
                $errors->add('err', 'Không thể xóa cách dùng này.');
                return redirect()->route('ds-cachdung.get')->withErrors($errors);
            }
            $cachdung = CachDung::find($id);
            $cachdung->delete();
            return redirect()->route('ds-cachdung.get')->with('success','Xóa thành công.');
        }
    }
}
