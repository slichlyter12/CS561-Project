<?php
	require('../db_config/conn.php');
	$sql="SELECT title, created_time, stdnt_first_name, stdnt_last_name,status, num_liked FROM t_question ORDER BY created_time DESC";
	$result=mysql_query($sql);

	if($result) {
		$questions=array();
		$i=0;
		while($row=mysql_fetch_array($result,MYSQL_ASSOC)){
			$questions[$i]['TITLE']=$row['title'];
			$questions[$i]['NAME']=$row['stdnt_first_name'].' '.$row['stdnt_last_name'];
			$questions[$i]['CREATE_TIME']= date('Y-m-d g:i a', strtotime($row['created_time']));
			switch($row['status']){
				case '0': $questions[$i]['STATUS']= 'Proposed';break;
				case '1': $questions[$i]['STATUS']= 'Answered';break;
				case '2': $questions[$i]['STATUS']= 'Deleted';
			}
			$questions[$i]['NUM_JOIN']=$row['num_liked'];
			$i++;
		}
	}
	echo json_encode(array('QUESTIONS'=>$questions));
	mysql_free_result($result);
	mysql_close();
?>
