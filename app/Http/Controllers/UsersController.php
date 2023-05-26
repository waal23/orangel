<?php

namespace App\Http\Controllers;

use App\Models\Images;
use App\Models\Interest;
use App\Models\LiveApplications;
use App\Models\LiveHistory;
use App\Models\Myfunction;
use App\Models\RedeemRequest;
use App\Models\Report;
use App\Models\UserNotification;
use App\Models\Users;
use App\Models\VerifyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class UsersController extends Controller
{

    function logOutUser(Request $request)
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

        $user->device_token = null;
        $user->save();

        return response()->json(['status' => true, 'message' => 'User logged out successfully !']);
    }

    function fetchUsersByCordinates(Request $request)
    {
        $rules = [
            'lat' => 'required',
            'long' => 'required',
            'km' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $users = Users::with('images')->where('is_block', 0)->where('is_fake', 0)->where('show_on_map', 1)->where('anonymous', 0)->get();

        $usersData = [];
        foreach ($users as $user) {

            $distance = Myfunction::point2point_distance($request->lat, $request->long, $user->lattitude, $user->longitude, "K", $request->km);
            $user['interests']  = Interest::whereIn('id', explode(",", $user['interests']))->get();
            if ($distance) {
                array_push($usersData, $user);
            }
        }
        return response()->json(['status' => true, 'message' => 'Data fetched successfully !', 'data' => $usersData]);
    }

    function addUserImage(Request $req)
    {
        $img = new Images();
        $imgfile = $req->file('image');
        $path = $imgfile->store('uploads');
        $img->image = $path;
        $img->user_id = $req->id;
        $img->save();

        return json_encode([
            'status' => true,
            'message' => 'Image Added successfully!',
        ]);
    }
    function deleteUserImage($imgId)
    {
        $img = Images::find($imgId);

        $imgCount = Images::where('user_id', $img->user_id)->count();
        if ($imgCount == 1) {
            return json_encode([
                'status' => false,
                'message' => 'Minimum one image is required !',
            ]);
        }

        unlink(storage_path('app/public/' . $img->image));
        $img->delete();
        return json_encode([
            'status' => true,
            'message' => 'Image Deleted successfully!',
        ]);
    }
    function updateUser(Request $req)
    {
        $result = Users::where('id', $req->id)->update([
            "fullname" => $req->fullname,
            "age" => $req->age,
            "password" => $req->password,
            "bio" => $req->bio,
            "about" => $req->about,
            "instagram" => $req->instagram,
            "youtube" => $req->youtube,
            "facebook" => $req->facebook,
            "live" => $req->live,
        ]);

        return json_encode([
            'status' => true,
            'message' => 'data updates successfully!',
        ]);
    }
    function test(Request $req)
    {

        $user = Users::with('liveApplications')->first();

        $intrestIds = Interest::inRandomOrder()->limit(4)->pluck('id');

        return json_encode(['data' => $intrestIds]);
    }

    function addFakeUserFromAdmin(Request $request)
    {
        $user = new Users();
        $user->identity = Myfunction::generateFakeUserIdentity();
        $user->fullname = $request->fullname;
        $user->youtube = $request->youtube;
        $user->facebook = $request->facebook;
        $user->instagram = $request->instagram;
        $user->age = $request->age;
        $user->live = $request->live;
        $user->about = $request->about;
        $user->bio = $request->bio;
        $user->password = $request->password;
        $user->gender = $request->gender;
        $user->is_verified = 2;
        $user->can_go_live = 2;
        $user->is_fake = 1;

        // Interests
        $interestIds = Interest::inRandomOrder()->limit(4)->pluck('id')->toArray();
        $user->interests = implode(',', $interestIds);

        $user->save();
        foreach ($request->file('image') as $img) {
            $it = new Images();
            $path = $img->store('uploads');
            $it->image = $path;
            $it->user_id = $user->id;
            $it->save();
        }
        return response()->json(['status' => true, 'message' => "Fake user added successfully !"]);
    }

    function getExplorePageProfileList(Request $request)
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

        $blocked_users = explode(',', $user->blocked_users);
        array_push($blocked_users, $user->id);

        $profiles =  Users::with('images')->whereNotIn('id', $blocked_users)->where('is_block', 0)->inRandomOrder()->limit(15)->get();

        foreach ($profiles as $user) {
            $user['interests']  = Interest::whereIn('id', explode(",", $user['interests']))->get();
        }

        return json_encode([
            'status' => true,
            'message' => 'data found successfully!',
            'data' => $profiles,
        ]);
    }

    function getRandomProfile(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'gender' => 'required',
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

        $blocked_users = explode(',', $user->blocked_users);
        array_push($blocked_users, $user->id);
        if ($request->gender == 3) {
            $randomUser =  Users::with('images')->whereNotIn('id', $blocked_users)->where('is_block', 0)->inRandomOrder()->first();
        } else {
            $randomUser =  Users::with('images')->whereNotIn('id', $blocked_users)->where('is_block', 0)->where('gender', $request->gender)->inRandomOrder()->first();
        }
        $randomUser['interests']  = Interest::whereIn('id', explode(",", $user['interests']))->get();

        return json_encode([
            'status' => true,
            'message' => 'data found successfully!',
            'data' => $randomUser,
        ]);
    }

    function updateUserBlockList(Request $request)
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

        $user = Users::where('id', $request->user_id)->first();
        if ($user == null) {
            return response()->json(['status' => false, 'message' => "User doesn't exists !"]);
        }

        $user->blocked_users = $request->blocked_users;
        $user->save();

        $data = Users::with('images')->where('id', $request->user_id)->first();

        $data['interests'] = Interest::whereIn('id', explode(",", $data['interests']))->get();

        return response()->json(['status' => true, 'message' => "Blocklist updated successfully !", 'data' => $data]);
    }

    function deleteMyAccount(Request $request)
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

        Images::where('user_id', $user->id)->delete();
        UserNotification::where('user_id', $user->id)->delete();
        LiveApplications::where('user_id', $user->id)->delete();
        LiveHistory::where('user_id', $user->id)->delete();
        RedeemRequest::where('user_id', $user->id)->delete();
        VerifyRequest::where('user_id', $user->id)->delete();
        Report::where('user_id', $user->id)->delete();
        UserNotification::where('data_user_id', $user->id)->delete();
        $user->delete();

        return response()->json(['status' => true, 'message' => "Account Deleted Successfully !"]);
    }

    function rejectVerificationRequest($id)
    {
        $verifyRequest = VerifyRequest::find($id);
        $verifyRequest->user->is_verified = 0;
        $verifyRequest->user->save();
        $verifyRequest->delete();
        return response()->json(['status' => true]);
    }

    function approveVerificationRequest($id)
    {
        $verifyRequest = VerifyRequest::find($id);
        $verifyRequest->user->is_verified = 2;
        $verifyRequest->user->save();
        $verifyRequest->delete();
        // GlobalFunction::sendPushToUser(env("APP_NAME"), "Your Profile has been verified successfully!" ,$verifyRequest->user->device_token);
        return response()->json(['status' => true]);
    }

    function fetchverificationRequests(Request $request)
    {
        $totalData =  VerifyRequest::count();
        $rows = VerifyRequest::orderBy('id', 'DESC')->get();
        $result = $rows;

        $columns = array(
            0 => 'id',
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = VerifyRequest::count();
        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = VerifyRequest::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  VerifyRequest::with('user')
                ->whereHas('user', function ($query) use ($search) {
                    $query->Where('fullname', 'LIKE', "%{$search}%")
                        ->orWhere('identity', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = VerifyRequest::with('user')
                ->whereHas('user', function ($query) use ($search) {
                    $query->Where('fullname', 'LIKE', "%{$search}%")
                        ->orWhere('identity', 'LIKE', "%{$search}%");
                })
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


            $selfieUrl = "public/storage/" . $item->selfie;
            $selfie = '<img style="cursor: pointer;" class="img-preview" rel="' . $selfieUrl . '" src="' . $selfieUrl . '" width="50" height="50">';

            $docUrl = "public/storage/" . ($item->document);
            $document = '<img style="cursor: pointer;" class="img-preview" rel="' . $docUrl . '" src="' . $docUrl . '" width="50" height="50">';


            $approve = '<a href=""class=" btn btn-success text-white approve ml-2" rel=' . $item->id . ' >' . __("Approve") . '</a>';
            $reject = '<a href=""class=" btn btn-danger text-white reject ml-2" rel=' . $item->id . ' >' . __("Reject") . '</a>';

            $action = $approve . $reject;

            $data[] = array(
                $image,
                $selfie,
                $document,
                $item->document_type,
                $item->fullname,
                $item->user->identity,
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

    function verificationrequests()
    {
        return view('verificationrequests');
    }

    function applyForVerification(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'document' => 'required',
            'document_type' => 'required',
            'selfie' => 'required',
            'fullname' => 'required',
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

        if ($user->is_verified == 1) {
            return json_encode([
                'status' => false,
                'message' => 'The request has been submitted already!',
            ]);
        }
        if ($user->is_verified == 2) {
            return json_encode([
                'status' => false,
                'message' => 'This user is already verified !',
            ]);
        }

        $verifyReq = new VerifyRequest();
        $verifyReq->user_id = $request->user_id;
        $verifyReq->document_type = $request->document_type;
        $verifyReq->fullname = $request->fullname;
        $verifyReq->status = 0;
        $verifyReq->document = $request->file('document')->store('uploads');
        $verifyReq->selfie = $request->file('selfie')->store('uploads');
        $verifyReq->save();

        $user->is_verified = 1;
        $user->save();

        $user['images'] = Images::where('user_id', $request->user_id)->get();
        $user['interests'] = Interest::whereIn('id', explode(",", $user['interests']))->get();

        return json_encode(['status' => true, 'message' => "Verification request submitted successfully !", 'data' => $user]);
    }

    function updateLikedProfile(Request $request)
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

        $user->likedprofile = $request->profiles;
        $user->save();

        $data = Users::where('id', $request->user_id)->first();

        $data['images'] = Images::where('user_id', $request->user_id)->get();
        $data['interests'] = Interest::whereIn('id', explode(",", $data['interests']))->get();


        return json_encode(['status' => true, 'message' => __('app.Updatesuccessful'), 'data' => $data]);
    }

    function fetchSavedProfiles(Request $request)
    {

        $rules = [
            'user_id' => 'required'
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

        $array = explode(',', $user->savedprofile);
        $data =  Users::whereIn('id', $array)->with('images')->get();
        $data = $data->reverse()->values();


        return json_encode([
            'status' => true,
            'message' => 'profiles fetched successfully!',
            'data' => $data
        ]);
    }

    function allowLiveToUser($id)
    {

        Users::where('id', $id)->update(['can_go_live' => 2]);
        return response()->json(['status' => true, 'message' => __('app.Updatesuccessful')]);
    }
    function restrictLiveToUser($id)
    {

        Users::where('id', $id)->update(['can_go_live' => 0]);
        return response()->json(['status' => true, 'message' => __('app.Updatesuccessful')]);
        //

    }

    function increaseStreamCountOfUser(Request $request)
    {
        $rules = [
            'user_id' => 'required'
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

        $user->total_streams += 1;
        $result = $user->save();

        if ($result) {
            return json_encode([
                'status' => true,
                'message' => 'Stream count increased successfully',
                'total_streams' => $user->total_streams
            ]);
        } else {
            return json_encode([
                'status' => false,
                'message' => 'something went wrong!',

            ]);
        }
    }

    function minusCoinsFromWallet(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'amount' => 'required'
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

        if ($user->wallet < $request->amount) {
            return json_encode([
                'status' => false,
                'message' => 'No enough coins in the wallet!',
                'wallet' => $user->wallet,
            ]);
        }

        $user->wallet -= $request->amount;
        $result = $user->save();

        if ($result) {
            return json_encode([
                'status' => true,
                'message' => 'coins deducted from wallet successfully',
                'wallet' => $user->wallet,
                'total_collected' => $user->total_collected,
            ]);
        } else {
            return json_encode([
                'status' => false,
                'message' => 'something went wrong!',

            ]);
        }
    }

    function addCoinsToWallet(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'amount' => 'required'
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

        $user->wallet  += $request->amount;
        $user->total_collected += $request->amount;
        $result = $user->save();

        if ($result) {
            return json_encode([
                'status' => true,
                'message' => 'coins added to wallet successfully',
                'wallet' => $user->wallet,
                'total_collected' => $user->total_collected,
            ]);
        } else {
            return json_encode([
                'status' => false,
                'message' => 'something went wrong!',

            ]);
        }
    }


    function updateLiveStatus(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'state' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $user = Users::find($request->user_id);
        $user->is_live_now = $request->state;
        $user->save();

        $data = Users::with('images')->where('id', $request->user_id)->first();
        $data['interests']  = Interest::whereIn('id', explode(",", $data['interests']))->get();

        return json_encode([
            'status' => true,
            'message' => 'is_live_now state updated successfully',
            'data' => $data
        ]);
    }
    function onOffVideoCalls(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'state' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $user = Users::find($request->user_id);
        $user->is_video_call = $request->state;
        $user->save();

        $data = Users::with('images')->where('id', $request->user_id)->first();
        $data['interests']  = Interest::whereIn('id', explode(",", $data['interests']))->get();

        return json_encode([
            'status' => true,
            'message' => 'is_video_call state updated successfully',
            'data' => $data
        ]);
    }

    function onOffAnonymous(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'state' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $user = Users::find($request->user_id);
        $user->anonymous = $request->state;
        $user->save();

        $data = Users::with('images')->where('id', $request->user_id)->first();
        $data['interests']  = Interest::whereIn('id', explode(",", $data['interests']))->get();

        return json_encode([
            'status' => true,
            'message' => 'anonymous state updated successfully',
            'data' => $data
        ]);
    }

    function onOffShowMeOnMap(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'state' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $user = Users::find($request->user_id);
        $user->show_on_map = $request->state;
        $user->save();

        $data = Users::with('images')->where('id', $request->user_id)->first();
        $data['interests']  = Interest::whereIn('id', explode(",", $data['interests']))->get();

        return json_encode([
            'status' => true,
            'message' => 'show_on_map state updated successfully',
            'data' => $data
        ]);
    }


    function onOffNotification(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'state' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $user = Users::find($request->user_id);
        $user->is_notification = $request->state;
        $user->save();

        $data = Users::with('images')->where('id', $request->user_id)->first();
        $data['interests']  = Interest::whereIn('id', explode(",", $data['interests']))->get();

        return json_encode([
            'status' => true,
            'message' => 'notification state updated successfully',
            'data' => $data
        ]);
    }

    function fetchAllUsers(Request $request)
    {

        $totalData =  Users::count();
        $rows = Users::orderBy('id', 'DESC')->get();


        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'fullname'
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = Users::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Users::Where('fullname', 'LIKE', "%{$search}%")
                ->orWhere('identity', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Users::where('identity', 'LIKE', "%{$search}%")
                ->orWhere('fullname', 'LIKE', "%{$search}%")
                ->count();
        }
        $data = array();
        foreach ($result as $item) {

            if ($item->is_block == 0) {
                $block  =  '<a class=" btn btn-danger text-white block" rel=' . $item->id . ' >' . __('app.Block') . '</a>';
            } else {
                $block  =  '<a class=" btn btn-success  text-white unblock " rel=' . $item->id . ' >' . __('app.Unblock') . '</a>';
            }

            if ($item->gender == 1) {
                $gender = ' <span  class="badge bg-dark text-white  ">' . __('app.Male') . '</span>';
            } else {
                $gender = '  <span  class="badge bg-dark text-white  ">' . __('app.Female') . '</span>';
            }

            if (count($item->images) > 0) {
                $image = '<img src="public/storage/' . $item->images[0]->image . '" width="50" height="50">';
            } else {
                $image = '<img src="http://placehold.jp/150x150.png" width="50" height="50">';
            }

            if ($item->can_go_live == 2) {
                $liveEligible = ' <span class="badge bg-success text-white  ">Yes</span>';;
            } else {
                $liveEligible = ' <span class="badge bg-danger text-white  ">No</span>';;
            }

            $action = '<a href="' . route('viewUserDetails', $item->id) . '"class=" btn btn-primary text-white " rel=' . $item->id . ' ><i class="fas fa-eye"></i></a>';

            $data[] = array(


                $image,
                $item->identity,
                $item->fullname,
                $liveEligible,
                $item->age,
                $gender,
                $block,
                $action,

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

    function fetchStreamerUsers(Request $request)
    {
        $totalData =  Users::where('can_go_live', '=', 2)->count();
        $rows = Users::where('can_go_live', '=', 2)->orderBy('id', 'DESC')->get();


        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'fullname'
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = Users::where('can_go_live', '=', 2)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Users::where(function ($query) use ($search) {
                $query->Where('fullname', 'LIKE', "%{$search}%")
                    ->orWhere('identity', 'LIKE', "%{$search}%");
            })
                ->where('can_go_live', '=', 2)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Users::where(function ($query) use ($search) {
                $query->Where('fullname', 'LIKE', "%{$search}%")
                    ->orWhere('identity', 'LIKE', "%{$search}%");
            })
                ->where('can_go_live', '=', 2)
                ->orWhere('fullname', 'LIKE', "%{$search}%")
                ->count();
        }
        $data = array();
        foreach ($result as $item) {

            if ($item->is_block == 0) {
                $block  =  '<a class=" btn btn-danger text-white block" rel=' . $item->id . ' >' . __('app.Block') . '</a>';
            } else {
                $block  =  '<a class=" btn btn-success  text-white unblock " rel=' . $item->id . ' >' . __('app.Unblock') . '</a>';
            }

            if ($item->gender == 1) {
                $gender = ' <span  class="badge bg-dark text-white  ">' . __('app.Male') . '</span>';
            } else {
                $gender = '  <span  class="badge bg-dark text-white  ">' . __('app.Female') . '</span>';
            }

            if (count($item->images) > 0) {
                $image = '<img src="public/storage/' . $item->images[0]->image . '" width="50" height="50">';
            } else {
                $image = '<img src="http://placehold.jp/150x150.png" width="50" height="50">';
            }

            if ($item->can_go_live == 2) {
                $liveEligible = ' <span class="badge bg-success text-white  ">Yes</span>';;
            } else {
                $liveEligible = ' <span class="badge bg-danger text-white  ">No</span>';;
            }

            $action = '<a href="' . route('viewUserDetails', $item->id) . '"class=" btn btn-primary text-white " rel=' . $item->id . ' ><i class="fas fa-eye"></i></a>';

            $data[] = array(


                $image,
                $item->identity,
                $item->fullname,
                $liveEligible,
                $item->age,
                $gender,
                $block,
                $action,

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
    function fetchFakeUsers(Request $request)
    {
        $totalData =  Users::where('is_fake', '=', 1)->count();
        $rows = Users::where('is_fake', '=', 1)->orderBy('id', 'DESC')->get();


        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'fullname'
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = Users::where('is_fake', '=', 1)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Users::where(function ($query) use ($search) {
                $query->Where('fullname', 'LIKE', "%{$search}%")
                    ->orWhere('identity', 'LIKE', "%{$search}%");
            })
                ->where('is_fake', '=', 1)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Users::where(function ($query) use ($search) {
                $query->Where('fullname', 'LIKE', "%{$search}%")
                    ->orWhere('identity', 'LIKE', "%{$search}%");
            })
                ->where('is_fake', '=', 1)
                ->orWhere('fullname', 'LIKE', "%{$search}%")
                ->count();
        }
        $data = array();
        foreach ($result as $item) {

            if ($item->is_block == 0) {
                $block  =  '<a class=" btn btn-danger text-white block" rel=' . $item->id . ' >' . __('app.Block') . '</a>';
            } else {
                $block  =  '<a class=" btn btn-success  text-white unblock " rel=' . $item->id . ' >' . __('app.Unblock') . '</a>';
            }

            if ($item->gender == 1) {
                $gender = ' <span  class="badge bg-dark text-white  ">' . __('app.Male') . '</span>';
            } else {
                $gender = '  <span  class="badge bg-dark text-white  ">' . __('app.Female') . '</span>';
            }

            if (count($item->images) > 0) {
                $image = '<img src="public/storage/' . $item->images[0]->image . '" width="50" height="50">';
            } else {
                $image = '<img src="http://placehold.jp/150x150.png" width="50" height="50">';
            }

            $action = '<a href="' . route('viewUserDetails', $item->id) . '"class=" btn btn-primary text-white " rel=' . $item->id . ' ><i class="fas fa-eye"></i></a>';

            $data[] = array(
                $image,
                $item->fullname,
                $item->identity,
                $item->password,
                $item->age,
                $gender,
                $block,
                $action,

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

    function addUserDetails(Request $req)
    {

        if ($req->has('password')) {
            $data = Users::where('identity', $req->identity)->where('password', $req->password)->first();
            if ($data == null) {
                return json_encode(['status' => false, 'message' => "Incorrect Identity and Password combination"]);
            }
        }


        $data = Users::where('identity', $req->identity)->first();

        if ($data == null) {
            $user = new Users;

            $user->fullname = Myfunction::customReplace($req->fullname);
            $user->identity = $req->identity;
            $user->device_token = $req->device_token;
            $user->device_type = $req->device_type;
            $user->login_type = $req->login_type;

            $user->save();

            $data =  Users::with('images')->where('id', $user->id)->first();
            $data['interests']  = Interest::whereIn('id', explode(",", $data['interests']))->get();


            return json_encode(['status' => true, 'message' => __('app.UserAddSuccessful'), 'data' => $data]);
        } else {
            Users::where('identity', $req->identity)->update([
                'device_token' => $req->device_token,
                'device_type' => $req->device_type,
                'login_type' => $req->login_type,

            ]);

            $data = Users::with('images')->where('id', $data['id'])->first();
            $data['interests']  = Interest::whereIn('id', explode(",", $data['interests']))->get();

            return json_encode(['status' => true, 'message' => __('app.UserAllReadyExists'), 'data' => $data]);
        }
    }

    function searchUsersForInterest(Request $req)
    {

        $rules = [
            'start' => 'required',
            'count' => 'required',
            'interest_id' => 'required',
        ];

        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $interestID = $req->interest_id;

        $result =  Users::with('images')
            ->Where('fullname', 'LIKE', "%{$req->keyword}%")
            ->whereRaw("find_in_set($interestID , interests)")
            ->offset($req->start)
            ->limit($req->count)
            ->get();

        foreach ($result as $user) {
            $user['interests']  = Interest::whereIn('id', explode(",", $user['interests']))->get();
        }

        if (isEmpty($result)) {
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

    function searchUsers(Request $req)
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

        $result =  Users::with('images')
            ->Where('fullname', 'LIKE', "%{$req->keyword}%")
            ->offset($req->start)
            ->limit($req->count)
            ->get();

        foreach ($result as $user) {
            $user['interests']  = Interest::whereIn('id', explode(",", $user['interests']))->get();
        }

        if (isEmpty($result)) {
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


    function updateProfile(Request $req)
    {


        $data = Users::where('id', $req->user_id)->first();

        if ($data == null) {


            return json_encode(['status' => false, 'message' => __('app.UserNotFound')]);
        } else {


            if ($req->deleteimagestitle != null) {
                foreach ($req->deleteimagestitle as $oneImageData) {
                    unlink(storage_path('app/public/' . $oneImageData));
                }
            }


            if ($req->has("deleteimageids")) {
                Images::whereIn('id', $req->deleteimageids)->delete();
            }

            $user = Users::find($req->user_id);
            if ($req->has("fullname")) {
                $user->fullname = Myfunction::customReplace($req->fullname);
            }
            if ($req->has("gender")) {
                $user->gender = $req->gender;
            }
            if ($req->has('youtube')) {
                $user->youtube = $req->youtube;
            }
            if ($req->has("instagram")) {
                $user->instagram = $req->instagram;
            }
            if ($req->has("facebook")) {
                $user->facebook = $req->facebook;
            }
            if ($req->has("live")) {
                $user->live =  Myfunction::customReplace($req->live);
            }
            if ($req->has("bio")) {
                $user->bio = Myfunction::customReplace($req->bio);
            }
            if ($req->has("about")) {
                $user->about = Myfunction::customReplace($req->about);
            }
            if ($req->has("lattitude")) {
                $user->lattitude = $req->lattitude;
            }
            if ($req->has("longitude")) {
                $user->longitude = $req->longitude;
            }
            if ($req->has("age")) {
                $user->age = $req->age;
            }
            if ($req->has("interests")) {
                $user->interests = $req->interests;
            }
            $result = $user->save();

            if ($req->file('image')) {
                foreach ($req->file('image') as $img) {
                    $it = new Images();
                    $path = $img->store('uploads');
                    $it->image = $path;
                    $it->user_id = $req->user_id;
                    $it->save();
                }
            }



            $data = Users::where('id', $req->user_id)->first();

            $data['images']  = Images::where('user_id', $req->user_id)->get();
            $data['interests']  = Interest::whereIn('id', explode(",", $data['interests']))->get();

            return json_encode(['status' => true, 'message' => __('app.Updatesuccessful'), 'data' => $data]);
        }
    }


    function blockUser($id)
    {
        Users::where('id', $id)->update(['is_block' => 1]);
        Report::where('user_id', $id)->delete();
        return response()->json(['status' => true, 'message' => __('app.Updatesuccessful')]);
    }

    function unblockUser($id)
    {
        Users::where('id', $id)->update(['is_block' => 0]);

        return response()->json(['status' => true, 'message' => __('app.Updatesuccessful')]);
    }


    function viewUserDetails($id)
    {

        $data =  Users::where('id', $id)->with('images')->first();

        return view('viewuser', ['data' => $data]);
    }

    function getProfile(Request $req)
    {

        $data = Users::where('id', $req->user_id)->first();

        $data['images']  = Images::where('user_id', $req->user_id)->get();
        $data['interests']  = Interest::whereIn('id', explode(",", $data['interests']))->get();

        return json_encode(['status' => true, 'message' => __('app.fetchSuccessful'), 'data' => $data]);
    }

    public function updateSavedProfile(Request $req)
    {

        Users::where('id', $req->user_id)->update(['savedprofile' => $req->profiles]);

        $data = Users::where('id', $req->user_id)->first();

        $data['images']  = Images::where('user_id', $req->user_id)->select('image', 'id')->get();
        $data['interests']  = Interest::whereIn('id', explode(",", $data['interests']))->select('image', 'title')->get();


        return json_encode(['status' => true, 'message' => __('app.Updatesuccessful'), 'data' => $data]);
    }


    function getUserDetails(Request $request)
    {

        $data =  Users::where('identity', $request->email)->first();

        if ($data != null) {
            $data['image']  = Images::where('user_id', $data['id'])->first();
            // Interests added
            $data['interests']  = Interest::whereIn('id', explode(",", $data['interests']))->get();
        } else {
            return json_encode(['status' => false, 'message' => __('app.UserNotFound')]);
        }
        $data['password'] = '';
        return json_encode(['status' => true, 'message' => __('app.fetchSuccessful'), 'data' => $data]);
    }
}
