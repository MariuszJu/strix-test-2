<?php

namespace RockSolidSoftware\StrixTest\Controller;

use RockSolidSoftware\StrixTest\Runtime\Runtime;
use RockSolidSoftware\StrixTest\Presenter\IndexPresenter;
use RockSolidSoftware\StrixTest\Processor\IndexProcessor;

class IndexController extends Controller
{

    /** @var IndexPresenter */
    private $presenter;

    /** @var IndexProcessor */
    private $processor;

    /**
     * IndexController constructor
     *
     * @param IndexPresenter $presenter
     * @param IndexProcessor $processor
     */
    public function __construct(IndexPresenter $presenter, IndexProcessor $processor)
    {
        $this->presenter = $presenter;
        $this->processor = $processor;
    }

    /**
     * @throws \Exception
     */
    public function index()
    {
        try {
            return $this->presenter->index();
        } catch (\RuntimeException $e) {
            $message = $e->getMessage();
        } catch (\Throwable $e) {
            $message = 'General error occured. Please try again later';
        }

        $this->error($message);
    }

    /**
     * @throws \Exception
     * @param string $message
     */
    private function error(string $message)
    {
        if (Runtime::isCommandLineInterface()) {
            Runtime::console()->writeError($message);
            exit;
        }

        die($message);
    }

}
