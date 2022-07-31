<?php

namespace Mazba\Models;

use Exception;
use Mazba\Currency\Currency;

class ConfigModel
{
    /**
     * @var string
     */
    protected $table = 'configs';

    /**
     * @return string[]
     */
    public static function getConfig(): array
    {
        return [
          'deposit'=>[
              'private'=>0.03,
              'business'=>0.03,
          ],
          'withdraw'=>[
              'private'=>0.3,
              'business'=>0.5,
              'free_upto'=>3,
              'free_upto_amount'=>1000,
          ],
        ];
    }
}
