<?php
	/*
		This file handles the display of the search form
	*/
?>

<form role="search" method="get" id="searchform" action="<?php echo home_url() ?>" _lpchecked="1">
	<div><label class="screen-reader-text" for="s">Search for:</label>
	<input placeholder='Search' type="text" value="" name="s" id="s">
	<input type="submit" id="searchsubmit" value="Search">
	</div>
</form>