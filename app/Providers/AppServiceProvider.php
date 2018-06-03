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
        $sobndakhamtrongngay = PhieuKhamBenh::where('NgayKham', date('Y-m-d'))->count();
        $sobntoida = ThamSo::where('ThamSo', 'SoBenhNhanToiDa')->first()->GiaTri;
        if ($sobndakhamtrongngay - $sobntoida == 0)
            $soconlai = 0;
        else $soconlai = $sobntoida - $sobndakhamtrongngay;

        // Sharing
        View::share(['sobnconlai' => $soconlai]);
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
