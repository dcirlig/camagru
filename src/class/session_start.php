<?php

require_once  'sql.class.php';

session_start();

if (isset($_SESSION['user_id']))
{

  $pdo = myPDO::getInstance();
  $query = "
      SELECT user_id, firstname, lastname, username FROM users
      WHERE user_id=:user_id
      ";
  $sql = $pdo->prepare($query);
  $sql->execute(
          array(
              ':user_id' => $_SESSION['user_id']
              )
          );
  $count = $sql->rowCount();
  if ($count > 0)
  {
    $result = $sql->fetchAll();
    foreach($result as $row)
    {
      $fname = $row['firstname'];
      $lname = $row['lastname'];
      $login = $row['username'];
    }
  }
}