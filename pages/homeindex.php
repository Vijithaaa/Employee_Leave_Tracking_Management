<?php
// include '../configuration/config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../asset/css/style.css">
    <!-- <link rel="stylesheet" href="/emp_leave_app_tracking_ver1_copy/asset/css/style.css"> -->


    <title>home</title>
    <style>
        .card {
            margin-bottom: 20px;
            border-radius: 10px;
            border: none;
            background-color: transparent;
            box-shadow: 1px 2px 5px 1px;
        }

        .card-header {
            font-weight: bold;
            background-color: #0d1d35;
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }

        .highlight {

            padding: 2px 5px;
            border-radius: 3px;
            font-weight: bold;
        }

        h1 {
            margin-bottom: 30px;
            text-align: center;
            color: #041a3c;
        }
    </style>




</head>

<body>
    <div class="container">
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
            <div class="container-fluid">

                <img src="../asset/images/infiniti_logo.png" alt="Logo" style="width:80px;" class="rounded-pill"> Leave Tracking Application</a>
                <ul class="navbar-nav">
                    <!-- <li class="nav-item">

                        <a class="nav-link" href="auth/login_employee_page.php"><i class="bi bi-box-arrow-in-right"></i> login</a>
                    </li>
                    <li class="nav-item">

                        <a class="nav-link" href="auth/admin_login_form.php"><i class="bi bi-person-circle"></i> admin</a>
                    </li> -->
                    <!-- Change these links in your navbar -->
                    <li class="nav-item">
                        <a class="nav-link" href="auth/login.php?type=employee"><i class="bi bi-box-arrow-in-right"></i> login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="auth/login.php?type=admin"><i class="bi bi-person-circle"></i> admin</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container-fluid mt-3">

            <div class="container">
                <h1>Employee Leave Benefits</h1>

                <div class="row">
                    <!-- Maternity Leave Card -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                Maternity Leave
                            </div>
                            <div class="card-body">
                                <p>Paid leave for mothers before and after childbirth.</p>
                                <p>Total leave: <span class="highlight">26 weeks</span> (first two children), <span class="highlight">12 weeks</span> (third child onwards)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Paternity Leave Card -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                Paternity Leave
                            </div>
                            <div class="card-body">
                                <p>Leave for new fathers to care for their newborn.</p>
                                <p>Total leave: <span class="highlight">15 days</span> (govt sector), varies in private sector</p>
                            </div>
                        </div>
                    </div>

                    <!-- Sick Leave Card -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                Sick Leave
                            </div>
                            <div class="card-body">
                                <p>Leave for employees when unwell or injured.</p>
                                <p>Total leave: <span class="highlight">12 days</span> per year (minimum)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Casual Leave Card -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                Casual Leave
                            </div>
                            <div class="card-body">
                                <p>Short planned leaves for personal reasons.</p>
                                <p>Total leave: Varies by company (typically <span class="highlight">7-12 days</span>)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Earned Leave Card -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                Earned Leave
                            </div>
                            <div class="card-body">
                                <p>Accrued leave based on days worked.</p>
                                <p>Total leave: Varies by region (can be <span class="highlight">carried forward</span>)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Other Leaves Card -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                Other Leaves
                            </div>
                            <div class="card-body">
                                <p>Special leaves offered by some companies.</p>
                                <p>Includes: <span class="highlight">Marriage leave</span> (3-14 days), <span class="highlight">Compensatory leave</span>, <span class="highlight">LOP leave</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>