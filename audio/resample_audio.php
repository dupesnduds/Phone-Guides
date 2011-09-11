#!/usr/bin/php -q

/**
 * Resample_audio.php
 *
 * Resamples studio recorded audio files into phone friendly audio files
 * @author Cleave Pokotea 
 * @copyright 2009-2011 Cleave Pokotea
 * @link http://www.tumunu.com Tumunu
 *
 ***
 * Copyright (c) 2009-2011 Cleave Pokotea
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without 
 * modification, are permitted provided that the following conditions are met:
 *  - Redistributions of source code must retain the above copyright notice, 
 *    this list of conditions and the following disclaimer.
 *  - Redistributions in binary form must reproduce the above copyright 
 *    notice, this list of conditions and the following disclaimer in 
 *    the documentation and/or other materials provided with the distribution.
 *  - Neither the name of the <ORGANIZATION> nor the names of its 
 *    contributors may be used to endorse or promote products 
 *    derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS 
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, 
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR 
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR 
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, 
 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, 
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR 
 * PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY 
 * OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING 
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS 
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 ***
 */

<?php
$input_path = "/home/inz/audio/";
$output_path = "/var/lib/asterisk/sounds/phone_guides/"; 

foreach ($argv as $str) {
	// clean up the path!
	$file = substr($str, 2);

	// check in the file is a wav file
	if(substr($file, -3) == 'wav') {
		$output_file = $output_path;
		$output_file .= substr($file, 0, 4);
		$output_file .= ".wav";
		
		$system_cmd = "sox -v 0.4 ".$file." -r 8000 -s -c 1 -w ".$output_file." resample -ql";
		echo $system_cmd;
		$ex = exec($system_cmd);	
	}	
}

?>