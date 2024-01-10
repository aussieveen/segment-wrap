<?php

require 'vendor/autoload.php';

$container = new \DI\Container();

$segment = $container->get(\App\SegmentMessAround\SegmentWrap::class);

$segment->run();
