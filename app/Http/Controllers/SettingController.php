<?php

namespace App\Http\Controllers;

use App\Models\Gifts;
use App\Models\Myfunction;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
   function updateGift(Request $request)
   {
      $gift = Gifts::find($request->id);
      $gift->coin_price = $request->coin_price;
      if ($request->has('image')) {
         $gift->image = $request->file('image')->store('uploads');
      }
      $gift->save();

      $data1['status'] = true;
      echo json_encode($data1);
   }
   function addGift(Request $request)
   {
      $gift = new Gifts();
      $gift->image = $request->file('image')->store('uploads');
      $gift->coin_price = $request->coin_price;
      $gift->save();

      $data1['status'] = true;
      echo json_encode($data1);
   }
   function deleteGift($id)
   {
      $data =  Gifts::where('id', $id);
      $data->delete();

      $data1['status'] = true;
      echo json_encode($data1);
   }
   function fetchAllGifts(Request $request)
   {
      $totalData =  Gifts::count();
      $rows = Gifts::orderBy('id', 'DESC')->get();

      $result = $rows;

      $columns = array(
         0 => 'id',
         1 => 'coin_price'
      );

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');
      $totalData = Gifts::count();
      $totalFiltered = $totalData;
      if (empty($request->input('search.value'))) {
         $result = Gifts::offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();
      } else {
         $search = $request->input('search.value');
         $result =  Gifts::Where('coin_price', 'LIKE', "%{$search}%")
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();
         $totalFiltered = Gifts::where('coin_price', 'LIKE', "%{$search}%")
            ->count();
      }
      $data = array();
      foreach ($result as $item) {

         $image = '<img src="public/storage/' . $item->image . '" width="50" height="50">';
         $imgUrl = env('image') . $item->image;

         if ($item->is_block == 0) {
            $block  =   '<a href="" data-img="' . $imgUrl . '" data-price="' . $item->coin_price . '"  rel="' . $item->id . '"   class="btn btn-primary  edit mr-2"><i class="fas fa-edit"></i></a><a href = ""  rel = "' . $item->id . '" class = "btn btn-danger delete text-white" > <i class="fas fa-trash-alt"></i> </a>';
         }

         $data[] = array(
            $image,
            $item->coin_price,
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
   function gifts()
   {
      return view('gifts');
   }
   function storeFileGivePath(Request $request)
   {
      $path = $request->file('file')->store('uploads');
      return json_encode(['status' => true, 'message' => __('app.Updatesuccessful'), 'path' => $path]);
   }
   function setting()
   {

      $admob = DB::table('admob')->get();
      $appdata = DB::table('appdata')->first();
      return view('setting', ['admob' => $admob, 'appdata' => $appdata]);
   }

   function updateAdmob(Request $req)
   {

      $admob = DB::table('admob')->where('id', $req->admob_id)->update(['banner_id' => Myfunction::customReplace($req->banner_id), 'native_id' => Myfunction::customReplace($req->native_id), 'interstitial_id' => Myfunction::customReplace($req->interstitial_id)]);

      return json_encode(['status' => true, 'message' => __('app.Updatesuccessful')]);
   }

   function updateOther(Request $req)
   {

      $admob = DB::table('othersettingdata')->where('id', $req->other_id)->update(['terms' => Myfunction::customReplace($req->terms), 'contact' => Myfunction::customReplace($req->contact), 'privacy' => Myfunction::customReplace($req->privacy)]);

      return json_encode(['status' => true, 'message' => __('app.Updatesuccessful')]);
   }

   function updateAppdata(Request $req)
   {

      DB::table('appdata')->where('id', 1)->update([
         'currency' => $req->currency,
         'min_threshold' => $req->min_threshold,
         'min_user_live' => $req->min_user_live,
         'max_minute_live' => $req->max_minute_live,
         'message_price' => $req->message_price,
         'reverse_swipe_price' => $req->reverse_swipe_price,
         'max_minute_live' => $req->max_minute_live,
         'coin_rate' => $req->coin_rate,
         'admob_int_ios' => $req->admob_int_ios,
         'admob_banner_ios' => $req->admob_banner_ios,
         'admob_int' => $req->admob_int,
         'admob_banner' => $req->admob_banner,
         'live_watching_price' => $req->live_watching_price,
      ]);

      return json_encode(['status' => true, 'message' => __('app.Updatesuccessful')]);
   }

   function getSettingData(Request $req)
   {
      $data['appdata'] = DB::table('appdata')->first();
      $data['gifts'] = Gifts::all();
      return json_encode(['status' => true, 'message' => __('app.fetchSuccessful'), 'data' => $data]);
   }
}
