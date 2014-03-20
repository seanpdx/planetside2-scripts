<html>
<head>
<title>Online PCYS members</title>
<style type="text/css">


.wrapper{
padding:1px;
width:165px;
height: 480px;
overflow: auto;
overflow-x: hidden;
}
table{
width:100%;
border:1px solid;

}
td.blue {color:#86E8F3;font-size:11px;font-weight:bold;font-family: Arial;}

</style>
</head>
<body>
<?php
	$jsonOutfit = file_get_contents("http://census.soe.com/get/ps2:v2/outfit?alias=PCYS&c:resolve=member_online_status");
	$aearray = json_decode($jsonOutfit, true);
	$outfit_list = $aearray["outfit_list"];
	$members = $outfit_list[0]["members"];
	$memberIDs;
	$memberData;
	$memberIDsOnline;
	$memberSpecifics;
	$onlineCount = 0;
	
	date_default_timezone_set('America/Vancouver');
	

		foreach($members as $key => $value)
	{
		//echo "Key: $key; Value: $value<br />\n";
		if ($value["online_status"] == "1") {
		  $onlineCount++;
		$memberIDsOnline[$key] = $value["character_id"];
		}
	}
	
	foreach($memberIDsOnline as $key => $value)
	{
		$query = "http://census.soe.com/get/ps2:v2/character/{$value}?c:resolve=name.first,experience_rank";
		$memberData[$value] = json_decode(file_get_contents($query), true);
	}
	
	foreach($memberData as $key => $value)
	{
		$memberSpecifics[$key] = array("ID" => $key, "battle_rank" =>  $value["character_list"][0]["battle_rank"]["value"], 
						"name" => $value["character_list"][0]["name"]["first"]);
	}
	
	//var_dump($memberData);
	
	function cmp($b, $a)
	{
           if ($a["battle_rank"] == $b["battle_rank"])
	{
              return 0;
}
	    return ($a["battle_rank"] < $b["battle_rank"]) ? -1 : 1;
	}
	
	usort($memberSpecifics, "cmp");
	echo "<p style = 'font-size:13px;color:#86E8F3;font-weight:bold;font-family: Arial;text-align: center'>Online Now: {$onlineCount}</p>";
	echo "<div class='wrapper'>";
	echo "<table cellpadding='1' border='1'>";
	echo "<tr><td class='blue'><b>Name</b></td><td class ='blue'><b>Battle Rank</b></td></tr>";
	foreach($memberSpecifics as $key => $value)
	{
		if($value["name"] == true)
		{
			echo "<tr>";

			echo "<td class ='blue'>{$value['name']} </td><td class ='blue'> {$value['battle_rank']} </td>";
			//echo "</br>{$value['name']}';
			echo "</tr>";
			
			
		}
		
	}
	echo "</table>";
	echo "</div>";
	
?>
</body>
<html>

