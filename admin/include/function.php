<?php
require('db.php');


date_default_timezone_set("Asia/Tashkent");
function is_session_started()

{

    if(php_sapi_name() !== "cli"){

        if(version_compare(phpversion(), "5.4.0", ">="))

            return session_status() === PHP_SESSION_ACTIVE ? true : false;

        else

            return session_id() === "" ? false : true;

    }

    return false;

}


if(!is_session_started()) session_start();




function get_token($name)

{

    $token = uniqid(rand(), true);

    $_SESSION[$name.'_token'] = $token;

    $_SESSION[$name.'_token_time'] = time();

    return $token;

}

/***********************************************************************

 * check_token() checks the validity of the token

 *

 * @return boolean

 */

function check_token($referer, $name, $type)

{

    if(isset($_SESSION[$name.'_token']) && isset($_SESSION[$name.'_token_time'])

    && (($type == "post" && isset($_POST['csrf_token']) && $_SESSION[$name.'_token'] == $_POST['csrf_token'])

    XOR ($type == "get" && isset($_GET['csrf_token']) && $_SESSION[$name.'_token'] == $_GET['csrf_token']))

    && ($_SESSION[$name.'_token_time'] >= (time()-1800))

    && isset($_SERVER['HTTP_REFERER']) && (strstr($_SERVER['HTTP_REFERER'], $referer) !== false))

        return true;

    else

        return false;

}


function all_shorts_list(){
    global $db;
    $result = $db->query('SELECT * FROM links ORDER BY id DESC');
    return $result;
}

function open_url($url)
{
  $url_c = parse_url($url);

  if ($response = @get_headers($url)){
    return true;
  }else{
    return false;
  }
}

function generate_hash($num){
    $chars = array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
  $hexadecimal = "";
  $num = intval($num);
    if ($num <= 62) {
       return $chars[$num];
    }else{

        while($num != 0){
            $residue = $num%62;
            $hexadecimal = $chars[$residue] + $hexadecimal;
            $num = floor($num / 62);
        }
        return $hexadecimal;
    }
  }
  function val_token_session_in_db($token, $id){
        if ($token === null || $id === null) {

            return false;

        }else{
            global $db;
            $result = $db->query("SELECT * FROM users WHERE token = ".$db->quote($token)." AND id = ".intval($id)."");

            if($result !== false && $db->last_row_count() == 1){
                return true;
            }else{
                return false;
            }
        }
  }


function bests_week_links(){
    global $db;
    $result = $db->query('SELECT * FROM links WHERE add_date >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY) ORDER BY count DESC LIMIT 5');
    return $result;
}

function bests_all_time_links(){
    global $db;
    $result = $db->query('SELECT * FROM links ORDER BY count DESC LIMIT 5');
    return $result;
}



function pagination($all_count, $current, $limit){
    if ($all_count > $limit) {
        if (!empty($_SERVER['QUERY_STRING'])) {
            $q = preg_replace('/&page=[0-9]+/', '', $_SERVER['QUERY_STRING']);
            $query_str = '?'.$q.'&page=';
        }else{
            $query_str = '?page=';
        }
       $page_count = ceil($all_count / $limit);
       echo "<ul class='pagination'>";
       for ($i = 1; $i <= $page_count; $i++) {
           echo "<li class='".($i == $current ? 'active' : '')."'><a href='".$query_str.$i."'>".$i."</a></li>";
       }
       echo "</ul>";
    }
    
}