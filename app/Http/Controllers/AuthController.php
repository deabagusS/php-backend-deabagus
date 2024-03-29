<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use App\Interfaces\UserRepositoryInterface;
use Validator;
 
class AuthController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
    ) {
        $this->userRepository = $userRepository;
        $this->middleware('auth:api', ['except' => ['login', 'register', 'unauthorized']]);
    }
 
    public function register(): JsonResponse
    {
        $rules = [
            'nama' => 'required',
            'email'=> 'required',
            'password'=> 'required',
        ];

        $validator = Validator::make(request()->all(), $rules);

        if ($validator->fails()) {
            return response()->json(setResponse(false, 'Parameter yang diinput belum sesuai', $validator->messages()->toArray()), 400);
        } else {
            $userDetail = request()->post();
            $userExist = $this->userRepository->userExist($userDetail['email']);
            
            if (!$userExist) {
                $userDetail['password'] = Hash::make($userDetail['password']);
                $this->userRepository->create($userDetail);

                return response()->json(setResponse(true, 'Registrasi berhasil', []), 201);
            } else {
                return response()->json(setResponse(false, 'Email sudah terdaftar, silahkan ganti', []), 400);
            }
        }
    }
  
    public function login(): JsonResponse
    {
        $credentials = request(['email', 'password']);
 
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(setResponse(false, 'Login gagal'), 400);
        }
        
        return $this->respondWithToken($token);
    }
 
    public function me(): JsonResponse
    {
        $data = auth()->user();
        return response()->json(setResponse(true, '', $data->toArray()), 200);
    }
    
    public function logout(): JsonResponse
    {
        auth()->logout();
 
        return response()->json(['message' => 'Successfully logged out'], 200);
    }
     
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }
 
    protected function respondWithToken(string $token): JsonResponse
    {
        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];

        return response()->json(setResponse(true, 'Login berhasil', $data), 200);
    }

    public function unauthorized(): JsonResponse
    {
        return response()->json(setResponse(false, 'Unauthorized'), 401);
    }
}