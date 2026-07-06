<?php
@session_start();
if (@$_SESSION['id'] == "" or @$_SESSION['nivel'] != 'Policial') {
	echo '<script>window.location="../"</script>';
	exit();
}
?>
