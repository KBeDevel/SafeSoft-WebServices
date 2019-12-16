<?php

include_once __DIR__.'/../shared/Connector.php';


class Tool {

    private $connection;

    public function __construct() {
        $this->connection = Connector::getConnector();
    }

    public function get($id) {

        $out_data = array();

        $stmt = mysqli_stmt_init($this->connection);

        mysqli_stmt_prepare($stmt, "SELECT `Name`, `Status`, `EventId` FROM `TOOLS` WHERE ToolId = ?");
        mysqli_stmt_bind_param($stmt, 's', $id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $name, $status, $event_id);
            mysqli_stmt_fetch($stmt);

            if ($name != null) {

                $temp_data['name'] = $name;
                $temp_data['status'] = $status;
                $temp_data['event_id'] = $event_id;

                $out_data[] = $temp_data;

            } else {

                $out_data['error'] = "Tool doesn't exists";
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

        mysqli_stmt_prepare($stmt, "SELECT `ToolId`, `Name`, `Status` FROM `TOOLS` WHERE EventId = ?");
        mysqli_stmt_bind_param($stmt, 's', $id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $tool_id, $name, $status, );
            
            while (mysqli_stmt_fetch($stmt)) {
                
                if ($name != null) {

                    $temp_data['tool_id'] = $tool_id;
                    $temp_data['name'] = $name;
                    $temp_data['status'] = $status;                    

                    $out_data[] = $temp_data;

                } else {

                    $out_data['error'] = "Tool doesn't exists";
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

        mysqli_stmt_prepare($stmt, "DELETE FROM `TOOLS` WHERE ToolId = ?");
        mysqli_stmt_bind_param($stmt, 's', $id);

        if (mysqli_stmt_execute($stmt)) {

            $out_data['error'] = "Deleted with with id: ".$id;
        } else {
            $out_data['error'] = "Internal server error. ".mysqli_error($this->connection);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($this->connection);

        return $out_data;
    }

    public function create($data) {

        $out_data = array(
            "tool_id" => null,
            "error" => null,
        );
        
        $stmt = mysqli_stmt_init($this->connection);

        do {
            $event_exists = true;

            $generated_tool_id = Strings::generateRandomString(32);

            mysqli_stmt_prepare($stmt, "SELECT EventId FROM `TOOLS` WHERE ToolId = ?");
            mysqli_stmt_bind_param($stmt, 's', $generated_tool_id);

            if (mysqli_stmt_execute($stmt)) {

                mysqli_stmt_bind_result($stmt, $temp_event_id);
                mysqli_stmt_fetch($stmt);
            }

            if ($temp_event_id == null) {
                $event_exists = false;
            }

        } while ($event_exists);

        mysqli_stmt_prepare($stmt, "INSERT INTO `TOOLS` (`ToolId`, `Name`, `Status`, `EventId`) VALUES ( ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'ssss', $generated_tool_id, $data['name'], $data['status'], $data['event_id']);

        if (mysqli_stmt_execute($stmt)) {

            $out_data['tool_id'] = $generated_tool_id;
        } else {
            
            $out_data['error'] = mysqli_error($this->connection);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($this->connection);

        return $out_data;
    }

}

?>
