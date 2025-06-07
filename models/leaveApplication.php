<?php
require_once 'curlFun.php';

class LeaveApplication extends ApiReq
{

    function __construct()
    {
        parent::__construct();
    }

    public function insertApplication($empId, $leave_type_id, $start_date, $end_date)
    {

        $data = [
            'empId' => $empId,
            'leave_type_id' => $leave_type_id,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];
        $InsertLeaveData =  $this->curl_call($data, "InsertLeaveData");
        return $InsertLeaveData;
    }

    public function SelectApplication($empId)
    {
        $data = ['empId' => $empId];

        $SelectApplication =  $this->curl_call($data, "SelectApplication");
        
        if (!empty($SelectApplication) && is_array($SelectApplication) && isset($SelectApplication['msg']) && is_array($SelectApplication['msg'])) {
            $SelectApplication = $SelectApplication['msg'];
        } else {
            $SelectApplication = [];
        }
        // $SelectApplication = $SelectApplication['msg'];
        return $SelectApplication;
    }


    public function deleteApplication($empId, $application_id)
    {
        $data = ['empId' => $empId, 'application_id' => $application_id];

        $deleteApplication =  $this->curl_call($data, "deleteApplication");

        return $deleteApplication;
    }

    public function SelectLeaveFormData($empId, $application_id)
    {
        $data = ['empId' => $empId, 'appId' => $application_id];

        $SelectLeaveFormData = $this->curl_call($data, "SelectLeaveFormData");

        return $SelectLeaveFormData['msg'];
    }


    //approver page

    public function UpdateLeaveData($empId, $leave_type_id, $start_date, $end_date, $application_id)
    {
        $data = [
            'empId' => $empId,
            'leave_type_id' => $leave_type_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'appId' => $application_id
        ];

        $UpdateLeaveData = $this->curl_call($data, "UpdateLeaveData");

        return $UpdateLeaveData;
    }



    public function  SelectAllApplication()
    {

        $SelectAllApplication = $this->curl_call([], "SelectAllApplication");

        return $SelectAllApplication;
    }

    public function Selecting_appIds($application_id)
    {

        $data = ['appId' => $application_id];

        $Selecting_appIds = $this->curl_call($data, "Selecting_appIds");

        return $Selecting_appIds['msg'];
    }

    public function updateLeaveApp($status, $application_id)
    {
        $data = ['status' => $status, 'appId' => $application_id];

        $updateLeaveApp = $this->curl_call($data, "updateLeaveApp");
        return $updateLeaveApp;
    }


    public function Insertdata_to_LeaveTrack($total_days, $leave_id, $emp_id)
    {
        $data = ['total_days' => $total_days, 'leave_id' => $leave_id, 'emp_id' => $emp_id];

        $Insertdata_to_LeaveTrack = $this->curl_call($data, "Insertdata_to_LeaveTrack");

        return $Insertdata_to_LeaveTrack;
    }
}
