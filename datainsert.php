<?php 
	require_once 'vendor/autoload.php';
    include("confs/config.php");

	$faker = Faker\Factory::create();
	$departments = ["Senior Leadership Team", "Administration Staff", "Subject Leaders","Learning Support"];

	$positions1 = ["QA Manager", "QA C", "Finance and Human Resources Director", "Registrar & Admissions Manager"];
	$positions2 = ["Receptionist", "IT Admin", "Heads PA/Office Manager", "Exams Officer/Data Manager"];
	$positions3 = ["Head of English", "Head of Computing", "Head of Technology"];
	$positions4 = ["Full-time Guide", "Part-time Guide"];

	$genders = ["Male","Female"];

	for ($i=3; $i <= 13; $i++) {
		$a=$faker->numberBetween(1, 25);

		$name = $faker->name();
		$email = $faker->email();
		$phone = $faker->phoneNumber();
		$gender = $genders[$faker->numberBetween(0, 1)];
		$password = $faker->randomNumber(5, true);
		$address = $faker->address();
		$status = 'Active';
		$profile = "upload/img/user".$a.".png";

		$datetimebetween = $faker->dateTimeBetween();
		$jod = date_format($datetimebetween,'Y-m-d');

		$dob = $faker->date();

		$users_sql = "INSERT INTO users(name, profile, email, password, gender, phone, address, status, joindate, dob) VALUES 
    	('$name','$profile','$email','$password','$gender','$phone','$address','$status','$jod', '$dob')";
    	mysqli_query($conn, $users_sql);

    	$userid = $conn->insert_id;

        $positionuser_sql = "INSERT INTO position_user(user_id, position_id, type) VALUES ('$userid','$a', 'QAC')";
        mysqli_query($conn, $positionuser_sql);

    	
	}

	

?>