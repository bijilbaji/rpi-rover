<html>
<?php 
require "./config.php";
?>
<head>
	<meta name="viewport" 
	content="width=device-width, initial-scale=1.0, user-scalable=no">
	<script type="text/javascript" src="./hammer.js"></script>
	<script type="text/javascript" src="./jquery.min.js"></script>
    <script src="http://<?php echo $webcam;?>/get_status.cgi"></script>
	<script src="http://<?php echo $webcam;?>/get_camera_params.cgi?user=<?php echo $user;?>&amp;pwd=<?php echo $pass;?>"></script>
	<script type="text/javascript">

	document.write('<script src="http://<?php echo $webcam;?>/english/string.js"><\/script>');

	var ptz_type=0;	
	if(top.client_minor==4) ptz_type=1;
	var PTZ_STOP=1;
	var TILT_UP=0;
	var TILT_UP_STOP=1;
	var TILT_DOWN=2;
	var TILT_DOWN_STOP=3;
	var PAN_LEFT=4;
	var PAN_LEFT_STOP=5;
	var PAN_RIGHT=6;
	var PAN_RIGHT_STOP=7;
	var PTZ_LEFT_UP=90;
	var PTZ_RIGHT_UP=91;
	var PTZ_LEFT_DOWN=92;
	var PTZ_RIGHT_DOWN=93;
	var PTZ_CENTER=25;
	var PTZ_VPATROL=26;
	var PTZ_VPATROL_STOP=27;
	var PTZ_HPATROL=28;
	var PTZ_HPATROL_STOP=29;
	var PTZ_PELCO_D_HPATROL=20;
	var PTZ_PELCO_D_HPATROL_STOP=21;
	var IO_ON=94;
	var IO_OFF=95;
	var count=0;

	function decoder_control_2(command)
	{
		action_zone.location='http://<?php echo $webcam;?>/decoder_control.cgi?user=<?php echo $user;?>&pwd=<?php echo $pass;?>&command='+command;
	}
	function camera_control_2(param,value)
	{
		action_zone.location='http://<?php echo $webcam;?>/camera_control.cgi?user=<?php echo $user;?>&pwd=<?php echo $pass;?>&param='+param+'&value='+value;
	}

	function vpatrol_onclick() 
	{
		if (!ptz_type) decoder_control_2(PTZ_VPATROL);

		reset_image();
	}
	function vpatrolstop_onclick() 
	{
		if (!ptz_type) decoder_control_2(PTZ_VPATROL_STOP);

		reset_image();
	}
	function hpatrol_onclick() 
	{
		ptz_type?decoder_control_2(PTZ_PELCO_D_HPATROL):decoder_control_2(PTZ_HPATROL);

		reset_image();
	}
	function hpatrolstop_onclick() 
	{
		ptz_type?decoder_control_2(PTZ_PELCO_D_HPATROL_STOP):decoder_control_2(PTZ_HPATROL_STOP);

		reset_image();
	}	
/* peng
function set_M(v,_v)
{
    camera_control_2(v,_v);
    //location.reload();
}
*/
function body_onload()
{   	
	window.status='';
	gocenter.title=top.str_center;
	preset_1_set.title = top.str_go;
	preset_2_set.title = top.str_go;
	preset_3_set.title = top.str_go;
	preset_4_set.title = top.str_go;
	preset_1_go.title = top.str_set2;
	preset_2_go.title = top.str_set2;
	preset_3_go.title = top.str_set2;
	preset_4_go.title = top.str_set2;
	resolution_sel.value=resolution;
	mode_sel.value=mode;
	brightness_input.value=Math.round(brightness / 16);
	contrast_input.value=contrast;
	image_reversal.checked=(flip&0x01)?true:false;
	image_mirror.checked=(flip&0x02)?true:false;
//	set_M(0,8); peng
load_video();
}	
function load_video()
{
	window.status=" ";
	setTimeout("reload_image()",40);
}
function reload_image()
{
 //   imgDisplay.src="snapshot.cgi?count="+count;
 //   count++;
 //   window.status='';
 //////////////////////
 var xx = new Image();
 xx.src = "http://<?php echo $webcam;?>/snapshot.cgi?user=<?php echo $user;?>&pwd=<?php echo $pass;?>&count="+count;	
 count++;
 document.getElementById("imgDisplay").src = xx.src;
 window.status=" ";
}
function reset_image()
{
	window.status=" ";
	setTimeout("reload_image()",40);
}

function snapshot()
{
	document.getElementById('snapshot').href="snapshot.cgi?user="+top.user+"&pwd="+top.pwd;
}
//peng start
function set_resolution()
{
	camera_control_2(0,resolution_sel.value);
//	setTimeout("reload_image()",40);
}
function plus_brightness()
{
	val=brightness_input.value;
	if (val++<15)
	{
		brightness_input.value=val;
		camera_control_2(1,val*16);
	}
}
function minus_brightness()
{
	val=brightness_input.value;
	if (val-->0)
	{
		brightness_input.value=val;
		camera_control_2(1,val*16);
	}	
}
function plus_contrast()
{
	val=contrast_input.value;
	if (val++<6)
	{
		contrast_input.value=val;
		camera_control_2(2,val);
	}
}
function minus_contrast()
{
	val=contrast_input.value;
	if (val-->0)
	{
		contrast_input.value=val;
		camera_control_2(2,val);
	}
}
function center_onclick()
{
	if (!ptz_type) decoder_control_2(PTZ_CENTER);
}
function up_onmousedown() 
{
	(flip&0x01)?decoder_control_2(TILT_DOWN):decoder_control_2(TILT_UP);
}
function up_onmouseup() 
{
	setTimeout("up_temp()",100);
}
function up_temp()
{
	if (!ptz_type)
		decoder_control_2(PTZ_STOP);
	else if (flip&0x01)
		decoder_control_2(TILT_DOWN_STOP);
	else	
		decoder_control_2(TILT_UP_STOP);
}
function down_onmousedown() 
{
	(flip&0x01)?decoder_control_2(TILT_UP):decoder_control_2(TILT_DOWN);
}
function down_onmouseup() 
{
	setTimeout("down_temp()",100);
}
function down_temp()
{
	if (!ptz_type)
		decoder_control_2(PTZ_STOP);
	else if (flip&0x01)
		decoder_control_2(TILT_UP_STOP);
	else
		decoder_control_2(TILT_DOWN_STOP);	
}
function left_onmousedown() 
{
	(flip&0x02)?decoder_control_2(PAN_RIGHT):decoder_control_2(PAN_LEFT);
}
function left_onmouseup() 
{
	setTimeout("left_temp()",100);
}
function left_temp()
{
	if (!ptz_type)
		decoder_control_2(PTZ_STOP);
	else if (flip&0x02)
		decoder_control_2(PAN_RIGHT_STOP);
	else	
		decoder_control_2(PAN_LEFT_STOP);	
}
function right_onmousedown() 
{
	(flip&0x02)?decoder_control_2(PAN_LEFT):decoder_control_2(PAN_RIGHT);
}
function right_onmouseup() 
{
	setTimeout("right_temp()",100);
}

function right_temp()
{
	if (!ptz_type)
		decoder_control_2(PTZ_STOP);
	else if (flip&0x02)
		decoder_control_2(PAN_LEFT_STOP);
	else	
		decoder_control_2(PAN_RIGHT_STOP);
}

function leftup_onmousedown() 
{
	if (ptz_type)
		return;
	if ((flip&0x03)==0x03)
		decoder_control_2(PTZ_RIGHT_DOWN);
	else if (flip&0x02)
		decoder_control_2(PTZ_RIGHT_UP);
	else if (flip&0x01)
		decoder_control_2(PTZ_LEFT_DOWN);
	else		
		decoder_control_2(PTZ_LEFT_UP);
}
function leftup_onmouseup() 
{
	setTimeout("ptz_temp()",100);
}
function ptz_temp()
{
	if (!ptz_type) decoder_control_2(PTZ_STOP);
}
function rightup_onmousedown() 
{
	if (ptz_type)
		return;
	if ((flip&0x03)==0x03)
		decoder_control_2(PTZ_LEFT_DOWN);
	else if (flip&0x02)
		decoder_control_2(PTZ_LEFT_UP);
	else if (flip&0x01)
		decoder_control_2(PTZ_RIGHT_DOWN);
	else		
		decoder_control_2(PTZ_RIGHT_UP);
}
function rightup_onmouseup() 
{
	setTimeout("ptz_temp()",100);
}
function leftdown_onmousedown() 
{
	if (ptz_type)
		return;
	if ((flip&0x03)==0x03)
		decoder_control_2(PTZ_RIGHT_UP);
	else if (flip&0x02)
		decoder_control_2(PTZ_RIGHT_DOWN);
	else if (flip&0x01)
		decoder_control_2(PTZ_LEFT_UP);
	else		
		decoder_control_2(PTZ_LEFT_DOWN);
}
function leftdown_onmouseup() 
{
	setTimeout("ptz_temp()",100);
}
function rightdown_onmousedown() 
{
	if (ptz_type)
		return;
	if ((flip&0x03)==0x03)
		decoder_control_2(PTZ_LEFT_UP);
	else if (flip&0x02)
		decoder_control_2(PTZ_LEFT_DOWN);
	else if (flip&0x01)
		decoder_control_2(PTZ_RIGHT_UP);
	else		
		decoder_control_2(PTZ_RIGHT_DOWN);
}
function rightdown_onmouseup() 
{
	setTimeout("ptz_temp()",100);
}
function set_flip()
{
	if (image_reversal.checked)
		flip|=1;
	else
		flip&=2;
	if (image_mirror.checked)
		flip|=2;
	else
		flip&=1;	
	camera_control_2(5,flip);
}
function default_all()
{
	resolution_sel.value=8;
	mode_sel.value = 0;
	brightness_input.value=6;
	contrast_input.value=4;
	camera_control_2(0,resolution_sel.value);
	camera_control_2(3,mode_sel.value);
	camera_control_2(2,contrast_input.value);
	camera_control_2(1,brightness_input.value*16);
}
//end
</script>
	<script>
	
	var txtArea
	function startup() {
		//txtArea = document.getElementById("txtAREA");
		//txtArea.innerHTML = "";

		var element = document.getElementById('imgDisplay');
		var hammertime = Hammer(element).on("release dragleft dragup dragdown dragright swipeleft swiperight swipeup", handleHammer);
		$('img').disableSelection();

	}

	function handleHammer(ev) {
		console.log(ev);
		ev.gesture.preventDefault();

		switch(ev.type) {
			case 'dragright':
			//log(">>dragright");
			D_right();
			break;

			case 'dragleft':
			//log("<<dragleft");
			D_left();
			break;

			case 'dragup':
			D_up();
			//log("^^dragup");
			break;

			case 'dragdown':
			D_down();
			//log("||dragdown");
			break;

			case 'swipeleft':
			//log("left");
			break;

			case 'swiperight':
			//log("right");
			break;

			case 'release':
			//log("released");
			releaseALL()
			break;
		};
		//document.getElementById("txtAREA").scrollTop=txtArea.scrollHeight;
	}

	var anyHIGH = false;

var Direction_Left = false;
function D_left(){
	if (Direction_Left==false ){
		if (Direction_Left==false && anyHIGH == true)
			releaseALL();
		$.post("r.php",{ Dctn:"L"});
		Direction_Left=true;
		anyHIGH= true;
	}
}

var Direction_Right = false;
function D_right(){
	if (Direction_Right==false){
		if (Direction_Right==false && anyHIGH == true)
			releaseALL();
		$.post("r.php",{ Dctn:"R"});
		Direction_Right=true;
		anyHIGH = true;
	}
}


var Direction_Forward = false;
function D_up(){
	if (Direction_Forward==false){
		if (Direction_Forward==false && anyHIGH == true)
			releaseALL();
		$.post("r.php",{ Dctn:"F"});
		Direction_Forward=true;
		anyHIGH = true;
	}
}


var Direction_Back = false;
function D_down(){
	if (Direction_Back==false){
		if (Direction_Back==false && anyHIGH == true)
			releaseALL();
		$.post("r.php",{ Dctn:"B"});
		Direction_Back=true;
		anyHIGH = true;
	}
}


function releaseALL(){
	$.post("r.php",{Dctn:"O"});
	anyHIGH = Direction_Left = Direction_Forward = Direction_Back = Direction_Right = false;
}


function log(txt){
	txtArea.innerHTML += "\n" + txt;
}

</script>
<style type="text/css">
#hammer{
	background-image: linear-gradient(bottom, rgb(8,71,122) 45%, rgb(34,100,159) 73%, rgb(62,130,191) 87%);
	background-image: -o-linear-gradient(bottom, rgb(8,71,122) 45%, rgb(34,100,159) 73%, rgb(62,130,191) 87%);
	background-image: -moz-linear-gradient(bottom, rgb(8,71,122) 45%, rgb(34,100,159) 73%, rgb(62,130,191) 87%);
	background-image: -webkit-linear-gradient(bottom, rgb(8,71,122) 45%, rgb(34,100,159) 73%, rgb(62,130,191) 87%);
	background-image: -ms-linear-gradient(bottom, rgb(8,71,122) 45%, rgb(34,100,159) 73%, rgb(62,130,191) 87%);

	background-image: -webkit-gradient(
		linear,
		left bottom,
		left top,
		color-stop(0.45, rgb(8,71,122)),
		color-stop(0.73, rgb(34,100,159)),
		color-stop(0.87, rgb(62,130,191))
		);
	border-radius: 6px;
}

html, body, div, span, object,
form, input, h1, h2, button, label, a, img {
  -moz-user-select: none;
  -webkit-user-select: none;
  /* this will work for QtWebKit in future */
  -webkit-user-drag: none;
}

</style>
</head>
<body onload="startup()" style="background-color: rgb(103, 107, 118);">
	<center><img id="imgDisplay" name="imgDisplay" alt="video" src="http://<?php echo $webcam;?>/snapshot.cgi?user=<?php echo $user;?>&amp;pwd=<?php echo $pass;?>&amp;count=1" onload="load_video()" width="100%" height="auto" style="max-width:320px"><br>
	<td width="112">
			    <div><img src="http://<?php echo $webcam;?>/images/ptz1.gif"><img src="http://<?php echo $webcam;?>/images/ptz3.gif"><img src="http://<?php echo $webcam;?>/images/ptz2.gif"></div>
			    <div><img src="http://<?php echo $webcam;?>/images/leftup_up.gif" onmousedown="leftup_onmousedown()" onmouseup="leftup_onmouseup()"><img src="http://<?php echo $webcam;?>/images/up_up.gif" onmousedown="up_onmousedown()" onmouseup="up_onmouseup()"><img src="http://<?php echo $webcam;?>/images/rightup_up.gif" onmousedown="rightup_onmousedown()" onmouseup="rightup_onmouseup()"></div>
			    <div><img src="http://<?php echo $webcam;?>/images/left_up.gif" onmousedown="left_onmousedown()" onmouseup="left_onmouseup()"><img id="gocenter" src="http://<?php echo $webcam;?>/images/center.gif" onclick="center_onclick()" title="center"><img src="http://<?php echo $webcam;?>/images/right_up.gif" onmousedown="right_onmousedown()" onmouseup="right_onmouseup()"></div>
			    <div><img src="http://<?php echo $webcam;?>/images/leftdown_up.gif" onmousedown="leftdown_onmousedown()" onmouseup="leftdown_onmouseup()"><img src="http://<?php echo $webcam;?>/images/down_up.gif" onmousedown="down_onmousedown()" onmouseup="down_onmouseup()"><img src="http://<?php echo $webcam;?>/images/rightdown_up.gif" onmousedown="rightdown_onmousedown()" onmouseup="rightdown_onmouseup()"></div>
		    </td></center>
<iframe style="DISPLAY: none" src="" name="action_zone"></iframe>
	<!--<textarea id="txtAREA" style="height:300px;"></textarea>-->
</body>
</html>