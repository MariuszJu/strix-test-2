<?php

namespace RockSolidSoftware\StrixTest\Presenter;

use RockSolidSoftware\StrixTest\Runtime\Runtime;
use RockSolidSoftware\StrixTest\Facade\FlashMessage;

abstract class Presenter
{

    /**
     * @throws \Exception
     * @throws \RuntimeException
     * @param string $template
     * @param array  $variables
     * @param bool   $partial
     * @return string|void
     */
    protected function render($template, array $variables = [], $partial = false)
    {
        $templateFile = str_replace('.', '/', $template);
        $viewsDirectory = STRIX_TEST_ROOT_DIR . '/resources/views/';
        $file = $viewsDirectory . $templateFile . '.php';
        
        if (!file_exists($file)) {
            throw new \RuntimeException('Template file for template' . $template . ' does not exists');
        }

        if (!$partial) {
            $variables['assetsPath'] = str_replace('index.php', '', Runtime::config()->configKey('system_url'))
                . '/resources/assets';

            $variables['messages'] = FlashMessage::getMessages();
        }

        ob_start();
        extract($variables);
        require $file;
        $content = ob_get_contents();
        ob_end_clean();

        if ($partial) {
            return $content;
        }

        echo $content;
    }

    /**
     * @throws \Exception
     * @throws \RuntimeException
     * @param string $template
     * @param array  $variables
     * @return string
     */
    public function partial($template, $variables = [])
    {
        return $this->render($template, $variables, true);
    }

}
