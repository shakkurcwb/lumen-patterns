<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public final function invoke(String $manager_name, Array $resources)
    {
        $manager_exists = array_key_exists($manager_name, $this->managers) ?? false;

        if ($manager_exists)
        {
            return $this->managers[$manager_name]->run($resources);
        }
        else
        {
            throw new \Exception('Manager could not be invoked.', 555);
        }
    }
}
