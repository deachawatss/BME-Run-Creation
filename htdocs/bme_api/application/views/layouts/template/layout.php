<?php
$CI =& get_instance();
echo @$header;

echo @$topmenu;

echo @$sidemenu;

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<div class='row'>
		<?php 
			echo @$top_content;
			echo @$content;
			echo @$lower_content;
		?>
	</div>
</div>

<?php 
echo @$footer;

?>

