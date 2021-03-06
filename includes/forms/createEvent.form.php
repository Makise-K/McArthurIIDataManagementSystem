<?php
  session_start();
  include('../autoloader.inc.php');

  if(isset($_POST['event-submit']))
  {
    $start_date = date('Y-m-d H:i:s', strtotime($_POST['start_date']. $_POST['start_time']));
    $end_date = date('Y-m-d H:i:s', strtotime($_POST['end_date'].$_POST['end_time']));
    $title = $_POST['title'];
    $description = $_POST['description'];

    $obj = new Events();
    $duplicate = $obj->checkTitle($title);

    if($duplicate?? null !== null)
    {
        header("Location: ../CreateEvent.php?error=duplicateTitle&start_date=".$_POST['start_date'].
        "&start_time=".$_POST['start_time']."&end_date=".$_POST['end_date']."&end_time=".$_POST['end_time'].
        "&description=".$description."&event-img=".$_FILES['event-img']);
        exit();
    }

    if($start_date > $end_date)
    {
      header("Location: ../CreateEvent.php?error=invalidDateTime&title=".$title.
      "&description=".$description."&event-img=".$_FILES['event-img']);
      exit();
    }



    $obj = new Events();
    $obj->setEvent($_SESSION['user_id'],$start_date, $end_date, $title, $description);
    $confid = $obj->getId($title);


    // LOOP FOR GETTING ALL THE USERS REGISTERED EXCEPT THE ADMINISTRATOR
    $objNotif = new Notifications();
    $objUser = new User();
    $resultUser = $objUser->getAllUserId();
    while($rowUser = mysqli_fetch_array($resultUser))
    {
      // INSERTING EVENTS DATA INTO NOTIFICATIONS TABLE
      $result = $obj->getEventsAll();
      while($row = mysqli_fetch_array($result))
      {
        if($row['id'] == $confid['id'])
          $objNotif->insertNotif($rowUser['user_id'], 'event', $row['id'], 'unread', $row['created']);
      }
    }



//image upload code

    if($_FILES['event-img']['name'] != null)
    {

      $file = $_FILES['event-img'];
      $filename = $_FILES['event-img']['name'];
      $fileTmpName = $_FILES['event-img']['tmp_name'];
      $fileSize = $_FILES['event-img']['size'];
      $fileError = $_FILES['event-img']['error'];
      $fileType = $_FILES['event-img']['type'];

      $fileExt = explode('.', $filename);
      $fileActualExt = strtolower(end($fileExt));

      $allowed = array('jpg', 'jpeg', 'png');

      if(in_array($fileActualExt, $allowed))
      {

        if($fileError === 0)
        {

          if($fileSize < 16777215)
          {

            $imgData =addslashes(file_get_contents($_FILES['event-img']['tmp_name']));
            $imageProperties = getimageSize($_FILES['event-img']['tmp_name']);
            $obj = new Events();
            $confid = $obj->getId($title);
            echo $confid['id'];
            $obj->imgUpload($confid['id'], $imageProperties, $imgData);

            header("Location: ../CreateEvent.php?success=eventCreated&id=".$confid['id']);
            exit();

          }
          else
          {
            echo $fileSize;
            header("Location: ../CreateEvent.php?error=imageSizeTooBig&start_date=".$_POST['start_date'].
            "&start_time=".$_POST['start_time']."&end_date=".$_POST['end_date']."&end_time=".$_POST['end_time'].
            "&description=".$description."&event-img=".$_FILES['event-img']."&title=".$title);
            exit();

          }
        }
        else
        {
          echo "there was an error in uploading the file";
          header("Location: ../CreateEvent.php?error=errorInUploadingFile&start_date=".$_POST['start_date'].
          "&start_time=".$_POST['start_time']."&end_date=".$_POST['end_date']."&end_time=".$_POST['end_time'].
          "&description=".$description."&event-img=".$_FILES['event-img']."&title=".$title);
          exit();
        }
      }

      else
      {
        echo "invalid filetype";
      }
    }
    elseif($_FILES['event-img']['name'] == null)
    {
      header("Location: ../CreateEvent.php?success=eventCreated&id=".$confid['id']);
      exit();
    }

 }
 else
 {
     header("Location: ../forms/logout.form.php");
     exit();
 }
