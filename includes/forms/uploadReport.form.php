<?php
//       refers to the file array in formData.append()
//                  V
//                  V    refers to the filename uploaded
//                  V       V
//                  V       V
//                  V       V
if(isset($_FILES['file']['name']))
{
    //uploading the files in the upload folder which is a new folder created by me
    foreach($_FILES['file']['name'] as $keys => $values)
    {
         // SAVE TO THE UPLOAD FOLDER DIRECTLY       INDEX IN FILE ARRAY
         //
         $nm = explode('.', $values);
         if(move_uploaded_file($_FILES['file']['tmp_name'][$keys], 'Reports/'.$_POST['filename'].'/'.$nm[0].'_'.$_POST['id'].'.'.$nm[1]))
         {
              echo "File Upload Success";
         }
         else
         {
              echo "File Upload Error";
         }
    }
}
?>