<?php

namespace RockSolidSoftware\StrixTest\Facade;

use RockSolidSoftware\StrixTest\Util\FlashMessage as FlashMessageUtil;

/**
 * @method static addMessage(string $type, string $message, bool $checkIfAlreadyExists = false)
 * @method static addSuccessMessage(string $message, bool $checkIfAlreadyExists = true)
 * @method static addErrorMessage(string $message, bool $checkIfAlreadyExists = true)
 * @method static addInfoMessage(string $message, bool $checkIfAlreadyExists = true)
 * @method static addWarningMessage(string $message, bool $checkIfAlreadyExists = true)
 * @method static getMessages(string $type = null, bool $clear = true)
 * @method static staticMessage(string $type = null, string $message)
 */
class FlashMessage extends Facade
{

    /**
     * @return FlashMessageUtil
     */
    public static function getService(): FlashMessageUtil
    {
        return new FlashMessageUtil();
    }

}
