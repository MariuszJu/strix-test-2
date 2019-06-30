<?php

namespace RockSolidSoftware\StrixTest\Processor;

use RockSolidSoftware\StrixTest\Service\StrixService;

class IndexProcessor extends Processor
{

    /** @var StrixService */
    private $service;

    /**
     * IndexProcessor constructor
     *
     * @param StrixService $service
     */
    public function __construct(StrixService $service)
    {
        $this->service = $service;
    }

}
