<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Error reporting
        $this->_errorReporting();
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

    /**
     * Check/set PHP error reporting level
     */
    protected function _errorReporting()
    {
        $errorReporting = @ini_get('error_reporting');
        $nextErrorReporting = config('app.error_reporting', $errorReporting);
        if ($errorReporting != $nextErrorReporting) {
            \error_reporting($nextErrorReporting);
        }
        return $this;
    }
}
