<?php

require_once "../../../config.php";

function has_access(): bool
{
    $apikey = get_config("local_mokitul", "downloadApiKey");

    // check if an api key was received, if not then require user to be logged in
    $received_apikey = optional_param("api_key", null, PARAM_RAW);

    if ($received_apikey == null) {
        require_login();
    } else {
        if (strcmp($apikey, $received_apikey) !== 0) {
            http_response_code(403);

            echo "Invalid API key.";
            return false;
        }
    }
    return true;
}
