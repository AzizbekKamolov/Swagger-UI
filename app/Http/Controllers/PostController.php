<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PostController extends Controller
{
    /**
     * @OA\Post (
     *     path="/api/post",
     *     security={{ "Bearer":{} }},
     *     tags={"Post"},
     *     @OA\Parameter(
     *          name="token",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          )
     *     ),
     *      @OA\Parameter(
     *          name="title",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          )
     *     ),
     *      @OA\Parameter(
     *          name="description",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          )
     *     ),
     *
     *     @OA\Response(response="200", description="Succesfully")
     * )
     */
    public function createPost(Request $request){
        $data = Validator::make($request->all(), [
            'token' => 'required',
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($data->fails()){
            return response()->json($data->errors());
        }

        $employee = Employee::where('token', $request->token)->first();
        if(!$employee){
            return response()->json([
                'message' => "unauthorized",
                'code' => 401
            ]);
        }

        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;
        $post->employee_id = $employee->id;
        $post->save();

        return response()->json([
            'message' => 'Posted successfully',
            'code' => 200,
        ]);

    }
}
