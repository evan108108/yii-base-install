<?php

class GlobalUnitTest extends CTestCase
{
    public function testGHelperFormatMoney()
    {
        // formatMoney($number, $symbol = '', $fractional=TRUE, $precision = 2)
        $number = '3333.3333';
        $expect = '$3,333.33';
        $result = GHelper::formatMoney($number, '$', TRUE, 2);
        $this->assertEquals($expect, $result);
        
        $expect = '$3,333.333';
        $result = GHelper::formatMoney($number, '$', TRUE, 3);
        $this->assertEquals($expect, $result);
        
        $expect = '$3,333';
        $result = GHelper::formatMoney($number, '$', FALSE, 3);
        $this->assertEquals($expect, $result);
    }
    
    public function testGHelperEmptyCurrencyValue()
    {
        $empty = (boolean) GHelper::emptyCurrencyValue('$0.00');
        $this->assertTrue($empty);
        
        $empty = (boolean) GHelper::emptyCurrencyValue('$250.00');
        $this->assertFalse($empty);
    }
    
    public function testGHelperTruncate()
    {
        $append = "...";
        $expect = 'Lorem ipsum'.$append;
        $text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent vel rutrum urna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.';
        $out  = GHelper::truncate($text,strlen($expect), $append);
        $this->assertEquals($expect, $out);
        
        $append = " +ReadMore.";
        $expect = 'Lorem ipsum'.$append;
        $text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent vel rutrum urna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.';
        $out  = GHelper::truncate($text, strlen($expect), $append);
        $this->assertEquals($expect, $out);
    }
    public function testGHelperCleanFilename()
    {
        $file_name = "any file 4.jpg";
        $expect = "any_file_4.jpg";
        $out = GHelper::cleanFilename($file_name);
        
        $this->assertEquals($expect, $out);
        
        $file_name = "añy fïle 4.jpg";
        $expect = "any_file_4.jpg";
        $out = GHelper::cleanFilename($file_name);
        
        $this->assertEquals($expect, $out);
        
        $file_name = "Añy fïLe 4.jpg";
        $expect = "any_file_4.jpg";
        $out = GHelper::cleanFilename($file_name);
        
        $this->assertEquals($expect, $out);
    }
    public function testGHelperCleanString()
    {
        // cleanString($text, $ws = '-', $lower = FALSE)
        $text = 'añy Fïle 4';
        $expect = 'any-File-4';
        $out = GHelper::cleanString($text);
        
        $this->assertEquals($expect, $out);
        
        $text = 'añy Fïle 4';
        $expect = 'any_File_4';
        $out = GHelper::cleanString($text, '_');
        
        $this->assertEquals($expect, $out);
        
        $text = 'añy Fïle 4';
        $expect = 'any_file_4';
        $out = GHelper::cleanString($text, '_', TRUE);
        
        $this->assertEquals($expect, $out);
    }
    public function testGHelperAscii()
    {
        $ascii_characters = array(
            'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i',
            'î'=>'i', 'ï'=>'i', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ü'=>'u',
            'ý'=>'y', 'ÿ'=>'y', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E',
            'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ù'=>'U', 'Ú'=>'U',
            'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y'
        );
        
        $text = '';
        $expect = '';        
        foreach($ascii_characters as $a => $c)
        {
            $text   .= $a;
            $expect .= $c;
        }
        $out = GHelper::ascii($text);
        
        $this->assertEquals($expect, $out);
        
    }
    public function testGHelperArrayToObject()
    {
        $array  = array('a'=>1, 'b'=>2, 'c'=>array('c1'=>10));
        
        $expect = new stdClass();
        $expect->a = 1;
        $expect->b = 2;
        $c = new stdClass();
        $c->c1 = 10;
        $expect->c = $c;
        
        $out = new stdClass();
        GHelper::array_to_object($array, $out);
        
        $this->assertEquals($expect, $out);
        
        $out = GHelper::array_to_object($array);
        
        $this->assertEquals($expect, $out);
        
    }
    public function testGHelperBuildLink()
    {
        Yii::app()->params['bizUnit'] = 'UNIT';
        $base_url = Yii::app()->request->getBaseUrl();
        $expect = '<a href="'.$base_url.'/UNIT/controller/view/id/1">Label</a>';
        $out = GHelper::buildLink(1, 'Label', 'controller');
        $this->assertEquals($expect, $out);
        
    }
    
    public function testGHelperDownloadImage()
    {
        
    }
    public function testGHelperAppendFilenameToPath()
    {
        $source = 'image.jpg';
        $target = '/usr/path/images';
        $expect = $target.'/'.$source;
        $out = GHelper::appendFilenameToPath($source, $target);
        
        $this->assertEquals($expect, $out);
        
        $source = 'image.jpg';
        $target = '/usr/path/images/';
        $expect = $target.$source;
        $out = GHelper::appendFilenameToPath($source, $target);
        
        $this->assertEquals($expect, $out);
    }
    public function testGHelperRemoveTrailingSlash()
    {
        $expect = '/usr/path';
        $path   = $expect.'/';
        $out = GHelper::removeTrailingSlash($path);
        $this->assertEquals($expect, $out);
        
        $expect = '/usr/path';
        $out = GHelper::removeTrailingSlash($expect);
        $this->assertEquals($expect, $out);
        
    }
    public function testGHelperGetPlaceholderImg()
    {
        // getPlaceholderImg($width, $height, $blank=FALSE, $text=NULL)
        
        $width  = 230;
        $height = 230;
        $expect = GHelper::$place_holder_url.$width.'x'.$height.'&text=No Image';
        $out = GHelper::getPlaceholderImg($width, $height);
        
        $this->assertEquals($expect, $out);
        
        $expect = GHelper::$place_holder_url.$width.'x'.$height;
        $out = GHelper::getPlaceholderImg($width, $height, TRUE);
        
        $this->assertEquals($expect, $out);
        
        $text = 'text';
        $expect = GHelper::$place_holder_url.$width.'x'.$height.'&text='.$text;
        $out = GHelper::getPlaceholderImg($width, $height, FALSE, $text);
        
        $this->assertEquals($expect, $out);
        
        $text = 'text';
        $expect = GHelper::$place_holder_url.$width.'x'.$height;
        $out = GHelper::getPlaceholderImg($width, $height, TRUE, $text);
        
        $this->assertEquals($expect, $out);
        
        
    }

    public function testGHelperResizeImage()
    {
        // resizeImage($resizeTo, $max_size, $imgwidth, $imgheight)
        $resizeTo = 'width';
        $max_size = 400;
        $imgwidth = 600;
        $imgheight = 900;
        
        $expect = new stdClass();
        $expect->w = $max_size;
        $expect->h = $imgheight * ($max_size/$imgwidth);//h * (max/w) => 900 * (400/600)
        
        $out = GHelper::resizeImage($resizeTo, $max_size, $imgwidth, $imgheight);
        
        $this->assertEquals($expect, $out);
        
        $resizeTo = 'height';
        $expect = new stdClass();
        $expect->h = $max_size;
        $expect->w = $imgwidth * ($max_size/$imgheight);//w * (max/h)
        
        $out = GHelper::resizeImage($resizeTo, $max_size, $imgwidth, $imgheight);
        
        $this->assertEquals($expect, $out);
    }
    
    public function testGHelperTo_camel_case()
    {
        // to_camel_case
        $expect = 'camelCase';
        $word = "camel_case";
        $out = GHelper::to_camel_case($word);
        
        $this->assertEquals($expect, $out);
        
        $expect = 'CamelCase';
        $word = "camel_case";
        $out = GHelper::to_camel_case($word, TRUE);
        
        $this->assertEquals($expect, $out);
    }
    
    public function testGHelperFrom_camel_case()
    {
        // from_camel_case
        $expect = 'camel_case';
        $word = "CamelCase";
        $out = GHelper::from_camel_case($word);
        
        $this->assertEquals($expect, $out);
    }
    
    public function testGHelperIs_ajax()
    {
        // is_ajax
        // GHelper::is_ajax();
    }
    
    public function testGHelperDepluralize()
    {
        $expect = 'car';
        $word = 'cars';
        $out = GHelper::depluralize($word);
        
        $this->assertEquals($expect, $out);
        
        $expect = 'galaxy';
        $word = 'galaxies';
        $out = GHelper::depluralize($word);
        
        $this->assertEquals($expect, $out);
    }
    
    public function testGHelperRemoveFilesFromDir()
    {
        // GHelper::removeFilesFromDir($path);
    }
    
    
    public function testGDateHelperDateToWords()
    {
        // $time = 
        // GDateHelper::dateToWords($time);
    }
    
    public function testGAuthHelperOperationFilterArray()
    {
        // GAuthHelper::operation_filter_array($columns, $items, $operation)
    }
    
    public function testGAuthHelperCheckAllAccess()
    {
        // GAuthHelper::checkAllAccess();
    }
    
    public function testGAuthHelperGenerateSalt()
    {
        $expect = 9;
        $out = GAuthHelper::generateSalt();
        $this->assertEquals($expect, strlen($out));
        
        $expect = 29;
        $out = GAuthHelper::generateSalt(29);
        $this->assertEquals($expect, strlen($out));
    }
    
    public function testGArHelperCloneModel()
    {
        // GArHelper::cloneModel($clone_id, $model);
    }
    
    public function testGGridHelperFormatDate()
    {
        // formatDate($date, $format = "m-d-y")
        // July 1, 2000 Saturday
        $date = mktime(0, 0, 0, 7, 1, 2000);
        $expect = '07-01-00';
        $out = GGridHelper::formatDate($date);
        
        $this->assertEquals($expect, $out);
        
        $date = '1 July 2000';
        $expect = '07-01-00';
        $out = GGridHelper::formatDate($date);
        
        $this->assertEquals($expect, $out);
        
        $date = '1 July 2000';
        $expect = '07.01.00';
        $out = GGridHelper::formatDate($date, "m.d.y");
        
        $this->assertEquals($expect, $out);
        
    }
    
    public function testGArrayHelperArray_to_csv()
    {
        // array_to_csv($array, $header_row = true, $col_sep = ",", $row_sep = "\n", $qut = '"')
        $expected = "\"age\",\"name\",\"lastname\"\n\"23\",\"pepe\",\"rone\"\n\"32\",\"pipo\",\"rana\"";
        $array    = array();
        $array[]  = array('age'=>23, 'name'=>'pepe', 'lastname'=>'rone');
        $array[]  = array('age'=>32, 'name'=>'pipo', 'lastname'=>'rana');
        $out = GArrayHelper::array_to_csv($array);
        
        $this->assertEquals($expected, $out);
        
        $expected = "\"23\",\"pepe\",\"rone\"\n\"32\",\"pipo\",\"rana\"";
        $array    = array();
        $array[]  = array('age'=>23, 'name'=>'pepe', 'lastname'=>'rone');
        $array[]  = array('age'=>32, 'name'=>'pipo', 'lastname'=>'rana');
        $out = GArrayHelper::array_to_csv($array, FALSE);
        
        $this->assertEquals($expected, $out);
        
        $expected = "\"23\":\"pepe\":\"rone\"\n\"32\":\"pipo\":\"rana\"";
        $array    = array();
        $array[]  = array('age'=>23, 'name'=>'pepe', 'lastname'=>'rone');
        $array[]  = array('age'=>32, 'name'=>'pipo', 'lastname'=>'rana');
        $out = GArrayHelper::array_to_csv($array, FALSE, ':');
        
        $this->assertEquals($expected, $out);
        
        $expected = "\"23\":\"pepe\":\"rone\";\"32\":\"pipo\":\"rana\"";
        $array    = array();
        $array[]  = array('age'=>23, 'name'=>'pepe', 'lastname'=>'rone');
        $array[]  = array('age'=>32, 'name'=>'pipo', 'lastname'=>'rana');
        $out = GArrayHelper::array_to_csv($array, FALSE, ':', ';');
        
        $this->assertEquals($expected, $out);
        
        $expected = "'23':'pepe':'rone';'32':'pipo':'rana'";
        $array    = array();
        $array[]  = array('age'=>23, 'name'=>'pepe', 'lastname'=>'rone');
        $array[]  = array('age'=>32, 'name'=>'pipo', 'lastname'=>'rana');
        $out = GArrayHelper::array_to_csv($array, FALSE, ':', ';', "'");
        
        $this->assertEquals($expected, $out);
    }
}
