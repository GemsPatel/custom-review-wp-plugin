<?php
/**********that code have to be include in theme function.php **********/
function tm_option($name=''){
	global $wpdb;
	$get = "";
	$get = $name;
	$sel = "p_".$get;
	if( $get != "" ){
		$row = $wpdb->get_row("select $sel from wp_theme_options_pre where id='1' ", ARRAY_A);
		if( $row ){
			echo $row[$sel];
		}else{
			echo "Invalide field name";
		}
	}else{
		echo "Add field name";
	}
}
/* theme option functions  */
?>