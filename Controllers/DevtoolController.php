<?php

namespace Kernel\Controllers;

use Kernel\Generator;
use Kernel\Response;

class DevtoolController
{
    public function permission(): Response
    {
        return json()->data(Generator::generatePermissionsData());
    }

    public function api(): Response
    {
        return json()->data(Generator::generateApisData());
    }
}
