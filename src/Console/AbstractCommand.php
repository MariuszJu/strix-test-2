<?php

namespace RockSolidSoftware\StrixTest\Console;

abstract class AbstractCommand
{

    /** @var string */
    public $name;

    /** @var string */
    public $signature;

    /** @var Console */
    private $console;

    /**
     * @throws \Exception
     * @param string|null $param
     * @return mixed
     */
    public function params($param = null)
    {
        return $this->console->params($param);
    }

    /**
     * @param Console|null $console
     * @return Console|null
     */
    public function console(Console $console = null)
    {
        if (!is_null($console)) {
            $this->console = $console;
        }

        return $this->console;
    }

    /**
     * @return void
     */
    public abstract function fire();

}
