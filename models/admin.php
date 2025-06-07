    <?php

    require_once 'curlFun.php';


    class Admin extends ApiReq {
        // private $apicall;

        public function __construct()
        {
            // $this->apicall = new ApiReq();
            parent::__construct();
        }

            public function adminAuth($adminName, $adminPass)
        {

            $data = ['adminName' => $adminName, 'adminPass' => $adminPass];
            $adminCheck = $this->curl_call($data, "AdminloginCheck");
            if (isset($adminCheck['status']) && $adminCheck['status'] === 'success') {
                // $successMsg= " admin checked";
                // header("Location: admin_page.php");
                // exit;
                return $adminCheck;
            }
        }

            function SelectRoleName()
        {
            $SelectRoleName = $this->curl_call([], "SelectRoleName");

            $role = [];

            foreach ($SelectRoleName['msg'] as $list) {
                $role[$list['role_id']] = $list['role_name'];
            }
            return $role;
        }


        function InsertEmployeeData($empName,$empEmail,$empGender,$empDateOfJoin,$empRoleId,$photoPath){
            $data = [
            'empName' => $empName,
            'empEmail' => $empEmail,
            'empGender' => $empGender,
            'empDateOfJoin' => $empDateOfJoin,
            'empRoleId' => $empRoleId,
            'employee_phpto'=>$photoPath
        ];
        
        $InsertEmployeeData = $this->curl_call($data, "InsertEmployeeData");
        // echo "<pre>"; print_r($data); echo "</pre>";
        // echo "<pre>"; print_r($InsertEmployeeData); echo "</pre>";

        return $InsertEmployeeData;
        }

        // function summa(){
        // $summa = $this->apicall->curl_call([], "summa");
        // echo $summa;
        // return $summa;
        // }


        

    }
