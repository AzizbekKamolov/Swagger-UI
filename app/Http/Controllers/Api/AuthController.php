<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @OA\Post (
     *     path="/api/register",
     *     tags={"Authorization"},
     *      @OA\Parameter(
     *          name="firstname",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="email",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          )
     *     ),
     *      @OA\Parameter(
     *          name="password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          )
     *     ),
     *     @OA\Response(response="200", description="Succesfully")
     * )
     */


    public function register(Request $request){
        $data = Validator::make($request->all(), [
            'firstname' => 'required',
            'email' => 'required|email|unique:employees',
            'password' => 'required|min:6|max:12',
        ]);

        if ($data->fails()){
            return response()->json($data->errors());
        }

        $employee = new Employee();
        $employee->firstname = $request->firstname;
        $employee->email = $request->email;
        $employee->password = Hash::make($request->password);
        $employee->save();

        $token = $employee->createToken('auth_token')->plainTextToken;
        Employee::where('email', $employee->email)->update([
            'token' => $token,
        ]);

        return response()->json([
            'your token' => $token,
            'message' => 'Registered successfully'
        ]);
    }
    /**
     * @OA\Post (
     *     path="/api/login",
     *     tags={"Authorization"},
     *     @OA\Parameter(
     *          name="email",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          )
     *     ),
     *      @OA\Parameter(
     *          name="password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          )
     *     ),
     *     @OA\Response(response="200", description="Succesfully")
     * )
     */


    public function login(Request $request){
        $data = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6|max:12',
        ]);

        if ($data->fails()){
            return response()->json($data->errors());
        }

        $employee = Employee::where('email', $request->email)->first();

        if (!$employee){
            return response()->json(['message' => 'unathorized'], 401);
        }

        if (!Hash::check($request->password, $employee->password)){
            return response()->json(['message' => 'Wrong password']);
        }
        $token = $employee->createToken('auth_token')->plainTextToken;

        Employee::where('email', $employee->email)->update([
            'token' => $token,
        ]);
        return response()->json(['your token' => $token]);
    }
}
