<?php
require_once "../client/custom_client.php";

require_once "../util/auth.php";
if (!has_access()) {
    return;
}

function print_default_response()
{
    echo "Invalid request method.";
}

function handle_get()
{
    $file_id = $_GET["file_id"];

    $client = new MokitulV1Client();

    return $client->download_file($file_id);
}

match ($_SERVER["REQUEST_METHOD"]) {
    "GET" => handle_get(),
    default => print_default_response(),
};
