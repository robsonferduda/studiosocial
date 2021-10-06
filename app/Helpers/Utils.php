<?php 

function dateTimeUtcToLocal(String $date_time): \DateTime
{
    $date = new \DateTime($date_time);
    return $date->setTimezone(new \DateTimeZone(config('app.timezone')));

}