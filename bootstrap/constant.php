<?php

define('ACTIVE', 1);
define('INACTIVE', 0);

define('ACTIVE_TEXT', [
    0	=>	'Inactive',
    1	=>	'Active'
]);

define('COMING_TEXT', [
    0	=>	'Normal',
    1	=>	'Incoming'
]);

define('HOT_TEXT', [
    0	=>	'Normal',
    1	=>	'Hot'
]);

// Tour types
define('IN_COUNTRY', 0);
define('INTERNATIONAL', 1);

define('TOUR_TYPE_TEXT', [
    0   =>  'In Country',
    1   =>  'International'
]);

// Service types
define('VISA', 1);
define('AIRPORT_CAR', 2);
define('HOTEL', 3);
define('FLIGHT_TICKET', 4);

define('SERVICE_TYPE_TEXT', [
    1   =>  'Visa',
    2   =>  'Xe đưa đón sân bay',
    3   =>  'Khách sạn',
    4   =>  'Vé máy bay'
]);

// Tour Order Status
define('WAITING', 1);
define('ACCEPTED', 2);

define('TOUR_ORDER_STATUS_TEXT', [
    1   =>  'Chờ duyệt',
    2   =>  'Đã duyệt'
]);
