<?php

namespace App\Http\Controllers;

use App\Models\Hobby;
use App\Models\User;
use DataTables;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $hobbies = Hobby::all();
        if ($request->ajax())
        {
            $query = User::select('*')->where('role', 'User');
            if ($search_by_hobby = $request->search_by_hobby)
            {
                $query->where('status', $search_by_hobby);
            }

            return Datatables::of($query)
                    ->addIndexColumn()
                    ->editColumn('photo', function(User $row) {
                        $photo = '<img class="rounded-circle header-profile-user" src="'.getUserImg($row->photo).'" alt="Header Avatar" width="50" height="50">';
    
                        return $photo;
                    })
                    ->editColumn('email', function(User $row) {
                        $email = '<a href="mailto:'.$row->email.'">'.$row->email.'</a>';
    
                        return $email;
                    })
                    ->addColumn('full_name',function(User $row){
                        $full_name = $row->first_name. ' ' . $row->last_name;
                        return $full_name;
                    })
                    ->editColumn('phone', function(User $row) {
                        $phone = '<a href="tel:'.$row->phone.'">'.$row->phone.'</a>';
    
                        return $phone;
                    })
                    ->editColumn('status', function(User $row) {
                        return getUserStatusHtml($row->status);
                    })
                    ->editColumn('created_at', function(User $row) {
                        return $row->created_at;
                    })
                    ->rawColumns(['photo','email','phone','status','created_at','actions'])
                    ->make(true);
        }
        return view('home',compact('hobbies'));
    }
}
