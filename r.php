<?php
/*if (isset($_POST['Pin']) && isset($_POST['status'])){
  if($_POST['status'] == "on"){
	exec('sudo ./run '.'0 '.(int)$_POST['Pin'].' 1');
 } else{
	exec('sudo ./run '.'0 '.(int)$_POST['Pin'].' 0');
  }
} else if(isset($_POST['Clear'])) {
	if($_POST['Clear'] == "true"){
		exec('sudo ./run '.'1 '.(int)$_POST['Pin'].' 0');
	}
}*/

if (isset($_POST['Dctn'])){
	$Direction = $_POST['Dctn'];

	switch($Direction){
		case 'F':
		exec('sudo ./run '.' F');
		break;

		case "B":
		exec('sudo ./run '.' B');
		break;

		case 'L':
		exec('sudo ./run '.' L');
		break;

		case "R":
		exec('sudo ./run '.' R');
		break;

		case "O":
		exec('sudo ./run '.' O');
		break;

		default:
		exec('sudo ./run '.' K');
		break;
	}
}
?>