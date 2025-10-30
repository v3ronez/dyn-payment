<?php

declare(strict_types=1);

namespace App\Http\Auth;

use App\Domain\User\FormRequest\RegisterRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
    }

    public function register(RegisterRequest $request)
    {
        dd($request->all());
    }
}
