<?php
    require_once '../client/custom_client.php';

    require_once('../../../config.php');

    require_login();

    function print_default_response() {
        http_response_code(405);

        echo "Invalid request method.";
    }

    function handle_get() {
        $fileid = $_GET['file_id'];

        if (!$fileid) {
            http_response_code(400);

            echo "Invalid request parameters.";

            return;
        }

        $client = new MokitulV1Client();

        $response = $client->summarize($fileid);

        echo $response;
    }

    match ($_SERVER['REQUEST_METHOD']) {
        "GET" => handle_get(),
        default => print_default_response(),
    };
