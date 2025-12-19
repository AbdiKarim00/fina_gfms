<?php

namespace App\Http\Controllers;

use App\Exceptions\AccountLockedException;
use App\Exceptions\AuthenticationException;
use App\Exceptions\InactiveAccountException;
use App\Exceptions\InvalidOtpException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Http\Responses\ApiResponse;
use App\Services\AuthService;
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
    public function login(LoginRequest $request)
    {
        try {
            $otpChannel = $request->input('otp_channel', 'email');

            $result = $this->authService->attemptLogin(
                $request->personal_number,
                $request->password,
                $otpChannel
            );

            return ApiResponse::success([
                'user_id' => $result['user_id'],
                'otp_channel' => $result['channel'],
            ], $result['message']);
        } catch (AccountLockedException $e) {
            return ApiResponse::error($e->getMessage(), 423);
        } catch (InactiveAccountException $e) {
            return ApiResponse::error($e->getMessage(), 403);
        } catch (AuthenticationException $e) {
            return ApiResponse::error($e->getMessage(), 401);
        } catch (\Exception $e) {
            return ApiResponse::serverError('An error occurred during login');
        }
    }

    /**
     * Step 2: Verify OTP and issue authentication token
     */
    public function verifyOtp(VerifyOtpRequest $request)
    {
        try {
            $otpChannel = $request->input('otp_channel', 'email');

            $result = $this->authService->verifyOtp(
                $request->user_id,
                $request->code,
                $otpChannel
            );

            return ApiResponse::success($result, 'Login successful');
        } catch (InvalidOtpException $e) {
            return ApiResponse::error($e->getMessage(), 400);
        } catch (AuthenticationException $e) {
            return ApiResponse::error($e->getMessage(), 401);
        } catch (\Exception $e) {
            return ApiResponse::serverError('An error occurred during OTP verification');
        }
    }

    /**
     * Logout user by revoking current token
     */
    public function logout(Request $request)
    {
        try {
            $this->authService->logout($request->user());

            return ApiResponse::success(null, 'Logged out successfully');
        } catch (\Exception $e) {
            return ApiResponse::serverError('An error occurred during logout');
        }
    }

    /**
     * Get authenticated user with roles and permissions
     */
    public function me(Request $request)
    {
        try {
            $userData = $this->authService->me($request->user());

            return ApiResponse::success($userData);
        } catch (\Exception $e) {
            return ApiResponse::serverError('An error occurred');
        }
    }
}
