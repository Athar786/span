<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\Hobby;
use App\Models\User;
use App\Models\UserHobby;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function sign_up(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email|unique:users|max:255',
            'phone' => 'required|unique:users',
            'photo' => 'required',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ]);
        serverside_validate($validator);
        $photo = '';
        if($request->hasFile('photo'))
        {
            $photo = time().'.'.$request->photo->extension();
            $request->photo->move(public_path('uploads/users/'),$photo);
        }

        $users = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'photo' => $photo,
            'password' => bcrypt($request->password),
            'role' => 'User',
            'status' => 1,
        ]);
        if($users)
        {
            
            $users->photo = getUserImg($users->photo);
            $this->response['status'] = 1;
            $this->response['data'] = $users;
            $this->response['token'] = $access_token = $users->createToken('span')->accessToken;
            $this->response['message'] = 'Thanks for signing up. Your account has been created';
        } else {
            $this->response['status'] = 0;
            $this->response['message'] = 'Fail';
        }
        return response()->json($this->response);
    }
    
    public function sign_in(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'phone' => 'required|digits:10',
            'password' => 'required|min:8'
        ]);
        serverside_validate($validator);
        $user_credentials = [
            'phone' => $request->phone,
            'password' => $request->password,
            'role' => 'User',
            'status' => 1,
        ];
        if(Auth::attempt($user_credentials))
        {
            $user = Auth::user();
            $this->response['status'] = 200;
            $this->response['message'] = "Login successfully";
            $user->photo = getUserImg($user->photo);
            if($user->AauthAcessToken()) {
                $user->AauthAcessToken()->delete();
            }
            $this->response['data'] = Auth::user();
            $this->response['token'] = $access_token = $user->createToken('span')->accessToken;
            return response()->json($this->response,200);
        } else {
            $this->response['status'] = 0;
            $this->response['message'] = "Invalid Phone number & password";
            return response()->json($this->response,200);
        }

    }

    public function logout()
    {
        $auth_user = Auth::guard('api')->user();
        DB::table('oauth_access_tokens')->where('user_id',$auth_user->id)->delete();
        $this->response['status'] = 200;
        $this->response['message'] = 'Logout Successfull';
        return response()->json($this->response,200);
    }

    public function hobbylist()
    {
        $hobbies = Hobby::all();
        if($hobbies)
        {
            foreach($hobbies as $key => $hobby) {
                unset($hobby->created_at);
                unset($hobby->updated_at);
            }
            $this->response['status'] = 200;
            $this->response['data'] = $hobbies;
            $this->response['message'] = 'Hobbies Successfull';
            return response()->json($this->response,200);
        }
    }

    public function myprofile()
    {
        $auth_user = Auth::guard('api')->user();
        foreach($auth_user->myhobbies as $hobbies) {
            $this->response['myHobbies'] = $hobbies->name;
            unset($hobbies->pivot);
            unset($hobbies->created_at);
            unset($hobbies->updated_at);
        }
        $auth_user->photo = getUserImg($auth_user->photo);
        $this->response['status'] = 200;
        $this->response['data'] = $auth_user;
        $this->response['message'] = 'My Profile Successfull';
        return response()->json($this->response,200);
    }


    public function update_user(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'photo' => 'image|mimes:jpeg,png,jpg',
            'name' => 'alpha:a-zA-z',
            'last_name' => 'alpha:a-zA-z',
        ]);
        serverside_validate($validator);
        $auth_user = Auth::guard('api')->user();

        if(isset($request->name) && $request->name) {
            $auth_user->first_name = $request->name;
        }

        if(isset($request->hobbies_ids) && $request->hobbies_ids) {
            $hobbies = explode(',',$request->hobbies_ids);
            UserHobby::where('user_id',$auth_user->id)->delete();
            foreach($hobbies as $hobby) {
                UserHobby::create([
                    'user_id' => $auth_user->id,
                    'hobbies_id' => $hobby
                ]);
            }
        }

        if($request->last_name) {
            $auth_user->last_name = $request->last_name;
        }

        if($auth_user->photo) {
            $file_img = $auth_user->photo;
        }

        // dd($file_img);
        if($file_img) {
            if($request->hasFile('photo'))
            {
                $photo = time().'.'.$request->photo->extension();
                $request->photo->move(public_path('/uploads/users'),$photo);
                deleteFile(public_path('/uploads/users').'/'.$file_img);
                $auth_user->photo = $photo;
            }
        } else {
            if($request->hasFile('photo'))
            {
                $photo = time().'.'.$request->photo->extension();
                $request->photo->move(public_path('/uploads/users'),$photo);
                $auth_user->photo = $photo;
            }
        }
        
        $auth_user->save();
        $this->response['status'] = 200;
        $auth_user->photo = getUserImg($auth_user->photo);
        foreach($auth_user->myhobbies as $hobbies) {
            $this->response['myHobbies'] = $hobbies->name;
            unset($hobbies->pivot);
            unset($hobbies->created_at);
            unset($hobbies->updated_at);
        }
        $this->response['data'] = $auth_user;
        $this->response['message'] = 'Successfully Update';
        return response()->json($this->response,200);
    }
}
