<?php

namespace RockSolidSoftware\StrixTest\Presenter;

use RockSolidSoftware\StrixTest\Service\StrixService;

class IndexPresenter extends Presenter
{

    /** @var StrixService */
    private $service;

    /**
     * IndexPresenter constructor
     *
     * @param StrixService $service
     */
    public function __construct(StrixService $service)
    {
        $this->service = $service;
    }

    /**
     * @throws \Exception
     * @throws \RuntimeException
     */
    public function index()
    {
        $viewData = [];
        $calculation = $this->service->calculateTripMeasures();

        foreach ($calculation as $item) {
            $trip = $item['trip'];

            $viewData['calculation'][] = [
                'trip'      => $trip['name'],
                'distance'  => $item['distance'],
                'interval'  => $trip['measure_interval'],
                'avg_speed' => $item['avg_speed'],
            ];
        }

        $this->render('index.index', $viewData);
    }

}
