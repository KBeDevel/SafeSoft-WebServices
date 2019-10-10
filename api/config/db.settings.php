<?php

# CODE SOURCE: https://gist.github.com/anjerodesu/4502474
# MODIFIED BY: KBeDeveloper

class DatabaseSettings{
	
    var $settings;
    
	function getSettings(){

		$settings['dbhost'] = '127.0.0.1';
		$settings['dbname'] = 'ssdb';
		$settings['dbusername'] = 'ssws';
		$settings['dbpassword'] = 'Sspw2019$';
		
		return $settings;
	}
}

?>