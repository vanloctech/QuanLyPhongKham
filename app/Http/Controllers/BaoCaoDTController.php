<?php

namespace App\Http\Controllers;

use App\BaoCaoDoanhThu;
use App\ChiTietBCDT;
use App\HoaDon;
use App\PhieuKhamBenh;
use Illuminate\Http\Request;

class BaoCaoDTController extends Controller
{
    //
    public function getDSBaoCaoDT()
    {
        $month = date('m/Y');
        $dem_baocaodt = BaoCaoDoanhThu::where('ThangNam', $month)->count();
        $baocaodt = BaoCaoDoanhThu::where('ThangNam', $month)->first();
        if ($dem_baocaodt != 0) {
            $ctbcdt = ChiTietBCDT::where('MaBCDT', $baocaodt->MaBCDT)->get();
        } else $ctbcdt = NULL;
        return view('index.baocaodt.danhsach', compact('baocaodt', 'ctbcdt'));
    }

    public function getAjaxBaoCaoDT(Request $request)
    {
        if ($request->ajax()) {
            $key = $request->key;
            $month = explode('-', $key)[1];
            $year = explode('-', $key)[0];
            $dem_baocaodt = BaoCaoDoanhThu::where('ThangNam', $month . "/" . $year)->count();
            $i = 0;
            if ($dem_baocaodt == 0) {
                echo "<tr>";
                echo "<td colspan='5'>Không có dữ liệu</td>";
                echo "</tr>";
//                echo count($ctbcdt);
            } else {
                $baocaodt = BaoCaoDoanhThu::where('ThangNam', $month . "/" . $year)->first();
                $ctbcdt = ChiTietBCDT::where('MaBCDT', $baocaodt->MaBCDT)->get();
                foreach ($ctbcdt as $detail) {
                    echo "<tr>";
                    echo "<td>" . ++$i . "</td>";
                    echo "<td>" . $detail->Ngay . "/" . $detail->baocaodoanhthu->ThangNam . "</td>";
                    echo "<td>" . $detail->SoBenhNhan . "</td>";
                    echo "<td>" . number_format($detail->DoanhThu) . " VND</td>";
                    echo "<td>" . round(($detail->DoanhThu / $detail->baocaodoanhthu->TongDoanhThu) * 100, 2) . "%</td>";
                    echo "</tr>";
                }
            }
        }
    }
}
