<?php

$now = new \DateTime();
echo strtolower($now->setTimezone(new DateTimeZone('UTC'))->format('m/d')) . "\n";
