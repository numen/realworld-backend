<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use User\Application\GetUserUseCase;

class GetUserController extends Controller
{
    private $getUserUseCase;

    public function __construct(GetUserUseCase $getUserUseCase)
    {
        $this->getUserUseCase = $getUserUseCase;
    }

    public function __invoke(Request $request) {
        $user_id = (string)$request->id;
        $user = $this->getUserUseCase->__invoke($user_id);
        return $user;
    }
}
