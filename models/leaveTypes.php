<?php
require_once 'curlFun.php';

class LeaveType extends ApiReq
{


    function __construct()
    {
        parent::__construct();
    }


    function getAllLeaveTypes()
    {
        $LeaveTypes = [];
        $LeaveTypeIdName = [];
        $getAllleaveTypes =  $this->curl_call([], "fetchLeavetype");
        $storeLeaveTypes = $getAllleaveTypes['msg'];
        // echo "<pre>"; print_r($storeLeaveTypes);echo "</pre>";

        foreach ($storeLeaveTypes as $list) {

            $LeaveTypeIdName[$list['leave_type_id']] = $list['leave_name'];
        }
        $LeaveTypes['original'] = $storeLeaveTypes;
        $LeaveTypes['leaveIdName'] = $LeaveTypeIdName;


        // echo "<pre>"; print_r($LeaveTypes);echo "</pre>";

        return $LeaveTypes;
    }


    public function fetchLeaveTaken($empId)
    {
        $leave = [];
        $total_leave = 0;
        $data = ['empId' => $empId];
        $fetchLeaveTaken = $this->curl_call($data, "fetchLeaveTaken");

        $LeaveTakenCount = $fetchLeaveTaken['msg'];
        foreach ($LeaveTakenCount as $takenCount) {
            $total_leave += $takenCount['leave_taken'];
        }
        $leave['leaveDetails'] = $LeaveTakenCount;
        $leave['totalCount'] = $total_leave;
        
        return $leave;
    }
}
