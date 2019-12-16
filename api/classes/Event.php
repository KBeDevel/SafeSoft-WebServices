<?php

include_once __DIR__.'/../shared/Connector.php';
include_once __DIR__.'/../shared/Strings.php';

class Event {

    private $connector;

    public function __construct() {
        $this->connector = Connector::getConnector();
    }

    public function get($id) {

        $out_data = array();

        $stmt = mysqli_stmt_init($this->connector);

        mysqli_stmt_prepare($stmt, "SELECT `EventId`, `SupervisorCorp`, `Type`, `CreatedAt`, `SubmittedAt`  FROM `EVENTS` WHERE `UserCode` = ?");
        mysqli_stmt_bind_param($stmt, 's', $id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $event_id, $supervisor, $type, $created_at, $submitted_at);
            
            while (mysqli_stmt_fetch($stmt)){

                if ($supervisor != null) {

                    $temp_data['event_id'] = $event_id;
                    $temp_data['supervisor'] = $supervisor;
                    $temp_data['type'] = $type;
                    $temp_data['created_at'] = $created_at;
                    $temp_data['submitted_at'] = $submitted_at;

                    $out_data[] = $temp_data;

                } else {

                    $out_data['error'] = "Event doesn't exists";
                    break;
                }
            }

            mysqli_stmt_free_result($stmt);
        } else {
            $out_data['error'] = "Internal server error. ".mysqli_error($this->connector);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($this->connector);

        return $out_data;
    }

    public function get_all() {

        $out_data = array();

        $stmt = mysqli_stmt_init($this->connector);

        mysqli_stmt_prepare($stmt, "SELECT `EventId`, `SupervisorCorp`, `Type`, `CreatedAt`, `SubmittedAt`, `UserCode` FROM `EVENTS`");

        if (mysqli_stmt_execute($stmt)) {

            mysqli_stmt_bind_result($stmt, $event_id, $supervisor, $type, $created_at, $submitted_at, $user_code);

            while (mysqli_stmt_fetch($stmt)) {

                if ($supervisor != null) {

                    $temp_data['event_id'] = $event_id;
                    $temp_data['supervisor'] = $supervisor;
                    $temp_data['type'] = $type;
                    $temp_data['created_at'] = $created_at;
                    $temp_data['submitted_at'] = $submitted_at;
                    $temp_data['user_code'] = $user_code;

                    $out_data[] = $temp_data;

                } else {

                    $out_data['error'] = "Event doesn't exists";
                    break;
                }
            }

            mysqli_stmt_free_result($stmt);
        } else {
            $out_data['error'] = "Internal server error. ".mysqli_error($this->connector);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($this->connector);

        return $out_data;        
    }

    public function delete($id) {

        $out_data = array();

        $stmt = mysqli_stmt_init($this->connector);

        mysqli_stmt_prepare($stmt, "DELETE FROM TOOLS WHERE EventId = ?");
        mysqli_stmt_bind_param($stmt, 's', $id);

        if (mysqli_stmt_execute($stmt)) {

            mysqli_stmt_close($stmt);
            $stmt = mysqli_stmt_init($this->connector);

            mysqli_stmt_prepare($stmt, "DELETE FROM COMMENTS WHERE EventId = ?");
            mysqli_stmt_bind_param($stmt, 's', $id);

            if (mysqli_stmt_execute($stmt)) {

                mysqli_stmt_close($stmt);
                $stmt = mysqli_stmt_init($this->connector);

                mysqli_stmt_prepare($stmt, "DELETE FROM `EVENTS` WHERE EventId = ?");
                mysqli_stmt_bind_param($stmt, 's', $id);

                if (mysqli_stmt_execute($stmt)) {
                    
                    $out_data['value'] = "Deleted Event with id: ".$id;
                } else {

                    $out_data['error'] = "Internal server error. ".mysqli_error($this->connector);
                }
            } else {

                $out_data['error'] = "Internal server error. ".mysqli_error($this->connector);
            }
        } else {

            $out_data['error'] = "Internal server error. ".mysqli_error($this->connector);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($this->connector);

        return $out_data;
    }

    public function create ($data) {

        $out_data = array(
            "event_id" => null,
            "error" => null,
        );
        
        $stmt = mysqli_stmt_init($this->connector);

        do {
            $event_exists = true;

            $generated_event_id = Strings::generateRandomString(32);

            mysqli_stmt_prepare($stmt, "SELECT UserCode FROM `EVENTS` WHERE EventId = ?");
            mysqli_stmt_bind_param($stmt, 's', $generated_event_id);

            if (mysqli_stmt_execute($stmt)) {

                mysqli_stmt_bind_result($stmt, $temp_user_code);
                mysqli_stmt_fetch($stmt);
            }

            if ($temp_user_code == null) {

                $event_exists = false;
            }

        } while ($event_exists);

        mysqli_stmt_close($stmt);
        $stmt = mysqli_stmt_init($this->connector);

        mysqli_stmt_prepare($stmt, "INSERT INTO `EVENTS` (`EventId`, `SupervisorCorp`, `Type`, `CreatedAt`, `ClosedAt`, `SubmittedAt`, `UserCode`) VALUES ( ?, ?, ?, CURRENT_TIMESTAMP, NULL, CURRENT_TIMESTAMP, ?)");
        mysqli_stmt_bind_param($stmt, 'ssis', $generated_event_id, $data['supervisor'], $data['type'], $data['user_code']);

        if (mysqli_stmt_execute($stmt)) {

            $out_data['event_id'] = $generated_event_id;
        } else {

            $out_data['error'] = mysqli_error($this->connector);
        }

        return $out_data;
    }
}

?>
