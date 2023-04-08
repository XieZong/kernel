<?php

namespace Kernel;

use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller;

abstract class BaseController extends Controller
{
    public array $rules = [];
    public array $messages = [];

    public function validator(): string
    {
        return Validator::make(request()->all(), $this->rules, $this->messages)->errors()->first();
    }
}
