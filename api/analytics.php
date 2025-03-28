<?php
require_once('../../../config.php');

require_login();

require_once '../client/custom_client.php';

function handle_post()
{
    $_VALUES = json_decode(file_get_contents('php://input'), true);

    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $sessionId = session_id();

    $event = $_VALUES;

    $client = new MokitulV1Client();
    $response = $client->send_analytics_event($event, $sessionId, $userAgent);

    echo $response;
}

function print_default_response()
{
    echo "Invalid request method.";
}

match ($_SERVER['REQUEST_METHOD']) {
    "POST" => handle_post(),
    default => print_default_response(),
};
