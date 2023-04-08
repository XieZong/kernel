<?php

namespace Kernel;

final class Route
{
    private array $routes;

    public function __construct(string $path, string $label, array $middleware = null)
    {
        $routes = ['path' => $path, 'label' => $label];
        is_array($middleware) && $routes['middleware'] = $middleware;
        $this->routes = $routes;
        return $this;
    }

    public function __invoke(): array
    {
        return $this->routes;
    }

    public static function init(string $path, string $label, array $middleware = null): self
    {
        return new self($path, $label, $middleware);
    }

    public function setDoc(array $doc): self
    {
        $this->routes = $this->routes + $doc;
        return $this;
    }
}
