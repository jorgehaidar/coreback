<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\CoreController;
use App\Mail\PasswordResetMail;
use App\Models\Security\User;
use App\Rules\StrongPassword;
use App\Services\Security\LogService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends CoreController
{
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        LogService::createLoginLogout('Login');

        return $this->respondWithToken($token);
    }
    public function getPermissions()
    {
        /** @var User $user */
        $user = Auth::user();

        return $user->getPermissions();
    }
    public function me()
    {
        return response()->json(auth()->user());
    }
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }
    protected function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    public function changePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => ['required', new StrongPassword(), 'different:old_password'],
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        $user = Auth::user();

        if (!Hash::check($request->input('old_password'), $user->getAuthPassword())) {
            return response()->json([
                'success' => false,
                'message' => 'Old password does not match our records.',
            ], Response::HTTP_BAD_REQUEST);
        }

        /** @var User $user */
        $user->password = Hash::make($request->input('new_password'));
        if (!$user->save()) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the password.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.',
        ], Response::HTTP_OK);
    }
    public function sendRecoveryEmail(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        $code = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($code),
                'code' => $code,
                'created_at' => now(),
            ]
        );

        Mail::to($request->email)->send(new PasswordResetMail($code));

        return response()->json([
            'success' => true,
            'message' => 'Recovery email sent successfully.',
        ], Response::HTTP_OK);
    }

    public function validateCode(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required|size:6',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        $resetToken = DB::table('password_reset_tokens')->where([
            'email' => $request->email,
            'code' => $request->code,
        ])->first();

        if (!$resetToken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code.',
            ], Response::HTTP_BAD_REQUEST);
        }

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        $user = User::query()->where('email', $request->email)->firstOrFail();

        $encryptedId = Crypt::encrypt($user->id);

        return response()->json([
            'success' => true,
            'message' => 'Code validated successfully.',
            'data' => $encryptedId,
        ], Response::HTTP_OK);
    }

    public function reset(Request $request, string $cypher): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', new StrongPassword()],
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        $userId = Crypt::decrypt($cypher);

        $updated = User::query()->where('id', $userId)->update([
            'password' => Hash::make($request->password),
        ]);

        if (!$updated) {
            return response()->json([
                'success' => false,
                'message' => 'Error while updating password.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.',
        ], Response::HTTP_OK);
    }
    private function validationErrorResponse($validator): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Validation error.',
            'errors' => $validator->errors(),
        ], Response::HTTP_BAD_REQUEST);
    }
}
