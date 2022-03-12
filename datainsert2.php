<?php 
	require_once 'vendor/autoload.php';
    include("confs/config.php");

	$faker = Faker\Factory::create();

	$categories = ["Service Learning", "Volunteer Programs", "Public Scholarship","Workplace and economic concerns", "Facilities and Venues", "Conferences", "Scheduled Castes", "Scheduled Tribes", "Backward Classes"];


	
	
	$filepath = ["upload/file/c4cdee40-0eee-3172-9bca-bdafbb743c17.pdf",
				"upload/file/8aef77e-040d-39a3-8f88-eca522f759ba.pdf",
				"upload/file/ecbee0e9-6fad-397b-88fb-d84704c7a71c.pdf",
				"upload/file/c7a76943-e2cc-3c99-b75b-ac2df15cb3cf.pdf",
				"upload/file/423cfca4-709c-3942-8d66-34b08affd90b.pdf",
				"upload/file/f6df6c74-2884-35c7-b802-6f96cf2ead01.pdf",
				"upload/file/f6df6c74-2884-35c7-b802-6f96cf2ead02.pdf",
				"upload/file/f6df6c74-2884-35c7-b802-6f96cf2ead03.pdf",
				"upload/file/f6df6c74-2884-35c7-b802-6f96cf2ead04.pdf",
				"upload/file/f6df6c74-2884-35c7-b802-6f96cf2ead05.pdf"
			];
	$status = ['on', 'off'];

	for ($i=0; $i <= 50 ; $i++) { 
		$title = $faker->realTextBetween($minNbChars = 160, $maxNbChars = 250, $indexSize = 2);
		$title = mysqli_escape_string($conn, $title);

		$description = $faker->realTextBetween($minNbChars = 1000, $maxNbChars = 5000, $indexSize = 2);
		$description = mysqli_escape_string($conn, $description);
		

		$categoryid = $faker->randomDigitNotNull(); // 1~9
		$file_path = $filepath[$faker->randomDigit()];
		$userid = $faker->numberBetween(3, 30);
		$anonymousStatus = $status[$faker->numberBetween(0, 1)];

		$ideas_sql = "INSERT INTO ideas(title, body, file, category_id, user_id, status) VALUES 
		    ('$title','$description','$file_path','$categoryid','$userid','$anonymousStatus')";
		mysqli_query($conn, $ideas_sql);
	}

		        echo mysqli_error($conn);


?>