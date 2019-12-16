<?php

include_once __DIR__.'/../shared/Connector.php';
include_once __DIR__.'/../shared/Strings.php';

class Comment {

    private $connection;

    public function __construct() {

        $this->connection = Connector::getConnector();        
    }

    public function get($id) {

        $out_data = array();

        $stmt = mysqli_stmt_init($this->connection);

        mysqli_stmt_prepare($stmt, "SELECT `Content`, `Type`, `EventId` FROM `COMMENTS` WHERE CommentId = ?");
        mysqli_stmt_bind_param($stmt, 's', $id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $content, $type, $event_id);
            mysqli_stmt_fetch($stmt);

            if ($content != null) {

                $out_data['content'] = $content;
                $out_data['type'] = $type;
                $out_data['event_id'] = $event_id;

            } else {

                $out_data['error'] = "Comment doesn't exists";
            }
        } else {
            $out_data['error'] = "Internal server error. ".mysqli_error($this->connection);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($this->connection);

        return $out_data;
    }

    public function get_by_event($id) {

        $out_data = array();

        $stmt = mysqli_stmt_init($this->connection);

        mysqli_stmt_prepare($stmt, "SELECT `CommentId`, `Content`, `Type` FROM `COMMENTS` WHERE EventId = ?");
        mysqli_stmt_bind_param($stmt, 's', $id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $comment_id, $content, $type);
            
            while (mysqli_stmt_fetch($stmt)) {
                if ($content != null) {

                    $temp_data['comment_id'] = $comment_id;
                    $temp_data['content'] = $content;
                    $temp_data['type'] = $type;

                    $out_data[] = $temp_data;

                } else {

                    $out_data['error'] = "Comment doesn't exists";
                }
            }
        } else {
            $out_data['error'] = "Internal server error. ".mysqli_error($this->connection);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($this->connection);

        return $out_data;
    }

    public function delete($id) {

        $out_data = array();

        $stmt = mysqli_stmt_init($this->connection);

        mysqli_stmt_prepare($stmt, "DELETE FROM `COMMENTS` WHERE CommentId = ?");
        mysqli_stmt_bind_param($stmt, 's', $id);

        if (mysqli_stmt_execute($stmt)) {

            $out_data['error'] = "Deleted comment with id: ".$id;
        } else {
            $out_data['error'] = "Internal server error. ".mysqli_error($this->connection);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($this->connection);

        return $out_data;
    }

    public function create($data) {

        $out_data = array(
            "comment_id" => null,
            "error" => null,
        );
        
        $stmt = mysqli_stmt_init($this->connection);

        do {
            $event_exists = true;

            $generated_comment_id = Strings::generateRandomString(32);

            mysqli_stmt_prepare($stmt, "SELECT EventId FROM `COMMENTS` WHERE CommentId = ?");
            mysqli_stmt_bind_param($stmt, 's', $generated_comment_id);

            if (mysqli_stmt_execute($stmt)) {

                mysqli_stmt_bind_result($stmt, $temp_event_id);
                mysqli_stmt_fetch($stmt);
            }

            if ($temp_event_id == null) {
                $event_exists = false;
            }

        } while ($event_exists);

        mysqli_stmt_prepare($stmt, "INSERT INTO `COMMENTS` (`CommentId`, `Content`, `Type`, `EventId`) VALUES ( ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'ssss', $generated_comment_id, $data['content'], $data['type'], $data['event_id']);

        if (mysqli_stmt_execute($stmt)) {
            $out_data['comment_id'] = $generated_comment_id;
        } else {
            $out_data['error'] = mysqli_error($this->connection);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($this->connection);

        return $out_data;
    }
}

?>
