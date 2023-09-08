<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // cek group user and get list menu
        view()->composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $role = $user->role;
                $menu = DB::table('menu')->where('role', $role)->where('is_active', 1)->where('is_parent', 1)->orderBy('sequence', 'asc')->get();
                foreach ($menu as $key => $value) {
                    $submenu = DB::table('menu')->where('parent_id', $value->id)->where('is_active', 1)->where('is_parent', 0)->where('role', $role)->orderBy('sequence', 'asc')->get();
                    if (count($submenu) > 0) {
                        $menu[$key]->sub_menu = $submenu;
                    }
                }
                $view->with('menu', $menu);
            }
        });
    }
}
