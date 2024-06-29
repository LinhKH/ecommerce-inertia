<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Illuminate\Http\Request;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $path = $request->path();
        if(($path == 'admin') && Session::get('admin')){
            return redirect('/admin/dashboard');
        }
        else if(($path!="admin") && (!Session::get('admin'))){
            return redirect('/admin');
        }

        return $next($request);
    }
}
