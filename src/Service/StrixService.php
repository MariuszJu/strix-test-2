<?php

namespace RockSolidSoftware\StrixTest\Service;

use RockSolidSoftware\StrixTest\Runtime\App;
use RockSolidSoftware\StrixTest\Util\FileReader;
use RockSolidSoftware\StrixTest\Runtime\Runtime;
use RockSolidSoftware\StrixTest\Database\Database;

class StrixService extends Service
{

    /** @var Database */
    protected $db;

    /**
     * StrixService constructor
     *
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->init();
    }

    /**
     * @throws \RuntimeException
     * @throws \Exception
     * @return array
     */
    public function calculateTripMeasures(): array
    {
        $calculation = [];
        $trips = $this->trips();
        $this->hydrateTrips($trips);

        foreach ($trips as $trip) {
            $measures = $trip['measures'];
            $lastMeasure = end($measures);

            $avgSpeeds = $this->averageSpeedsForTrip($trip);

            $distance = (float) (!empty($lastMeasure) ? $lastMeasure['distance'] : 0.0);
            $maxAvgSpeed = !empty($avgSpeeds) ? max($avgSpeeds) : 0;

            $calculation[] = [
                'trip'      => $trip,
                'distance'  => $distance,
                'avg_speed' => (int) $maxAvgSpeed,
            ];
        }

        return $calculation;
    }

    /**
     * @throws \RuntimeException
     * @param array $trip
     * @return array
     */
    protected function averageSpeedsForTrip(array $trip): array
    {
        $avgSpeeds = [];
        $measures = $trip['measures'] ?? [];
        $inteval = $trip['measure_interval'];

        if (($measuresCount = count($measures)) <= 1) {
            return [];
        }

        for ($i = 0; $i < $measuresCount; $i++) {
            if ($i === $measuresCount - 1) {
                break;
            }

            $current = $measures[$i];
            $next = $measures[$i + 1];

            if (($nextDistance = $next['distance']) < ($currentDistance = $current['distance'])) {
                throw new \RuntimeException(sprintf(
                    'Invalid data for trip %s provided: Distance of measure #%s is lower than previous measure',
                    $trip['name'], $i + 1
                ));
            }

            $avgSpeed = 3600 * ($nextDistance - $currentDistance) / $inteval;
            $avgSpeeds[] = $avgSpeed;
        }

        return $avgSpeeds;
    }

    /**
     * @throws \Exception
     * @param array $trips
     */
    protected function hydrateTrips(array &$trips)
    {
        foreach ($trips as &$trip) {
            $trip['measures'] = $this->measuresForTrip($trip);
        }
    }

    /**
     * @throws \Exception
     * @return array
     */
    protected function trips(): array
    {
        return $this->db->select('trips');
    }

    /**
     * @throws \Exception
     * @param array|int $trip
     * @return array
     */
    protected function measuresForTrip($trip): array
    {
        $sql = sprintf('SELECT * FROM `trip_measures` WHERE `trip_id` = "%s"',
            is_array($trip) ? $trip['id'] : (int) $trip
        );

        $result = $this->db->raw($sql);

        return $result;
    }

    /**
     * @return void
     */
    protected function init()
    {
        try {
            $dbName = Runtime::config()->configKey('database.name');
            $table = 'trips';

            $result = $this->db->raw("
                SELECT * 
                FROM information_schema.tables
                WHERE table_schema = '{$dbName}' 
                    AND table_name = '{$table}'
                LIMIT 1;
            ");
            
            if (empty($result)) {
                /** @var FileReader $reader */
                $reader = App::make(FileReader::class);
                $sql = $reader->init(STRIX_TEST_ROOT_DIR . '/resources/database/db.sql');

                $this->db->raw($sql->content());
            }
        } catch (\Throwable $e) {

        }
    }

}
