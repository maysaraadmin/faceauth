<?php
defined('MOODLE_INTERNAL') || die();

$functions = [
    'auth_faceauth_get_face_data' => [
        'classname'   => 'auth_faceauth\external',
        'methodname'  => 'get_face_data',
        'classpath'   => 'auth/faceauth/classes/external.php',
        'description' => 'Get face data for a specific user.',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'auth/faceauth:viewlogs'
    ],
    'auth_faceauth_delete_face_data' => [
        'classname'   => 'auth_faceauth\external',
        'methodname'  => 'delete_face_data',
        'classpath'   => 'auth/faceauth/classes/external.php',
        'description' => 'Delete face data for a specific user.',
        'type'        => 'write',
        'ajax'        => true,
        'capabilities'=> 'auth/faceauth:manage'
    ],
    'auth_faceauth_enroll_face' => [
        'classname'   => 'auth_faceauth\external',
        'methodname'  => 'enroll_face',
        'classpath'   => 'auth/faceauth/classes/external.php',
        'description' => 'Enroll face data for a specific user.',
        'type'        => 'write',
        'ajax'        => true,
        'capabilities'=> 'auth/faceauth:enrollface'
    ],
];

$services = [
    'Face Authentication Service' => [
        'functions' => [
            'auth_faceauth_get_face_data',
            'auth_faceauth_delete_face_data',
            'auth_faceauth_enroll_face'
        ],
        'requiredcapability' => '',
        'restrictedusers' => 0,
        'enabled' => 1,
        'shortname' => 'faceauth_service',
        'downloadfiles' => 0,
        'uploadfiles'  => 0
    ]
];