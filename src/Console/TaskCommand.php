<?php

namespace RockSolidSoftware\StrixTest\Console;

use RockSolidSoftware\StrixTest\Service\StrixService;

class TaskCommand extends AbstractCommand
{

    /** @var string */
    public $name = 'task';

    /** @var string */
    public $signature = 'task';

    /** @var StrixService */
    private $service;

    /**
     * TaskCommand constructor
     *
     * @param StrixService $service
     */
    public function __construct(StrixService $service)
    {
        $this->service = $service;
    }

    /**
     * @throws \Exception
     */
    public function fire()
    {
        $calculation = $this->service->calculateTripMeasures();

        $header = '| trip        | distance | measure interval | avg speed |';

        $this->console()->writeInfo(str_repeat('-', strlen($header)));
        $this->console()->writeInfo($header);
        $this->console()->writeInfo(str_repeat('-', strlen($header)));

        foreach ($calculation as $item) {
            $trip = $item['trip'];
            $tripName = strlen($trip['name']) > 8 ? substr($trip['name'], 0, 8) . '..' : $trip['name'];

            $name = str_pad($tripName, 11, ' ');
            $distance = str_pad($item['distance'], 8, ' ', STR_PAD_LEFT);
            $interval = str_pad($trip['measure_interval'], 16, ' ', STR_PAD_LEFT);
            $speed = str_pad($item['avg_speed'], 9, ' ', STR_PAD_LEFT);

            $this->console()->writeInfo(sprintf('| %s | %s | %s | %s |',
                $name, $distance,$interval, $speed
            ));
        }

        $this->console()->writeInfo(str_repeat('-', strlen($header)));

        exit;
    }

}
