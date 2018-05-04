<?php 
require('/admin/include/db.php');

function has_redirect_url($hash){
	global $db;
	$result_hash = $db->query("SELECT * FROM links WHERE hash = ".$db->quote($hash));

        if($result_user !== false && $db->last_row_count() == 1){

            $row = $result_hash->fetch();

            $new_count = intval($row['count']) + 1;

            $db->query("UPDATE links SET count = ".$new_count." WHERE id = ".$row['id']);

            return $row['url'];
        }else{
        	return false;
        }
}

