<?php
require_once 'functionfile.php';

if (isset($_SESSION['emp_logged_in']) || $_SESSION['emp_logged_in']  == true) {

    $empName = ucfirst($_SESSION['empName']);
    $empImage = $_SESSION['empImage'];
}
?>
<nav class="navbar navbar-expand-lg navbar-dark ">
  <div class="container-fluid">
  
      <?= getEmployeeProfileHtml($empImage) ?>
      <a class="navbar-brand text-dark"><h2><?=$empName?></h2></a>



            <!-- Extra placeholder -->  
        <?php if (isset($navbarExtraContent)) : ?>
            <div class="d-flex align-items-center">
                <?= $navbarExtraContent ?>
            </div>
        <?php endif; ?>

    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto"> <!-- Aligns to right -->
        <li class="nav-item">
                    <a class="nav-link text-dark" href="leave_tracking_employee_page.php"><i class="bi bi-activity"></i> Leave Tracking</a>
        </li>
        <li class="nav-item">
                    <a class="nav-link text-dark" href="leaveApplicationForm.php"><i class="bi bi-pencil-square"></i> Leave Application</a>
        </li>
        <li class="nav-item">
                    <a class="nav-link text-dark" href="leave_history.php"><i class="bi bi-chat-right-dots-fill"></i> Leave History</a>
        </li>
        <li class="nav-item">
                    <a class="nav-link text-danger" href="../auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>

        </li>
      </ul>
    </div>
  </div>
</nav>
