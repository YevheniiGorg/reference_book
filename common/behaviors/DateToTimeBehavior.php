<?php
/**
 * Created by PhpStorm.
 * User: Nout
 * Date: 04.09.2019
 * Time: 10:58
 */

namespace common\behaviors;

use yii\behaviors\AttributeBehavior;
use yii\base\InvalidConfigException;


class DateToTimeBehavior extends AttributeBehavior
{
    public $timeAttribute;
    public $format;

    public function getValue($event) {

        if (empty($this->timeAttribute)) {
            throw new InvalidConfigException(
                'Can`t find "fromAttribute" property in ' . $this->owner->className()
            );
        }
        $format = '';
        $format = !empty($this->format) ? $this->format : 'd.m.Y';
        if (!empty($this->owner->{$this->attributes[$event->name]})) {
            $this->owner->{$this->timeAttribute} = strtotime(
                $this->owner->{$this->attributes[$event->name]}
            );

            return date($format, $this->owner->{$this->timeAttribute});
        } else if (!empty($this->owner->{$this->timeAttribute})
            && is_numeric($this->owner->{$this->timeAttribute})
        ) {

            $this->owner->{$this->attributes[$event->name]} = date(
                $format,
                $this->owner->{$this->timeAttribute}
            );

            return $this->owner->{$this->attributes[$event->name]};
        }

        return null;
    }
}