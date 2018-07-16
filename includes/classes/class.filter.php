<?php defined('mainload') or die('Restricted Access'); ?>
<?php
class sanitizing{
	private $input;
	private $result;
	public function quotes($input){
		$result = filter_var($input,FILTER_SANITIZE_MAGIC_QUOTES);
		return $result;
	}
	public function tags($input){
		$result = filter_var($input,FILTER_SANITIZE_STRING);
		return $result;
	}
	public function special($input){
		$result = filter_var($input,FILTER_SANITIZE_SPECIAL_CHARS);
		return $result;
	}
	public function number($input){
		$result = filter_var($input,FILTER_SANITIZE_NUMBER_INT);
		return $result;
	}
	public function url($input){
		$result = filter_var($input,FILTER_SANITIZE_URL);
		return $result;
	}
	public function email($input){
		$result = filter_var($input,FILTER_SANITIZE_EMAIL);
		return $result;
	}
	public function symbol($input){    
		// Karakter yang sering digunakan untuk sqlInjection    
		$char = array ('-','/','\\',',','.','#',':',';','\'','"',"'",'[',']','{','}',')','(','|','`','~','!','@','%','$','^','&','*','=','?','+');  
		// Hilangkan karakter yang telah disebutkan di array $char  
		$result = str_replace($char,'',trim($input));     
		return $result;    
	}
	public function path($input){    
		// Karakter yang sering digunakan untuk sqlInjection    
		$char = array('\\',',','.php','.asp','.html','.htm','.xml','.jsp','.tpl','#',';','\'','"',"'",'[',']','{','}',')','(','|','`','~','!','@','%','$','^','&','*','=','?','+');  
		// Hilangkan karakter yang telah disebutkan di array $char  
		$result = str_replace($char,'',trim($input));     
		return $result;    
	}
	public function str($input){
		$result = $this->quotes($input);
		$result = $this->tags($result);
		$result = $this->special($result);
		return $result;
	}
}
$sanitize = new sanitizing();


class validation{
	private $input;
	private $result;
	public function alphanum($input){ 
		if(filter_var($input, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z0-9 ]+$/")))){
		$result = true; }else{ $result = false; }
		return $result;
	}
	public function url($input){
		if(filter_var($input,FILTER_VALIDATE_URL)){
		$result = true; }else{ $result = false; }
		return $result;
	}
	public function number($input){
		if(filter_var($input,FILTER_VALIDATE_INT)){
		$result = true; }else{ $result = false; }
		return $result;
	}
	public function email($input){
		if(filter_var($input,FILTER_VALIDATE_EMAIL)){
		$result = true; }else{ $result = false; }
		return $result;
	}
}
$validate = new validation();

?>