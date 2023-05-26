<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{

    function addReport(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'reason' => 'required',
            'description' => 'required',
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

        if ($user->is_block == 0) {
            $report = new Report();
            $report->description = $request->description;
            $report->user_id = $request->user_id;
            $report->reason = $request->reason;
            $report->save();
        }
        return json_encode(['status' => true, 'message' => __('app.AddSuccessful')]);
    }

    function fetchAllReport(Request $request)
    {

        $totalData =  Report::count();
        $rows = Report::orderBy('id', 'DESC')->with('user')->get();


        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'reason'
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = Report::count();
        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = Report::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)->with('user')
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Report::with('user')
                ->whereHas('user', function ($query) use ($search) {
                    $query->Where('fullname', 'LIKE', "%{$search}%")
                        ->orWhere('identity', 'LIKE', "%{$search}%");
                })
                ->orWhere('reason', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)->with('user')
                ->get();
            $totalFiltered = Report::with('user')
                ->whereHas('user', function ($query) use ($search) {
                    $query->Where('fullname', 'LIKE', "%{$search}%")
                        ->orWhere('identity', 'LIKE', "%{$search}%");
                })
                ->orWhere('reason', 'LIKE', "%{$search}%")
                ->count();
        }
        $data = array();
        foreach ($result as $item) {



            if ($item->user->images[0] != null) {
                $imgUrl = "public/storage/" . $item->user->images[0]->image;
            } else {
                $imgUrl = "http://placehold.jp/150x150.png";
            }
            $image = '<img src="' . $imgUrl . '" width="50" height="50">';


            $block = "";
            if ($item->user->is_block  == 0) {
                $block  =  '<a class=" btn btn-danger text-white block" rel=' . $item->user->id . ' >' . __('app.Block') . '</a>';
            } else {
                $block  =  '<a class=" btn btn-success  text-white unblock " rel=' . $item->user->id . ' >' . __('app.Unblock') . '</a>';
            }

            $data[] = array(
                $image,
                $item->user->identity,
                $item->user->fullname,
                $item->reason,
                $item->description,
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
}
