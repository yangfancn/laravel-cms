<?php

return [
    // HTTP status code messages
    '400' => 'Bad Request - The server could not understand the request due to invalid syntax.',
    '401' => 'Unauthorized - You must authenticate yourself to get the requested response.',
    '403' => 'Forbidden - You do not have access rights to the content.',
    '404' => 'Not Found - The server can not find the requested resource.',
    '405' => 'Method Not Allowed - The request method is known by the server but has been disabled and cannot be used.',
    '419' => 'Page Expired - Your session has expired, please refresh and try again.',
    '500' => 'Internal Server Error - The server has encountered a situation it doesn\'t know how to handle.',
    '502' => 'Bad Gateway - The server, while acting as a gateway or proxy, received an invalid response from the upstream server.',
    '503' => 'Service Unavailable - The server is not ready to handle the request.',
    '504' => 'Gateway Timeout - The server is acting as a gateway or proxy and did not get a response in time.',
];
