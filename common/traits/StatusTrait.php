<?php

namespace common\traits;

use Yii;

trait StatusTrait
{

    /**
     * @return array statuses list
     * STATUS_PUBLISHED = 1;
     * STATUS_DRAFT     = 0;
     */
    public static function statuses()
    {
        return [
            0 => 'Чрновик',//STATUS_DRAFT
            1 => 'Опубликовано',//STATUS_PUBLISHED
        ];
    }

}