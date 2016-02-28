<?php

/**
 * Class login
 * handles the user's login and logout process
 */
class Login {
	/**
	 *
	 * @var object The database connection
	 */
	private $db_connection = null;
	/**
	 *
	 * @var array Collection of error messages
	 */
	public $errors = array ();
	/**
	 *
	 * @var array Collection of success / neutral messages
	 */
	public $messages = array ();
	
	/**
	 * the function "__construct()" automatically starts whenever an object of this class is created,
	 * you know, when you do "$login = new Login();"
	 */
	public function __construct() {
		// create/read session, absolutely necessary
		session_start ();
		
		// check the possible login actions:
		// if user tried to log out (happen when user clicks logout button)
		if (isset ( $_POST ["logout"] )) {
			$this->doLogout ();
		} // login via post data (if user just submitted a login form)
		elseif (isset ( $_POST ["login"] )) {
			$this->dologinWithPostData ();
		}
	}
	
	/**
	 * log in with post data
	 */
	private function dologinWithPostData() {
		// check login form contents
		if (empty ( $_POST ['user_name'] )) {
			$this->errors [] = "Username field was empty.";
			$messageNum = 58;
		} elseif (empty ( $_POST ['user_password'] )) {
			$this->errors [] = "Password field was empty.";
			$messageNum = 59;
		} elseif (! empty ( $_POST ['user_name'] ) && ! empty ( $_POST ['user_password'] )) {
			
			// create a database connection, using the constants from config/db.php (which we loaded in index.php)
			$this->db_connection = new mysqli ( DB_HOST, DB_USER, DB_PASS, DB_NAME );
			
			// change character set to utf8 and check it
			if (! $this->db_connection->set_charset ( "utf8" )) {
				$this->errors [] = $this->db_connection->error;
			}
			
			// if no connection errors (= working database connection)
			if (! $this->db_connection->connect_errno) {
				
				// escape the POST stuff
				$user_name = $this->db_connection->real_escape_string ( $_POST ['user_name'] );
				
				// database query, getting all the info of the selected user (allows login via email address in the
				// username field)
				$sql = "SELECT *
                        FROM users
                        WHERE user_name = '" . $user_name . "' OR user_email = '" . $user_name . "';";
				$result_of_login_check = $this->db_connection->query ( $sql );
				
				// if this user exists
				if ($result_of_login_check->num_rows == 1) {
					
					// get result row (as an object)
					$result_row = $result_of_login_check->fetch_object ();
					
					// using PHP 5.5's password_verify() function to check if the provided password fits
					// the hash of that user's password
					if (password_verify ( $_POST ['user_password'], $result_row->user_password_hash )) {
						
						// write user data into PHP SESSION (a file on your server)
						$numberOfDays = 30;
						$userEmailHash = md5( strtolower( trim( $result_row->user_email )));
						setcookie("user_name", "$result_row->user_name", time() + 60 * 60 * 24 * $numberOfDays, "/","", 0);
						setcookie("user_id", "$result_row->user_id", time() + 60 * 60 * 24 * $numberOfDays, "/","", 0);
						setcookie("user_email", "$userEmailHash", time() + 60 * 60 * 24 * $numberOfDays, "/","", 0);
						setcookie("apiKey", "$result_row->apiKey", time() + 60 * 60 * 24 * $numberOfDays, "/","", 0);
						setcookie("user_login_status", "1", time() + 60 * 60 * 24 * $numberOfDays, "/","", 0);
					} else {
						$this->errors [] = "Wrong password. Try again.";
						$messageNum = 57;
					}
				} else {
					$this->errors [] = "This user does not exist.";
					$messageNum = 56;
				}
			} else {
				$this->errors [] = "Database connection problem.";
				$messageNum = 90;
			}
		}
	}
	
	/**
	 * perform the logout
	 */
	public function doLogout() {
		// delete the session of the user
		$_SESSION = array ();
		session_destroy ();
		setcookie("user_name", "", time() -60, "/","", 0);
		setcookie("user_email", "", time() -60, "/","", 0);
		setcookie("user_id", "", time() -60, "/","", 0);
		setcookie("apiKey", "", time() -60, "/","", 0);
		setcookie("DisplayStyle", "", time() -60, "/","", 0);
		setcookie("user_login_status", "0", time() -60, "/","", 0);
	}
	
	/**
	 * simply return the current state of the user's login
	 *
	 * @return boolean user's login status
	 */
	public function isUserLoggedIn() {
		if (isset ( $_COOKIE ['user_login_status'] ) and $_COOKIE ['user_login_status'] == 1) {
			return true;
		}
		// default return
		return false;
	}
}
