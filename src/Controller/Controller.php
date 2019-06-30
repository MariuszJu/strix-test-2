<?php

namespace RockSolidSoftware\StrixTest\Controller;

use RockSolidSoftware\StrixTest\Util\HttpHelper;
use RockSolidSoftware\StrixTest\Request\Request;
use RockSolidSoftware\StrixTest\Runtime\Runtime;

abstract class Controller
{

    /**
     * @throws \Exception
     * @return Request
     */
    protected function request()
    {
        return Runtime::request();
    }

    /**
     * @param string $url
     */
    public function redirect($url)
    {
        HttpHelper::redirect($url);
    }

    /**
     * @return void
     */
    public function refresh()
    {
        HttpHelper::refresh();
    }

}
