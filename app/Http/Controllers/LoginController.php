<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\DiamondPacks;
use App\Models\Gifts;
use App\Models\Interest;
use App\Models\LiveApplications;
use App\Models\RedeemRequest;
use App\Models\Report;
use App\Models\Users;
use App\Models\VerifyRequest;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    function index()
    {
        $totalUsers = Users::count();
        $liveStreamUsers = Users::where('can_go_live', 2)->count();
        $blockedUsers = Users::where('is_block', 1)->count();
        $liveApplications = LiveApplications::count();
        $pendingRedeems = RedeemRequest::where('status', 0)->count();
        $completedRedeems = RedeemRequest::where('status', 1)->count();
        $diamondPacks = DiamondPacks::count();
        $gifts = Gifts::count();
        $verifyRequests = VerifyRequest::count();
        $reports = Report::count();
        $interests = Interest::count();
        return view('index')->with([
            'totalUsers' => $totalUsers, 
            'liveStreamUsers' => $liveStreamUsers,
            'blockedUsers' => $blockedUsers,
            'liveApplications' => $liveApplications,
            'pendingRedeems' => $pendingRedeems,
            'completedRedeems' => $completedRedeems,
            'gifts' => $gifts,
            'diamondPacks' => $diamondPacks,
            'verifyRequests' => $verifyRequests,
            'reports' => $reports,
            'interests' => $interests,
        ]);
    }
    function login()
    {

        return  view('login.login');
    }

    function checklogin(Request $req)
    {

        $data = Admin::where('user_name', $req->user_name)->first();

        if ($req->user_name == $data['user_name'] && $req->user_password == $data['user_password']) {


            $req->session()->put('user_name', $data['user_name']);
            $req->session()->put('user_password', $data['user_password']);
            $req->session()->put('user_type', $data['user_type']);
            return  json_encode(['status' => true, "message" => "login susseccfull"]);
        } else {
            return   json_encode(['status' => false, "message" => "somethig wrong"]);
        }
    }

    function logout()
    {

        session()->pull('user_name');
        session()->pull('user_password');
        session()->pull('user_type');
        return  redirect(url('/'));
    }

    function profile()
    {
        $data = Admin::first();
        return view('setting.profile', ['data' => $data]);
    }

    function updateProflie(Request $req)
    {

        $item = Admin::where('user_id', 1)->update([
            'user_password' => $req->user_password,
            'user_name' => $req->user_name
        ]);


        return  json_encode(['status' => true, "message" => "Update susseccfull"]);
    }
}
