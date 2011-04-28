<script>
	$(function() {
		//$(".core-logging-bar").draggable();
	});
</script>
<?php
if(!function_exists('recursiveArrayDisplay')){
	function recursiveArrayDisplay($dataArray,$parentKey='',$depth=0){
		foreach($dataArray as $key => $value){
			$key = ($parentKey=='')? $key : $parentKey.'.'.$key;
			if(is_array($value)){
				recursiveArrayDisplay($value,$key,($depth+1));
			} else {
				echo('<div class="core-logging-bar-item">'.$key.' : '.$value.'</div>');
			}
		}
	}
}
if($isAjaxRequest){
	?>
<div id="core-logging-bar" class="core-logging-bar" style="width:500px">
	Logging Bar | <?php echo('M: <strong>'.$memory.' MB</strong> | T: <strong>'.$time.'ms</strong> | Q: <strong>'.count($queries).'</strong>'); ?> | <span id="core-logging-bar-show-details-span" class="click-span" onclick="NJSCORE.LoggingBar.popUpDetails()">Show</span>
	<?php
} else {
	?>
<div id="core-logging-bar" class="core-logging-bar" style="width:1000px">
	Logging Bar <?php echo empty($errors)? '' : '(<span style="color:#BA0016;font-weight:bold">'.count($errors).' errors on this page</span>)'; ?> | <?php echo('Max Memory Used: <strong>'.$memory.' MB</strong> | Page Load Time: <strong>'.$time.'ms</strong> | Logged Queries: <strong>'.count($queries).'</strong>'); ?> | <span id="core-logging-bar-show-details-span" class="click-span" onclick="NJSCORE.LoggingBar.toggleLoggingDetails()">Show Details</span> | <span class="click-span" onclick="NJSCORE.LoggingBar.closeLoggingBar()">Close</span>
	<?php
}
?>
	<div id="core-logging-bar-details" style="display:none">
		<div style="width:1000px;height:600px;overflow:auto">
			<div class="core-logging-bar-title">$_SERVER Information</div>
			<div class="core-logging-bar-body">
				<?php
				if(empty($_SERVER)){
					echo('<div class="core-logging-bar-item">No information to display...</div>');
				}else{
					recursiveArrayDisplay($_SERVER);
				}
				?>
			</div>
			<div class="core-logging-bar-title">$_COOKIE Information</div>
			<div class="core-logging-bar-body">
				<?php
				if(empty($_COOKIE)){
					echo('<div class="core-logging-bar-item">No information to display...</div>');
				}else{
					recursiveArrayDisplay($_COOKIE);
				}
				?>
			</div>
			<div class="core-logging-bar-title">$_GET Information</div>
			<div class="core-logging-bar-body">
				<?php
				if(empty($_GET)){
					echo('<div class="core-logging-bar-item">No information to display...</div>');
				}else{
					recursiveArrayDisplay($_GET);
				}
				?>
			</div>
			<div class="core-logging-bar-title">$_POST Information</div>
			<div class="core-logging-bar-body">
				<?php
				if(empty($_POST)){
					echo '<div class="core-logging-bar-item">No information to display...</div>';
				}else{
					recursiveArrayDisplay($_POST);
				}
				?>
			</div>
			<div class="core-logging-bar-title">$_FILES Information</div>
			<div class="core-logging-bar-body">
				<?php
				if(empty($_FILES)){
					echo('<div class="core-logging-bar-item">No information to display...</div>');
				}else{
					recursiveArrayDisplay($_FILES);
				}
				?>
			</div>
			<div class="core-logging-bar-title">Logged Errors</div>
			<div class="core-logging-bar-body">
				<?php
				if(empty($errors)){
					echo('<div class="core-logging-bar-item">No information to display...</div>');
				}else{
					foreach($errors as $error){
						if(isset($error['stackTrace'])){
							echo('<div class="core-logging-bar-item">Type ['.$error['type'].'] : Message ['.$error['message'].']<div><code><pre style="color:#BA0016;font-size:12px">'.$error['stackTrace'].'</pre></code></div></div>');
						} else {
							echo('<div class="core-logging-bar-item">Type ['.$error['type'].'] : Message ['.$error['message'].']</div>');
						}
					}
				}
				?>
			</div>
			<div class="core-logging-bar-title">Logged Queries</div>
			<div class="core-logging-bar-body">
				<?php
				if(empty($queries)){
					echo('<div class="core-logging-bar-item">No information to display...</div>');
				}else{
					foreach($queries as $query){
						echo('<div class="core-logging-bar-item">'.$query.'</div>');
					}
				}
				?>
			</div>
			<div class="core-logging-bar-title">Cached Info</div>
			<div class="core-logging-bar-body">
				<?php
				if(empty($cachedInfos)){
					echo('<div class="core-logging-bar-item">No information to display...</div>');
				}else{
					foreach($cachedInfos as $cachedInfo){
						echo('<div class="core-logging-bar-item">'.$cachedInfo.'</div>');
					}
				}
				?>
			</div>
			<div class="core-logging-bar-title">Environment</div>
			<div class="core-logging-bar-body">
				<div class="core-logging-bar-item"><?php echo $environment; ?></div>
			</div>
		</div>
	</div>
</div>