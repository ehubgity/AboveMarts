<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EditPointController extends Controller
{
    public function randomDigit()
    {
        $pass = substr(str_shuffle("01234561089abcDEfadsasfdasdfasdsfa3425542FGHIJnostXYZ"), 0, 30);
        return $pass;
    }
    public function index(Request $request)
    {
        $id = $request->id;

        if ($id == null) {
            return back()->with('toast_error', "Invalid Id");
        } else {
            $data = DB::table('users')
                ->where('userId', $id)
                ->first();

            return view('admin.edituserpoint')
                ->with('data', $data);

        }
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'addpoint' => 'required|numeric',
            'minuspoint' => 'required|numeric|',
        ]);

        if ($validator->fails()) {
            return back()
                ->with('toast_error', $validator->messages()->all()[0])
                ->withInput();
        }

        $user = DB::table('users')
            ->where('userId', $request->userId)
            ->first();

        $oldpoint = $user->point;
        $newpoint = $oldpoint + $request->addpoint - $request->minuspoint;
        $pointToSentToHistory = $request->addpoint - $request->minuspoint;

        DB::table('users')
            ->where('userId', $request->userId)
            ->update([
                'point' => $newpoint,
            ]);
        DB::table('points')->insert([
            'transactionId' => $this->randomDigit(),
            'userId' => $user->userId,
            'username' => $user->username,
            'point' => $pointToSentToHistory,
            'package' => 'None',
            'sponsor' => "Admin",
            'status' => 'CONFIRM',
            "created_at" => date('Y-m-d H:i:s'),
            "updated_at" => date('Y-m-d H:i:s'),
        ]);

        return back()->with('toast_success', 'Point has been updated');
    }


}
