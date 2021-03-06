<?php
// select users through email
class User extends Dbh
{
  //gets user info through email
  public function emailChecker($email)
  {
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$email]);
    $names = $stmt->fetch();
    return $names;

  }
  public function realEmailChecker($email, $id)
  {
    $sql = "SELECT * FROM users WHERE email = ? AND user_id <> ?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$email, $id]);
    $names = $stmt->fetch();
    return $names;

  }

  public function idChecker($id)
  {
    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$id]);
    $info = $stmt->fetch();
    return $info;
  }

  public function changePass($id,$pass)
  {
    $sql = "UPDATE users
    SET pass_word = ?
    WHERE user_id = ?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$pass, $id]);
  }

  public function setUserInfo($pass, $lname, $mname, $fname, $desig, $email)
  {
    $sql = "INSERT INTO users(pass_word, l_name, m_name, f_name, designation, email) VALUES (?, ?, ?, ?, ?,?)";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$pass, $lname, $mname, $fname, $desig, $email]);
  }

  public function updateUserInfo($id,$lname, $mname, $fname, $desig, $email)
  {
    $sql = "UPDATE users SET l_name = ?, m_name = ?, f_name = ?, designation = ?, email = ? WHERE user_id = ?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$lname, $mname, $fname, $desig, $email,$id]);
  }
  //delete user
  public function deleteUser($id)
  {
    $sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$id]);

    $sql = "DELETE FROM add_info WHERE user_id = ?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$id]);
  }

  // request sql
  public function getRequest()
  {
    $sql = "SELECT users.user_id, l_name, f_name, m_name, designation, grade, station FROM users
             LEFT JOIN add_info ON users.user_id = add_info.user_id
             WHERE users.status = ''";
    $res = $this->mySqli($sql);
    return $res;

  }
  public function deleteReq($id)
  {
    $sql = "DELETE  FROM users WHERE user_id=?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$id]);
  }

  public function setStatus($id)
  {
    $sql = "UPDATE users SET status = 'Activated'
            WHERE user_id = ?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$id]);
  }


  // Personnel sql
  public function getPersonnel()
  {
    $sql = "SELECT users.user_id, l_name, f_name, m_name, designation, grade, station FROM users
             LEFT JOIN add_info ON users.user_id = add_info.user_id AND status != ''
              ORDER BY station, l_name, f_name, m_name, grade";
    $res = $this->mySqli($sql);
    return $res;

  }

  // personel sql for principals
  public function getSchoolPersonnel($station)
  {
    $sql = "SELECT users.user_id, l_name, f_name, m_name, designation, grade, station FROM users
             INNER JOIN add_info ON users.user_id = add_info.user_id AND status != ''
             AND station = '{$station}'
             ORDER BY station, l_name, f_name, m_name, grade";
    $res = $this->mySqli($sql);
    return $res;

  }

  public function getAllUserId()
  {
    $sql = "SELECT user_id FROM users WHERE status !='Administrator'";
    $result = $this->mySqli($sql);
    return $result;
  }
}
