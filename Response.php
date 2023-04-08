<?php

namespace Kernel;

use Illuminate\Http\JsonResponse;

class Response extends JsonResponse
{
    public function message(string $value): Response
    {
        return $this->addData([__FUNCTION__ => $value]);
    }

    public function data($value = null): Response
    {
        return $this->addData([__FUNCTION__ => $value]);
    }

    public function total(int $value): Response
    {
        return $this->addData([__FUNCTION__ => $value]);
    }

    public function params($value): Response
    {
        return $this->addData([__FUNCTION__ => $value]);
    }

    public function code(int $value): Response
    {
        return $this->addData([__FUNCTION__ => $value]);
    }

    public function dict(array $value): Response
    {
        return $this->addData([__FUNCTION__ => $value]);
    }

    public function when($value, callable $callback = null, callable $default = null): Response
    {
        if ($value) $callback($this, $value);
        else if ($default) $default($this, $value);
        return $this;
    }

    private function addData(array $value): Response
    {
        $this->setData($value + $this->getData(true));
        return $this;
    }
}
