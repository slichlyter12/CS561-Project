<?php
	require('../db_config/conn2.php');
	
	function complete($mysqli, $isError, $msg, $data){
		$return = array('ERROR'=> $isError, 'MESSAGE'=>$msg, 'DATA'=>$data);
		$mysqli->close();
		exit(json_encode($return));
	}

	$questionData = json_decode(file_get_contents("php://input"), true);
	
	//Get user_id(ONID) from session
	$osuId = $_SESSION['onidid'];
	$classId = $_REQUEST['classid'];
	
	//Check is osu id not null
	if($osuId=='null') complete($mysqli, 2, 'Please log in first', NULL);

	//Check is class id not null
	if($classId=='null') exit('No class has been selected!');

	//Check is current user a Student or is the user exist
	$sql = 'SELECT r.role AS role, t.id AS id FROM t_user AS t,r_user_class AS r WHERE r.class_id = '.$classId.' AND t.osu_id = "'.$osuId .'"';
	$result = $mysqli->query($sql);
	if($result) {
		if($row = $result->fetch_assoc()){
			$role = $row['role'];
			$userId = $row['id'];
			if($role!='0') complete($mysqli, 1, 'No permission!', NULL);
		}else complete($mysqli, 1, 'Please sign up first!', NULL);
	}

	//Check and format the data
	//for title 
	if($questionData['TITLE']==''|| $questionData['TITLE']==NULL) complete($mysqli, 1, 'Question title cannot be empty!', NULL);
	$title = $questionData['TITLE'];
	//for description
	if($questionData['DESCRIPTION']==''|| $questionData['DESCRIPTION']==NULL) complete($mysqli, 1, 'Question description cannot be empty!', NULL);
	$description = $questionData['DESCRIPTION'];
	//for preferred time
	if($questionData['AVAILABLE_TIME']=='now')
		$preferredTime  = date('Y-m-d H:i:s', time());
	else if($questionData['AVAILABLE_TIME']!=''){
		$preferredTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d', time()).' '.$questionData['AVAILABLE_TIME']));
	}else complete($mysqli, 1, 'Peferred time cannot be empty!', NULL);
	//for created time
	$createdTime = date('Y-m-d H:i:s', time());

	//Get and check the user info
	$sql = 'SELECT first_name, last_name FROM t_user WHERE id ="'.$userId.'"';
	$result = $mysqli->query($sql);
	if($result) {
		if($row = $result->fetch_assoc()){
			$stdntFirstName = $row['first_name'];
			$stdntLastName = $row['last_name'];
		}else complete($mysqli, 1, 'User account is unavailable!', NULL);
	}

	//Check the class id 
	$sql = 'SELECT name FROM t_class WHERE id = '.$classId;
	$result = $mysqli->query($sql);
	if($result) {
		if($row = $result->fetch_assoc()){
			$className = $row['name'];
		}else complete($mysqli, 1, 'Current class is unavailable!', NULL);
	}
	//Add new question
	$sql = 'INSERT INTO t_question (class_id, stdnt_first_name, stdnt_last_name, stdnt_user_id, created_time, title, description, preferred_time, num_liked) VALUES ('.$classId.', "'.$stdntFirstName.'", "'.$stdntLastName.'", '.$userId.', "'.$createdTime.'", "'.$title.'", "'.$description.'", "'.$preferredTime.'", 0)';
	$result = $mysqli->query($sql);
	if($result) complete($mysqli, 0, 'Create succeed!', NULL);
	else complete($mysqli, 1, 'Create failed!', NULL);
?>
