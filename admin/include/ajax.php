<?php 
require('function.php');

if(check_token("/admin/", "crud_url", "post")){
	if (isset($_POST['full_url'])) {

		$url = (isset($_POST['full_url']) ? $_POST['full_url'] : '');
		$search = array('http://', 'https://', 'http://www.', 'https://www.');
		$url = str_replace($search, '', $url);
		$url = 'http://'.$url;
		if (strlen($url) > 5) {
			$result_link = $db->query("SELECT * FROM links WHERE url = ".$db->quote($login));

	        if($result_link !== false && $db->last_row_count() == 1){
	        	$row = $result_user->fetch();
	        	echo json_encode(array('result' => 'true', 'hash' => $row['hash']));
	        }else{
				if (open_url($url)) {
					$result_id = $db->query('SELECT id FROM links ORDER BY id DESC LIMIT 1');
					$row_id = $result_id->fetch();
					$lastId = intval($row_id['id']) + 1;
					$hash = generate_hash($lastId);

					$result = $db->query("INSERT INTO links (id, hash, url, add_date) VALUES (".intval($lastId).", '".$hash."', '".$url."','".date('Y-m-d H:i:s')."')");
						if ($result) {
							echo json_encode(array('result' => 'true', 'hash' => $hash));
						}else{
							echo json_encode(array('result' => 'false', 'error' => "INSERT INTO links (id, hash, url, add_date) VALUES (".intval($lastId).", '".$hash."', '".$url."','".date('Y-m-d H:i:s')."')"));
						}
				}else{
					echo json_encode(array('result' => 'false', 'error' => 'not_open_url'));
				}
	        }
			
		}
	}elseif(isset($_POST['edit_url'])){

	}elseif(isset($_POST['delete_url'])){
		$result = $db->query("DELETE FROM links WHERE id = ".intval($_POST['id']));
			if ($result) {
				echo json_encode(array('result' => 'true', 'id' => $_POST['id']));
			}else{
				echo json_encode(array('result' => 'false', 'error' => 'SQL error'));
			}

	}else{
		header('Location: /');
		exit();
	}
}else{
	header('Location: /');
	exit();
}

