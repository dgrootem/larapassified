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
    private function safeColumns()
    {
        return ['id', 'name', 'email', 'isactive', 'isadmin'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->isadmin && Auth::user()->isactive) {
            return User::select($this->safeColumns())->get(); //avoid leaking password info
        } else return '';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->saveUser($request, null);
        /*
        if (Auth::user()->isadmin && Auth::user()->isactive){
            $user = User::create($request->all());
            if (array_key_exists('password',$validatedData))
                if (!empty($validatedData['password']))
                    $user->password = Hash::make($request['password']);
            return $user->pluck('name','email','isactive','isadmin');
        }
        */
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
        return $this->safeGetUserInfo($id);
    }

    private function safeGetUserInfo($id)
    {
        Log::debug("retrieving info for userid=" . $id);
        return User::select($this->safeColumns())->where('id', $id)->get();
    }

    private function saveUser(Request $request, $id)
    {
        Log::debug(Auth::user()->id);
        Log::debug($id);
        //admins can change everything about users
        if (Auth::user()->isadmin && Auth::user()->isactive && Auth::user()->id != $id) {
            if ($request->has('name') && $request->has('email'))
                $validatedData = $request->validate([
                    'name' => 'required',
                    'password' => 'min:8',
                    'email' => 'required|email',
                ]);
            else
                $validatedData = $request->validate(['password' => 'min:8']);

            if ($request->has('name') && $request->has('email')) {
                $check2 = User::where('email', $validatedData['email'])->get();
                if (($check2->count() == 1) && ($check2->first()->id != $id)) {
                    $check2Debug = $check2->first();
                    Log::debug("aantal users met dit emailadres=" . $check2->count());
                    Log::debug("check2Debug->id=" . $check2->first()->id);
                    Log::debug("id to check=" . $id);
                    throw new Exception("email already taken by other user.");
                }
                if ($check2->count() > 1) throw new Exception('multiple users with this emailadress found... aborting');
                $user = null;
                if ($id === null) {
                    $user = new User;
                    Log::debug("creating user...");
                } else
                    $user = User::findOrFail($id);

                $user->name = $validatedData['name'];
                $user->email = $validatedData['email'];
            } else
                $user = User::findOrFail($id);

            //prevent fails since this can also be called from the 'my profile' component where this info is not available
            if ($request->has('isadmin')) $user->isadmin = $request['isadmin'];
            if ($request->has('isactive')) $user->isactive = $request['isactive'];
            if ($request->has('readonly')) $user->readonly = $request['readonly'];

            if (array_key_exists('password', $validatedData))
                if (!empty($validatedData['password']))
                    $user->password = Hash::make($request['password']);
            $user->save();
            $rid = $user->id;

            return $this->safeGetUserInfo($rid);
        } else if (Auth::user()->isactive)  // we allow a normal user to ONLY update his/her own password
        {
            $user = Auth::user();
            if ($request->has('oldpassword') && Hash::check($request['oldpassword'], $user->password)) {
                //old password matches, test if new pass complies with password policy
                $validatedData = $request->validate([
                    'password' => 'min:8',
                ]);
                if (array_key_exists('password', $validatedData)) {
                    if (!empty($validatedData['password'])) {
                        $user->password = Hash::make($request['password']);
                    }
                    $user->save();
                    $rid = $user->id;
                    return $this->safeGetUserInfo($rid);
                }
            } else {
                throw new Exception('Oud wachtwoord niet geldig');
            }
        } else return null;
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
        return $this->saveUser($request, $id);
        /*
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
            $user->isadmin = $request['isadmin'];
            $user->isactive = $request['isactive'];
            if (array_key_exists('password',$validatedData))
                if (!empty($validatedData['password']))
                    $user->password = Hash::make($request['password']);
            $user->save();
            return User::findOrFail($id)->pluck('name','email','isactive','isadmin');
        }
        */
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
        if (Auth::user()->isadmin && Auth::user()->isactive) {
            $user = User::findOrFail($id);
            $user->delete();
            return '';
        }
        //TODO: else throw exception!!
    }

    public function userattribs($id)
    {
        Log::debug('retrieve info for user id=' . $id);
        return User::select('id', 'isadmin', 'isactive', 'readonly', 'name')->where('id', $id)->first();
    }
}
