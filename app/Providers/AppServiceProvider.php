<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use App\PhieuKhamBenh;
use App\ThamSo;
use Illuminate\Support\Facades\View;
use App\ThongTinPhongKham;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $SoBNDaKhamTrongNgay = PhieuKhamBenh::where('NgayKham', date('Y-m-d'))->count();
        $SoBNToiDa = ThamSo::where('ThamSo', 'SoBenhNhanToiDa')->first()->GiaTri;
        if ($SoBNDaKhamTrongNgay - $SoBNToiDa == 0)
            $SoConLai = 0;
        else $SoConLai = $SoBNToiDa - $SoBNDaKhamTrongNgay;

        // Sharing
        View::share(['SoBNConLai' => $SoConLai]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
