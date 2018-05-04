<?php
require ('include/function.php');
if (!isset($_SESSION['user']['user_token']) && !val_token_session_in_db($_SESSION['user']['user_token'], $_SESSION['user']['id'])) {
	header('Location: login.php');
	exit();
}elseif($_SESSION['user']['user_token_time'] < time()){

	$db->query("UPDATE users SET token = NULL WHERE id=".$_SESSION['user']['id']);

	unset($_SESSION['user']);

	header('Location: login.php');
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Dashboard</title>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="<?php echo get_token('crud_url') ?>">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.0/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style type="text/css">
		.d-none{display: none!important;}
		.height-50{height: 100px;}
		#copy-url{margin-left: 10px;}
	</style>
</head>
<body>
	<header>
		<div class="container">
			<ul class="nav navbar navbar-right">
				<li><a href="/admin/login.php?action=logout">Loguot</a></li>
			</ul>
		</div>
	</header>
	<div class="container">
		<div class="row">
			<div class="col-sm-6">
				<h4 class="title">Add URL</h4>
				<form id="short_add" action="javascript:void(0);">
					<div class="form-group">
						<input type="text" name="full_url" id="full_url" class="form-control" placeholder="Long url..." required="" /></input>
					</div>
					<div class="form-group">
						<button type="submit" name="submit" class="btn btn-block">Add url</button>
					</div>
				</form>
			</div>	
			<div class="col-sm-6">
				<h4 class="title">Search</h4>
				<form id="search-id" action="/admin/index.php?action=search">
					<div class="form-group">
						<input type="text" name="s" id="form_search" class="form-control" placeholder="Search..." required="" /></input>
					</div>
					<div class="form-group">
						<select name="t" class="form-control" id="form_type">
							<option selected value="id">ID</option>
							<option value="hash">Hash</option>
							<option value="url">URL</option>
						</select>
					</div>
					<div class="form-group">
						<button type="submit" name="action" value="search" class="btn btn-block">Search</button>
					</div>
				</form>
			</div>
		</div>
		<h4 class="title">All URL</h4>
		<table id="all_links_table" class="table table-striped">
		    <thead>
		      <tr>
		        <th>#</th>
		        <th>Hash</th>
		        <th>View</th>
		        <th></th>
		        <th></th>
		      </tr>
		    </thead>
		    <tbody>
		    	<?php 
		    		$limit = 2;
		    		$sql_count = "SELECT COUNT(*) as col FROM links";
		    		$query = $db->query($sql_count);
					$row_count = $query->fetch();
					$all_count = $row_count['col'];
					$current_page = (!isset($_GET['page']) ? 1 : $_GET['page']);
		    		if(!isset($_GET['page']) || $_GET['page'] == 1){
		    			$offset = 0;
		    		}else{
		    			$offset = ($_GET['page'] - 1) * $limit;
		    		}
		    		$sql = "SELECT * FROM links";

		    	if (isset($_GET['action']) && $_GET['action'] == 'search') {

		    		if (isset($_GET['s']) && isset($_GET['t'])) {
		    			$text = $_GET['s'];
		    			if ($_GET['t'] == 'hash') {
		    				$arr = array('http://', 'https://', $_SERVER['SERVER_NAME'].'/');
		    				$text = str_replace($arr, '', $text);
					    }
					    $type = $_GET['t'];
				        
				        $sql .= " WHERE ".$type." LIKE '%".$text."%'";
				        $sql_count .= " WHERE ".$type." LIKE '%".$text."%'";
		    			$query_s = $db->query($sql_count);
						$row_count = $query_s->fetch();
						$all_count = $row_count['col'];
		    			
		    		}else{
		    			header('Location: /admin/');
		    		}
		    		
		    	}

		    	$sql .= " ORDER BY id DESC LIMIT ".$limit." OFFSET ".$offset;

		    	$results = $db->query($sql);

		    	while ($row = $results->fetch()):
		    	 ?>
		      <tr>
		        <td><?php echo $row['id']; ?></td>
		        <td><a class="hash-links" href="javascript:void(0);" data-clipboard-text="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/'.$row['hash']; ?>"><?php echo $_SERVER['SERVER_NAME'].'/'.$row['hash']; ?></a></td>
		        <td><?php echo $row['count']; ?></td>
		        <td><a href="javascript:void(0);" class="edit-link"><i class="fa fa-edit"></i></a></td>
		        <td><a href="javascript:void(0);" data-id="<?php echo $row['id']; ?>" class="remove-link"><i class="fa fa-trash"></i></a></td>
		      </tr>
		  <?php endwhile ?>
		    </tbody>
		  </table>
		  <div class="col-sm-12">
		  	<?php pagination($all_count, $current_page, $limit); ?>
		  </div>
		  <div class="col-sm-6">
		  	<div id="horCharts" class="d-none">
		  		<?php 
		  		$bests_week = bests_week_links();

		    	while ($row = $bests_week->fetch()):
		  		 ?>
			  <span class="label" data-count="<?php echo $row['count']; ?>"><?php echo '#'.$row['id']; ?></span>
			<?php endwhile; ?>
			</div>
		  	<canvas id="countCharts" width="600" height="400">
		  		
		  	</canvas>
		  </div>

		  <div class="col-sm-6">
		  	<div id="verCharts" class="d-none">
		  		<?php 
		  		$bests_all = bests_all_time_links();

		    	while ($row = $bests_all->fetch()):
		  		 ?>
			  <span class="label" data-count="<?php echo $row['count']; ?>"><?php echo '#'.$row['id']; ?></span>
			<?php endwhile; ?>
			</div>
		  	<canvas id="weekCountCharts" width="600" height="400">
		  		
		  	</canvas>
		  </div>
	</div>
	<footer>
		<div class="container">
			<div class="height-50"></div>
		</div>
	</footer>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.7.1/clipboard.min.js"></script>
	<script type="text/javascript">
		$(window).on("unload", function(e) {
    alert("call");
    console.log("this will be triggered");
});
	</script>
	<script src="js/script.js"></script>
</body>
</html>
