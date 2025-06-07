<?php
include '../configuration/config.php';
// include '../api_conn/curlFun.php';

class Server extends Database
{
    public $pdo;
    public function __construct()
    {
        parent::__construct();

        $db = new Database();
        $this->pdo = $db->getConnection();
    }


    public function SelectAllEmployees($response)
    {
        $stmt = $this->pdo->prepare("SELECT employee_name, role_id FROM employee_detail
                                 WHERE employee_id = :employee_id");
        $stmt->bindParam(':employee_id', $response['data']['employee_id'], PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $employee = $stmt->fetch(PDO::FETCH_ASSOC);
            return json_encode([
                'status' => 'success',
                'msg' => [
                    'employee_name' => $employee['employee_name'],
                    'role_id' => $employee['role_id']
                ]
            ]);
        } else {
            return json_encode([
                'status' => 'error',
                'msg' => 'Employee not found'
            ]);
        }
    }

    public function AdminloginCheck($response)
    {
        $stmt = $this->pdo->prepare("SELECT * from admin WHERE name=:name AND pass=:pass");
        $stmt->bindParam(':name', $response['data']['adminName']);
        $stmt->bindParam(':pass', $response['data']['adminPass']);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            return json_encode(['status' => 'success', 'msg' => $admin]);
        } else {
            return json_encode(['status' => 'error', 'msg' => false]);
        }
    }

    public function SelectRoleName()
    {
        $stmt = $this->pdo->prepare("SELECT * from role_detail");
        if ($stmt->execute() && $stmt->rowCount() > 0) {
            $value = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($value) {
                return json_encode(['status' => 'success', 'msg' => $value]);
            }
        } else {
            return json_encode(['status' => 'error', 'msg' => 'no role_detail in db']);
        }
    }


    public function InsertEmployeeData($response)
    {
        $empName = $response['data']['empName'];
        $empEmail = $response['data']['empEmail'];
        $empGender = $response['data']['empGender'];
        $empDateOfJoin = $response['data']['empDateOfJoin'];
        $empRoleId = $response['data']['empRoleId'];
        $photoPath = $response['data']['employee_phpto'];


        $stmt = $this->pdo->prepare("INSERT into employee_detail(employee_name,emp_email_id,gender,date_of_joining,role_id,employee_image) 
                                  values (:employee_name,:emp_email_id,:gender,:date_of_joining,:role_id,:employee_image)");
        $stmt->bindParam(':employee_name', $empName);
        $stmt->bindParam(':emp_email_id', $empEmail);
        $stmt->bindParam(':gender', $empGender);
        $stmt->bindParam(':date_of_joining', $empDateOfJoin);
        $stmt->bindParam(':role_id', $empRoleId);
        $stmt->bindParam(':employee_image', $photoPath);
        
        // $stmt->execute();
        // echo "Hi";
        if ($stmt->execute()) {
             $lastId = $this->pdo->lastInsertId();
            return json_encode(['status' => 'success', 'msg' => [ 'employee_id' => $lastId] ]);
        } else {
            return json_encode(['status' => 'error', 'msg' => 'dberror']);
        }
    }

    public function EmployeeCheck($response)
    {

        // echo "called";

        $stmt = $this->pdo->prepare("SELECT *  FROM employee_detail 
                          WHERE employee_id = :employee_id 
                          AND employee_name = :employee_name");
        $stmt->bindParam(':employee_id', $response['data']['employee_id'], PDO::PARAM_INT);
        $stmt->bindParam(':employee_name', $response['data']['employee_name'], PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $employee = $stmt->fetch(PDO::FETCH_ASSOC);
            return json_encode([
                'status' => 'success',
                'msg' => $employee
                // 'employee_data' => $employee // Include full data,

            ]);
        } else {
            return json_encode(['status' => 'error', 'msg' => false]);
        }
    }


    public function SelectRoleId($response)
    {
        $stmt = $this->pdo->prepare('SELECT role_id FROM employee_detail where employee_id = :employee_id');
        $stmt->bindParam(':employee_id', $response['data']['empId']);
        if ($stmt->execute() && $stmt->rowCount() > 0) {
            $value = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($value) {

                return json_encode(['status' => 'success', 'msg' => $value]);
            } else {
                return json_encode(['status' => 'error', 'msg' => 'no such user in db']);
            }
        }
    }


    public function fetchRoleName($response)
    {
        $stmt = $this->pdo->prepare("SELECT role_name FROM role_detail WHERE role_id = :role_id");
        $stmt->bindParam(':role_id', $response['data']['roleId']);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $value = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($value) {
                return (json_encode(['status' => 'success', 'msg' => $value]));
            } else {
                return (json_encode(['status' => 'error', 'msg' => 'no such role_id in db']));
            }
        }
    }


    public function fetchLeavetype()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM leave_types");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $value = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($value) {
                return json_encode(['status' => 'success', 'msg' => $value]);
            } else {
                return json_encode(['status' => 'error', 'msg' => 'no such leavetype in db']);
            }
        }
    }


    public function fetchLeaveTaken($response)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM leave_tracking WHERE employee_id =:employee_id ");
        $stmt->bindParam(':employee_id', $response['data']['empId']);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $value = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($value) {
                return json_encode(['status' => 'success', 'msg' => $value]);
            }
        } else {
            return json_encode(['status' => 'error', 'msg' => 'no leave record in db']);
        }
    }



    public function InsertLeaveData($response)
    {
        $stmt = $this->pdo->prepare("INSERT INTO leave_application(employee_id, leave_type_id, leave_start_date, leave_end_date)
                              VALUES
                              (:employee_id,:leave_type_id,:leave_start_date,:leave_end_date)
                              ");
        $stmt->bindParam(':employee_id', $response['data']['empId']);
        $stmt->bindParam(':leave_type_id', $response['data']['leave_type_id']);
        $stmt->bindParam(':leave_start_date', $response['data']['start_date']);
        $stmt->bindParam(':leave_end_date', $response['data']['end_date']);
        $stmt->execute();
        // print_r(json_encode($result));
        $lastInsertId = $this->pdo->lastInsertId();
        if ($lastInsertId) {
            return json_encode(['status' => 'success', 'msg' => $lastInsertId]);
        } else {
            return json_encode(['status' => 'error', 'msg' => 'no leave record in db']);
        }
    }



    public function SelectApplication($response)
    {

        $stmt = $this->pdo->prepare("SELECT * from leave_application WHERE employee_id=:employee_id");
        $stmt->bindParam(':employee_id', $response['data']['empId']);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $value = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($value) {
                return json_encode(['status' => 'success', 'msg' => $value]);
            }
        } else {
            return json_encode(['status' => 'error', 'msg' => 'no leave record in db']);
        }
    }

    public function deleteApplication($response)
    {

        $stmt = $this->pdo->prepare("DELETE FROM leave_application 
                                where application_id = :application_id 
                                AND employee_id = :empId
                                AND status = 'pending'");
        $stmt->bindParam(':empId', $response['data']['empId']);
        $stmt->bindParam(':application_id', $response['data']['application_id']);
        if ($stmt->execute()) {
            return json_encode(['status' => 'success', 'msg' => true]);
        } else {
            return json_encode(['status' => 'error', 'msg' => 'no record in db']);
        }
    }

    public function UpdateLeaveData($response)
    {
        $stmt = $this->pdo->prepare("UPDATE leave_application 
                        SET leave_type_id = :leave_type_id, 
                            leave_start_date = :start_date, 
                            leave_end_date = :end_date,
                            reqested_date = CURRENT_TIMESTAMP
                        WHERE application_id = :appId 
                        AND employee_id = :empId
                        AND status = 'pending'");

        $stmt->bindParam(':empId', $response['data']['empId']);
        $stmt->bindParam(':appId', $response['data']['appId']);
        $stmt->bindParam(':leave_type_id', $response['data']['leave_type_id']);
        $stmt->bindParam(':start_date', $response['data']['start_date']);
        $stmt->bindParam(':end_date', $response['data']['end_date']);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                return json_encode(['status' => 'success', 'msg' => true]);
            } else {
                return json_encode(['status' => 'error', 'msg' => 'No matching pending record found to update']);
            }
        }
    }

    public function SelectLeaveFormData($response)
    {

        $stmt = $this->pdo->prepare("SELECT * FROM leave_application 
                                        WHERE application_id = :appId AND employee_id = :empId");
        $stmt->bindParam(':empId', $response['data']['empId']);
        $stmt->bindParam(':appId', $response['data']['appId']);
        $stmt->execute();
        $existingData = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($existingData) {
            if ($stmt->rowCount() > 0) {
                return json_encode(['status' => 'success', 'msg' => $existingData]);
            } else {
                return json_encode(['status' => 'error', 'msg' => 'No matching pending record found to update']);
            }
        }
    }

    public function SelectAllApplication()
    {
        $stmt = $this->pdo->prepare("SELECT * from leave_application WHERE status='pending'");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $value = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($value) {
                return json_encode(['status' => 'success', 'msg' => $value]);
            }
        } else {
            return json_encode(['status' => 'error', 'msg' => 'no leave record in db']);
        }
    }

    public function selectEmployeeName()
    {

        $stmt = $this->pdo->prepare("SELECT employee_id,employee_name from employee_detail");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $value = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($value) {
                return json_encode(['status' => 'success', 'msg' => $value]);
            }
        } else {
            return json_encode(['status' => 'error', 'msg' => 'no name record in db']);
        }
    }

    public function updateLeaveApp($response)
    {
        $stmt = $this->pdo->prepare("UPDATE leave_application 
                        SET 
                        status =:status,
                        response_date = CURRENT_TIMESTAMP() 
                        WHERE 
                        application_id = :appId");
        $stmt->bindParam(':status', $response['data']['status']);
        $stmt->bindParam(':appId', $response['data']['appId']);
        if ($stmt->execute()) {
            // Check if any rows were actually affected
            if ($stmt->rowCount() > 0) {
                return json_encode(['status' => 'success', 'msg' => 'Application updated successfully']);
            } else {
                return json_encode(['status' => 'error', 'msg' => 'No application found with that ID']);
            }
        } else {
            return json_encode(['status' => 'error', 'msg' => 'Database update failed']);
        }
    }

    public function Selecting_appIds($response)
    {
        $stmt = $this->pdo->prepare("SELECT * from leave_application where application_id=:application_id");
        $stmt->bindParam(':application_id', $response['data']['appId']);
        if ($stmt->execute() && $stmt->rowCount() > 0) {
            $value = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($value) {
                return json_encode(['status' => 'success', 'msg' => $value]);
            }
        } else {
            return json_encode(['status' => 'error', 'msg' => 'no application details in db']);
        }
    }

    public function Insertdata_to_LeaveTrack($response)
    {
        $stmt = $this->pdo->prepare("INSERT into leave_tracking(employee_id,leave_type_id,leave_taken) 
                                  values (:emp_id,:leave_id,:total_days)");
        $stmt->bindParam(':emp_id', $response['data']['emp_id']);
        $stmt->bindParam(':leave_id', $response['data']['leave_id']);
        $stmt->bindParam(':total_days', $response['data']['total_days']);

        if ($stmt->execute()) {
            return json_encode(['status' => 'success', 'msg' => true]);
        } else {
            return json_encode(['status' => 'error', 'msg' => 'data not inserted']);
        }
    }
}


$serverobj = new Server();


$headers = getallheaders();
$apiKey = $headers['API-KEY'] ?? null;

// if ($_SERVER["HTTP_API_KEY"] == "testuser") {
if ($apiKey == "testuser") {

    header('Content-Type: application/json');

    $response = json_decode($_POST['req'], true);

    switch ($response['action']) {

        case "SelectAllEmployees":

            $SelectAllEmployees = $serverobj->SelectAllEmployees($response);
            echo $SelectAllEmployees;
            break;

        case "AdminloginCheck":

            $AdminloginCheck = $serverobj->AdminloginCheck($response);
            echo $AdminloginCheck;
            break;

        case "SelectRoleName":

            $SelectRoleName = $serverobj->SelectRoleName();
            echo $SelectRoleName;
            break;

        case "InsertEmployeeData":

            $InsertEmployeeData = $serverobj->InsertEmployeeData($response);
            echo $InsertEmployeeData;
            break;


        case "EmployeeCheck":

            $EmployeeCheck = $serverobj->EmployeeCheck($response);
            echo $EmployeeCheck;
            break;

        case "SelectRoleId":

            $SelectRoleId = $serverobj->SelectRoleId($response);
            echo $SelectRoleId;
            break;

        case "fetchRoleName":

            $fetchRoleName = $serverobj->fetchRoleName($response);
            echo $fetchRoleName;
            break;


        case "fetchLeavetype":

            $fetchLeavetype = $serverobj->fetchLeavetype();
            echo $fetchLeavetype;
            break;


        case "fetchLeaveTaken":

            $fetchLeaveTaken = $serverobj->fetchLeaveTaken($response);
            echo $fetchLeaveTaken;
            break;

        case "InsertLeaveData":
            $InsertLeaveData = $serverobj->InsertLeaveData($response);
            echo $InsertLeaveData;
            break;

        case "SelectApplication":

            $SelectApplication = $serverobj->SelectApplication($response);
            echo $SelectApplication;
            break;

        case "deleteApplication":

            $deleteApplication = $serverobj->deleteApplication($response);
            echo $deleteApplication;
            break;


        case "UpdateLeaveData":

            $UpdateLeaveData = $serverobj->UpdateLeaveData($response);
            echo $UpdateLeaveData;
            break;

        case "SelectLeaveFormData":

            $SelectLeaveFormData = $serverobj->SelectLeaveFormData($response);
            echo $SelectLeaveFormData;
            break;


        case "SelectAllApplication":

            $SelectAllApplication = $serverobj->SelectAllApplication();
            echo $SelectAllApplication;
            break;

        case "selectEmployeeName":

            $selectEmployeeName = $serverobj->selectEmployeeName();
            echo $selectEmployeeName;
            break;

        case "updateLeaveApp":
            $updateLeaveApp = $serverobj->updateLeaveApp($response);
            echo $updateLeaveApp;
            break;


        case "Selecting_appIds":

            $Selecting_appIds = $serverobj->Selecting_appIds($response);
            echo $Selecting_appIds;
            break;

        case "Insertdata_to_LeaveTrack":
            $Insertdata_to_LeaveTrack = $serverobj->Insertdata_to_LeaveTrack($response);
            echo $Insertdata_to_LeaveTrack;

            break;





        default:
            http_response_code(400);
            echo json_encode(['status' => 'error', 'msg' => 'Invalid action specified']);
            break;
    }
}
