<?php 
	require_once 'vendor/autoload.php';
    include("confs/config.php");

	$faker = Faker\Factory::create();

	$status = ['on', 'off'];

	for ($i = 0; $i < 70; $i++) {
	    $userid = $faker->numberBetween(1, 30);

		$body = $faker->realTextBetween($minNbChars = 50, $maxNbChars = 300, $indexSize = 2);
		$body = mysqli_escape_string($conn, $body);		

		$ideaid = $faker->numberBetween(1, 51);

		$anonymousStatus = $status[$faker->numberBetween(0, 1)];

		$ideas_sql = "INSERT INTO comments(body, status, idea_id, user_id) VALUES 
		    ('$body','$anonymousStatus','$ideaid','$userid')";
		mysqli_query($conn, $ideas_sql);
	}

	for ($i = 0; $i < 10; $i++) {
		$userid = $faker->unique()->randomDigitNotNull();

		$ideaid = $faker->numberBetween(1, 51);
		$react = $faker->numberBetween(0, 1);

		$ideas_sql = "INSERT INTO reacts(react, idea_id, user_id) VALUES 
		    ('$react','$ideaid','$userid')";
		mysqli_query($conn, $ideas_sql);
	}

	$userid = '10';
	$ideaid = $faker->numberBetween(1, 51);
	$react = $faker->numberBetween(0, 1);

	$ideas_sql = "INSERT INTO reacts(react, idea_id, user_id) VALUES 
		    ('$react','$ideaid','$userid')";
	mysqli_query($conn, $ideas_sql);


	for ($i = 0; $i < 10; $i++) {
		$userid = '1'.$faker->unique()->randomDigitNotNull();

		$ideaid = $faker->numberBetween(1, 51);
		$react = $faker->numberBetween(0, 1);

		$ideas_sql = "INSERT INTO reacts(react, idea_id, user_id) VALUES 
		    ('$react','$ideaid','$userid')";
		mysqli_query($conn, $ideas_sql);
	}

	$userid = '20';
	$ideaid = $faker->numberBetween(1, 51);
	$react = $faker->numberBetween(0, 1);

	$ideas_sql = "INSERT INTO reacts(react, idea_id, user_id) VALUES 
		    ('$react','$ideaid','$userid')";
	mysqli_query($conn, $ideas_sql);

	for ($i = 0; $i < 10; $i++) {
		$userid = '2'.$faker->unique()->randomDigitNotNull();

		$ideaid = $faker->numberBetween(1, 51);
		$react = $faker->numberBetween(0, 1);

		$ideas_sql = "INSERT INTO reacts(react, idea_id, user_id) VALUES 
		    ('$react','$ideaid','$userid')";
		mysqli_query($conn, $ideas_sql);
	}

	$userid = '30';
	$ideaid = $faker->numberBetween(1, 51);
	$react = $faker->numberBetween(0, 1);

	$ideas_sql = "INSERT INTO reacts(react, idea_id, user_id) VALUES 
		    ('$react','$ideaid','$userid')";
	mysqli_query($conn, $ideas_sql);



	for ($i = 0; $i < 100; $i++) {
		$userid = $faker->numberBetween(1,30);

		$ideaid = $faker->numberBetween(1, 51);
		$react = $faker->numberBetween(0, 1);

		$ideas_sql = "INSERT INTO reacts(react, idea_id, user_id) VALUES 
		    ('$react','$ideaid','$userid')";
		mysqli_query($conn, $ideas_sql);
	}


?>