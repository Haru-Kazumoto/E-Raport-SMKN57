<?php
/** 
 * This class will provide server side validation for different rules with custom 
 * provided message for respective rule. 
 * 
 * @author: Alankar More. 
 * modif by: um412
 */ 

class validasiInput{


    /** 
     * Posted values by the user 
     * 
     * @var array 
     */ 
    protected static $_values; 

    /** 
     * Rules set for validation 
     * 
     * @var array 
     */ 
    protected static $_rules; 

    /** 
     * Error messages 
     * 
     * @var array 
     */ 
    protected static $_messages; 

    /** 
     * To send response 
     * 
     * @var array 
     */ 
    protected static $_response = array(); 

    /** 
     * For storing HTMl objects 
     * 
     * @var array 
     */ 
    protected static $_elements; 

    /** 
     * Html object 
     * 
     * @var string 
     */ 
    protected static $_inputElement; 

    /** 
     * Value of Html object 
     * 
     * @var mixed (string|boolean|integer|double|float) 
     */ 
    protected static $_elementValue; 

    /** 
     * Name of validation rule 
     * 
     * @var string 
     */ 
    protected static $_validationRule; 

    /** 
     * Value of validation rule 
     * 
     * @var mixed (string|boolean|integer|double|float) 
     */ 
    protected static $_ruleValue; 

    /** 
     * Initializing class 
     * 
     * @param array $inputArray 
     * @param array $values 
     */ 
    public static function _initialize(array $inputArray, array $values) { 
        self::$_values = $values; 
        self::$_response = array(); 

        self::generateArrays($inputArray); 
        return self::applyValidation(); 
    } 

    /** 
     * Separating rules and values 
     * 
     * @param array $input 
     */ 
    public static function generateArrays(array $input) { 
        self::$_messages = $input['messages']; 
        self::$_rules = $input['rules']; 
    } 

    /** 
     * Applying validation for the form values 
     * 
     */ 
    public static function applyValidation() { 
        foreach (self::$_rules as $rk => $rv) { 
            $_element = self::$_rules[$rk]; 
            if (is_array($_element)) { 
                foreach ($_element as $key => $ruleValue) { 
                    if (!self::$_elements[$rk]['inValid']) { 
                        $method = "_" . $key; 
                        self::$_inputElement = $rk; 
                        self::$_elementValue = self::$_values[$rk]; 
                        self::$_validationRule = $key; 
                        self::$_ruleValue = $ruleValue; 

                        self::$method(); 
                    } 
                } 
            } 
        } 

        if (count(self::$_response) == 0) { 
            self::$_response['valid'] = true; 
        } 

        return self::$_response; 
    } 

    /** 
     * Method to check wheather the input element holds the value. 
     * If not then assingn message which is set by the user. 
     * 
     */ 
    protected static function _required() { 
        if (self::$_ruleValue) { 
            if (trim(self::$_elementValue) == NULL && 
                strlen(self::$_elementValue) == 0) { 
                self::setErrorMessage("Field Required"); 
                self::setInvalidFlag(true); 
            } else { 
                self::setInvalidFlag(false); 
            } 
        } 
    } 

    /** 
     * Maximum length of input 
     * 
     */ 
    protected static function _maxLength() { 
        if (self::$_ruleValue) { 
            if (strlen(trim(self::$_elementValue)) > self::$_ruleValue) { 
                self::setErrorMessage("Enter at most " . self::$_ruleValue . " charachters only"); 
                self::setInvalidFlag(true); 
            } else { 
                self::setInvalidFlag(false); 
            } 
        } 
    } 

    /** 
     * Minimum length of input 
     * 
     */ 
    protected static function _minLength() { 
        if (self::$_ruleValue) { 
            if (self::$_ruleValue > strlen(trim(self::$_elementValue))) { 
                self::setErrorMessage("Enter at least " . self::$_ruleValue . " charachters "); 
                self::setInvalidFlag(true); 
            } else { 
                self::setInvalidFlag(false); 
            } 
        } 
    } 

    /** 
     * Allow alphabets only 
     * 
     */ 
    protected static function _number() { 
        if (self::$_ruleValue) { 
            $str = filter_var(trim(self::$_elementValue), FILTER_SANITIZE_NUMBER_INT); 
            if (!preg_match('/[0-9]/', $str)) { 
                self:: setErrorMessage("Enter numbers only"); 
                self::setInvalidFlag(true); 
            } else { 
                self::setInvalidFlag(false); 
            } 
        } 
    } 

    /** 
     * Allow alphabets only 
     * 
     */ 
    protected static function _alphabetsOnly() { 
        if (self::$_ruleValue) { 
            $str = filter_var(trim(self::$_elementValue), FILTER_SANITIZE_STRING); 
            if (!preg_match('/[a-zA-z]/', $str)) { 
                self:: setErrorMessage("Enter alphabates only"); 
                self::setInvalidFlag(true); 
            } else { 
                self::setInvalidFlag(false); 
            } 
        } 
    } 

    /** 
     * Allow alphabets and numbers only 
     * 
     */ 
    protected static function _alphaNumeric(){ 
        if (self::$_ruleValue) { 
            $str = trim(self::$_elementValue); 
            if (!preg_match('/[a-zA-z0-9]/', $str)) { 
                self:: setErrorMessage("Alphanumeric only"); 
                self::setInvalidFlag(true); 
            } else { 
                self::setInvalidFlag(false); 
            } 
        } 
    } 

    /** 
     * To check enter email is valid 
     * 
     */ 
    protected static function _email(){ 
       if (self::$_ruleValue) { 
            $str = filter_var(trim(self::$_elementValue), FILTER_VALIDATE_EMAIL); 
            if (!$str) { 
                self:: setErrorMessage("Enter valid email"); 
                self::setInvalidFlag(true); 
            } else { 
                self::setInvalidFlag(false); 
            } 
        } 
    } 

    /** 
     * To check enter url is valid 
     * 
     */ 
    protected static function _url(){ 
       if (self::$_ruleValue) { 
            $str = filter_var(trim(self::$_elementValue), FILTER_VALIDATE_URL); 
            if (!$str) { 
                self:: setErrorMessage("Enter valid URL"); 
                self::setInvalidFlag(true); 
            } else { 
                self::setInvalidFlag(false); 
            } 
        } 
    } 

    /** 
     * Setting invalid flag for every element 
     * 
     * @param boolean $flag 
     */ 
    private static function setInvalidFlag($flag) { 
        self::$_elements[self::$_inputElement]['inValid'] = $flag; 
    } 

    /** 
     * Setting error message for the input element 
     * 
     * @param string $message 
     */ 
    private static function setErrorMessage($message) { 
        if (self::$_messages[self::$_inputElement][self::$_validationRule]) { 
            $message = self::$_messages[self::$_inputElement][self::$_validationRule]; 
        } 
       array_push(self::$_response, ucfirst($message)); 
    } 
} 

You can use this class in your application as below:

<form name="frmTest" id="frmTest" action="" method="POST">
    <input type="text" name="first_name" id="first_name" value = "" />
    <button name="submit" value="Submit" type="submit" >Submit</button>
</form>

<?php
require_once 'validation.php';
// Rules specification.
$rules = array('method' => 'POST',
    'rules' => array('first_name' => array('required' => true)
    ),
    'messages' => array('first_name' => array('required' => 'Please enter first name')
    )
);

$userPostedData = $_POST;
$response = Validation::_initialize($rules, $userPostedData);

// if some error messages are present.
if (!$response['valid']) {
    // it will give you the array with error messages.
    echo "<pre>";
    print_r($response);
} else {
    // all applied validations are passed. You can deal with your submitted information now.
    echo "<pre>";
    print_r($_POST);
}

/*
function validationUID($var,$tb,$fld){
	
	if(preg_match('/^[a-zA-Z0-9]{5,}$/', $var)) { // for english chars + numbers only
    // valid username, alphanumeric & longer than or equals 5 chars
		return true;
	}
	return false;
}

$str = "";
function validate_username($str) 
{
    $allowed = array(".", "-", "_"); // you can add here more value, you want to allow.
    if(ctype_alnum(str_replace($allowed, '', $str ))) {
        return $str;
    } else {
        $str = "Invalid Username";
        return $str;
    }
}

function filterName ($name, $filter = "[^a-zA-Z0-9\-\_\.]"){
    return preg_match("~" . $filter . "~iU", $name) ? false : true;
}

if ( !filterName ($name) ){
 print "Not a valid name";
}



//validasi input jpg/png

0

here is another tip. Dont rely on the ['type'] element, it is too unreliable. Instead check the file header itself to see what the file type actually is. 
Something like so:

 <?php


 // open the file and check header

 $tempfile = $FILES['tmp_name'];
                  
 if (!($handle = fopen($tempfile, 'rb')))
 {
   echo 'open file failed';
   fclose($handle);
   exit;

 }else{
  
        $hdr = fread($handle, 12); //should grab first 12 of header
        fclose($handle);                                     
    
 
        //now check the header results
        $subheaderpre = substr($hdr, 0, 12);
        $subheader = trim($subheaderpre);
     
        //get hex value to check png 
        $getbytes = substr($subheader, 0, 8);
        $hxval = bin2hex($getbytes);
     
     
         if ((substr($subheader, 0, 4) == "\xff\xd8\xff\xe0") && (substr($subheader, 6, 5) == "JFIF\x00"))
         {

           //passed jpg test
 
         }elseif($hxval == "89504e470d0a1a" || substr($subheader, 0, 8) == "\x89PNG\x0d\x0a\x1a\x0a")
           {

              //passed png test 
                
           }else{
              
                 //fail both 

                 echo 'Sorry but image failed to validate, try another image';
                 exit;                            
                      
                 }//close else elseif else
              
    }//close else ! $handle 


//image validatin using mime
$allowed_mime_type_arr = array('image/gif','image/jpeg','image/pjpeg','image/png','image/x-png');
$mime = get_mime_by_extension($_FILES['uploadimage']['name']);
if(in_array($mime, $allowed_mime_type_arr)){
   echo 'Valid Image format';
}else{ 
   echo 'Invalid Image format';
}


*/

 