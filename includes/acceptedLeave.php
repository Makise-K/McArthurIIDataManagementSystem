<?php

session_start();
$exp = "/Principal/";
if($_SESSION['status']  != 'Administrator' AND !preg_match($exp, $_SESSION['designation']))
{
  header("Location: forms/logout.form.php");
  exit();
}

include('autoloader.inc.php'); ?>

<!DOCTYPE html>

<html lang="en">

    <head>

        <meta charset="utf-8" />

        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <meta name="description" content="" />

        <meta name="author" content="" />

        <title>McArthurII District AcceptedLeave</title>

        <link href="css/styles.css" rel="stylesheet" />
        <link href="css/events.css" rel="stylesheet" />
        <link rel="stylesheet" href="css/table.css">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>

    </head>

    <body class="sb-nav-fixed">

      <?php include('topbar.php'); ?>

      <?php include('sidebar.php'); ?>

      <div></div>

      <div id="layoutSidenav_content">

          <main>

              <div class="container-fluid">

                  <h1 class="mt-4">Accepted Leave</h1>

                  <ol style = "background-color:#86B898" class="breadcrumb mb-4">

                    <li class="breadcrumb-item active"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="LeaveRequests.php">Leave Requests</a></li>
                    <li class="breadcrumb-item active">Accepted Leave</li>


                  </ol>

                  <div class ="requests">

                    <!--REQUESTS TABLE-->
                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Type of Leave</th>
                                        <th>School</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php

                                      $obj = new Leave();
                                      $res;
                                      if($_SESSION['status']  == 'Administrator')
                                      {
                                        $res = $obj->acceptedLeaveList();
                                      }
                                      else
                                      {
                                        $res = $obj->acceptedSchoolLeaveList($_SESSION['station']);
                                      }


                                      while($row = mysqli_fetch_array($res))
                                      {
                                          echo "<tr>
                                                <td><a href = Profile.php?id=".$row['user_id'].">".$row['l_name'].", ".$row['f_name']." ". $row['m_name']."</a></td>".
                                               "<td>".$row['designation']."</td>".
                                               "<td>".$row['leaveType']."</td>".
                                               "<td>".$row['station']."</td>".
                                               "<td>".date('Y-M-d',strtotime($row['start_date']))."</td>".
                                               "<td>".date('Y-M-d',strtotime($row['end_date']))."</td>
                                                </tr>";

                                      }
                                     ?>


                                </tbody>
                            </table>

                        </div>
                        <!-- BUTTONS FO FUTURE UPGRADE -->
                        <!-- <div style = "position: static; display:flex; justify-content:center;" name="buttonDiv">

                            <button onclick="return warndel();" type='submit' value='submit' name='decline-req' class='btn btn-primary' style="width:35%;margin-right:30px; height:100%; background:red; border-radius:3px;">DELETE</button>
                            <script>
                            function warndel()
                            {

                              var result = confirm("Are you sure you want to delete checked request/s?");
                              if(!result)
                              {

                                  alert('Submission Canceled');
                                  return false;

                              }
                            }
                            </script>

                            <button onclick="return warnAccpt();" type='submit' value='submit' name='accpt-req' class='btn btn-primary' style="width:35%; border-radius:3px; height:100%;">ACCEPT</button>
                            <script>
                            function warnAccpt()
                            {

                              var result = confirm("Are you sure you want to accpet checked request/s?");
                              if(!result)
                              {

                                  alert('Submission Canceled');
                                  return false;

                              }
                            }
                            </script>
                        </div> -->
                </div>




                  </div>



              </div>

          </main>

        <?php include('footer.php') ?>

        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>

        <!-- SCRIPT FOR SELECT ALL CHECKBOX -->

        <script>
        $(document).ready(function()
        {
          $('#checkAll').click(function()
        {
          if($(this).is(':checked'))
          {
            $('.check').prop('checked', true);
          }
          else
          {
            $('.check').prop('checked', false);
          }
        });
        });


        </script>

    </body>

</html>
