<?php

    class MokitulClient {
        /*
         * Send a message to the API
         */
        public function send_message($conversationId, $message) {}



        /*
         * Gets all files attached to the course
         * 
         * Is supposed to be used from within the pyhton api itself
         * 
         * @return FileContext
         */
        public function get_course_files() {}

        public function get_attached_files() {
            global $DB;

            $files = $DB->get_records('files', array('contextid' => $this->courseId));

            return new FileContext($files);
        }
    }

    class FileContext {
        private $files = array();

        public function __construct($files) {
            $this->files = $files;
        }
    }

    class UserContext {
        
    }
