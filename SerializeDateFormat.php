<?php

namespace Kernel;

use DateTimeInterface;

trait SerializeDateFormat
{
    protected function serializeDate(DateTimeInterface $date, $format = 'Y-m-d H:i:s'): string
    {
        return $date->format($format);
    }
}