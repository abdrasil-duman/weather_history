<?php

namespace App\Http\Controllers;

use App\Models\Temperature;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GetDailyHistory extends Controller
{
    public function temperature(Request $request)
    {
        $token = $request->header('x-token');
        if ($token != env('X_TOKEN') || !isset($token)) {
            return ['success' => false,
                'message' => 'Your auth token is incorrect or empty, please contact administrator',
                'code' => 124
            ];
        }
        $date = is_null($request['date'])
            ? date('Y-m-d')
            : $request['date'];
        $startDate=$date.' 00:00:00';
        $endDate=$date.' 23:59:59';
        if (!$this->validateDate($date, 'Y-m-d')) {
            return ['success' => false,
                'message' => 'Enter valid date',
                'code' => 123
            ];
        }
        $temp = DB::table('temperature_history')
            ->select('created_at', 'temp')
            ->where('created_at', '>', $startDate)
            ->where('created_at', '<', $endDate)
            ->get();
        return response()->json($temp);
    }

    function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}
