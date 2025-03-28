<?php
require_once "../../../config.php";
require_once $CFG->libdir . "/filelib.php";

class MokitulV1Client
{
    private $user_id;

    private $service_url;
    private $analytics_url;

    private $curl;

    private $conversation_path;
    private $summary_path;
    private $analytics_path;

    public function __construct()
    {
        global $USER;
        $this->user_id = $USER->id;

        $this->service_url = get_config("local_mokitul", "serviceUrl");
        $this->analytics_url = get_config("local_mokitul", "analyticsUrl"); //get_config('local_mokitul', 'analyticsUrl');
        $this->ollama_model = get_config("local_mokitul", "llmModel");

        $this->curl = new \curl();

        $this->curl->setopt([
            "CURLOPT_HTTPHEADER" => ["Content-Type: application/json"],
        ]);

        $this->conversation_path = "$this->service_url/api/v1/mokitul/conversation";

        $this->summary_path = "$this->service_url/api/v1/moodle/summary";

        $this->analytics_path = "$this->analytics_url/analytics";
    }

    // get a summary of a single file
    public function summarize($id)
    {
        try {
            $url = "$this->summary_path/$id";
            $response = $this->curl->get($url);

            return $response;
        } catch (Exception $e) {
            echo "Error: $e";

            http_response_code(500);

            return;
        }
    }

    // retrieves all conversations for a user
    public function query_all_conversations(
        $courseId = null,
        $fileId = null,
        $scope = null
    ) {
        $url = "$this->service_url/api/v1/conversations/?user_id=$this->user_id&course_id=$courseId&file_id=$fileId&scope=$scope";

        $response = $this->curl->get($url);

        return $response;
    }

    // starts a new conversation
    public function start_conversation(
        $courseId,
        $fileId,
        $summary,
        $timestamp,
        $scope
    ) {
        $url = "$this->service_url/api/v1/conversations";

        $context = [
            "courseId" => (string) $courseId,
            "fileIds" => [(string) $fileId],
            "scope" => $scope,
        ];

        $data = [
            "context" => $context,
            "user" => $this->user_id,
            "messages" => [],
            "summary" => $summary,
            "timestamp" => (string) $timestamp,
        ];

        $response = $this->curl->post($url, json_encode($data));

        return $response;
    }

    // sends a message
    //
    // also pushed the message to the history of a conversation
    public function send_message(string $conversationId, string $message)
    {
        $url = "$this->service_url/api/v1/conversations/$conversationId/message";

        $data = [
            "message" => $message,
            "model" => $this->ollama_model,
        ];

        $response = $this->curl->put($url, json_encode($data));

        return $response;
    }

    // delete a conversation
    public function delete_conversation(string $conversationId)
    {
        $url = "$this->service_url/api/v1/conversations/$conversationId";

        $response = $this->curl->delete($url);

        return $response;
    }

    // attaches files to a conversation
    //
    // used for the retrieval of information
    public function attach_files(string $conversationId, array &$fileIds)
    {
        $url = "$this->service_url/moodle/v1/conversation/$conversationId/context";

        $data = ["toBeAdded" => $fileIds];

        $response = $this->curl->put($url, json_encode($data));

        return $response;
    }

    // removes files from a conversation
    public function remove_files(string $conversationId, array $fileIds)
    {
        $url = "$this->service_url/moodle/v1/conversation/$conversationId/context";

        $data = ["toBeRemoved" => $fileIds];

        $response = $this->curl->put($url, json_encode($data));

        return $response;
    }

    // This might override the currently present course
    public function attach_course(string $conversationId, string $courseId)
    {
        $url = "$this->service_url/moodle/v1/conversation/$conversationId/context";

        $data = ["courseId" => $courseId];

        $response = $this->curl->put($url, json_encode($data));

        return $response;
    }

    public function remove_course(string $conversationId)
    {
        $url = "$this->service_url/moodle/v1/conversation/$conversationId/context";

        $data = ["removeCourse" => $courseId];

        $response = $this->curl->put($url, json_encode($data));

        return $response;
    }

    public function retrieve_files_for_course(string $courseId, string $userId)
    {
        global $DB;

        $course = $DB->get_record(
            "course",
            ["id" => $courseId],
            "*",
            MUST_EXIST
        );
        $mods = get_coursemodules_in_course("mokitul", $courseId);

        $fileIds = [];

        foreach ($mods as $cm) {
            $modulecontext = context_module::instance($cm->id);
            $fs = get_file_storage();
            $files = $fs->get_area_files(
                $modulecontext->id,
                "mod_mokitul",
                "attachments"
            );

            foreach ($files as $file) {
                if (empty($file->get_filesize())) {
                    continue;
                }

                $fileIds[] = $file->get_id();
            }
        }

        // check if user has access and the file is public

        return $fileIds;
    }

    public function download_file(string $fileId)
    {
        $fs = get_file_storage();

        $file = $fs->get_file_by_id($fileId);

        $forcedownload = true;

        $options = [];

        send_stored_file($file, 0, 0, $forcedownload, $options);
    }

    public function retrieve_files_for_courses(array $ids, string $userId)
    {
        $id = optional_param("id", 0, PARAM_INT);

        $files = [];

        foreach ($ids as $id) {
            $files[] = $this->retrieve_files_for_course($id, $userId);
        }
    }

    public function send_analytics_event(
        array $event,
        string $sessionId,
        string $userAgent
    ) {
        $pseudoDevice = hash("sha256", $this->user_id . $userAgent);
        //hash the user and session id
        $hashedUser = hash("sha256", $this->user_id); //todo secret key settings for hashing
        $hashedSession = hash("sha256", $sessionId); //todo maybe add caching for the hashed values

        $event["eventProperties"]["model"] = $this->ollama_model;

        $data = [
            "userId" => $hashedUser,
            "sessionId" => $hashedSession,
            "deviceId" => $pseudoDevice,
            "event" => $event,
        ];

        $response = $this->curl->post(
            $this->analytics_path,
            json_encode($data)
        );

        return $response;
    }
}
