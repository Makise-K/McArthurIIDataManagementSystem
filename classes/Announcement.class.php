<?php
class Announcement extends Dbh
{
  public function checkTitle($title)
  {
    $sql = "SELECT title FROM announcement WHERE title = ?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$title]);
    $info = $stmt->fetch();
    return $info;

  }

  public function getId($title)
  {
    $sql = "SELECT id FROM announcement WHERE title = ?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$title]);
    $confid = $stmt->fetch();
    return $confid;
  }

  public function insertImg($id, $imageProperties, $imgData)
  {
    $conn = mysqli_connect("localhost", "root", "", "mddb");
    $sql = "INSERT INTO ann_img(id, imageType, imageData)
    VALUES('{$id}', '{$imageProperties['mime']}', '{$imgData}')";
    $current_id = mysqli_query($conn, $sql) or die("<b>Error:</b> Problem on Image Insert<br/>" . mysqli_error($conn));
  }

  public function editImg($id, $imageProperties, $imgData)
  {
    $conn = mysqli_connect("localhost", "root", "", "mddb");
    $sql = "UPDATE ann_img
    SET imageType = '{$imageProperties['mime']}', imageData = '{$imgData}'
    WHERE id =".$id;
    $current_id = mysqli_query($conn, $sql) or die("<b>Error:</b> Problem on Image Insert<br/>" . mysqli_error($conn));
  }


  public function createAnn($user_id, $title, $desc)
  {
    $sql = "INSERT INTO announcement(user_id, title, description) VALUES (?, ?, ?)";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$user_id, $title, $desc]);
  }

  public function editAnn($id,$user_id,$title, $desc)
  {
    $sql = "UPDATE announcement SET user_id=?, title = ?, description = ?
            WHERE id = ?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$user_id, $title, $desc, $id]);

  }

  public function getSingleAnn($id)
  {
    $sql = "SELECT * FROM announcement WHERE id = ?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$id]);
    $info = $stmt->fetch();
    return $info;
  }
  public function getAnn()
  {
    $sql = "SELECT *
            FROM announcement
            ORDER BY date_created DESC
            LIMIT 17";

    $info = $this->mySqli($sql);
    return $info;
  }
  public function getAllAnn()
  {
    $sql = "SELECT * FROM announcement";
    $info = $this->mySqli($sql);
    return $info;
  }
}
