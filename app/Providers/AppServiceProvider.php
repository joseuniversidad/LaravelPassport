<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Carbon\CarbonInterval;
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
        Passport::enablePasswordGrant();

    Passport::tokensExpireIn(CarbonInterval::hours(1));
    Passport::refreshTokensExpireIn(CarbonInterval::days(30));

    Passport::tokensCan([
        'productos.read'   => 'Ver listado y detalle de productos',
        'productos.write'  => 'Crear y editar productos',
        'productos.delete' => 'Eliminar productos',
        'usuarios.read'    => 'Ver listado de usuarios',
        'admin'            => 'Acceso administrativo completo',
        'reportes'         => 'Acceder a reportes del sistema',
    ]);

    Passport::setDefaultScope('productos.read');
    }
}
