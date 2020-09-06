<?php

namespace App\Http\Middleware;

class VerifyToken
{
    protected static $except = [
        'Home/Index/index',
        'Home/Index/checktoken',
        'Home/Chat/index',
        'Home/Bindchat/index',
    ];

    public function handle($request)
    {
        if (in_array(implode('/', $request), self::$except)) 
            return true;

        //检查登录状态
        switch ($request['class']) {
            case 'Home':
                $headers = getallheaders();
                $access_token = $headers['Access-Token'] ?? null;
                if (empty($access_token))
                    redirect(url(''));
                $memberService = make('App/Services/MemberService');
                $res = $memberService->getToken($access_token);
                if (!$res || $res['type'] != 1)
                    redirect(url(''));
                $GLOBALS['login_member_id'] = $res['member_id'];
                break;
            case 'Admin':
                $headers = getallheaders();
                $access_token = $headers['Access-Token'] ?? null;
                if (empty($access_token))
                    redirect(url(''));
                $memberService = make('App/Services/MemberService');
                $res = $memberService->getToken($access_token);
                if (!$res || $res['type'] != 5)
                    redirect(url(''));
                $GLOBALS['login_member_id'] = $res['member_id'];
                break;
        }
    }
}
