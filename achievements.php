<?php
$appid = $_GET["appid"];//"238090";
$key = "6C19EE4B8F5FF72E437C093E020BDE07";
$steamid = $_GET["steamid"];//"76561198015294053";

$url_player_achievements = sprintf("http://api.steampowered.com/ISteamUserStats/GetPlayerAchievements/v0001/?appid=%s&key=%s&steamid=%s", $appid, $key, $steamid);
$url_game_schema = sprintf("http://api.steampowered.com/ISteamUserStats/GetSchemaForGame/v2/?key=%s&appid=%s", $key, $appid);
$url_game_details = sprintf("https://store.steampowered.com/api/appdetails?appids=%s", $appid);

$json_player_achievements = file_get_contents($url_player_achievements);
$json_game_schema = file_get_contents($url_game_schema);
$json_game_details = file_get_contents($url_game_details);

$data_player_achievements = (array) json_decode($json_player_achievements, true);
$data_game_schema = (array) json_decode($json_game_schema, true);
$data_game_details = (array) json_decode($json_game_details, true);

$achievements_of_player = $data_player_achievements["playerstats"]["achievements"];
$achievements_of_game   = $data_game_schema["game"]["availableGameStats"]["achievements"];

$achievements = array();
foreach($achievements_of_game as $key => $value){
	$achievements[] = array_merge($achievements_of_player[$key], $value);
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="//ajax.aspnetcdn.com/ajax/jquery.ui/1.12.1/jquery-ui.min.js"></script>
	<style type="text/css">
		body{
			font-family: Gill Sans, sans-serif;
			background-color: lightgray;
		}
		
		.achievementsContainer{
			margin-right: 50px;
			margin-left: 50px;
		}
		
		.ghostAchiev, .achiev{
			padding: 10px;
			border: 1px solid black;
			border-radius: 5px;
			margin-bottom: 5px;
			background: rgba(255,0,0,0.3);
		}
		
		.ghostAchiev{
			height: 64px;
			background: gray;
		}
		
		.achieved{
			background: rgba(0,255,0,0.3);
		}
		
		.img{
			float: left;
			border: 1px solid black;
			width: 64px;
			height: 64px;
			margin-right: 15px;
		}
		
		.text{
			//border: 1px solid black;
			min-height: 64px;
			overflow: hidden;
			text-align: left;
		}
		
		.name{
			padding-top: 12px;
			padding-bottom: 5px;
		}
		
		.description{
			font-size: 85%;
		}
	</style>
</head>
<body draggable="false">
<h1 style="text-align: center;"><?php echo $data_game_details[$appid]["data"]["name"]; ?></h1>
<div id="achievCont" class="achievementsContainer">
<?php foreach($achievements as $value){?>
	<div id="<?php echo $value["name"]; ?>" class="achiev <?php if((bool)$value["achieved"]) echo "achieved"?>">
		<div class="img">
			<img src="<?php echo $value["icon"];?>">
		</div>
		<div class="text">
			<div class="name">
				<?php echo $value["displayName"]; ?>
			</div>
			<div  class="description">
				<?php echo $value["description"]; ?>
			</div>
		</div>
	</div>
<?php } ?>
</div>
<script>
	$('#achievCont').sortable({
        placeholder: 'ghostAchiev',
		axis: "y"
    });
</script>
</body>
</html>

<!--
[63] => Array
        (
            [apiname] => SNIPER3_REWARD_DLC02_NO_HEALING
            [achieved] => 0
            [unlocktime] => 0
            [name] => SNIPER3_REWARD_DLC02_NO_HEALING
            [defaultvalue] => 0
            [displayName] => Nothing to lose
            [hidden] => 0
            [description] => Complete the mission without using any healing items
            [icon] => https://steamcdn-a.akamaihd.net/steamcommunity/public/images/apps/238090/41deaa8ab0a0e10aeeccced7f7f4693f60ddd3e4.jpg
            [icongray] => https://steamcdn-a.akamaihd.net/steamcommunity/public/images/apps/238090/dcdf0b0f0b0b5177786fe8d79ebb137cf2ed2f59.jpg 
		}
-->