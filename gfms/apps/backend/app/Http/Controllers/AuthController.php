<?php

namespace App\Http\Controllers;

use App\Exceptions\AccountLockedException;
use App\Exceptions\AuthenticationException;
use App\Exceptions\InactiveAccountException;
use App\Exceptions\InvalidOtpException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * Step 1: Login with personal number and password
     * Sends OTP to user's email or phone
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $otpChannel = $request->input('otp_channel', 'email');
            
            $result = $this->authService->attemptLogin(
                $request->personal_number,
                $request->password,
                $otpChannel
            );

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => [
                    'user_id' => $result['user_id'],
                    'otp_channel' => $result['channel'],
                ],
            ], 200);
        } catch (AccountLockedException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 423);
        } catch (InactiveAccountException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 403);
        } catch (AuthenticationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during login',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Step 2: Verify OTP and issue authentication token
     */
    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        try {
            $otpChannel = $request->input('otp_channel', 'email');
            
            $result = $this->authService->verifyOtp(
                $request->user_id,
                $request->code,
                $otpChannel
            );

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => $result,
            ], 200);
        } catch (InvalidOtpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (AuthenticationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during OTP verification',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Logout user by revoking current token
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $this->authService->logout($request->user());

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during logout',
            ], 500);
        }
    }

    /**
     * Get authenticated user with roles and permissions
     */
    public function me(Request $request): JsonResponse
    {
        try {
            $userData = $this->authService->me($request->user());

            return response()->json([
                'success' => true,
                'data' => $userData,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
            ], 500);
        }
    }
}
