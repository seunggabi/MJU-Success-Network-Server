<?php
	function closeDB() {
		global $mysqli;

		$mysqli->close();
	}

	function getStatus($code) {
		switch($code) {
			case 0: return "Fail";
			default: return "Success";
		}
	}

	function insertLog($token, $g_id, $content) {
		global $mysqli;

		$user = getUser($token);
		
		$sql = "INSERT INTO `log` (u_id, g_id, l_content) VALUES ('$user[u_id]', '$g_id', '$content')";
		$result = $mysqli->query($sql);
		return getStatus($result);
	}

	function insert($table, $data) {
		global $mysqli;

		$keys = "";
		$values = "";

		foreach($data as $key => $value) {
			if($keys)
				$keys .= ", ";
			$keys .= $key;
			
			if($values)
				$values .= ", ";
			$values .= "'".$value."'";

		}

		$sql = "INSERT INTO `".$table."` (".$keys.") VALUES (".$values.")";
		$result = $mysqli->query($sql);
		return getStatus($result);
	}

	function select($table, $proj, $data, $order) {
		global $mysqli;

		$wheres = "";
		foreach($data as $key => $value) {
			if($wheres)
				$wheres .= " AND ";
			$wheres .= $key."='".$value."'";
		}

		$sql = "SELECT ".$proj." FROM `".$table."`";
		if($wheres) $sql .= " WHERE ".$wheres;
		if($order)	$sql .= " ORDER BY ".$order;
		
		$rows = array();
		$result = $mysqli->query($sql);
		if(@$result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				array_push($rows, $row);
			}
		}
		return $rows;
	}

	function update($table, $data, $wheres) {
		global $mysqli;

		$updates = "";
		foreach($data as $key => $value) {
			if($updates)
				$updates .= ", ";
			$updates .= $key."='".$value."'";
		}

		$sql = "UPDATE `".$table."` SET ".$updates." WHERE ".$wheres;
		$result = $mysqli->query($sql);
		return getStatus($result);
	}

	function delete($table, $data) {
		global $mysqli;

		$wheres = "";
		foreach($data as $key => $value) {
			if($wheres)
				$wheres .= " AND ";
			$wheres .= $key."='".$value."'";
		}

		$sql = "DELETE FROM `".$table."`";
		if($wheres) $sql .= " WHERE ".$wheres;
		
		$result = $mysqli->query($sql);
		return getStatus($result);
	}

/* start user */
	function insertUser($token) {
		global $mysqli;

		$sql = "INSERT INTO `user` (token) VALUES ('$token') ON DUPLICATE KEY UPDATE token = '$token'";
		$result = $mysqli->query($sql);
		return getStatus($result);
	}

	function getUser($token) {
		$table = "user";
		$proj = "*";
		
		$data = array(
			'token' => $token
		);

		$order = "";
		return select($table, $proj, $data, $order)[0];
	}

	function getUserList($g_id) {
		global $mysqli;

		if($g_id)	$sql = "SELECT u_alarm, u.token, j_alarm FROM `user` u JOIN `join` j ON u.u_id = j.u_id WHERE j.g_id = '$g_id' AND j_status ='Y'"; 
		else	$sql = "SELECT u_alarm, token FROM `user`";
		
		$users = array();
		$result = $mysqli->query($sql);
		if($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				if(!isset($row['j_alarm'])) $row['j_alarm'] = "Y";
				array_push($users, $row);
			}
		}
		return $users;
	}
	
	function updateUser($token, $data) {
		global $mysqli;

		$update = "";
		foreach($data as $key => $value) {
			if($key == "token")
				continue;
			if($update)
				$update .= ", ";
			$update .= $key."='".$value."'";
		}

		$sql = "UPDATE `user` SET ".$update." WHERE token = '$token'";
		$result = $mysqli->query($sql);
		return getStatus($result);
	}
/* end user */

/* start group */
	function getLatestGroupID($token) {
		$table = "group";
		$proj = "*";
		$user = getUser($token);
		$u_id = $user['u_id'];
		
		$data = array(
			'u_id' => $u_id
		);
		$order = "g_id DESC";
		$rows = select($table, $proj, $data, $order);
		return $rows[0]['g_id'];
	}

	function insertGroup($token, $g_name, $g_intro, $g_tag) {
		$table = "group";
		$user = getUser($token);
		$u_id = $user['u_id'];
		
		$data = array(
			'g_name' => $g_name
			, 'g_intro' => $g_intro
			, 'g_tag' => $g_tag
			, 'u_id' => $u_id
		);
		insert($table, $data);
		sleep(1);

		$g_id = getLatestGroupID($token);
		insertLog($token, 0, "[".$g_id."]그룹 생성");
		return insertJoin($token, $g_id); 
	}

	function updateGroup($token, $g_id, $g_name, $g_intro, $g_tag, $g_hidden) {
		$table = "group";
		$user = getUser($token);
		$u_id = $user['u_id'];

		$data = array(
			'g_name' => $g_name
			, 'g_intro' => $g_intro
			, 'g_tag' => $g_tag
			, 'g_hidden' => $g_hidden
		);

		$wheres = "g_id = '".$g_id."' AND u_id = '".$u_id."'";
		insertLog($token, 0, "[".$g_id."]그룹 수정");
		return update($table, $data, $wheres);
	}

	function getGroupList($search) {
		global $mysqli;

		$sql = "SELECT u.u_id, u_name, g.g_id, g_name, g_intro, g_tag, g_time, g_status, g_hidden FROM `user` u";
		$sql .= " JOIN `group` g ON u.u_id = g.u_id";
		$sql .= " WHERE g_status = 'Y' AND g_hidden = 'N'";
		
		if($search) {
			$searchs = " AND (";
			$fields = array("g_name", "u_name", "g_tag", "g_intro");
			foreach($fields as $key) {
				if($searchs != " AND (")
					$searchs .= " OR ";
				$searchs .= $key." LIKE '%".$search."%'";
			}
			$searchs .= ")";
			$sql .= $searchs;
		}
		$sql .= " ORDER BY g.g_id DESC";
 
		$groups = array();
		$result = $mysqli->query($sql);
		if($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				array_push($groups, $row);
			}
		}

		return $groups;
	}

	function closeGroup($token, $g_id) {
		$table = "group";
		$user = getUser($token);
		$u_id = $user['u_id'];

		$data = array(
			'g_status' => 'N'
		);

		$wheres = "g_id = '".$g_id."' AND u_id = '".$u_id."'";
		insertLog($token, 0, "[".$g_id."]그룹 종료");
		return update($table, $data, $wheres);
	}

	function isGroup($token, $g_id) {
		$table = "group";
		$proj = "g_id";
		$user = getUser($token);
		$u_id = $user['u_id'];
		$data = array(
			'u_id' => $u_id
			, 'g_id' => $g_id
		);
		
		$order = "";
		return $check = array('check' => count(select($table, $proj, $data, $order)));
	}

	function getGroup($g_id) {
		$table = "group";
		$proj = "*";
		$data = array(
			'g_id' => $g_id
		);
		
		$order = "";
		return select($table, $proj, $data, $order)[0];
	}
/* end group */

/* start join */
	function insertJoin($token, $g_id) {
		$table = "join";
		$user = getUser($token);
		$u_id = $user['u_id'];
		$data = array(
			'u_id' => $u_id
			, 'g_id' => $g_id
		);
		
		insertLog($token, 0, "[".$g_id."]그룹 참가");
		insertLog($token, $g_id, "[".$user['u_name']."]님이 그룹에 참가하셨습니다.");
		return insert($table, $data);
	}


	function isJoin($token, $g_id) {
		$table = "join";
		$proj = "j_id";
		$user = getUser($token);
		$u_id = $user['u_id'];
		$data = array(
			'u_id' => $u_id
			, 'g_id' => $g_id
		);
		
		$order = "";
		return $check = array('check' => count(select($table, $proj, $data, $order)));
	}

	function getJoinList($token, $search) {
		global $mysqli;
		
		$user = getUser($token);
		$u_id = $user['u_id'];

		$sql = "SELECT u.u_id, u.u_name, g.g_id, g_name, g_intro, g_tag, g_time, g_status, g_hidden, j_alarm FROM `user` u";
		$sql .= " JOIN `group` g ON u.u_id = g.u_id";
		$sql .= " JOIN `join` j ON g.g_id = j.g_id WHERE g_status = 'Y' AND j.u_id = ".$u_id;
		
		if($search) {
			$searchs = " AND (";
			$fields = array("g_name", "u_name", "g_tag", "g_intro");
			foreach($fields as $key) {
				if($searchs != " AND (")
					$searchs .= " OR ";
				$searchs .= $key." LIKE '%".$search."%'";
			}
			$searchs .= ")";
			$sql .= $searchs;
		}
		$sql .= " ORDER BY g_id DESC";
 
		$groups = array();
		$result = $mysqli->query($sql);
		if($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				array_push($groups, $row);
			}
		}
		return $groups;
	}

	function getJoinUserList($token, $g_id) {
		global $mysqli;

		$check = isGroup($token, $g_id)['check'];

		if($check) {
			$sql = "SELECT u.u_id, u_name, j.j_id, j_status, j_time FROM `user` u";
			$sql .= " JOIN `join` j ON u.u_id = j.u_id WHERE j.g_id = ".$g_id;

			$users = array();
			$result = $mysqli->query($sql);
			if($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$row['g_id'] = $g_id;
					array_push($users, $row);
				}
			}
			return $users;
		}
 
	}

	function getJoin($g_id, $u_id) {
		$table = "join";
		$proj = "*";
		
		$data = array(
			'g_id' => $g_id
			, 'u_id' => $u_id
		);

		$order = "";
		return select($table, $proj, $data, $order)[0];
	}

	function deleteJoin($token, $g_id) {
		$table = "join";
		$proj = "j_id";
		$user = getUser($token);
		$u_id = $user['u_id'];
		$data = array(
			'u_id' => $u_id
			, 'g_id' => $g_id
			, 'j_status' => 'Y'
		);
		
		insertLog($token, 0, "[".$g_id."] 그룹탈퇴");
		insertLog($token, $g_id, "[".$user['u_name']."]님이 그룹에서 나가셨습니다.");
		closeGroup($token, $g_id);
		return delete($table, $data);
	}

	function changeJoin($token, $g_id, $u_id) {
		$table = "join";
		$user = getUser($token);
		$u_id = $user['u_id'];
		$check = isGroup($token, $g_id)['check'];
		$status = getJoin($g_id, $u_id)['j_status'];
		if($check) {
			$data = array(
				'j_status' => toggleYN($status)
			);
			$wheres = "g_id = '".$g_id."' AND u_id = '".$u_id."'";
			
			insertLog($token, 0, "[".$g_id."]그룹 [".$u_id."]사용자 ");
			return update($table, $data, $wheres);
		}
	}

	function changeJoinAlarm($token, $g_id) {
		$table = "join";
		$user = getUser($token);
		$u_id = $user['u_id'];
		$status = toggleYN(getJoin($g_id, $u_id)['j_alarm']);
		$data = array(
			'j_alarm' => $status
		);
		$wheres = "g_id = '".$g_id."' AND u_id = '".$u_id."'";
		
		insertLog($token, 0, "[".$g_id."]그룹 [".$u_id."]사용자 [".$status."]알람");
		return update($table, $data, $wheres);
	}

	function changeJoinStatus($token, $g_id, $u_id) {
		$table = "join";
		$check = isGroup($token, $g_id)['check'];

		if($check) {
			$status = toggleYN(getJoin($g_id, $u_id)['j_status']);
			$data = array(
				'j_status' => $status
			);
			$wheres = "g_id = '".$g_id."' AND u_id = '".$u_id."'";
			
			insertLog($token, 0, "[".$g_id."]그룹 [".$u_id."]사용자 [".$status."]상태");
			return update($table, $data, $wheres);
		}
	}
/* end join */

/* start manager */
	function getManagerList() {
		$table = "manager";
		$proj = "*";
		$data = array();
		$order = "";

		return select($table, $proj, $data, $order);
	}
/* end manager */

/* start notice */
	function insertNotice($m_id, $content) {
		$table = "notice";
		$data = array(
			'm_id' => $m_id
			, 'n_content' => $content
		);
		
		return insert($table, $data);
	}

	function getNoticeList($search) {
		global $mysqli;

		$sql = "SELECT m.m_id, m_name, n.n_id, n_content, n_time FROM `manager` m";
		$sql .= " JOIN `notice` n ON m.m_id = n.m_id";
		
		if($search) {
			$searchs = " WHERE n_content LIKE '%".$search."%'";
			$sql .= $searchs;
		}
		$sql .= " ORDER BY n.n_id DESC";
 
		$notices = array();
		$result = $mysqli->query($sql);
		if($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				array_push($notices, $row);
			}
		}

		return $notices;
	}
/* end notice */

/* start attend */
	function insertAttend($token, $g_id, $s_id, $u_id) {
		$table = "attend";
		$data = array(
			's_id' => $s_id
			, 'u_id' => $u_id
		);
		if(isGroup($token, $g_id))
			return insert($table, $data);
	}
/* end attend */

/* start log */
	function getChatting($token, $g_id) {
		global $mysqli;
		$user = getUser($token);
		$u_id = $user['u_id'];
		
		$check = isJoin($token, $g_id)['check'];
		if($check) {
			$sql = "SELECT u.u_id, u_name, l.l_id, l_time, l_content FROM `user` u JOIN `log` l ON u.u_id = l.u_id WHERE l.g_id = '$g_id'";
			$rows = array();
			$result = $mysqli->query($sql);
			if(@$result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					array_push($rows, $row);
				}
			}
			return $rows;
		}
	}
/* end log */

/* start schedule */
	function insertSchedule($token, $g_id, $s_name, $s_content, $s_datetime, $s_gps_logitude, $s_gps_latitude, $s_gps_location, $s_gps_name) {
		$table = "schedule";
		$user = getUser($token);
		$u_id = $user['u_id'];
		
		$check = isJoin($token, $g_id)['check'];
		if($check) {
			$data = array(
				'g_id' => $g_id
				, 's_name' => $s_name
				, 's_content' => $s_content
				, 's_datetime' => $s_datetime
				, 's_gps_logitude' => $s_gps_logitude
				, 's_gps_latitude' => $s_gps_latitude
				, 's_gps_location' => $s_gps_location
				, 's_gps_name' => $s_gps_name
			);
			insertLog($token, $g_id, "[".$s_name."]약속 생성");
			return insert($table, $data);
		}
	}
/* end schedule */

?>