<?php

namespace App\Http\Controllers\API\Auth;

use App\Exceptions\NotOwnerException;
use App\Http\Requests\API\ForgotPasswordRequest;
use App\Notifications\API\ResetPassword;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function __construct ()
    {
        $this->middleware('guest');
    }

    /**
     * @OA\POST(
     *      path="auth/password/forgot",
     *      tags={"Auth"},
     *      description="Send link for recovery password.",
     *      summary="Send recovery password link",
     *      operationId="forgotPassword",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="email",
     *                      type="string"
     *                  ),
     *                  required={"email"}
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Link sended",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="success"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response="401",
     *          description="User access errors",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="User is registered through Facebook.|You are not pet owner."
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="field",
     *                 @OA\Items(type="string", example="Invalid data")
     *             )
     *         )
     *     ),
     * )
     */
    /**
     * Handle the incoming request.
     *
     * @param ForgotPasswordRequest $request
     * @return Response
     * @throws NotOwnerException
     */
    public function __invoke(ForgotPasswordRequest $request)
    {
        $user = User::query()->where('email', $request->email)->first();

        if(!$user->isPetOwner())
            throw new NotOwnerException();

        if($user->isFacebookUser())
            return response()->json(['message' => 'User is registered through Facebook.'], 401);

        $broker = Password::broker();
        $token = $broker->createToken($user);

        $user->notify(new ResetPassword($token, $user->email));

        return response()->json(['message' => 'success']);
    }
}
