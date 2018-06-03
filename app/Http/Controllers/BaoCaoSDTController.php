<?php

namespace App\Http\Controllers;

use App\BaoCaoSuDungThuoc;
use App\ChiTietBCSDT;
use App\ChiTietPKB;
use App\PhieuKhamBenh;
use App\Thuoc;
use Illuminate\Http\Request;

class BaoCaoSDTController extends Controller
{
    //
    protected function ThongKeThuoc($t, $n)
    {
        $thang = $t."/".$n;
        $thang2 = $n."-".$t;
        $dem_bcsdt = BaoCaoSuDungThuoc::where('ThangNam', $thang)->count();
        if ($dem_bcsdt == 0) //bao cao chua duoc tao
        {
//          tao bao cao va thong ke
            $bcsdt = new BaoCaoSuDungThuoc();
            $bcsdt->ThangNam = $thang;
            $bcsdt->save();

            $pkb = PhieuKhamBenh::where('NgayKham', 'like', $thang2 . '%')->get();

            $dsthuoc = Thuoc::all();
            foreach ($dsthuoc as $thuoc) {
                $soluongdung = 0;
                $solandung = 0;

//                SQL thay the
//                SELECT MaThuoc, SUM(chitietphieukham.SoLuong),COUNT(chitietphieukham.MaThuoc) FROM phieukham,chitietphieukham
//	               WHERE phieukham.NgayKham LIKE '2018-05%'
//                  AND phieukham.MaPKB = chitietphieukham.MaPKB
//                  GROUP BY chitietphieukham.MaThuoc
                foreach ($pkb as $detail) {
                    $ctpkb = ChiTietPKB::where('MaThuoc', $thuoc->MaThuoc)->where('MaPKB', $detail->MaPKB)->first();
                    if (isset($ctpkb)) {
                        $soluongdung += $ctpkb->SoLuong;
                        $solandung++;
                    }
                }
                $ctbcsdt = new ChiTietBCSDT();
                $ctbcsdt->MaBCSDT = $bcsdt->MaBCSDT;
                $ctbcsdt->MaThuoc = $thuoc->MaThuoc;
                $ctbcsdt->SoLuongDung = $soluongdung;
                $ctbcsdt->SoLanDung = $solandung;
                $ctbcsdt->save();
            }
        } else //bao cao thang da duoc tao
        {
            //thong ke lai
            $bcsdt = BaoCaoSuDungThuoc::where('ThangNam', $thang)->first();
//
            $pkb = PhieuKhamBenh::where('NgayKham', 'like', $thang2 . '%')->get();
//
            $dsthuoc = Thuoc::all();
            foreach ($dsthuoc as $thuoc) {
                $soluongdung = 0;
                $solandung = 0;

//                SQL thay the
//                SELECT MaThuoc, SUM(chitietphieukham.SoLuong),COUNT(chitietphieukham.MaThuoc) FROM phieukham,chitietphieukham
//	               WHERE phieukham.NgayKham LIKE '2018-05%'
//                  AND phieukham.MaPKB = chitietphieukham.MaPKB
//                  GROUP BY chitietphieukham.MaThuoc
                foreach ($pkb as $detail) {
                    $ctpkb = ChiTietPKB::where('MaThuoc', $thuoc->MaThuoc)->where('MaPKB', $detail->MaPKB)->first();
                    if (isset($ctpkb)) {
                        $soluongdung += $ctpkb->SoLuong;
                        $solandung++;
                    }
                }
                $ctbcsdt = ChiTietBCSDT::where('MaThuoc', $thuoc->MaThuoc)->where('MaBCSDT', $bcsdt->MaBCSDT)->first();
                if (isset($ctbcsdt)) {
                    $ctbcsdt->SoLuongDung = $soluongdung;
                    $ctbcsdt->SoLanDung = $solandung;
                    $ctbcsdt->save();
                }
                else {
                    $ctbcsdt = new ChiTietBCSDT();
                    $ctbcsdt->MaBCSDT = $bcsdt->MaBCSDT;
                    $ctbcsdt->MaThuoc = $thuoc->MaThuoc;
                    $ctbcsdt->SoLuongDung = $soluongdung;
                    $ctbcsdt->SoLanDung = $solandung;
                    $ctbcsdt->save();
                }
            }
        }
    }

    protected function SoSanhThang($t, $n)
    {
        if ($n > date('Y'))
            return false;
        elseif ($n == date('Y')) {
            if ($t > date('m'))
                return false;
            else return true;
        } else return true;
    }

    public function getDSBaoCaoSDT()
    {
        $t = date('m');
        $n = date('Y');
        $thang = date('m/Y');
        $this->ThongKeThuoc($t, $n);
        $bcsdt = BaoCaoSuDungThuoc::where('ThangNam', $thang)->first();
        $ctbcsdt = ChiTietBCSDT::where('MaBCSDT', $bcsdt->MaBCSDT)->get();
        return view('index.baocaosdt.danhsach', compact('ctbcsdt'));
    }

    public function getAjaxBaoCaoSDT(Request $request)
    {
        if ($request->ajax()) {
            $key = $request->key;
            $key = explode('-', $key);
            $t = $key[1];
            $n = $key[0];
            $thang = $t . "/" . $n;
            //chon thang lon hon thang hien tai
            if ($this->SoSanhThang($t, $n) == false) {
                echo "<tr>";
                echo "<td colspan='5'>Không có dữ liệu</td>";
                echo "</tr>";
            } else {
                $this->ThongKeThuoc($t, $n);
                $bcsdt = BaoCaoSuDungThuoc::where('ThangNam', $thang)->first();
                if (isset($bcsdt)) {
                    $ctbcsdt = ChiTietBCSDT::where('MaBCSDT', $bcsdt->MaBCSDT)->get();
                    $i = 0;
                    foreach ($ctbcsdt as $detail) {
                        echo "<tr>";
                        echo "<td>" . ++$i . "</td>";
                        echo "<td>" . $detail->thuoc->TenThuoc . "</td>";
                        echo "<td>" . $detail->thuoc->donvi->TenDonVi . "</td>";
                        echo "<td>" . number_format($detail->SoLuongDung, 0, ',', '.') . "</td>";
                        echo "<td>" . number_format($detail->SoLanDung, 0, ',', '.') . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr>";
                    echo "<td colspan='5'>Không có dữ liệu</td>";
                    echo "</tr>";
                }
            }
        }
    }
}
