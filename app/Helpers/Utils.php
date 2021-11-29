<?php 

function dateTimeUtcToLocal(String $date_time): \DateTime
{
    $utc = new DateTimeZone('UTC');
    $date = new \DateTime($date_time, $utc);
    return $date->setTimezone(new \DateTimeZone(config('app.timezone')));
}