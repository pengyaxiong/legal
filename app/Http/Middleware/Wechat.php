<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class Wechat
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!session('wechat.user')) {
            $openid=$request->openid;

            $user = User::where('openid', $openid)->first();
            if ($user) {
                $user->update([
                    'openid'=>$openid,
                ]);
            } else {
                $user = User::create([
                    'openid'=>$openid,
                ]);
            }
            session(['wechat.user' => $user]);
        }

        return $next($request);
    }
}