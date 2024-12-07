<?php
defined('MOODLE_INTERNAL') || die();

$tasks = [
    [
        'classname' => 'auth_faceauth\task\ExecuteFacematchTask',
        'blocking' => 0,
        'minute' => '*/15', // Run every 15 minutes.
        'hour' => '*',
        'day' => '*',
        'month' => '*',
        'dayofweek' => '*'
    ],
    [
        'classname' => 'auth_faceauth\task\InitiateFacematchTask',
        'blocking' => 0,
        'minute' => '*/30', // Run every 30 minutes.
        'hour' => '*',
        'day' => '*',
        'month' => '*',
        'dayofweek' => '*'
    ]
];