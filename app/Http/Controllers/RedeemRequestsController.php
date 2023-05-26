<?php

namespace App\Http\Controllers;

use App\Models\RedeemRequest;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RedeemRequestsController extends Controller
{
    //

    function redeemrequests()
    {
        return view('redeemrequests');
    }

    function getRedeemById($id)
    {
        $data = RedeemRequest::with('user')->where('id', $id)->first();
        $data->user->image = $data->user->images[0]->image;
        echo json_encode($data);
    }

    function completeRedeem(Request $request)
    {
        $redeem = RedeemRequest::find($request->id);
        $redeem->status = 1;
        $redeem->amount_paid = $request->amount_paid;
        $result = $redeem->save();

        if ($result) {
            return json_encode(['status' => true, 'message' => 'Redeem Request updated successfully']);
        } else {
            return json_encode(['status' => false, 'message' => 'something went wrong']);
        }
    }

    function fetchCompletedRedeems(Request $request)
    {

        $totalData =  RedeemRequest::where('status', '=', 1)->count();
        $rows = RedeemRequest::orderBy('id', 'DESC')->get();

        $result = $rows;

        $columns = array(
            0 => 'id'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = RedeemRequest::where('status', '=', 1)->count();

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = RedeemRequest::where('status', '=', 1)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  RedeemRequest::where('status', 1)
                ->Where('request_id', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = RedeemRequest::where('status', 1)
                ->Where('request_id', 'LIKE', "%{$search}%")
                ->count();
        }
        $data = array();

        $app_data = DB::table('appdata')->first();

        foreach ($result as $item) {

            if (count($item->user->images) > 0) {
                $image = '<img src="public/storage/' . $item->user->images[0]->image . '" width="50" height="50">';
            } else {
                $image = '<img src="http://placehold.jp/150x150.png" width="50" height="50">';
            }

            $block  =   '<a href=""  rel="' . $item->id . '"   class="btn btn-primary view-request mr-2">View</a><a href = ""  rel = "' . $item->id . '" class = "btn btn-danger delete-cat text-white" > Delete </a>';

            $data[] = array(
                $image,
                '<p>' . $item->user->fullname . '</p>',
                '<p>' . $item->request_id . '</p>',
                '<p>' . $item->coin_amount . '</p>',
                '<p>' . $item->amount_paid . '</p>',
                '<p>' . $item->payment_gateway . '</p>',
                $block,

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

    function deleteRedeemRequest($id)
    {
        $data =  RedeemRequest::where('id', $id);
        $data->delete();

        $data1['status'] = true;

        echo json_encode($data1);
    }

    function fetchPendingRedeems(Request $request)
    {

        $totalData =  RedeemRequest::where('status', '=', 0)->count();
        $rows = RedeemRequest::orderBy('id', 'DESC')->get();

        $result = $rows;

        $columns = array(
            0 => 'id'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = RedeemRequest::where('status', '=', 0)->count();

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = RedeemRequest::where('status', '=', 0)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  RedeemRequest::where('status', 0)
                ->Where('request_id', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = RedeemRequest::where('status', 0)
                ->Where('request_id', 'LIKE', "%{$search}%")
                ->count();
        }
        $data = array();

        $app_data = DB::table('appdata')->first();

        foreach ($result as $item) {

            if (count($item->user->images) > 0) {
                $image = '<img src="public/storage/' . $item->user->images[0]->image . '" width="50" height="50">';
            } else {
                $image = '<img src="http://placehold.jp/150x150.png" width="50" height="50">';
            }

            $block  =   '<a href=""  rel="' . $item->id . '"   class="btn btn-success complete-redeem mr-2">Complete</a><a href = ""  rel = "' . $item->id . '" class = "btn btn-danger delete-cat text-white" > Delete </a>';

            $payable_Amount = $app_data->coin_rate * $item->coin_amount;

            $data[] = array(
                $image,
                '<p>' . $item->user->fullname . '</p>',
                '<p>' . $item->request_id . '</p>',
                '<p>' . $item->coin_amount . '</p>',
                '<p>' . $app_data->currency . ' ' . $payable_Amount . '</p>',
                '<p>' . $item->payment_gateway . '</p>',
                $block,

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

    function placeRedeemRequest(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'payment_gateway' => 'required',
            'account_details' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $user = Users::find($request->user_id);
        if ($user == null) {
            return json_encode([
                'status' => false,
                'message' => 'user not found!',
            ]);
        }

        $appdata = DB::table('appdata')->first();

        if ($user->wallet < $appdata->min_threshold) {
            return json_encode([
                'status' => false,
                'message' => 'User has not completed minimum threshold!',
            ]);
        }

        $redeemRequest = new RedeemRequest();
        $redeemRequest->user_id = $user->id;
        $redeemRequest->request_id = $this->generateCode();
        $redeemRequest->coin_amount = $user->wallet;
        $redeemRequest->payment_gateway = $request->payment_gateway;
        $redeemRequest->account_details = $request->account_details;
        $redeemRequest->account_details = $request->account_details;

        $user->wallet = 0;
        $user->save();

        $result = $redeemRequest->save();
        if ($result) {
            return json_encode([
                'status' => true,
                'message' => 'Redeem Request placed successfully!',
            ]);
        } else {
            return json_encode([
                'status' => false,
                'message' => 'something went wrong!',
            ]);
        }
    }

    function fetchMyRedeemRequests(Request $request)
    {
        $rules = [
            'user_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $user = Users::find($request->user_id);
        if ($user == null) {
            return json_encode([
                'status' => false,
                'message' => 'user not found!',
            ]);
        }
        $redeems = RedeemRequest::where('user_id', $request->user_id)->get();

        return json_encode([
            'status' => true,
            'message' => 'Data fetch successfully !',
            'data' => $redeems,
        ]);
    }


    function generateCode()
    {


        function generateRandomString($length)
        {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }


        $token =  rand(100000, 999999);

        $first = generateRandomString(3);
        $first .= $token;
        $first .= generateRandomString(3);



        $count = RedeemRequest::where('request_id', $first)->count();

        while ($count >= 1) {

            $token =  rand(100000, 999999);

            $first = generateRandomString(3);
            $first .= $token;
            $first .= generateRandomString(3);
            $count = RedeemRequest::where('request_id', $first)->count();
        }

        return $first;
    }
}
