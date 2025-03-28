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
    $course_id = $_GET["course_id"];

    $client = new MokitulV1Client();

    $response = $client->retrieve_files_for_course($course_id, 1);

    echo json_encode($response);
}

match ($_SERVER["REQUEST_METHOD"]) {
    "GET" => handle_get(),
    default => print_default_response(),
};
