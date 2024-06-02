<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
     /**
      *  @OA\Post(
      *      path="/api/v1/user/profile/update",
      *      tags={"Profile"},
      *      summary="Profile Update",
      *      operationId="ProfileUpdate",
      *      description="ProfileUpdate",
      *      security={{"bearerAuth":{}}},
      *      @OA\Parameter(
      *          name="name",
      *          required=true,
      *          in="query",
      *          example="",
      *          description="Name",
      *          @OA\Schema(
      *          type="string",
      *         ),
      *       ),
      *      @OA\Response(
      *         response=200,
      *         description="json schema",
      *         @OA\MediaType(
      *             mediaType="application/json",
      *         ),
      *     ),
      *     @OA\Response(
      *         response=404,
      *         description="Invalid Request"
      *     ),
      * )
      */

     public function profileUpdate(Request $request)
     {
          try {
               $rules = [
                    'name' => 'required|string'
               ];

               $message = [
                    'name.required' => 'Name is required',
                    'name.string' => 'Name should be string',
               ];

               $validator = Validator::make($request->all(), $rules, $message);

               if ($validator->fails()) {
                    return response()->json([
                         'status_code' => 400,
                         'message' => 'Validation error',
                         'errors' => $validator->errors()
                    ], 400);
               }

               $user_id = auth()->user()->id;

               $user = User::find($user_id);
               $user->name = $request->name;
               $user->save();

               $data = [
                    'status_code' => 200,
                    'message' => 'Profile updated successfully.',
                    'data' => [
                         'user' => $user
                    ]
               ];

               return sendJsonResponse($data);
          } catch (\Exception $e) {
               Log::error([
                    'method' => __METHOD__,
                    'error' => ['file' => $e->getFile(), 'line' => $e->getLine(), 'message' => $e->getMessage()],
                    'created_at' => date("Y-m-d H:i:s")
               ]);
               return sendJsonResponse(array('status_code' => 500, 'message' => 'Something went wrong.'));
          }
     }
}
