<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Myfunction extends Model
{
    use HasFactory;

    public static function sendPushToUser($title, $message, $token)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $api_key = env('FCMKEY');
        $notificationArray = array('title' => $title, 'body' => $message, 'sound' => 'default', 'badge' => '1');

        $fields = array('to' => "/token/" . $token, 'notification' => $notificationArray, 'priority' => 'high');
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
            $response['status'] = true;
            $response['message'] = 'Notification sent successfully !';
        } else {
            $response['status'] = false;
            $response['message'] = 'Something Went Wrong !';
        }
        // echo json_encode($response);
    }

    public static function point2point_distance($lat1, $lon1, $lat2, $lon2, $unit = 'K', $radius)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return (($miles * 1.609344) <= $radius);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    public static   function customReplace($string)
    {
        return  str_replace(array('<', '>', '{', '}', '[', ']', '`'), '', $string);
    }

    public static function generateFakeUserIdentity()
    {
        $token =  rand(100000, 999999);
        $first = MyFunction::generateRandomString(3);
        $first .= $token;
        $first .= MyFunction::generateRandomString(3);

        $count = Users::where('identity', $first)->count();
        while ($count >= 1) {
            $token =  rand(100000, 999999);
            $first = MyFunction::generateRandomString(3);
            $first .= $token;
            $first .= MyFunction::generateRandomString(3);
            $count = Users::where('identity', $first)->count();
        }
        return $first;
    }

    public static function generateRandomString($length)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
