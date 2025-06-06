<?php

require_once 'curlFun.php';

class Employee extends ApiReq
{

    public function __construct()
    {
        // $this->apicall = new ApiReq();
        parent::__construct();
    }

    public function empAuthenticate($employeeName, $employeeId)
    {
        // echo "called";
        // echo $employeeName;

        $data = [
            'employee_name' => $employeeName,
            'employee_id' => $employeeId
        ];

        $response = $this->curl_call($data, 'EmployeeCheck');
        // print_r($response);
        if (isset($response['status']) && $response['status'] === 'success') {
            return $response; // return employee data
        }
    }

    public function SelectRoleId($empId)
    {
        $data = ['empId' => $empId];

        $response = $this->curl_call($data, "SelectRoleId");
        if (isset($response['status']) && $response['status'] === 'success') {

            $result = $response['msg'];

            return $result;
        }
    }

    public function fetchRoleName($roleId)
    {
        $data = ['roleId' => $roleId];

        $response = $this->curl_call($data, "fetchRoleName");
        // return $response;
        if (isset($response['status']) && $response['status'] === 'success') {
            $result = $response['msg'];
            // echo "<pre>"; print_r($result);echo "</pre>"; 

            return $result;
        }
    }

    public function selectEmployeeName(){
        $empIdName = [];
        $selectEmployeeName =$this->curl_call([],"selectEmployeeName");
        
        foreach($selectEmployeeName['msg'] as $data){
            
            $empIdName[$data['employee_id']] = $data['employee_name'];
        }


        return $empIdName;
    }




} //class
