<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\Category; 
use App\Events\user_tb_data; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class ApiController extends Controller
{

    /*
        'username.required' => 'The username field is required.',
        'username.unique' => 'The username has already been taken.',
        'username.regex' => 'The username should only contain letters and numbers.',
        'password.required' => 'The password field is required.',
        'password.min' => 'The password must be at least 8 characters long.',
    */

    public function createUser(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'regex:/^[A-Za-z0-9]+$/','unique:user_tb,username'],
            'password' => 'required|min:3',
        ]);
        
        if ($validator->fails()) {
            $errorMessages = $validator->errors()->all();
            return $errorMessages[0];
        }

        $user = new User();
        $user->username = $request->input('username');
        $user->password = bcrypt($request->input('password'));
        $user->role = $request->input('role');
        $user->save();
        broadcast(new user_tb_data()); 
        return 'success';
    }

    public function loginUser(Request $request){
        $credentials = $request->only('username', 'password');

        $user = User::where('username', $credentials['username'])->first();
    
        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Authentication successful
            return response()->json($user);
        } else {
            // Authentication failed
            return 'invalid';
        }
     
    }

    public function viewUser(){
        $user = User::all();
        return response()->json($user);
    }

    //If an error occurs during the execution of the truncate method, it will throw an exception and halt the execution of the code. 
    //same logic in other function

    public function deleteAllUser(){
        User::truncate();
        broadcast(new user_tb_data());
        return response()->json(['message' => 'All users have been deleted']);
    }

    public function deleteUserByUser_id(Request $request){
        $user = User::where('user_id',$request->input('user_id'))->first();
        if($user){
            $user->delete();
            broadcast(new user_tb_data());
        }
        return response()->json('success');
    }
    
    public function updateUserByUser_id(Request $request){
        $user = User::where('user_id',$request->input('user_id'))->first();
        $user ->username = $request->input('username');
        $user ->password = bcrypt($request->input('password'));
        $user->save();
        broadcast(new user_tb_data());
        return 'success';
    }

    public function createCategory(Request $request){
        $category = new Category();
        $category -> user_id = $request->input('user_id');
        $category -> category = $request->input('category');
        $category -> save();
        return 'success';
    }

    public function viewCategory(){
        $category = Category::all();
        return response()->json($category);
    }

    /* Note: 
     you cannot send data using the GET method and receive it as a request in Laravel 
     without modifying the URL.
    */
    
    public function viewCategoryByUserId(Request $request){
        $user_id = $request->input('user_id'); 
        $category = Category::where('user_id',$user_id)->get();
        return response()->json($category);
    }

    // joining of table user and category
    public function viewUserCategory(){
        $userCategory = DB::table('user_tb')
        ->join('category_tb', 'user_tb.user_id', '=', 'category_tb.user_id')
        ->select('user_tb.*', 'category_tb.*')
        ->get();

        return response()->json($userCategory);
    }

    // joining of table user and category with where clause (experiment)
    public function viewUserCategoryUser_id(){
        $userCategory = DB::table('user_tb')
        ->join('category_tb', 'user_tb.user_id', '=', 'category_tb.user_id')
        ->select('user_tb.*', 'category_tb.*')
        ->where('user_tb.user_id', '=', 2)
        ->get();

        return response()->json($userCategory);
    }

}
