<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\Interest;
use App\Models\Myfunction;
use App\Models\UserNotification;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    //

    function notifyLikedUser(Request $req)
    {
        $rules = [
            'user_id' => 'required',
            'data_user_id' => 'required',
            'type' => 'required',
        ];

        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }
        $user = Users::find($req->user_id);
        $data_user = Users::find($req->data_user_id);
        if ($user == null) {
            return json_encode([
                'status' => false,
                'message' => 'user not found!',
            ]);
        }
        if ($data_user == null) {
            return json_encode([
                'status' => false,
                'message' => 'data user not found!',
            ]);
        }

        $userNotification = new UserNotification();
        $userNotification->user_id = $user->id;
        $userNotification->data_user_id = $data_user->id;
        $userNotification->type = $req->type;
        $userNotification->save();

        $message = "";
        if ($req->type == 1) {
            $message = $data_user->fullname . " has liked your profile, you should check their profile!";
        }
        Myfunction::sendPushToUser($data_user->fullname, $message, $user->device_token);

        return response()->json(['status' => true, 'message' => 'notification added successfully!']);
    }

    function notifications(Request $req)
    {
        return view('notifications');
    }

    function updateNotification(Request $request)
    {
        $notification = AdminNotification::find($request->id);
        $notification->title = $request->title;
        $notification->message = $request->message;
        $result = $notification->save();

        if ($result) {
            return json_encode(['status' => true, 'message' => 'Notification update successfully']);
        } else {
            return json_encode(['status' => false, 'message' => 'something went wrong']);
        }
    }

    function getNotificationById($id)
    {
        $data = AdminNotification::where('id', $id)->first();
        echo json_encode($data);
    }

    function deleteNotification($id)
    {
        $data =  AdminNotification::where('id', $id);
        $data->delete();

        $data1['status'] = true;

        echo json_encode($data1);
    }

    function addNotification(Request $request)
    {

        $notification = new AdminNotification();
        $notification->title = $request->title;
        $notification->message = $request->message;
        $result = $notification->save();

        $url = 'https://fcm.googleapis.com/fcm/send';
        $api_key = env('FCMKEY');
        $notificationArray = array('title' => $notification->title, 'body' => $notification->message, 'sound' => 'default', 'badge' => '1');

        $fields = array('to' => '/topics/orange', 'notification' => $notificationArray, 'priority' => 'high');
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $api_key
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // print_r(json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
            Log::debug(curl_error($ch));
        }
        curl_close($ch);


        if ($result) {
            return json_encode(['status' => true, 'message' => __('app.AddSuccessful')]);
        } else {
            return json_encode(['status' => false, 'message' => 'something went wrong']);
        }
    }

    function fetchAllNotification(Request $request)
    {

        $totalData =  AdminNotification::count();
        $rows = AdminNotification::orderBy('id', 'DESC')->get();

        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'title'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = AdminNotification::count();
        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = AdminNotification::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  AdminNotification::Where('title', 'LIKE', "%{$search}%")
                ->orWhere('message', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = AdminNotification::where('title', 'LIKE', "%{$search}%")
                ->orWhere('message', 'LIKE', "%{$search}%")
                ->count();
        }
        $data = array();
        foreach ($result as $item) {


            if ($item->is_block == 0) {
                $block  =   '<a href=""  rel="' . $item->id . '"   class="btn btn-primary  edit_cats mr-2"><i class="fas fa-edit"></i></a><a href = ""  rel = "' . $item->id . '" class = "btn btn-danger delete-cat text-white" > <i class="fas fa-trash-alt"></i> </a>';
            }

            $data[] = array(
                '<p>' . $item->title . '</p>',
                '<p>' . $item->message . '</p>',
                $block

            );
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        );
        echo json_encode($json_data);
        exit();
    }

    function getAdminNotifications(Request $req)
    {
        $rules = [
            'start' => 'required',
            'count' => 'required',
        ];

        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $result =  AdminNotification::offset($req->start)
            ->limit($req->count)
            ->orderBy('id', 'DESC')
            ->get();


        if ($result->isEmpty()) {
            return json_encode([
                'status' => true,
                'message' => 'No data found',
                'data' => $result
            ]);
        }


        return json_encode([
            'status' => true,
            'message' => 'data get successfully',
            'data' => $result
        ]);
    }

    function getUserNotifications(Request $req)
    {
        $rules = [
            'user_id' => 'required',
            'start' => 'required',
            'count' => 'required',
        ];

        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $result =  UserNotification::Where('user_id', '=', $req->user_id)
            ->with('data_user')
            ->with('data_user.images')
            ->offset($req->start)
            ->limit($req->count)
            ->orderBy('id', 'DESC')
            ->get();


        if ($result->isEmpty()) {
            return json_encode([
                'status' => true,
                'message' => 'No data found',
                'data' => $result
            ]);
        }

        foreach ($result as $item) {
            $item->data_user->interests  = Interest::whereIn('id', explode(",", $item->data_user->interests))->get();
        }

        return json_encode([
            'status' => true,
            'message' => 'data get successfully',
            'data' => $result
        ]);
    }
}
