<?php

# By Samer (https://www.facebook.com/samer.ata.aliraqi)
# It is your responsibility to secure the access to this file!

header("Cache-Control: no-cache, must-revalidate");


$ps = explode("\n", trim(shell_exec('ps axo pid,ppid,%cpu,pmem,user,group,args --sort %cpu')));
foreach($ps AS $process){
$processes[]=preg_split('@\s+@', trim($process), 7 );
}

$head= array_shift($processes);
$processes = array_reverse($processes);


$output='';


foreach ($head AS $f) $output.="<td class=\"head\">$f</td>";

$output=sprintf('<tr class="head">%s</tr>',$output);

foreach($processes AS $p){
	$output.='<tr>';
	foreach ($p AS $i=>$f){
		if($i==0) $output.=sprintf('<td>%1$s</td>',$f);
		elseif($i==2) $output.=sprintf('<td class="cpu">%1$s<ins style="width:%1$s%%"></ins></td>',$f);
		elseif($i==3) $output.=sprintf('<td class="mem">%1$s<ins style="width="%1$s%%"></ins></td>',$f);
		elseif($i == 6) $output.=sprintf('<td class="command">%1$s</td>',$f);
		else $output.=sprintf('<td>%1$s</td>',$f);
	}
	$output.='</tr>';
}

$cpu=implode('&nbsp;&nbsp;&nbsp;', sys_getloadavg());

$output=sprintf('<table data-cpu="%s" id="process">%s</table>',$cpu, $output);

if(!empty($_GET['ajax'])) exit($output);

# This long string because of base64 of small loading image to make the script consist of one PHP file.

$output =sprintf('<div id=upper>CPU: <span id="cpu">%s</span> <img src="data:image/gif;base64,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" id="loading" style="opacity:0;"/> <button id="ref">Referesh</button> <button id="play">Play</button> Search for Process: <input type="text" id="search"/></div>%s', $cpu,$output);

$currentpath= (($_SERVER["HTTPS"])? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . parse_url( $_SERVER['REQUEST_URI'] , PHP_URL_PATH);




?>
<!DOCTYPE html>
<html>
<head>
<title>Server Processes</title>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<style>
body{margin:25px;font:bold 16px arial; background-color:yellow;color:black;line-height:30px;}
h2{padding:10px;}
td{padding:3px;position:relative;border:1px solid black;vertical-align:top;min-width:100px}
td.head{text-decoration: underline;font-weight:bold;}
td ins{position:absolute;left:0;height:100%;top: 0;background-color: gray; lightgray;}
.cpu,.mem{text-align:center;}
#upper{background-color:black;border: 1px solid white;padding:10px; color:white;}
#cpu{color:yellow;margin-right:20px;margin-right:7px;}
</style>
</head>
<body>
<h2><?Php echo gethostname(); ?> </h2> 
<?Php echo $output; ?>
<script>
$(function(){
	var clv, stop=true
	$('#ref').on('click', function (){
		$('#loading').css('opacity',1);
		$.get('<?php echo $currentpath  ?>?ajax=1', function(h){
			$('#loading').css('opacity',0);
			$('#process').replaceWith(h);
			$('#cpu').html($('#process').data('cpu'));
			if(!stop) clv= setTimeout(function(){$('#ref').click();}, 4000);
		},'html');
	});

	$('#play').on('click',function (){
		if(!stop){
			stop=true;
			clearTimeout(clv);
			$(this).html('Play');
			}
		else{
			stop=false;
			$(this).html('Stop');
			$('#ref').click();
		}
	});

	$('#search').on('input', function(){
		if(!$.trim($(this).val()).length) {$('.command').parent().show();return;}
		var v=$.trim($(this).val());
		$('.command').each(function(i){
			if($(this).html().indexOf(v) == -1) $(this).parent().hide();
		});
	});
});



</script>
</body>

</html>
