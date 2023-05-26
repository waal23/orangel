<?php

namespace App\Http\Controllers;

use App\Models\Interest;
use App\Models\Myfunction;
use Illuminate\Http\Request;

class InterestController extends Controller
{
    function fetchAllInterest(Request $request)
    {


        $totalData =  Interest::count();
        $rows = Interest::orderBy('id', 'DESC')->get();


        $categories = $rows;

        $columns = array(
            0 => 'id',
            1 => 'title'
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = Interest::count();
        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $categories = Interest::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $categories =  Interest::Where('title', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Interest::where('id', 'LIKE', "%{$search}%")
                ->orWhere('title', 'LIKE', "%{$search}%")
                ->count();
        }
        $data = array();
        foreach ($categories as $cat) {

            $edit = '<a href="" data-toggle="modal" id="' . $cat->id . '" rel="' . $cat->image . '"  data-id="' . $cat->title . '" data-target="#edit_cat_modal" class="btn btn-primary mr-2 edit_cats"><i class="fas fa-edit"></i></a>';
            $delete = '<a href = ""  rel = "' . $cat->id . '" class = "btn btn-danger delete-cat text-white" > <i class="fas fa-trash-alt"></i> </a>';

            $action = $edit . $delete;

            $data[] = array(
                $cat->title,
                $action
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


    function addInterest(Request $req)
    {
        $cat = new Interest();

        $cat->title =   Myfunction::customReplace($req->title);

        $cat->save();
        $data['status'] = true;
        $data['message'] = "add successfull";

        echo json_encode($data);
    }


    function updateInterest(Request $req)
    {




        if ($req->image == "") {
            Interest::where('id', $req->id)->update(['title' => Myfunction::customReplace($req->title)]);
        } else {
            $path = $req->file('image')->store('uploads');
            Interest::where('id', $req->id)->update(['title' => Myfunction::customReplace($req->title), 'image' => $path]);
        }

        $data['status'] = true;
        $data['message'] = __('app.Updatesuccessful');

        echo json_encode($data);
    }

    function getInterests()
    {
        $data = Interest::orderBy('id', 'DESC')->get();

        return json_encode(['status' => true, 'message' => __('app.fetchSuccessful'), 'data' => $data]);
    }
    function deleteInterest($id)
    {

        $data =  Interest::where('id', $id);
        $data->delete();

        $data1['status'] = true;
        $data1['message'] = "delete successfull";

        echo json_encode($data1);
    }
}
