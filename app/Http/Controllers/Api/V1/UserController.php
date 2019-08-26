<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->isadmin && Auth::user()->isactive){
            return User::all();
        }
        else return '';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->isadmin && Auth::user()->isactive){
            $user = User::create($request->all());
            return $user;
        }
        //TODO: else throw exception!!

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return User::findOrFail($id);
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->isadmin && Auth::user()->isactive){
            $validatedData = $request->validate([
                'name' => 'required',
                'password' => 'min:8',
                'email' => 'required|email',
            ]);
            $check2 = User::where('email',$validatedData['email'])->get();
            if (($check2->count()==1) && ($check2->first()->id != $id)) {
                $check2Debug = $check2->first();
                Log::debug("aantal users met dit emailadres=".$check2->count());
                Log::debug("check2Debug->id=".$check2->first()->id);
                Log::debug("id to check=".$id);
                throw new Exception("email already taken by other user.");
            }
            $user = User::findOrFail($id);
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            if (array_key_exists('password',$validatedData))
                if (!empty($validatedData['password']))
                    $user->password = Hash::make($request['password']);
            $user->save();
            return User::findOrFail($id)->pluck('name','email');
        }
        //TODO: else throw exception!!
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->isadmin && Auth::user()->isactive){
            $user = User::findOrFail($id);
            $user->delete();
            return '';
        }
        //TODO: else throw exception!!
    }
}
