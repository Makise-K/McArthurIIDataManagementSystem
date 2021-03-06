<?php
session_start();
include('../autoloader.inc.php');

if(isset($_POST['submit-registry']))
{
  if($_SESSION['user_id']===null)
  {
    // header("Location: login.php");
    echo $_SESSION['user_id'];
    exit();
  }

  $file = $_FILES['img-profile'];
  $filename = $_FILES['img-profile']['name'];
  $fileTmpName = $_FILES['img-profile']['tmp_name'];
  $fileSize = $_FILES['img-profile']['size'];
  $fileError = $_FILES['img-profile']['error'];
  $fileType = $_FILES['img-profile']['type'];

  $fileExt = explode('.', $filename);
  $fileActualExt = strtolower(end($fileExt));

  $allowed = array('jpg', 'jpeg', 'png');

  if(in_array($fileActualExt, $allowed))
  {
    if($fileError === 0)
    {
      if($fileSize < 1002400)
      {

        $imgData =addslashes(file_get_contents($_FILES['img-profile']['tmp_name']));
  	    $imageProperties = getimageSize($_FILES['img-profile']['tmp_name']);
        $obj = new ProfilePic();
        $confid = $obj->pic_upload($_SESSION['user_id'], $imageProperties, $imgData);
      }
      else
      {
        echo $fileSize;
         echo "file is bigger than 1mb";
      }
    }
    else
    {
      echo "there was an error in uploading the file";
    }
  }
  else
  {
    echo "invalid filetype";
  }

  $grade = $_POST['grade'];
  $station = $_POST['station']?? "1";
  $dateofbirth = $_POST['dateofbirth'];
  $civilstatus = $_POST['civil-stat'];
  $highesteducattn = $_POST['high-educ'];
  $specification = $_POST['specification'];
  $orig_appointment = $_POST['orig-appointment'];
  $dateofpromo = $_POST['lat-promotion'];
  $contactnum = $_POST['contact-num'];
  $fbacct = $_POST['fb-username'];



  if($_POST['civil-stat'] == null)
  {
    header("Location: ../register2.php?error=noCivilStatus&station=".$station."&dateofbirth="
    .$dateofbirth."&grade=".$grade."&highesteducattn=".$highesteducattn."&specification=".$specification.
    "&orig_appointment=".$orig_appointment."&dateofpromo=".$dateofpromo."&contactnum=".$contactnum."&fbacct=".$fbacct);
    exit();
  }

  if($station === null or $station == "1")
  {
    header("Location: ../register2.php?error=noSchoolAssigned&civilstatus=".$civilstatus."&dateofbirth="
    .$dateofbirth."&grade=".$grade."&highesteducattn=".$highesteducattn."&specification=".$specification.
    "&orig_appointment=".$orig_appointment."&dateofpromo=".$dateofpromo."&contactnum=".$contactnum."&fbacct=".$fbacct);
    exit();
  }
  elseif(!preg_match("/^[0-9]{3}-[0-9]{4}-[0-9]{4}$/", $_POST['contact-num']))
  {
    header("Location: ../register2.php?error=invalidContactNumber&station=".$station."&dateofbirth="
    .$dateofbirth."&civilstatus=".$civilstatus."&highesteducattn=".$highesteducattn."&specification=".$specification.
    "&orig_appointment=".$orig_appointment."&dateofpromo=".$dateofpromo."&grade=".$grade."&fbacct=".$fbacct);
    exit();
  }

  $obj = new AddInfo();

  $obj->setAddInfo($_SESSION['user_id'], $grade, $station, $dateofbirth, $civilstatus, $highesteducattn, $specification, $orig_appointment, $dateofpromo, $contactnum, $fbacct);
  $objAnn = new Announcement();
  $objNotif = new Notifications();
  // INSERTING ANNOUNCEMENTS DATA INTO NOTIFICATIONS TABLE
  $result = $objAnn->getAllAnn();
  while($row = mysqli_fetch_array($result))
  {
    $objNotif->insertNotif($_SESSION['user_id'], 'announcement', $row['id'], 'unread', $row['date_created']);
  }
  // INSERTING EVENTS DATA INTO NOTIFICATIONS TABLE
  $objEven = new Events();
  $result = $objEven->getEventsAll();
  foreach($result as $i)
  {
    $objNotif->insertNotif($_SESSION['user_id'], 'event', $i['id'], 'unread', $i['created']);
  }
  // INSERTING REPORTS DATA INTO NOTIFICATIONS TABLE
  $objRep = new Reports();
  $result = $objRep->getAllReports();
  foreach($result as $i)
  {
    $objNotif->insertNotif($_SESSION['user_id'], 'report', $i['report_id'], 'unread', $i['date_created']);
  }
  header("Location: ../login.php?success=signedupsuccessfully");
  session_unset();
  session_destroy();

  exit();
}

else
{
  // header("Location: ../login.php");
  echo "hello";
  exit();
}
