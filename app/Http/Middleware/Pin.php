<?php

namespace App\Http\Middleware;

use App\Filament\Resources\UserResource\Pages\EditUser;
use Closure;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class Pin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $auth = Filament::auth();

        if (
            $auth->check()
            && !$auth->user()->pin
            && Route::currentRouteName() !== 'filament.admin.resources.users.edit'
            && Route::currentRouteName() !== 'filament.admin.auth.logout'
        ) {
            Notification::make()
                ->title(__('filament-panels::translations.user.please_set_your_pin'))
                ->warning()
                ->send();

            return redirect()
                ->route(EditUser::getRouteName(), $auth->user());
        }

        return $next($request);
    }
}
