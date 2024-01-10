<?php

declare(strict_types=1);

namespace App\SegmentMessAround;

use Segment\Segment;

class SegmentWrap
{
    private const USER_ID = 'simonLocalTesting';
    private const WRITE_KEY = '';

    private Segment $segment;

    public function __construct(Segment $segment)
    {
        $this->segment = $segment;

        $this->segment->init(
            self::WRITE_KEY,
            [
                'debug' => true,
                'error_handler' => function ($code, $msg) {
                    echo "Segment error: $msg\n";
                    echo "Segment error code: $code\n";
                },
                'host' => 'events.eu1.segmentapis.com'
            ]
        );
    }

    public function run(): void
    {
        $timestamp = time();

        if (!$this->identify($timestamp)) {
            return;
        }

        $this->page($timestamp);
        $this->track($timestamp);

        $flushResult = $this->segment->flush();
        echo $flushResult ? "Flush successful\n" : "Flush failed\n";
        echo "Done\n";
    }

    private function identify(int $timestamp): bool
    {
        echo "Identifying user\n";
        $result = $this->segment->identify([
            'userId' => self::USER_ID,
            'traits' => [
                'email' => 'simon.mcwhinnie@notmyrealemail.com',
                'name' => 'Simon McWhinnie',
                'createdAt' => $timestamp,
            ]
        ]);
        echo $result ? "Identify successful\n" : "Identify failed\n";
        return $result;


    }

    private function track(int $timestamp): bool
    {
        echo "Tracking event\n";
        $result = $this->segment->track([
            'userId' => self::USER_ID,
            'event' => 'Simon is running a test',
            'properties' => [
                'env' => 'local',
                'ide' => 'PHPStorm',
                'timestamp' => $timestamp,
            ]
        ]);
        echo $result ? "Track successful\n" : "Track failed\n";
        return $result;
    }

    private function page(int $timestamp): bool
    {
        echo "Tracking page\n";
        $result = $this->segment->page([
            'userId' => self::USER_ID,
            'category' => 'Application',
            'name' => 'PHP Console',
            'properties' => [
                'url' => 'local.phpstorm.com',
                'timestamp' => $timestamp,
            ]
        ]);
        echo $result ? "Page successful\n" : "Page failed\n";
        return $result;
    }
}
