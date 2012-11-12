<?php
/**
 * TODO: Move implementations out of global functions into class methods, keep
 *       the global function alive (for now).
 *       Group methods by helper class. 
 *
 * This file contains constants and shortcut functions that are commonly used.
 * @package application.components
 * @since 1.0
 */

/**
 * This is the shortcut to DIRECTORY_SEPARATOR
 */
defined('DS') or define('DS',DIRECTORY_SEPARATOR);

/*
 * This should be set to the default system user.
 */
defined('SYSTEM_USER_ID') or define('SYSTEM_USER_ID', 1);


function formatAsNumber($string)
{
     // This regex pattern means anything that is not a number
     $pattern = '/[^0-9]/';
     // preg_replace searches for the pattern in the string and replaces all instances with an empty string
     return preg_replace($pattern, '', $string) / 100;
}



function forceLogin($user_id)
{
  $user = User::model()->findByPk($user_id);

  $identity = new UserIdentity($user->username, $user->password);
  $identity->authenticate(false);
  Yii::app()->user->login($identity);
  return true;
}

function getEc2InstanceIp()
{
  if($_SERVER['SERVER_ADDR'] == '127.0.0.1')
    return $_SERVER['HTTP_HOST'];

  $aws_access_key = get_cfg_var('aws.access_key');
  $aws_secret_key = get_cfg_var('aws.secret_key');

  exec("bash -l -c \"/opt/aws/bin/ec2-describe-instances --aws-access-key $aws_access_key --aws-secret-key $aws_secret_key | grep elasticbeanstalk-default,DZDB-" . strtoupper(get_cfg_var('aws.param1')) . " -A2 | grep {$_SERVER['SERVER_ADDR']} | cut -f4\"", $result);  
  return $result[0];
}

function getCacheConfig()
{
  $server = get_cfg_var('aws.param1');
  if(!$server)
    return array('class' => 'CApcCache');
  else {
    $servers = array();
    $memCacheConfig =  require(dirname(__FILE__).'/../config/cache-'.$server.'.php');
    $serverCount = count($memCacheConfig);
    foreach($memCacheConfig as $server)
    {
      $server['weight'] = round(100/$serverCount);
      $servers[] = $server;
    }
    return array('class'=>'CMemCache','servers'=>$servers);
  }
}

function mergeArrays($array1, $array2, $stripIndexes=array())
{
  $merged = CMap::mergeArray($array1, $array2);
  if (PHP_SAPI == 'cli') 
  {
    foreach($stripIndexes as $index)
    {
      unset($merged[($index)]);
    }
  }
  return $merged;
}

/**
 * This is the shortcut to CHtml::encode
 */
function h($text,$limit=0)
{
	if($limit && strlen($text)>$limit && ($pos=strpos($text,' ',$limit))!==false)
		$text=substr($text,0,$pos);
	return htmlspecialchars($text,ENT_QUOTES,Yii::app()->charset);
}

/**
 * 
 */
function truncate($string, $max = 13, $rep = '...') 
{ 
    return GHelper::truncate($string, $max, $rep);
} 

/**
 * This is the shortcut to nl2br(CHtml::encode())
 * @param string the text to be formatted
 * @param integer the maximum length of the text to be returned. If false, it means no truncation.
 * @param boolean whether to show "read more" link if the text limit is reached.
 * @return string the formatted text
 */
function nh($text,$limit=0,$readMore=false)
{
	if($limit && strlen($text)>$limit)
	{
		if(($pos=strpos($text,' ',$limit))!==false)
			$limit=$pos;
		$ltext=substr($text,0,$limit);
		if($readMore)
		{
			$rtext=substr($text,$limit);
			return nl2br(htmlspecialchars($ltext,ENT_QUOTES,Yii::app()->charset))
				. l(t('read more').' >','#',array('class'=>'read-more'))
				. '<span class="read-more" style="display:none;">'
				. nl2br(htmlspecialchars($rtext,ENT_QUOTES,Yii::app()->charset))
				. '</span>';
		}
		else
			return nl2br(htmlspecialchars($ltext.' ......',ENT_QUOTES,Yii::app()->charset));
	}
	else
		return nl2br(htmlspecialchars($text,ENT_QUOTES,Yii::app()->charset));
}

/**
 * This is the shortcut to CHtmlPurifier::purify().
 */
function ph($text)
{
	static $purifier;
	if($purifier===null)
		$purifier=new CHtmlPurifier;
	return $purifier->purify($text);
}

function camelCase($str)
{
  return str_replace(' ', '', lcfirst(ucwords(str_replace('_', ' ', $str)))); 
}

/**
 * This is the shortcut to CHtml::link()
 */
function l($text,$url='#',$htmlOptions=array())
{
	return CHtml::link($text, $url, $htmlOptions);
}

/**
 * This is the shortcut to Yii::t() with default category='tce'.
 * Note that the category parameter is moved from the first to the third position.
 */
function t($message, $params=array(), $category='application', $source=null, $language=null)
{
	return Yii::t($category, $message, $params, $source, $language);
}

/**
 * This is the shortcut to Yii::app()->createUrl()
 */
function url($route,$params=array(),$ampersand='&')
{
	if (Yii::app() instanceof CConsoleApplication)
	{
		return Yii::app()->params['host'].Yii::app()->getUrlManager()->createUrl($route,$params,$ampersand);
	}
	else	
		return Yii::app()->createUrl($route,$params,$ampersand);
}

/**
 * This is the shortcut to Yii::app()->request->baseUrl
 * If the parameter is given, it will be returned and prefixed with the app baseUrl.
 */
function bu($url='')
{
  static $baseUrl;
  $baseUrl=Yii::app()->request->baseUrl;
  $baseUrl .= $baseUrl.'/'.ltrim($url,'/');
  if(param('root_control'))
  {
    $baseUrl = "/" . param('root_control') . $baseUrl;
  }
  return $baseUrl; 
}

/**
 * Returns the named application parameter.
 * This is the shortcut to Yii::app()->params[$name].
 */
function param($name)
{
  static $dbParams;
  if(isset(Yii::app()->params[$name]))
    return Yii::app()->params[$name];
	else
    return "";    
}

/**
 * This is the shortcut to Yii::app()->user.
 * @return WebUser
 */
function user()
{
	return Yii::app()->user;
}

/**
 * This is the shortcut to Yii::app()
 * @return CWebApplication
 */
function app()
{
	return Yii::app();
}

/**
 * This is the shortcut to Yii::app()->clientScript
 * @return CClientScript
 */
function cs()
{
	return Yii::app()->clientScript;
}

/**
 * This is the shortcut to Yii::app()->db
 * @return CDbConnection
 */
function db()
{
	return Yii::app()->db;
}

/**
 * Merges two arrays.
 * This is similar to array_merge except that it also merges numerical keys.
 * @param array first array
 * @param array second array
 * @return array the merged array
 */
function mergeArray($a,$b)
{
	foreach($b as $k=>$v)
		$a[$k]=$v;
	return $a;
}

/**
 * This is the shortcut to Yii::app()->user->checkAccess().
 */
function allow($operation,$params=array(),$allowCaching=true)
{
	return Yii::app()->user->checkAccess($operation,$params,$allowCaching);
}

/**
 * Ensures the current user is allowed to perform the specified operation.
 * An exception will be thrown if not.
 * This is similar to {@link access} except that it does not return value.
 */
function ensureAllow($operation,$params=array(),$allowCaching=true)
{
	if(!Yii::app()->user->checkAccess($operation,$params,$allowCaching))
		throw new CHttpException(403,t('You are not allowed to perform this operation.'));
}

/**
 * Just to keep the joke alive, i will add this method here :)
 * 
 * We can use this method to filter array based on operation
 * checks. For instance, to filter the columns of a grid view.
 * @param   array Original array i.e. grid column array.
 * @param   array Items to be FILTERED.
 * @param   string Operation to checkAccess.
 * @return  array the filtered array
 * 
 * @author emiliano.burgos@controlgroup.com
 */
function authArrayFilter($columns, $items, $operation)
{
    return GAuthHelper::operation_filter_array($columns, $items, $operation);
};

/**
 * Makes a string to be URL friendly.
 * @param string the string to be slugified
 * @return string the URL-friendly string
 */
function slugify($text, $ws = '-')
{
	return GHelper::cleanString($text, $ws);
}

function ascii($text)
{
	return GHelper::ascii($text);
}


/**
 * Get array of all timezones
 * @return array all timezones
 */
function timeZones()
{
	$timeZones = DateTimeZone::listIdentifiers();
	$ret =array();
	foreach ( $timeZones as $timeZone ) 
		$ret[$timeZone] = $timeZone;

	return $ret;
}

/**
 * @return string ISO Countrie name by code.
 * @author emiliano.burgos@controlgroup.com
 */
function getCountry($code) {
    $code = strtoupper($code);
    $countries = ISOCountries();
    if (array_key_exists($code, $countries)) {
        return $countries[$code];
    }
    return FALSE;
}

/**
 * ISO-3166-1 http://en.wikipedia.org/wiki/ISO_3166-1
 *
 * @return array All ISO countries.
 * @author emiliano.burgos@controlgroup.com
 */
function ISOCountries()
{
    $_countries = array(
      "US" => "United States",
      "GB" => "United Kingdom",
      "AF" => "Afghanistan",
      "AL" => "Albania",
      "DZ" => "Algeria",
      "AS" => "American Samoa",
      "AD" => "Andorra",
      "AO" => "Angola",
      "AI" => "Anguilla",
      "AQ" => "Antarctica",
      "AG" => "Antigua And Barbuda",
      "AR" => "Argentina",
      "AM" => "Armenia",
      "AW" => "Aruba",
      "AU" => "Australia",
      "AT" => "Austria",
      "AZ" => "Azerbaijan",
      "BS" => "Bahamas",
      "BH" => "Bahrain",
      "BD" => "Bangladesh",
      "BB" => "Barbados",
      "BY" => "Belarus",
      "BE" => "Belgium",
      "BZ" => "Belize",
      "BJ" => "Benin",
      "BM" => "Bermuda",
      "BT" => "Bhutan",
      "BO" => "Bolivia",
      "BA" => "Bosnia And Herzegowina",
      "BW" => "Botswana",
      "BV" => "Bouvet Island",
      "BR" => "Brazil",
      "IO" => "British Indian Ocean Territory",
      "BN" => "Brunei Darussalam",
      "BG" => "Bulgaria",
      "BF" => "Burkina Faso",
      "BI" => "Burundi",
      "KH" => "Cambodia",
      "CM" => "Cameroon",
      "CA" => "Canada",
      "CV" => "Cape Verde",
      "KY" => "Cayman Islands",
      "CF" => "Central African Republic",
      "TD" => "Chad",
      "CL" => "Chile",
      "CN" => "China",
      "CX" => "Christmas Island",
      "CC" => "Cocos (Keeling) Islands",
      "CO" => "Colombia",
      "KM" => "Comoros",
      "CG" => "Congo",
      "CD" => "Congo, The Democratic Republic Of The",
      "CK" => "Cook Islands",
      "CR" => "Costa Rica",
      "CI" => "Cote D'Ivoire",
      "HR" => "Croatia (Local Name: Hrvatska)",
      "CU" => "Cuba",
      "CY" => "Cyprus",
      "CZ" => "Czech Republic",
      "DK" => "Denmark",
      "DJ" => "Djibouti",
      "DM" => "Dominica",
      "DO" => "Dominican Republic",
      "TP" => "East Timor",
      "EC" => "Ecuador",
      "EG" => "Egypt",
      "SV" => "El Salvador",
      "GQ" => "Equatorial Guinea",
      "ER" => "Eritrea",
      "EE" => "Estonia",
      "ET" => "Ethiopia",
      "FK" => "Falkland Islands (Malvinas)",
      "FO" => "Faroe Islands",
      "FJ" => "Fiji",
      "FI" => "Finland",
      "FR" => "France",
      "FX" => "France, Metropolitan",
      "GF" => "French Guiana",
      "PF" => "French Polynesia",
      "TF" => "French Southern Territories",
      "GA" => "Gabon",
      "GM" => "Gambia",
      "GE" => "Georgia",
      "DE" => "Germany",
      "GH" => "Ghana",
      "GI" => "Gibraltar",
      "GR" => "Greece",
      "GL" => "Greenland",
      "GD" => "Grenada",
      "GP" => "Guadeloupe",
      "GU" => "Guam",
      "GT" => "Guatemala",
      "GN" => "Guinea",
      "GW" => "Guinea-Bissau",
      "GY" => "Guyana",
      "HT" => "Haiti",
      "HM" => "Heard And Mc Donald Islands",
      "VA" => "Holy See (Vatican City State)",
      "HN" => "Honduras",
      "HK" => "Hong Kong",
      "HU" => "Hungary",
      "IS" => "Iceland",
      "IN" => "India",
      "ID" => "Indonesia",
      "IR" => "Iran (Islamic Republic Of)",
      "IQ" => "Iraq",
      "IE" => "Ireland",
      "IL" => "Israel",
      "IT" => "Italy",
      "JM" => "Jamaica",
      "JP" => "Japan",
      "JO" => "Jordan",
      "KZ" => "Kazakhstan",
      "KE" => "Kenya",
      "KI" => "Kiribati",
      "KP" => "Korea, Democratic People's Republic Of",
      "KR" => "Korea, Republic Of",
      "KW" => "Kuwait",
      "KG" => "Kyrgyzstan",
      "LA" => "Lao People's Democratic Republic",
      "LV" => "Latvia",
      "LB" => "Lebanon",
      "LS" => "Lesotho",
      "LR" => "Liberia",
      "LY" => "Libyan Arab Jamahiriya",
      "LI" => "Liechtenstein",
      "LT" => "Lithuania",
      "LU" => "Luxembourg",
      "MO" => "Macau",
      "MK" => "Macedonia, Former Yugoslav Republic Of",
      "MG" => "Madagascar",
      "MW" => "Malawi",
      "MY" => "Malaysia",
      "MV" => "Maldives",
      "ML" => "Mali",
      "MT" => "Malta",
      "MH" => "Marshall Islands",
      "MQ" => "Martinique",
      "MR" => "Mauritania",
      "MU" => "Mauritius",
      "YT" => "Mayotte",
      "MX" => "Mexico",
      "FM" => "Micronesia, Federated States Of",
      "MD" => "Moldova, Republic Of",
      "MC" => "Monaco",
      "MN" => "Mongolia",
      "MS" => "Montserrat",
      "MA" => "Morocco",
      "MZ" => "Mozambique",
      "MM" => "Myanmar",
      "NA" => "Namibia",
      "NR" => "Nauru",
      "NP" => "Nepal",
      "NL" => "Netherlands",
      "AN" => "Netherlands Antilles",
      "NC" => "New Caledonia",
      "NZ" => "New Zealand",
      "NI" => "Nicaragua",
      "NE" => "Niger",
      "NG" => "Nigeria",
      "NU" => "Niue",
      "NF" => "Norfolk Island",
      "MP" => "Northern Mariana Islands",
      "NO" => "Norway",
      "OM" => "Oman",
      "PK" => "Pakistan",
      "PW" => "Palau",
      "PA" => "Panama",
      "PG" => "Papua New Guinea",
      "PY" => "Paraguay",
      "PE" => "Peru",
      "PH" => "Philippines",
      "PN" => "Pitcairn",
      "PL" => "Poland",
      "PT" => "Portugal",
      "PR" => "Puerto Rico",
      "QA" => "Qatar",
      "RE" => "Reunion",
      "RO" => "Romania",
      "RU" => "Russian Federation",
      "RW" => "Rwanda",
      "KN" => "Saint Kitts And Nevis",
      "LC" => "Saint Lucia",
      "VC" => "Saint Vincent And The Grenadines",
      "WS" => "Samoa",
      "SM" => "San Marino",
      "ST" => "Sao Tome And Principe",
      "SA" => "Saudi Arabia",
      "SN" => "Senegal",
      "SC" => "Seychelles",
      "SL" => "Sierra Leone",
      "SG" => "Singapore",
      "SK" => "Slovakia (Slovak Republic)",
      "SI" => "Slovenia",
      "SB" => "Solomon Islands",
      "SO" => "Somalia",
      "ZA" => "South Africa",
      "GS" => "South Georgia, South Sandwich Islands",
      "ES" => "Spain",
      "LK" => "Sri Lanka",
      "SH" => "St. Helena",
      "PM" => "St. Pierre And Miquelon",
      "SD" => "Sudan",
      "SR" => "Suriname",
      "SJ" => "Svalbard And Jan Mayen Islands",
      "SZ" => "Swaziland",
      "SE" => "Sweden",
      "CH" => "Switzerland",
      "SY" => "Syrian Arab Republic",
      "TW" => "Taiwan",
      "TJ" => "Tajikistan",
      "TZ" => "Tanzania, United Republic Of",
      "TH" => "Thailand",
      "TG" => "Togo",
      "TK" => "Tokelau",
      "TO" => "Tonga",
      "TT" => "Trinidad And Tobago",
      "TN" => "Tunisia",
      "TR" => "Turkey",
      "TM" => "Turkmenistan",
      "TC" => "Turks And Caicos Islands",
      "TV" => "Tuvalu",
      "UG" => "Uganda",
      "UA" => "Ukraine",
      "AE" => "United Arab Emirates",
      "UM" => "United States Minor Outlying Islands",
      "UY" => "Uruguay",
      "UZ" => "Uzbekistan",
      "VU" => "Vanuatu",
      "VE" => "Venezuela",
      "VN" => "Viet Nam",
      "VG" => "Virgin Islands (British)",
      "VI" => "Virgin Islands (U.S.)",
      "WF" => "Wallis And Futuna Islands",
      "EH" => "Western Sahara",
      "YE" => "Yemen",
      "YU" => "Yugoslavia",
      "ZM" => "Zambia",
      "ZW" => "Zimbabwe"
    );
    return $_countries;
}

/**
 * 
 */
function USAStates()
{
	$state_list = array('AL'=>"Alabama",  
			'AK'=>"Alaska",  
			'AZ'=>"Arizona",  
			'AR'=>"Arkansas",  
			'CA'=>"California",  
			'CO'=>"Colorado",  
			'CT'=>"Connecticut",  
			'DE'=>"Delaware",  
			'DC'=>"District Of Columbia",  
			'FL'=>"Florida",  
			'GA'=>"Georgia",  
			'HI'=>"Hawaii",  
			'ID'=>"Idaho",  
			'IL'=>"Illinois",  
			'IN'=>"Indiana",  
			'IA'=>"Iowa",  
			'KS'=>"Kansas",  
			'KY'=>"Kentucky",  
			'LA'=>"Louisiana",  
			'ME'=>"Maine",  
			'MD'=>"Maryland",  
			'MA'=>"Massachusetts",  
			'MI'=>"Michigan",  
			'MN'=>"Minnesota",  
			'MS'=>"Mississippi",  
			'MO'=>"Missouri",  
			'MT'=>"Montana",
			'NE'=>"Nebraska",
			'NV'=>"Nevada",
			'NH'=>"New Hampshire",
			'NJ'=>"New Jersey",
			'NM'=>"New Mexico",
			'NY'=>"New York",
			'NC'=>"North Carolina",
			'ND'=>"North Dakota",
			'OH'=>"Ohio",  
			'OK'=>"Oklahoma",  
			'OR'=>"Oregon",  
			'PA'=>"Pennsylvania",  
			'RI'=>"Rhode Island",  
			'SC'=>"South Carolina",  
			'SD'=>"South Dakota",
			'TN'=>"Tennessee",  
			'TX'=>"Texas",  
			'UT'=>"Utah",  
			'VT'=>"Vermont",  
			'VA'=>"Virginia",  
			'WA'=>"Washington",  
			'WV'=>"West Virginia",  
			'WI'=>"Wisconsin",  
			'WY'=>"Wyoming");
	return $state_list;
}
/**
 * Send message with a subject,view and options
 * @param array/integer the message recipient user id(s)
 * @param string the message subject
 * @param string the message view
 * @param array option for the data
 * @param int $from_id (optional) ID of the sender
 * @return void
 */

function years()
{
  $years = array();
  for($i=date('Y');$i>(date('Y') - 100); $i--)
    $years[$i] = $i;
  return $years;
}

function days()
{
  $days = array();
  for($i=1; $i<32; $i++)
    $days[(str_pad($i, 2, "0", STR_PAD_LEFT))] = $i;
  return $days;
}

function months()
{
  $months = array();
  for($i=1; $i<13; $i++)
    $months[(str_pad($i, 2, "0", STR_PAD_LEFT))] = date("M", mktime(0, 0, 0, $i, 1, date('Y')));
  return $months;
}


function compareDate($date1, $date2)
{
	$time1=strtotime($date1);
	$time2=strtotime($date2);
	if ($time1<$time2)
		return -1;
	else if ($time1==$time2)
		return 0;
	else 
		return 1;
}

function showDayOfWeek($date,$short=false)
{
    $days = array('Sunday',
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday',
                );
    $days2 = array('Sun.',
                'Mon.',
                'Tue.',
                'Wed.',
                'Thu.',
                'Fri.',
                'Sat.',
                );

    $tmp =  new DateTime($date);
    if ($short)
    	return $days2[$tmp->format('w')];
    else	
    	return $days[$tmp->format('w')];
}

/**
 * 
 */
function ls($pattern="*", $folder="", $recursivly=false, $options=array('return_files','return_folders')) {
    if($folder) {
        $current_folder = realpath('.');
        if(in_array('quiet', $options)) { // If quiet is on, we will suppress the 'no such folder' error
            if(!file_exists($folder)) return array();
        }
        
        if(!chdir($folder)) return array();
    }
    
    
    $get_files    = in_array('return_files', $options);
    $get_folders= in_array('return_folders', $options);
    $both = array();
    $folders = array();
    
    // Get the all files and folders in the given directory.
    if($get_files) $both = glob($pattern, GLOB_BRACE + GLOB_MARK);
    if($recursivly or $get_folders) $folders = glob("*", GLOB_ONLYDIR + GLOB_MARK);
    
    //If a pattern is specified, make sure even the folders match that pattern.
    $matching_folders = array();
    if($pattern !== '*') $matching_folders = glob($pattern, GLOB_ONLYDIR + GLOB_MARK);
    
    //Get just the files by removing the folders from the list of all files.
    $all = array_values(array_diff($both,$folders));
        
    if($recursivly or $get_folders) {
        foreach ($folders as $this_folder) {
            if($get_folders) {
                //If a pattern is specified, make sure even the folders match that pattern.
                if($pattern !== '*') {
                    if(in_array($this_folder, $matching_folders)) array_push($all, $this_folder);
                }
                else array_push($all, $this_folder);
            }
            
            if($recursivly) {
                // Continue calling this function for all the folders
                $deep_items = ls($pattern, $this_folder, $recursivly, $options); # :RECURSION:
                foreach ($deep_items as $item) {
                    array_push($all, $this_folder . $item);
                }
            }
        }
    }
    
    if($folder) chdir($current_folder);
    return $all;
}

/**
* Generatting CSV formatted string from an array.
* By Sergey Gurevich.
*/
function array_to_csv($array, $header_row = true, $col_sep = ",", $row_sep = "\n", $qut = '"')
{
	if (!is_array($array) or !is_array($array[0])) return false;
	$output = '';
	//Header row.
	if ($header_row)
	{
		foreach ($array[0] as $key => $val)
		{
			//Escaping quotes.
			$key = str_replace($qut, "$qut$qut", $key);
			$output .= "$col_sep$qut$key$qut";
		}
		$output = substr($output, 1)."\n";
	}
	//Data rows.
	foreach ($array as $key => $val)
	{
		$tmp = '';
		foreach ($val as $cell_key => $cell_val)
		{
			//Escaping quotes.
			$cell_val = str_replace($qut, "$qut$qut", $cell_val);
			$tmp .= "$col_sep$qut$cell_val$qut";
		}
		$output .= substr($tmp, 1).$row_sep;
	}
	
    // return $output;
    // Added trim, remove trailing \n     
	return rtrim($output, $row_sep);
}

function checkAllAccess($authItems=array())
{
  return GAuthHelper::checkAllAccess($authItems);
}

function generateSalt($saltLength = 9)
{
    return GAuthHelper::generateSalt($saltLength);
}

function depluralize($word){
    return GHelper::depluralize($word);
}

/**
 * TODO: Merge with GHelper::array_to_object
 */
function arrayToObject($array) {
    if(!is_array($array)) {
        return $array;
    }
    
    $object = new stdClass();
    if (is_array($array) && count($array) > 0) {
      foreach ($array as $name=>$value) {
         $name = strtolower(trim($name));
         if (!empty($name)) {
            $object->$name = arrayToObject($value);
         }
      }
      return $object; 
    }
    else {
      return FALSE;
    }
}

/**
 * TODO: Do we need this? The array_to_object
 *       seems a better approach.
 */
class arrayClass
{
    public function __construct( $array = array() )
    {
        foreach( $array as $key => $value )
        {
            if( TRUE === is_array($value) )
                $this->$key = new arrayClass( $value );
            else
                $this->$key = $value;
        }
    }
}

/**
 * Hack;
 * http://dzdb.local/app/DZ-LLC#inventory => TRUE.
 * @author emiliano.burgos@controlgroup.com
 */
function disable_bootstrap() {
	//This might be that we are on the CLI?
	if (php_sapi_name() === 'cli' || !isset($_SERVER['REQUEST_URI']) ) 
		return FALSE;
	//if(!isset($_SERVER['REQUEST_URI'])) return FALSE;
	
	return strpos($_SERVER["REQUEST_URI"], '/app/') === 0; 	
}

/**
 * 
 * @author emiliano.burgos@controlgroup.com
 */
function is_ajax( ) {
    return GHelper::is_ajax();
}

/**
 * Translates a camel case string into a string with underscores (e.g. firstName -&gt; first_name)
 * @param    string   $str    String in camel case format
 * @return    string            $str Translated into underscore format
 * 
 * @author emiliano.burgos@controlgroup.com
 */
function from_camel_case($str) {
    return GHelper::from_camel_case($str);
}
 
/**
 * Translates a string with underscores into camel case (e.g. first_name -&gt; firstName)
 * @param    string   $str                     String in underscore format
 * @param    bool     $capitalise_first_char   If true, capitalise the first char in $str
 * @return   string                              $str translated into camel caps
 * 
 * @author emiliano.burgos@controlgroup.com
 */  
function to_camel_case($str, $capitalise_first_char = FALSE) {
    return GHelper::to_camel_case($str, $capitalise_first_char);
}

class GArrayHelper 
{
    static public function array_to_csv($array, $header_row = true, $col_sep = ",", $row_sep = "\n", $qut = '"')
    {
        return array_to_csv($array, $header_row, $col_sep, $row_sep, $qut);
    }
}

/**
 * This class should hold all grid view helper methods.
 * 
 * @author emiliano.burgos@controlgroup.com
 */
class GGridHelper 
{
    static public function formatDate($date, $format = "m-d-y")
    {
        if(is_string($date))
            $date = strtotime($date);
        
        return date ($format, $date);
    }
}

class GArHelper 
{
    

    /**
     * 
     */
    static public function cloneModel($clone_id, &$model)
    {
        $clone = $model::model()->findByPk($clone_id);
        if($clone)
        {
            $model->attributes = $clone->attributes;
            unset($model->id);
            
            //remove all inique attributes?
            foreach($model->rules() as $rules)
            {
                list($rules, $rule) = $rules;
                
                if($rule !== 'unique')
                    continue;
                
                if(strpos($rules, ','))
                    $rules = implode(',',$rules);
                else $rules = array($rules);
                
                foreach($rules as $attribute)
                    unset($model->$attribute);
                
            }
        }
    }
    
    /**
     * 
     */
    static public function getAttributesMarkedUnique($model)
    {
        $unique = array();
        foreach($model->rules() as $rules)
        {
            list($rules, $rule) = $rules;
            
            if($rule !== 'unique')
                continue;
            
            if(strpos($rules, ','))
                $rules = implode(',',$rules);
            else $rules = array($rules);
            
            $unique = CMap::mergeArray($unique, $rules);
        }
        
        return $unique;
    }
}
/**
 * This class should hold all auth related helper methods.
 * 
 * @author emiliano.burgos@controlgroup.com
 */
class GAuthHelper 
{
    /**
     * 
     */
    static public function checkAllAccess($authItems=array())
    {
      for($i=0; $i<count($authItems); $i++)
      {
        if(!Yii::app()->user->checkAccess($authItems[$i]))
          return false;
      }
      return true;
    }
    
    /**
     * 
     */
    static public function generateSalt($saltLength = 9)
    {
        return substr(md5(uniqid(rand(), true)), 0, $saltLength);
    }
    
    /**
     * TODO: Check if operation is an array, and if it is, perform
     * all the checks. 
     *
     * We can use this method to filter array based on operation
     * checks. For instance, to filter the columns of a grid view.
     * @param   array Original array i.e. grid column array.
     * @param   array Items to be FILTERED.
     * @param   string Operation to checkAccess.
     * @return  array the filtered array
     * 
     * @author emiliano.burgos@controlgroup.com
     */
    static public function operation_filter_array($columns, $items, $operation)
    {
        //If we have rights, return columns as it is:
        if(Yii::app()->user->checkAccess($operation))
           return $columns;
        
        //We suppose that items is an array:
        foreach($items as $item)
        {
            $index = array_search($item, $columns);
            array_splice($columns, $index, 1);
        }
       
        return $columns;
    }
}
/**
 * All Date/Time related helper methods.
 * 
 * @author miguel.bermudez@controlgroup.com
 */
class GDateHelper 
{
    const STR_TODAY = 'today';
    const STR_YESTERDAY = 'yesterday';
    const STR_DAYS = '%d days ago';
    const STR_WEEK = "1 week ago";
    const STR_WEEKS = "%d weeks ago";
    const DATE_FORMAT = 'Y-m-d';
    
    //Display time as in relative time (e.g. 4 days ago)
    //Assume time params is a MYSQL DATETIME value

    static public function dateToWords($time)
    {

        //change sql datetime to unix timestamp
        $phptime = strtotime($time); 
         
        //The functions takes the date as a timestamp         
        $_word = "";

        //Get the difference between the current time and the time given in days 
        $days = intval((time() - $phptime) / 86400);
     
        if($days < 0) return -1;
     
        switch($days) {
            case 0: $_word = self::STR_TODAY;
                    break;
            case 1: $_word = self::STR_YESTERDAY;
                    break;
            case ($days >= 2 && $days <= 6): 
                  $_word =  sprintf(self::STR_DAYS, $days);
                  break;
            case ($days >= 7 && $days < 14): 
                  $_word= self::STR_WEEK;
                  break;

                  $_word =  sprintf(self::STR_WEEKS, intval($days / 7));
                  break;
            default : 
                return date(self::DATE_FORMAT, $phptime);
        }
        return $_word;
    }
}

/**
 * TODO: Break and group methods into related subclasses.
 */
class GHelper 
{
    
    /**
     * 
     */
     static public function depluralize($word){
        // Here is the list of rules. To add a scenario,
        // Add the plural ending as the key and the singular
        // ending as the value for that key. This could be
        // turned into a preg_replace and probably will be
        // eventually, but for now, this is what it is.
        //
        // Note: The first rule has a value of false since
        // we don't want to mess with words that end with
        // double 's'. We normally wouldn't have to create
        // rules for words we don't want to mess with, but
        // the last rule (s) would catch double (ss) words
        // if we didn't stop before it got to that rule. 
        $rules = array( 
            'ss' => false, 
            'os' => 'o', 
            'ies' => 'y', 
            'xes' => 'x', 
            'oes' => 'o', 
            'ies' => 'y', 
            'ves' => 'f', 
            's' => '');
        // Loop through all the rules and do the replacement. 
        foreach(array_keys($rules) as $key){
            // If the end of the word doesn't match the key,
            // it's not a candidate for replacement. Move on
            // to the next plural ending. 
            if(substr($word, (strlen($key) * -1)) != $key) 
                continue;
            // If the value of the key is false, stop looping
            // and return the original version of the word. 
            if($key === false) 
                return $word;
            // We've made it this far, so we can do the
            // replacement. 
            return substr($word, 0, strlen($word) - strlen($key)) . $rules[$key]; 
        }
        return $word;
    }
    /**
     * 
     * @author emiliano.burgos@controlgroup.com
     */
    static public function is_ajax( ) {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        (strtolower(  $_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'));
    }
    
    /**
     * Translates a camel case string into a string with underscores (e.g. firstName -&gt; first_name)
     * @param    string   $str    String in camel case format
     * @return    string            $str Translated into underscore format
     * 
     * @author emiliano.burgos@controlgroup.com
     */
    static public function from_camel_case($str) {
        $str[0] = strtolower($str[0]);
        $func = create_function('$c', 'return "_" . strtolower($c[1]);');
        return preg_replace_callback('/([A-Z])/', $func, $str);
    }

    /**
     * Translates a string with underscores into camel case (e.g. first_name -&gt; firstName)
     * @param    string   $str                     String in underscore format
     * @param    bool     $capitalise_first_char   If true, capitalise the first char in $str
     * @return   string                              $str translated into camel caps
     * 
     * @author emiliano.burgos@controlgroup.com
     */  
    static public function to_camel_case($str, $capitalise_first_char = FALSE) {
        if($capitalise_first_char) {
              $str[0] = strtoupper($str[0]);
        }
        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/_([a-z])/', $func, $str);
    }
    /**
     * We pass the array to be sorted by reference. It will be modified
     * in the method, no return.
     * 
     * @param array     $array  Multidimensional array to be sorted.
     * @param string    $type   asort || ksort.
     */
    static public function sort_deep(&$array, $type = "asort"){
        $type($array);
        foreach($array as $value)
            if(is_array($value))
                self::sort_deep($value);
    }
    
    
    /**
     * @param Number    $number     Item to be formated.
     * @param String    $symbol     Symbol to append. Default empty.
     * @param boolean   $fractional Do we display fraction units. Default TRUE.
     * @param int       $precision  How many precision digits we show. Default 2.
     * 
     * @return String   Formated currency string.
     */
    static public function formatMoney($number, $symbol = '', $fractional=TRUE, $precision = 2) {
        $number = trim($number);
             
        if ($fractional) $number = sprintf("%.{$precision}f", $number);
        else $number = sprintf("%.0f", $number);
        
        while(TRUE) { 
            $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number); 
            if ($replaced != $number) $number = $replaced; 
            else break; 
        }
        return $symbol.$number; 
    } 
    
    /**
     * We are checking for $0.00 
     */
    static public function emptyCurrencyValue($price)
    {
        //TODO: Make .00 optional, and as many 0 as we want.
        return preg_match('/^[$|£|€]+0\.00/', $price);
    }
    
    /**
     * @param String $string    Source to truncate.
     * @param int    $max       Max length of $string.
     * @param String $append    String to append when $max reached.
     */
    static public function truncate($string, $max = 30, $append = "...")
    {
        if (strlen($string) <= ($max + strlen($append))) 
        { 
            return $string; 
        } 
        
        $leave = $max - strlen ($append); 
        
        $output = substr_replace($string, $append, $leave); 
        return trim($output);
        //return str_replace(' ...', '...', $output); 
    }
    
    /**
     * 
     */
    static public function cleanFilename($file_name, $lower = TRUE)
    {
        $info = pathinfo($file_name);
        $ext  = $info['extension'];
        $file_name = $info['filename'];
        return self::cleanString($file_name, '_', $lower).".$ext";
    }
    
    /**
     * Cleans a string to be URL/file friendly.
     * @param $text string  the string to be slugified
     * @param $ws sring   the string used to replace white sapces.
     * @return string the URL-friendly string
     */
    static public function cleanString($text, $ws = '-', $lower = FALSE)
    {
        $text=strtr($text,array(
            ','=>'-', '\''=>'-', '"'=>'-', '/'=>'',
            'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i',
            'î'=>'i', 'ï'=>'i', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ü'=>'u',
            'ý'=>'y', 'ÿ'=>'y', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E',
            'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ù'=>'U', 'Ú'=>'U',
            'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y',
        ));
        $text = trim(preg_replace('/\W+/', $ws, $text),$ws);
        
        if($lower)
            $text = strtolower($text);
        
        return $text;
    }
    
    /**
     * 
     */
    static public $ascii_characters = array(
            'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i',
            'î'=>'i', 'ï'=>'i', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ü'=>'u',
            'ý'=>'y', 'ÿ'=>'y', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E',
            'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ù'=>'U', 'Ú'=>'U',
            'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y',
        );
    static public function ascii($text)
    {
        return strtr($text, self::$ascii_characters);
    }

    /**
     * 
     */
    static public function array_to_object($array, &$obj = NULL)
    {
        if(!$obj) $obj = new stdClass();
            
        foreach ($array as $key => $value)
        {
            //TODO: Ensure $key has a valid format!
            
            if (is_array($value))
            {
                $obj->$key = new stdClass();
                self::array_to_object($value, $obj->$key);
            }
            else
            {
                $obj->$key = $value;
            }
        }
        return $obj;
     }
    
    /**
     * 
     */
    static public function buildLink($id, $label, $controller, $action = 'view',$htmlOptions=array())
     {
         $url = Yii::app()->request->getBaseUrl().'/'.Yii::app()->params['bizUnit']."/$controller/$action/id/".$id;
         
         if($label === FALSE)
            return $url;
         
         return CHtml::link($label, $url,$htmlOptions);
     }
     
    /**
     * 
     * @param   string  $source
     * @param   string  $target
     * @param   boolean $extract_filename Defaults to FALSE. Get the filename from the 
     *                                    $source and append to $target.
     * 
     * @return  mixed   FALSE if file does not exist, else the path to the file. 
     */
    static public function downloadImage($source, $target, $extract_filename = FALSE, $use_cached = TRUE)
    {
        //get rid of relative components in the path.
        //$target = realpath($target);
        
        //TODO: This could fail if the path does not exist. What do we do?
        //we can try to create or just return FALSE?!
        //if(!$target)
            //handle not realpath error.
        /*
        echo "--------------------------";
        echo "Source: ".($source).' <<<br/>';
        //echo "Source realpath: ".realpath($source).' <<<br/>';
        echo "Target: ".($target).' <<<br/>';
        echo "Target realpath: ".(realpath($target).' <<<br/>');
        */
        if($extract_filename)
        {
            $path_info = pathinfo($source);
            $file_name = is_string($extract_filename) ? $extract_filename : $path_info['filename'].'.'.$path_info['extension'];
            $target = self::removeTrailingSlash($target, DS).DS.$file_name;    
            //echo "Filename realpath: ".($target).' <<<br/>';
            
        }
        
        if(file_exists($target))
            @unlink($target);
        
        //If the $target file exists, is not corrupt, we could return that.
        //if(file_exists($target) && self::notCorruptImage($target,TRUE,TRUE) && $use_cached)
        //    return $target;
        //exit("Exit: ".$target);
        //exec('curl '.$source.' > '.$target);
        $ch = curl_init($source);
        $fp = fopen($target, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //In case there is a redirect in the url, ie. Facebook imgs.
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        /*
        $current = file_get_contents($source);
        // Append a new person to the file
        $fp = fopen($target, 'wb');
        fclose($fp);
        // Write the contents back to the file
        file_put_contents($target, $current);
*/
        if(file_exists($target))
            return $target;
        else return FALSE;    
    }
    
    /**
     * 
     */
    static public function appendFilenameToPath($source, $target)
    {
        $path_info = pathinfo($source);
        $file_name = $path_info['filename'].'.'.$path_info['extension'];
        return self::removeTrailingSlash($target, DS).DS.$file_name;   
    }
    
    /**
     * Checks for corrupted image files. Optionally, it will remove the 
     * corrupted files.
     * 
     * It should work with both remote and local paths.
     * 
     * @param string    Path to the image file.
     * @param $unlink   If the image is corrupted, removes the file.
     * @param $deep     Performs a more resource intense check.
     */
    static public function isCorruptImage($path, $unlink = FALSE, $deep = FALSE)
    {
        if(!filter_var($path, FILTER_VALIDATE_URL) && (! file_exists($path) || ! filesize($path)))
        {
            if($unlink)
                @unlink($path);
            
            return TRUE;
        }
        
        //This works for remote images.
        if( @getimagesize($path) === FALSE)
        {
            if($unlink)
                @unlink($path);
            
            return TRUE;
        }
         
        if(function_exists('exif_imagetype'))
        {
            if (exif_imagetype($path) === FALSE)
            {
                if($unlink)
                    @unlink($path); // image is corrupted
                    
                return TRUE;
            } else if(!$deep) return FALSE;
        }
        
        
        //TODO: We should check if GD is available. Also, make sure this works.
        if (@imagecreatefromstring(file_get_contents($path)) === FALSE)
        {
            if($unlink)
                @unlink($path); // image is corrupted
            return TRUE;
        }
        
        return FALSE; 
    }
    
    /**
     * @see GHelper::isCorruptImage
     */
    static public function notCorruptImage($path, $unlink = FALSE, $deep = FALSE)
    {
        return ! self::isCorruptImage($path,$unlink, $deep);
        
    }
    
    /**
     * 
     */
    static public function removeTrailingSlash($path, $slash = '/')
    {
        if(substr($path, -1) == $slash)
            return substr($path, 0, -1);
        
        return $path;
        
    }

    /**
     * Work Image Placeholder 
     * 
     * @param string $width     Image width
     * @param string $height    Image height
     * @param bool   $blank     If true, placeholder image will be nothing 
     * @param string $text      Preivew Image Text if applicable, NULL default
     *
     */
    static public $place_holder_url = 'http://placehold.it/';
    static public function getPlaceholderImg($width, $height, $blank=FALSE, $text=NULL)
    {
        $placeholdLink =  'http://placehold.it/'.$width.'x'.$height;
        
        if($blank) 
            return $placeholdLink;
    
        //README: The url intentionally has no "?" sign 
        //go ask placehold.it...
        if(isset($text))
            return $placeholdLink.'&text='.$text;
        else 
            return $placeholdLink.'&text=No Image';
    }
    
    /**
     * 
     */
    static public function resizeImage($resizeTo, $max_size, $imgwidth, $imgheight)
    {
        $resizeTo = 'resizeTo'.ucfirst($resizeTo);
        
        return self::$resizeTo($max_size, $imgwidth, $imgheight);
    }
    
    /**
     * 
     */
    static public function resizeToHeight($height, $iw, $ih) 
    {
        $ratio = $height / $ih;
        $width = $iw * $ratio;
        
        $out = new stdClass();
        $out->w = $width;
        $out->h = $height;
        
        return $out;
    }
    
    /**
     * 
     */
    static public function resizeToWidth($width, $iw, $ih) 
    {
        $ratio  = $width / $iw;
        $height = $ih * $ratio;
        
        $out = new stdClass();
        $out->w = $width;
        $out->h = $height;
        
        return $out;
    }
    
    /**
     * 
     */
    static public function removeFilesFromDir($path, $ext = 'png')
    {
        $path = self::removeTrailingSlash($path, DS).DS;
        
        $files = glob("{$path}*.{$ext}");
        
        foreach($files as $file)
            @unlink($file);            
     }
    
    static public function printDir($path, $url)
    {
        $url = self::removeTrailingSlash($url);
        // open this directory 
        $myDirectory = opendir($path);
        
        // get each entry
        while($entryName = readdir($myDirectory)) {
            $dirArray[] = $entryName;
        }
        
        // close directory
        closedir($myDirectory);
        
        //  count elements in array
        $indexCount = count($dirArray);
        print ("$indexCount files<br>\n");
        
        // sort 'em
        sort($dirArray);
        
        // print 'em
        print("<TABLE border=1 cellpadding=5 cellspacing=0 class=whitelinks>\n");
        print("<TR><TH>Filename</TH><th>Filetype</th><th>Filesize</th></TR>\n");
        // loop through the array of files and print them all
        for($index=0; $index < $indexCount; $index++) {
                if (substr("$dirArray[$index]", 0, 1) != "."){ // don't list hidden files
                print("<TR><TD><a target='_blank' href=\"$url/$dirArray[$index]\">$dirArray[$index]</a></td>");
                print("<td>");
                print(@filetype($dirArray[$index]));
                print("</td>");
                print("<td>");
                print(@filesize($dirArray[$index]));
                print("</td>");
                print("</TR>\n");
            }
        }
        print("</TABLE>\n");
    }
}

class GEc2Relay {
    
    /**
     * 
     */
    static public function forceLogin($user_id)
    {
      $user = User::model()->findByPk($user_id);
      $identity = new UserIdentity($user->username, 'NOT_NEEDED');
      $identity->authenticate(FALSE);
      Yii::app()->user->login($identity);
      return TRUE;
    }
    
    /**
     * 
     */
    static public function setRelayKey($params = array())
    {
      $id = md5(time());
      $params['route'] .= "&key=$id";
      $params['user_id'] = Yii::app()->user->id;
      Yii::app()->cache->set($id, $params, (60 * 120));
      
      return $id;
    }
    
    /**
     * 
     */
    static public function isDeploymentEnvironment(){
        return function_exists('get_cfg_var');
    }
    

    /**
     * 
     */
    static public function getEc2InstanceIp()
    {
      if($_SERVER['SERVER_ADDR'] == '127.0.0.1')
        return $_SERVER['HTTP_HOST'];
    
      $aws_access_key = get_cfg_var('aws.access_key');
      $aws_secret_key = get_cfg_var('aws.secret_key');
    
      exec("bash -l -c \"/opt/aws/bin/ec2-describe-instances --aws-access-key $aws_access_key --aws-secret-key $aws_secret_key | grep elasticbeanstalk-default,DZDB-INT -A2 | grep 10.85.133.13 | cut -f4\"", $result);  
      return $result[0];
    }
}

class GVersion {
    
    /**
     * This relays on having a build-number.txt file on the root level
     * that gets updated with the Bamboo current build number.
     * There needs to be a build command in Bamboo to generate the file.
     */
  static public function get_Version()
  {
    $version = self::get_version_number();
    
    $build_num = self::get_build_number();
    
    return "v{$version[2]}.{$version[1]}.{$build_num}";
  }
  
    static public function get_build_number()
    {
         $path = YiiBase::getPathOfAlias('webroot');
         
        $path = "$path/../build-number.txt";
        if(!is_file($path))
            return '';
        $file = file($path);
        // print_r($file);
        $build_num = $file[2];
        $build_num = explode('=', $build_num);
        $build_num = $build_num[1];
        return $build_num;
    }
    
    static public function get_version_number()
    {
        $path = YiiBase::getPathOfAlias('webroot');
        // $path = "$path/../version.txt";
        
        $path = "$path/../version.txt";
        $file = file($path);
        if(!is_file($path))
          return '';
        
        foreach($file as $i => $line)
            $file[$i] = trim($line);
        
        
        return $file;
    }
}
