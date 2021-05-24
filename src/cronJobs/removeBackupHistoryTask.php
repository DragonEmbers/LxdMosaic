<?php

use Crunz\Schedule;

$container = (new \DI\ContainerBuilder())->build();

$env = new Dotenv\Dotenv(__DIR__ . "/../../");
$env->load();

$getInstanceSetting = $container->make("dhope0000\LXDClient\Model\InstanceSettings\GetSetting");
$timezone = $getInstanceSetting->getSettingLatestValue(dhope0000\LXDClient\Constants\InstanceSettingsKeys::TIMEZONE);


$schedule = new Schedule();
$task = $schedule->run(PHP_BINARY . '  ' . __DIR__ . '/scripts/removeBackupHistory.php');
$task
    ->daily()
    ->at("03:00")
    ->description('Removing deleted backup history')
    ->timezone($timezone);

return $schedule;
