<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Blog;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport\HashApi;

class BlogController extends Controller
{
    public function sendResponse($result, $message)
    {
        $response = [
            'code' => 200,
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'code' => 404,
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::all();
        return [
            "status" => 1,
            "data" => $blogs
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'

        ]);

        $user = User::create($request->all());
        // $success['token'] =  $user->createToken('MyApp')->accessToken->token;
        $success['name'] = $user->name;
        return $this->sendResponse($success, 'User register successfully.');
    }
    public function login(Request $request, User $user)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {

            $success['token'] =  $user->createToken('MyApp')->accessToken;
            $success['name'] =  $user->name;
            return $this->sendResponse($success, 'User login successfully.');
        }
        return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
    }
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'title' => 'required',
    //         'body' => 'required',
    //     ]);

    //     $blogs = Blog::create($request->all());
    //     return [
    //         "status" => 1,
    //         "data" => $blogs
    //     ];
    // }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        if (Auth::check()) {

            return $this->sendResponse(Blog::all(), ' Successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);

        $blog->update($request->all());

        return [
            "status" => 1,
            "data" => $blog,
            "msg" => "Blog updated successfully"
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();
        return [
            "status" => 1,
            "data" => $blog,
            "msg" => "Blog deleted successfully"
        ];
    }
}
