<?php
/**
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


/**
 * This is the shortcut to CHtml::encode
 */
function h($text,$limit=0)
{
	if($limit && strlen($text)>$limit && ($pos=strpos($text,' ',$limit))!==false)
		$text=substr($text,0,$pos);
	return htmlspecialchars($text,ENT_QUOTES,Yii::app()->charset);
}

function truncate($string, $max = 13, $rep = '...') 
{ 
    if (strlen($string) <= ($max + strlen($rep))) 
    { 
        return $string; 
    } 
    $leave = $max - strlen ($rep); 
    $pre = substr_replace($string, $rep, $leave); 
    return str_replace(' ...', '...', $pre); 
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
 * Makes a string to be URL friendly.
 * @param string the string to be slugified
 * @return string the URL-friendly string
 */
function slugify($text)
{
	$text=strtr($text,array(
		','=>'-', '\''=>'-', '"'=>'-', '/'=>'',
		'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i',
		'î'=>'i', 'ï'=>'i', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ü'=>'u',
		'ý'=>'y', 'ÿ'=>'y', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E',
		'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ù'=>'U', 'Ú'=>'U',
		'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y',
	));
	return trim(strtolower(preg_replace('/\W+/', '-', $text)),'-');
}

function ascii($text)
{
	return strtr($text,array(
		'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i',
		'î'=>'i', 'ï'=>'i', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ü'=>'u',
		'ý'=>'y', 'ÿ'=>'y', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E',
		'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ù'=>'U', 'Ú'=>'U',
		'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y',
	));
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
	
	return $output;
}



function generateSalt($saltLength = 9)
{
    return substr(md5(uniqid(rand(), true)), 0, $saltLength);
}

function depluralize($word){
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

?>

