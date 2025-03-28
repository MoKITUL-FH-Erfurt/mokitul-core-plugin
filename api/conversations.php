<?php
require_once "../client/custom_client.php";

require_once "../../../config.php";

require_login();

function print_default_response()
{
    echo "Invalid request method.";
}

function handle_post()
{
    $_VALUES = json_decode(file_get_contents("php://input"), true);

    $course_id = $_VALUES["courseId"] ?? null;
    $file_id = $_VALUES["fileId"] ?? null;
    $summary = $_VALUES["summary"] ?? null;
    $timestamp = $_VALUES["timestamp"] ?? time();
    $scope = $_VALUES["scope"] ?? null;

    $client = new MokitulV1Client();
    $response = $client->start_conversation(
        $course_id,
        $file_id,
        $summary,
        $timestamp,
        $scope
    );

    echo $response;
}

function handle_update()
{
    $_VALUES = json_decode(file_get_contents("php://input"), true);

    $conversationId = $_REQUEST["conversationId"];
    $prompt = $_VALUES["message"];

    $client = new MokitulV1Client();
    $response = $client->send_message($conversationId, $prompt);

    echo $response;
}

function handle_get()
{
    $course_id = !empty($_GET["courseId"]) ? $_GET["courseId"] : null;
    $file_id = !empty($_GET["fileId"]) ? $_GET["fileId"] : null;
    $scope = !empty($_GET["scope"]) ? $_GET["scope"] : null;

    $client = new MokitulV1Client();
    $response = $client->query_all_conversations($course_id, $file_id, $scope);

    echo $response;
}

function handle_delete()
{
    $conversationId = $_REQUEST["conversationId"];

    $client = new MokitulV1Client();
    $response = $client->delete_conversation($conversationId);

    echo $response;
}

match ($_SERVER["REQUEST_METHOD"]) {
    "POST" => handle_post(),
    "GET" => handle_get(),
    "PUT" => handle_update(),
    "DELETE" => handle_delete(),
    default => print_default_response(),
};
