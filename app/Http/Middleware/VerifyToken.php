<?php

namespace App\Http\Middleware;

class VerifyToken
{
    protected static $except = [
        'Home/Index/index',
        'Home/Index/checktoken',
        'Home/Chat/index',
        'Home/Bindchat/index',
        'Admin/Login/index',
        'Admin/Login/login',
    ];

    public function handle($request)
    {
        if (in_array(implode('/', $request), self::$except)) 
            return true;

        //检查登录状态
        switch ($request['class']) {
            case 'Home':
                if (empty(\frame\Session::get('home_member_id')))
                    redirect(url('login'));
                break;
            case 'Admin':
                // if (empty(\frame\Session::get('admin_member_id')))
                    // redirect(url('login'));
                break;
        }
    }
}
