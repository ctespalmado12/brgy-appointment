<?php

//Appointment.php
require_once __DIR__ . '/../vendor/autoload.php';

class Appointment
{
	public $base_url = 'http://localhost/brgy-appointment/';
	public $connect;
	public $query;
	public $statement;
	public $now;

	private $dotenv;

	private $DB_HOST;
	private $DB_USER;
	private $DB_PASS;
	private $DB_NAME;

	public function __construct()
	{

		// Load .env
		$this->dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
		$this->dotenv->load();

		// Read values from .env
		$this->DB_HOST = $_ENV['DB_HOST'];
		$this->DB_USER = $_ENV['DB_USER'];
		$this->DB_PASS = $_ENV['DB_PASS'];
		$this->DB_NAME = $_ENV['DB_NAME'];

		// Create connection
		$this->connect = new PDO(
			"mysql:host={$this->DB_HOST};dbname={$this->DB_NAME}",
			$this->DB_USER,
			$this->DB_PASS
		);

		date_default_timezone_set('Asia/Kolkata');

		session_start();

		$this->now = date("Y-m-d H:i:s",  STRTOTIME(date('h:i:sa')));
	}

	function execute($data = null)
	{
		$this->statement = $this->connect->prepare($this->query);
		if ($data) {
			$this->statement->execute($data);
		} else {
			$this->statement->execute();
		}
	}

	function row_count()
	{
		return $this->statement->rowCount();
	}

	function statement_result()
	{
		return $this->statement->fetchAll();
	}

	function get_result()
	{
		return $this->connect->query($this->query, PDO::FETCH_ASSOC);
	}

	function is_login()
	{
		if (isset($_SESSION['admin_id'])) {
			return true;
		}
		return false;
	}

	function is_master_user()
	{
		if (isset($_SESSION['user_type'])) {
			if ($_SESSION["user_type"] == 'Master') {
				return true;
			}
			return false;
		}
		return false;
	}

	function clean_input($string)
	{
		$string = trim($string);
		$string = stripslashes($string);
		$string = htmlspecialchars($string);
		return $string;
	}

	function Generate_appointment_no()
	{
		$this->query = "
		SELECT MAX(appointment_number) as appointment_number FROM appointment
		";

		$result = $this->get_result();

		$appointment_number = 0;

		foreach ($result as $row) {
			$appointment_number = $row["appointment_number"];
		}

		if ($appointment_number > 0) {
			return $appointment_number + 1;
		} else {
			return '1000';
		}
	}

	function get_total_today_appointment()
	{
		$this->query = "
		SELECT * FROM appointment
		INNER JOIN brgy_official_schedule 
		ON brgy_official_schedule.brgy_official_schedule_id = appointment.brgy_official_schedule_id 
		WHERE schedule_date = CURDATE() 
		";
		$this->execute();
		return $this->row_count();
	}

	function get_total_yesterday_appointment()
	{
		$this->query = "
		SELECT * FROM appointment
		INNER JOIN brgy_official_schedule 
		ON brgy_official_schedule.brgy_official_schedule_id = appointment.brgy_official_schedule_id 
		WHERE schedule_date = CURDATE() - 1
		";
		$this->execute();
		return $this->row_count();
	}

	function get_total_seven_day_appointment()
	{
		$this->query = "
		SELECT * FROM appointment 
		INNER JOIN brgy_official_schedule 
		ON brgy_official_schedule.brgy_official_schedule_id = appointment.brgy_official_schedule_id 
		WHERE schedule_date >= DATE(NOW()) - INTERVAL 7 DAY
		";
		$this->execute();
		return $this->row_count();
	}

	function get_total_appointment()
	{
		$this->query = "
		SELECT * FROM appointment 
		";
		$this->execute();
		return $this->row_count();
	}

	function get_total_client()
	{
		$this->query = "
		SELECT * FROM client 
		";
		$this->execute();
		return $this->row_count();
	}
}
