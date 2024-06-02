<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLogin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /** @OA\Post(
     *     path="/api/v1/user/login",
     *     summary="Login",
     *     tags={"Login"},
     *     description="Login",
     *     operationId="Login",
     *
     *     @OA\Parameter(
     *         name="mobile",
     *         in="query",
     *         example="7777777777",
     *         description="Enter Mobile Number",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *       @OA\Parameter(
     *         name="device_id",
     *         required=false,
     *         in="query",
     *         example="",
     *         description="Device Id",
     *
     *         @OA\Schema(
     *           type="string",
     *        ),
     *      ),
     *
     *       @OA\Parameter(
     *         name="push_notification_id",
     *         required=false,
     *         in="query",
     *         example="",
     *         description="push notification id",
     *
     *         @OA\Schema(
     *           type="string",
     *        ),
     *      ),
     *
     *      @OA\Parameter(
     *         name="device_type",
     *         required=false,
     *         in="query",
     *         example="Android",
     *         description="Android|IOS",
     *
     *         @OA\Schema(
     *            type="string",
     *        ),
     *      ),
     *
     *     @OA\Parameter(
     *         name="device_name",
     *         required=false,
     *         in="query",
     *         example="One Plus Nord",
     *
     *         @OA\Schema(
     *            type="string",
     *        ),
     *      ),
     *
     *     @OA\Parameter(
     *         name="carrier_name",
     *         required=false,
     *         in="query",
     *         example="NA",
     *         description="",
     *
     *         @OA\Schema(
     *              type="string",
     *        ),
     *      ),
     *
     *     @OA\Parameter(
     *         name="os_version",
     *         required=false,
     *         in="query",
     *         example="9",
     *         description="",
     *
     *         @OA\Schema(
     *              type="string",
     *        ),
     *      ),
     *
     *     @OA\Parameter(
     *         name="app_version",
     *         required=false,
     *         in="query",
     *         example="1.0",
     *         description="",
     *
     *         @OA\Schema(
     *              type="string",
     *        ),
     *      ),
     *
     *     @OA\Parameter(
     *         name="device_country",
     *         required=false,
     *         in="query",
     *         example="NA",
     *         description="",
     *
     *         @OA\Schema(
     *              type="string",
     *        ),
     *      ),
     *
     *     @OA\Parameter(
     *         name="time_zone",
     *         required=false,
     *         in="query",
     *         example="Asia/Kolkata",
     *         description="",
     *
     *         @OA\Schema(
     *              type="string",
     *        ),
     *      ),
     *
     *      @OA\Response(
     *         response=200,
     *         description="json schema",
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Invalid Request"
     *     ),
     * )
     */
    public function login(Request $request)
    {
        try {
            $rules = [
                'mobile' => ['required', 'regex:/^[0-9]{10}$/'],
                'device_id' => ['required']
            ];

            $messages = [
                'mobile.required' => 'Mobile is required',
                'mobile.regex' => 'Mobile number should be in proper format',
                'device_id.required' => 'Device Id is required',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                $data = [
                    'status_code' => 400,
                    'message' => $validator->errors()->first(),
                    'data' => null,
                ];

                return sendJsonResponse($data);
            }

            $user = User::where('mobile', $request->mobile)->first();

            if (!$user) {
                //Check if a user is present wth same device_id
                $check = User::where('device_id', $request->device_id)->first();

                if(!empty($check)){
                    $data = [
                        'status_code' => 400,
                        'message' => 'You can not login to this device with new number. Please Contact Administrator.',
                        'data' => [],
                    ];
    
                    return sendJsonResponse($data);
                }

                // Current date in Y-m-d format
                $subcription_start = Carbon::now()->format('Y-m-d');
        
                // Free subscription duration
                $free_subcription = 15; // 15 Days
        
                // Calculate subscription end date
                $subcription_end = Carbon::now()->addDays($free_subcription)->format('Y-m-d');

                // Create new user
                $user = User::create([
                    'role_id' => 2,
                    'mobile' => $request->mobile,
                    'device_id' => $request->device_id,
                    'subcription_start' => $subcription_start,
                    'subcription_end' => $subcription_end
                ]);
            } else {
                //Check if device id is same

                if($request->device_id != $user->device_id){
                    $data = [
                        'status_code' => 400,
                        'message' => 'Device id does not match',
                        'data' => [],
                    ];

                    return sendJsonResponse($data);
                }

                //Check if the subcription has expired..
                $subcription_end = $user->subcription_end;

                if(!empty($subcription_end)){
                    if(Carbon::now()->greaterThan($subcription_end)){
                        $data = [
                            'status_code' => 400,
                            'message' => 'Subscription Expired',
                            'data' => [],
                        ];
    
                        return sendJsonResponse($data);
                    }
                } else {
                    $data = [
                        'status_code' => 400,
                        'message' => 'Subscription Expired',
                        'data' => [],
                    ];

                    return sendJsonResponse($data);
                }
            }

            $user = User::getUserDataUsingMobile($request->mobile);

            $authData = [];
            try {
                // attempt to verify the credentials and create a token for the user

                if (!$token = JWTAuth::fromUser($user)) {
                    $data = [
                        'status_code' => 401,
                        'message' => 'Unauthorized',
                        'data' => [],
                    ];

                    return sendJsonResponse($data);
                }

                $request->merge(['user_id' => $user->id]);

                UserLogin::addUserLoginData($request);

                $user = User::getUserDetails($user->id);

                // Save Details
                $authData['id'] = $user->id;
                $authData['userDetails'] = $user;
                $authData['token'] = $token;
                $authData['token_type'] = 'bearer';
                $authData['expires_in'] = JWTAuth::factory()->getTTL() * 60 * 24;

                $data = [
                    'status_code' => 200,
                    'message' => 'Login Successfully!',
                    'data' => $authData,
                ];

                return sendJsonResponse($data);
            } catch (JWTException $e) {
                if ($e instanceof \PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException) {
                    $data = [
                        'status_code' => 401,
                        'message' => 'Token Expired',
                    ];
                } elseif ($e instanceof \PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException) {
                    $data = [
                        'status_code' => 400,
                        'message' => 'Invalid Token',
                    ];
                } else {
                    $data = [
                        'status_code' => 400,
                        'message' => 'Token Not found',
                    ];
                }

                return sendJsonResponse($data);
            }
        } catch (\Exception $e) {
            Log::error(
                [
                    'method' => __METHOD__,
                    'error' => [
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'message' => $e->getMessage(),
                    ],
                    'created_at' => now(),
                ]
            );

            return sendJsonResponse([
                'status_code' => 500,
                'message' => $e,
                'data' => null,
            ]);
        }
    }
}
