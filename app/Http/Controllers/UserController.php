<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getAllUsers()
    {
        $users = User::getAllUsers();

        return response()->json([
            'code' => 200,
            'data' => $users,
        ], 200);
    }

    public function getTotalRowUser()
    {
        $totalRow = User::count();

        return response()->json([
            'code' => 200,
            'data' => ['totalRow' => $totalRow],
        ], 200);
    }

    public function getPaginationUsers(Request $request)
    {
        $page = $request->query('page', 1);
        $limit = 5;
        $start = ($page - 1) * $limit;

        $paginationUsers = User::orderBy('id', 'ASC')
            ->skip($start)
            ->take($limit)
            ->get();

        return response()->json([
            'code' => 200,
            'data' => $paginationUsers,
        ], 200);
    }

    public function getUser(Request $request)
    {
        $userId = $request->query('id');
        if (!$userId) {
            return response()->json([
                'code' => 400,
                'msg' => 'Missing input!',
            ], 400);
        }

        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'code' => 404,
                'msg' => 'User not found!',
            ], 404);
        }

        return response()->json([
            'code' => 200,
            'data' => $user,
        ], 200);
    }

    public function createUser(Request $request)
    {
        $userData = $request->only(['email', 'password', 'fullName', 'address', 'gender', 'phoneNumber']);

        $validator = Validator::make($userData, [
            'email' => 'required|email|unique:user',
            'password' => 'required|min:6',
            'fullName' => 'required',
            'address' => 'nullable',
            'gender' => 'nullable|in:Male,Female',
            'phoneNumber' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'msg' => $validator->errors()->first(),
            ], 400);
        }
        
        try {
            $user = User::create([
                'email' => $userData['email'],
                'password' => bcrypt($userData['password']),
                'fullName' => $userData['fullName'],
                'address' => $userData['address'],
                'gender' => $userData['gender'],
                'phoneNumber' => $userData['phoneNumber'],
            ]);

            return response()->json([
                'code' => 201,
                'msg' => 'Create user successfully!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 400,
                'msg' => $e->getMessage(),
            ], 400);
        }
    }

    public function updateUser(Request $request)
    {
        $userId = $request->input('id');
        
        if (!$userId) {
            return response()->json([
                'code' => 400,
                'msg' => 'Missing input!',
            ], 400);
        }

        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'code' => 400,
                'msg' => 'User does not exist!',
            ], 400);
        }

        $userData = $request->except('id', 'email', 'password');

        try {
            $user->update($userData);

            return response()->json([
                'code' => 200,
                'msg' => 'Update user successfully!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'msg' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteUser(Request $request)
    {
        $id = $request->query('id');
        
        if (!$id) {
            return response()->json([
                'code' => 400,
                'msg' => 'Missing input!',
            ], 400);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'code' => 400,
                'msg' => 'User does not exist!',
            ], 400);
        }

        try {
            $user->delete();

            return response()->json([
                'code' => 200,
                'msg' => 'Delete user successfully!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'msg' => $e->getMessage(),
            ], 500);
        }
    }
}
