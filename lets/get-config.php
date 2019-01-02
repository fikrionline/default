<?php


ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);


//@@@@ 
//@@@@ 
//@@@@ git clone https://github.com/fikrionline/default.git
//@@@@ cd default/lets/
//@@@@ chmod +x ./renew.sh
//@@@@ ./renew.sh
//@@@@ 
//@@@@ 

// Settings
$dir_source = './source';
$dir_result = './result';

//Create dir is not exist
if(!is_dir($dir_source)) {
    @mkdir($dir_source, 0755, TRUE);
}
if(!is_dir($dir_result)) {
    @mkdir($dir_result, 0755, TRUE);
}

// e-Mail contact
$mail_contact = "removalrequestwebmaster@gmail.com";

// User Friendly print_r
if(!function_exists('print_rr')) {
	function print_rr($content, $return = false) {
		$output = '<div style="border: 1px solid; resize: both; overflow: auto;color:#000000;background-color:#ffffff;"><pre>' . print_r($content, true) . '</pre></div>';
		if ($return) {
			return $output;
		} else {
			echo $output;
		}
	}
}

// Get text from file
function get_text($raw, $start, $finish) {
	$data = substr($raw, strpos($raw, $start));
	$data = str_replace($start, '', substr($data, 0, strpos($data, $finish)));
	$data = preg_replace('/\s+/', ' ', $data);
	return $data;
}


// Function to running script
function getDirectory($path = '.', $level = 0) {
	global $dir_source, $dir_result, $mail_contact;

    $ignore = array( 'cgi-bin', '.', '..' );
    // Directories to ignore when listing output. Many hosts
    // will deny PHP access to the cgi-bin.

    $dh = @opendir( $path );
    // Open the directory to the handle $dh
    
    while( false !== ( $file = readdir( $dh ) ) ){
    // Loop through the directory
    
        if( !in_array( $file, $ignore ) ){
        // Check that this file is not to be ignored
            
            $spaces = str_repeat( '&nbsp;', ( $level * 4 ) );
            // Just to add spacing to the list, to better
            // show the directory tree.
            
            if( is_dir( "$path/$file" ) ){
            // Its a directory, so we need to keep reading down...
            
                //echo "<strong>$spaces $file</strong><br />";
                getDirectory( "$path/$file", ($level+1) );
                // Re-call this same function but on a new directory.
                // this is what makes function recursive.
            
            } else {

                //Check file is .conf or not
                if(stripos($file, '.conf') !== false) {

                	// Read file
                	$file_source = file_get_contents($dir_source.'/'.$file);
                	$file_result = $dir_result.'/'.$file;

                	// Get server name
                	$domain_name = get_text($file_source, "443 ssl", "root");
                	$domain_name = trim(get_text($domain_name, "server_name", ";")); //print_rr($domain_name);

                	//Get server path
                	$webroot_path = trim(get_text($file_source, "root", ";")); //print_rr($webroot_path);

                	//Write to file
                	$text_to_write = "";
                	$text_to_write .= "domains = ".$domain_name."\n";
                	$text_to_write .= "rsa-key-size = 2048"."\n";
                	$text_to_write .= "server = https://acme-v01.api.letsencrypt.org/directory"."\n";
                	$text_to_write .= "email = ".$mail_contact."\n";
                	$text_to_write .= "text = True"."\n";
                	$text_to_write .= "authenticator = webroot"."\n";
                	$text_to_write .= "webroot-path = ".$webroot_path."/"."\n";
                	file_put_contents($file_result, $text_to_write);
                	print_rr($text_to_write);

                }
            
            }
        
        }
    
    }
    
    closedir( $dh );
    // Close the directory handle

}

getDirectory($dir_source);
