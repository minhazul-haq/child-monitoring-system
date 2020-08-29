<?php
	
	//general menu
	function createGeneralMenu()
	{
	?>
		<div id="menu" align="center">
			<ul>
				<li><a href="/index/index"> Home </a></li>
				<li><a href="/register/register"> User registration </a></li>
				<li><a href="/index/download"> Application download </a></li>
				<li><a href="/index/feedback"> Feedback </a></li>
                <li><a href="/index/about"> About us </a></li>
			</ul>
		</div>
	<?php
	}
		
	//show last date of update
	function showLastUpdate()
	{
		echo ("Last update: 11 October, 2012");
	}
?>