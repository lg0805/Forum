<?php 
require_once('db.php');
require_once('common.php');
require_once('functions.php');

class User{

	// Permission levers
	const CREATE_FORUM = 2;
	const MOVE_MESSAGE = 4;
	const DELETE_MESSAGE = 8;
	const DELETE_FORUM = 16;

	private $uid;	//user id
	private $fields;	// user record fields

	// initalize a User object
	public function __construct(){
		$this->uid = null;
		$this->fields = array('username' => '',
								'password' => '',
								'emailAddr' => '',
								'isActive' => false,
								'permission' => 0);
	}

	// override magic method to retrieve properties
	public function __get($field){
		if ($field == 'userId') {
			return $this ->uid;
		} else {
			return $this->fields[$field];
		}
	}

	// override magic method to set properties
	public function __set($field, $value){

		if (array_key_exists($field, $this->fields)) {
			$this->fields[$field] = $value;
		}
	}

	// return if username is valid format
	public static function validateUsername($username){

		return preg_match('/^[A-Z0-9]{2,20}$/i', $username);
	}

	// return if email address is valid format
	public static function validateEmailAddr($email){

		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	// return an object populated based on the record's user id
	public static function getById($user_id){

		$user = new User();

		$query =sprintf('SELECT USERNAME, PASSWORD, EMAIL_ADDR, IS_ACTIVE, PERMISSION FROM %sUSER WHERE USER_ID = %d', DB_TBL_PREFIX, $user_id);

		$result = mysql_query($query, $GLOBALS['DB']);

		if (mysql_num_rows($result)) {
			$row = mysql_fetch_assoc($result);

			$user->username = $row['USERNAME'];
			$user->password = $row['PASSWORD'];
			$user->emailAddr = $row['EMAIL_ADDR'];
			$user->isActive = $row['IS_ACTIVE'];
			$user->permission = $row['PERMISSION'];
			$user->uid = $user_id;
		}
		mysql_free_result($result);
		return $user;
	}

	// return an object populated based on the record's username
	public static function getByUsername($username){

		$user = new User();

		$query = "SELECT USER_ID, PASSWORD, EMAIL_ADDR, IS_ACTIVE, PERMISSION FROM WROX_USER WHERE USERNAME = '$username'";

		$result = mysql_query($query, $GLOBALS['DB']);

		if (mysql_num_rows($result)) {
			$row = mysql_fetch_assoc($result);
			$user->username = $username;
			$user->password = $row['PASSWORD'];
			$user->emailAddr = $row['EMAIL_ADDR'];
			$user->isActive = $row['IS_ACTIVE'];
			$user->permission = $row['PERMISSION'];
			$user->uid = $row['USER_ID'];
		}
		mysql_free_result($result);
		return $user;
	}

	// save the record to the database
	public function save(){

		if ($this->uid) {
			// 更新记录
			$query = sprintf('UPDATE %sUSER SET USERNAME = "%s", PASSWORD = "%s", EMAIL_ADDR = "%s", IS_ACTIVE = %d, PERMISSION = %d WHERE USER_ID=%d', DB_TBL_PREFIX, $this->username, $this->password, $this->emailAddr, $this->isActive, $this->permission, $this->uid);
			// echo "update";
			
			return mysql_query($query, $GLOBALS['DB']);
		} else {

			$query = "INSERT INTO WROX_USER(USERNAME, PASSWORD, EMAIL_ADDR, IS_ACTIVE, PERMISSION) VALUES('$this->username', '$this->password','$this->emailAddr','$this->isActive', '$this->permission')";
			// echo $query;
			// exit();

			// echo "insert";
			if (mysql_query($query, $GLOBALS['DB'])) {
				$this->uid = mysql_insert_id($GLOBALS['DB']);
				return true;
			 } else {
			 	return false;
			 }
		}
	}

	// set the records as inactive and return an activation token
	public function setPending(){
		$this->isActive = false;
		$this->save(); // make sure the record is saved

		$token = random_text(5);
		$query = sprintf('INSERT INTO %sPENDING(USER_ID, TOKEN) VALUES(%d, "%s")', DB_TBL_PRIFIX, $this->uid, $token);
		return (mysql_query($query, $GLOBALS['DB'])) ? $token : false;
	}


	// clear the user's pending status and set the record as active
	public function clearPending($token){

		$query = sprintf('SELECT TOKEN FROM %sPENDING WHERE USER_ID=%d AND TOKEN = "%s"', DB_TBL_PRIFIX, $this->uid, 
			mysql_real_escape_string($token, $GLOBALS['DB']));

		$result = mysql_query($query, $GLOBALS['DB']);

		if (!mysql_num_rows($result)) {
			mysql_free_result($result);
			return false;
		} else {
			mysql_free_result($result);
			$query = sprintf('DELETE FORM %sPENDING WHERE USER_ID = %d AND TOKEN = "%s"', DB_TBL_PRIFIX, $this->uid, 
				mysql_real_escape_string($token, $GLOBALS['DB']));

			if (!mysql_query($query, $GLOBALS['DB'])) {
				return false;
			} else {
				$this->isActive = true;
				return $this->save();
			}

		}
	}


	public function setInactive(){

		$this->isActive = false;
		$this->save();

		$token = random_text(5);
		$query = "INSERT INTO WROX_PENDING(USER_ID, TOKEN) VALUES('$this->uid', '$token')";
		return (mysql_query($query, $GLOBALS['DB'])?"$token":false);
	}

	public function setActive($token){

		$query = "SELECT TOKEN FROM WROX_PENDING WHERE USER_ID=$this->uid and TOKEN='$token'";
		
		$result = mysql_query($query, $GLOBALS['DB']);

		if (!mysql_num_rows($result)) {
			mysql_free_result($result);
			// echo $query;
			// exit();
			return false;
		} else {
			mysql_free_result($result);

			$query = "delete from WROX_PENDING where USER_ID = $this->uid and TOKEN = '$token'";

			if (!mysql_query($query, $GLOBALS['DB'])) {
				return false;
			} else {
				$this->isActive = true;
				return $this->save();
			}
		}
		
	}


}

$u = User::getById(1);
$u->setInactive();
// $u->save();
// $u->setInactive();


// 插入数据
/*
$u = new User();
$u->username = "lbh";
$u->password = sha1("lbh");
$u->emailAddr = "lbh@qq.com";
$u->save();
 */


// 更新记录
/*
$u = User::getByUsername('lbh2');
$u->username = "lbh1";
$u->password = sha1("lbh1");
$u->save();
 */

// /*
// static关键字
// static成员是唯一存在的，在多个对象之间共享
// static成员使用类名直接访问，如：类名::静态成员属性名;或类名::成员方法名();
// 在类中声明的成员方法中，使用self来访问其他静态成员，如:self::静态属性；
//  */
// class Myclass{

// 	static $count;

// 	function __construct(){

// 		self::$count++;
// 		// 在类中声明的成员方法中，使用self来访问其他静态成员，如:self::静态属性；
// 	}

// 	static function getCount(){

// 		return self::$count;
// 		// 在类中声明的成员方法中，使用self来访问其他静态成员，如:self::静态属性；
// 	}
// }

// Myclass::$count = 0; 
// // static成员使用类名直接访问


// $myc1 = new Myclass();
// $myc2 = new Myclass();
// $myc3 = new Myclass();

// echo Myclass::getCount();
// // static成员使用类名直接访问
// echo $myc2->getCount();
// echo $myc3->getCount();
