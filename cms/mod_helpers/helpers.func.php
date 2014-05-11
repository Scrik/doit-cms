<?php

//helper input для отображения текстового поля
function input ($params=array())
{
	if(!isset($params['type'])) {
		$params['type'] = 'text';
	}
	print '<input ' . inputparams($params) . ' >';
}

function inputparams($params=array())
{

	$cfo = d()->current_form_object;
	$attr='';
	if(isset($params['style'])) {
		$attr .= ' style="'.$params['style'].'" ';
	}
	
	//TODO: проверка на класс error
	if(isset($params['class'])) {
		$attr .= ' class="'.$params['class'].'" ';
	}
	
	if(isset($params['type'])) {
		$attr .= ' type="'.$params['type'].'" ';
	}
	
	if(isset($params['title'])) {
		$attr .= ' title="'.$params['title'].'" ';
	}
	
	if(isset($params['placeholder'])) {
		$attr .= ' placeholder="'.$params['placeholder'].'" ';
	}
	
	if(isset($params['name'])) {
		$attr .= ' name="'.$params['name'].'" ';
		
	} else {
		if(d()->current_form_simple_names){
			$attr .= ' name="'.$params[0].'" ';
		}else{
			$attr .= ' name="'.$cfo.'['.$params[0].']" ';
		}
		
	}
//	print '<!-- ';
//	var_dump($_POST['document[document_type]']);
//	print ' -->';
	
	if(isset($params['checked'])) {
		$attr .= ' checked="'.$params['checked'].'" ';
	} else {
		
		if ($params['type']=='radio'){
			if(isset($_POST['_action'])) {
				//Был POST-запрос

				if(d()->current_form_simple_names){
					if(isset($params['name'])){

						if( isset($params['value']) && $_POST[$params['name']]==$params['value']) {
							//Совпало значение
							$attr .= ' checked="checked" ';
						}
					}else{
						
						if( isset($params['value']) && $_POST[$params[0]]==$params['value']) {
							//Совпало значение
							$attr .= ' checked="checked" ';
						}
					}
				}else{
					if(isset($params['name'])){

						if( isset($params['value']) && $_POST[$params['name']]==$params['value']) {
							//Совпало значение
							$attr .= ' checked="checked" ';
						}
					}else{
						
						if( isset($params['value']) && $_POST[$cfo][$params[0]]==$params['value']) {
							//Совпало значение
							$attr .= ' checked="checked" ';
						}
					}
				}
			} else {
				if(isset($params['value']) && count(d()->{$cfo})>0 && d()->{$cfo}->{$params[0]}==$params['value']){
					$attr .= ' checked="checked" ';
				}else{
					if(isset($params['value']) && (count(d()->{$cfo})==0 || d()->{$cfo}->{$params[0]}=='') && isset($params['default_checked'])){
						$attr .= ' checked="checked" ';	
					
					}
				}

			}
		}
	}
	
	if(isset($params['id'])) {
		$attr .= ' id="'.$params['id'].'" ';
	}else{
		$attr .= ' id="'.$cfo.'_'.$params[0].'" ';		
	}
	
	if(isset($params['attr'])) {
		$attr .= ' '.$params['attr'].' ';
	}
	
	if(isset($params['value'])) {
		$attr .= ' value="'.$params['value'].'" ';
	}else{
		if(isset($_POST['_action'])) {				
			if(isset($params['name'])) {
				$attr .= ' value= "'.  htmlspecialchars( $_POST[$params['name']]) .'" ';
			} else {
				if(d()->current_form_simple_names){
					$attr .= ' value= "'.  htmlspecialchars($_POST[$params[0]]) .'" ';
				}else{
					$attr .= ' value= "'.  htmlspecialchars($_POST[$cfo][$params[0]]) .'" ';
				}
			}
		} else {
			$attr .= ' value= "'.  htmlspecialchars(d()->{$cfo}->{$params[0]}) .'" ';
		}
	}
	
	return ' ' . $attr . ' ';
}


//helper textarea для отображения текстового поля
function textarea ($params=array())
{

	$cfo = d()->current_form_object;
	$attr='';
	if(isset($params['style'])) {
		$attr .= ' style="'.$params['style'].'" ';
	}
	
	//TODO: проверка на класс error
	if(isset($params['class'])) {
		$attr .= ' class="'.$params['class'].'" ';
	}

	if(isset($params['placeholder'])) {
		$attr .= ' placeholder="'.$params['placeholder'].'" ';
	}

	if(isset($params['rows'])) {
		$attr .= ' rows="'.$params['rows'].'" ';
	}
	
	if(isset($params['id'])) {
		$attr .= ' id="'.$params['id'].'" ';
	}else{
		$attr .= ' id="'.$cfo.'_'.$params[0].'" ';		
	}
	
	if(isset($params['attr'])) {
		$attr .= ' '.$params['attr'].' ';
	}
	
	
	if(isset($params['name'])) {
		$attr .= ' name="'.$params['name'].'" ';
		
	} else {
		if(d()->current_form_simple_names){
			$attr .= ' name="'.$params[0].'" ';
		}else{
			$attr .= ' name="'.$cfo.'['.$params[0].']" ';
		}
		
	}
	
	$value="";
	if(isset($params['value'])) {
		$value = htmlspecialchars( $params['value']);
	}else{
		if(isset($_POST['_action'])) {					
			if(isset($params['name'])) {
				$value =  htmlspecialchars( $_POST[$params['name']]) ;
			} else {
				if(d()->current_form_simple_names){
					$value =   htmlspecialchars($_POST[$params[0]]) ;
				}else{
					$value =   htmlspecialchars($_POST[$cfo][$params[0]]) ;
				}
			}
		} else {
			$value =  htmlspecialchars(d()->{$cfo}->{$params[0]}) ;
		}
	}
	
	return '<textarea ' . $attr . '>' . $value . '</textarea>';
}

function form ($params=array()) 
{
	$attr="";
	$additions = '';
	d()->current_form_simple_names = false;
	if (isset($params[1])) {
		d()->current_form_object = $params[1];
	} else {
		d()->current_form_object = 'data';
	}
	
	if(isset($params['action'])) {
		$attr .= ' action="'.$params['action'].'" ';
	}
	
	if(isset($params['ajax']) && $params['ajax']==true) {
		$attr .= ' onsubmit="window._current_form=$(this);$.ajax({\'type\':\'post\',\'url\': $(this).attr(\'action\')?$(this).attr(\'action\'):document.location.href ,\'data\':$(this).serialize(),\'success\':function(recieved_data){eval(recieved_data)}});return false;" ';
		
	}
	
	if(isset($params['simple_names']) && $params['simple_names']==true) {
		d()->current_form_simple_names = true;
		$additions .= ' <input type="hidden" name="_is_simple_names" value="1" >';
	}	
	if(isset($params['iframe']) && $params['iframe']==true) {
		$iframe_id = "hidden_".rand(111,999);
		$attr .= ' target="'.$iframe_id.'" ';
		
		$additions .= '<iframe style="display:none" id="'.$iframe_id.'" name="'.$iframe_id.'"></iframe>';
		$additions .= ' <input type="hidden" name="_is_iframe" value="1" >';
		
	}
		
	if(isset($params['global']) && $params['global']==true) {
		
		$additions .= ' <input type="hidden" name="_global" value="1" >';
		if(!isset($_SESSION['_form_sign_key']) || $_SESSION['_form_sign_key']==''){
			
			$key = sha1 (uniqid().mt_rand().microtime().'salt'.$_SERVER["REMOTE_PORT"].mt_rand());
			$_SESSION['_form_sign_key'] = $key;
			
			 
		}else{
			$key = $_SESSION['_form_sign_key'];
		}
		$run_before='';
		if(isset($params['run_before']) && $params['run_before']!='') {
			$run_before = md5($params['run_before']);
		}
		
		$sign = sha1('salt_sign'.md5($key).md5(d()->current_form_object).md5($params[0]).$run_before);
		$additions .= ' <input type="hidden" name="_run_before" value="'.$params['run_before'].'" >';
		$additions .= ' <input type="hidden" name="_global_sign" value="'.$sign.'" >';
		
	}
	
	if(isset($params['style'])) {
		$attr .= ' style="'.$params['style'].'" ';
	}
	
	if(isset($params['target'])) {
		$attr .= ' target="'.$params['target'].'" ';
	}
	
	if(isset($params['class'])) {
		$attr .= ' class="'.$params['class'].'" ';
	}
	
	if(isset($params['enctype'])) {
		$attr .= ' enctype="'.$params['enctype'].'" ';
	}else{
		if(isset($params['upload']) && $params['upload']==true) {
			$attr .= ' enctype="multipart/form-data" ';
		}
	}
	if(isset($params['id'])) {
		$attr .= ' id="'.$params['id'].'" ';
	}	
	
	
	$result =  "<form method='POST' ".$attr.">";
	$result .= ' <input type="hidden" name="_element" value="' . d()->current_form_object .'" >';
	$result .= ' <input type="hidden" name="_action" value="'.$params[0].'" >';
	$result .= $additions;
	return $result;
	
}


function tag ($params=array())
{

	$attr='';

	if(isset($params['attr'])) {
		$attr .= ' '.$params['attr'].' ';
	}
	
	foreach($params as $key=>$value){
		if(!is_numeric($key) && ($key!='attr')){
			$attr .= ' '.$key.'="'.$value.'" ';
		}
	}
	
	if(isset($params[1])) {
		return '<'.$params[0].  $attr . '>'.$params[1].'</'.$params[0].'>';
	}else{
		return '<'.$params[0].  $attr . '>';
	}
	
}

function notice($params=array())
{
	//Если пусто, не выводить
	if(d()->notice=='' || count(d()->notice)==0) {
		return '';
	}
	$str='';
	
	if($params[0]=='bootstrap'){
		//Пользуемся оттсестированной функцией tag()
		if(!isset($params['style'])){
			$params['style']='';
			$params['class']='alert alert-error';
		}
		$params[0]='ul';
		
		$str .= tag($params)	;
		
		foreach(d()->notice as $value){
		$str .='<li style="margin-left:20px;">'.$value.'</li>';
	}
	
	}else{
		//Пользуемся оттсестированной функцией tag()
		if(!isset($params['style'])){
			$params['style']='padding:15px;padding-left:25px;border:1px solid red;';
		}
		$params[0]='ul';
		
		$str .= tag($params);
		
		foreach(d()->notice as $value){
		$str .='<li>'.$value.'</li>';
	}
	
	
	}
	
	
	
	
	
	$str .= '</ul>';


	return $str;
}

function jquery_notice(){
	if(isset(d()->datapool['inputs_with_errors']) && count(d()->datapool['inputs_with_errors'])!=0 && isset($_POST['_element'])){
	$noticed_inputs = array_values(d()->datapool['inputs_with_errors']);
	$response.=  "$('.error').removeClass('error');\n";
	foreach($noticed_inputs as $key=>$input){
		if(isset($_POST['_is_simple_names']) && $_POST['_is_simple_names']=='1'){
			$element_name = "'*[name=\"".$input."\"]'";
		}else{
			$element_name = "'*[name=\"".$_POST['_element'].'['.$input.']'."\"]'";	
		}
		
		$response .=  '$('.$element_name.').parent().parent().addClass("error");'."\n";
	}
	print "\n<script>".'$(function(){'.$response.'});'."</script>\n";
}
}

function flash($params=array())
{
	
	//Если пусто, не выводить
	if(!isset($_SESSION['flash']) || $_SESSION['flash']=='' ){
		return '';
	}
	$str='';
	
	if($params[0]=='bootstrap'){
		//Пользуемся оттсестированной функцией tag()
		if(!isset($params['style'])){
			$params['style']='';
			$params['class']='alert alert-info';
		}
		
		$params[0]='div';
		$str .= tag($params);
		$str .=$_SESSION['flash'];
	} else{
		$params[0]='div';
		$str .= tag($params);
		if(!isset($params['style'])){
			$params['style']='';
		}
		$str .=$_SESSION['flash'];
	}
	$str .= '</div>';
	unset($_SESSION['flash']);
	return $str;
}

function link_to($params)
{
	
	$href = path_to(array($params[0]));
	
	$attr='';
	
	if(isset($params['style'])) {
		$attr .= ' style="'.$params['style'].'" ';
	}
	
	//TODO: проверка на класс error
	if(isset($params['class'])) {
		$attr .= ' class="'.$params['class'].'" ';
	}
	
	if(isset($params['id'])) {
		$attr .= ' id="'.$params['id'].'" ';
	}
	
	if(isset($params['attr'])) {
		$attr .= ' '.$params['attr'].' ';
	}
	
	return '<a href= "' . $href . '" ' . $attr . '>'.$params[1].'</a>';
	
}



//DEPRECATED
function path_to($params)
{
	$result='/';
	foreach (d()->urls as $rules) {
		foreach($rules as $rule) {
			if ($rule == $params[0]) {
				$result = $rules[0];
			}
		}
	}
	if(substr($result,-6)=='/index') {
		$result = substr($result,0,-5);
	}
	return $result;
	
}

function preview($adress,$param1=false,$param2=false )
{
	if(is_array($adress)){
		if(isset($adress['height']) || isset($adress[2])){
			if(isset($adress['width'])){
				$width=$adress['width'];
			}else{
				$width=$adress[1];
			}
			if(isset($adress['height'])){
				$height=$adress['height'];
			}else{
				$height=$adress[2];
			}
			$adress = $adress[0];
		} else {
			$num=1;
			if(isset($adress[1])){
				$num=$adress[1];
			}
			$adress = $adress[0];
			if($adress==''){
				return '';
			}
			$ext=strtolower(strrchr($adress, '.'));
			if(!in_array($ext,array('.gif','.jpg','.png','.jpeg'))){
				return '';
			}
			return substr($adress, 0, strrpos($adress, "/") + 1) . ".thumbs/preview".$num."_" . substr($adress, strrpos($adress, "/") + 1);
		}
		//Массив значений
	}else{
		if($param2===false){
			//обычная превью
			$num=1;
			if($param1!==false){
				$num=$param1;
			}
			if($adress==''){
				return '';
			}
			$ext=strtolower(strrchr($adress, '.'));
			if(!in_array($ext,array('.gif','.jpg','.png','.jpeg'))){
				return '';
			}
			return substr($adress, 0, strrpos($adress, "/") + 1) . ".thumbs/preview".$num."_" . substr($adress, strrpos($adress, "/") + 1);
		}else{
			//необычная превью без массива значений
			$width=$param1;
			$height=$param2;
		}
	}
	if($adress==''){
		return '';
	}
	$ext=strtolower(strrchr($adress, '.'));
	if(!in_array($ext,array('.gif','.jpg','.png','.jpeg'))){
		return '';
	}
	$preview_adress = substr($adress, 0, strrpos($adress, "/") + 1) . ".thumbs/preview".$width.'x'.$height."_" . substr($adress, strrpos($adress, "/") + 1);
	
	//генерирование изображения при его отсуствии
	if(!file_exists($_SERVER['DOCUMENT_ROOT'].$preview_adress)){
		
		//Создание превью
		
		$filename = $_SERVER['DOCUMENT_ROOT'].$adress;
		$dest = $_SERVER['DOCUMENT_ROOT'].$preview_adress;
		
		$dest_folder = $_SERVER['DOCUMENT_ROOT'].substr($adress, 0, strrpos($adress, "/") + 1) . ".thumbs";
		if(!file_exists($dest_folder)){
			mkdir($dest_folder);
			chmod($dest_folder, 0777);
		}
		
		$format = strtolower(substr(strrchr($filename,"."),1));
		switch($format) {
			case 'gif' :
				$type ="gif";
				$img = ImageCreateFromGif($filename);
				break;
			case 'png' :
				$type ="png";
				$img = ImageCreateFromPng($filename);
				imageSaveAlpha($img, true);
				break;
			case 'jpg' :
				$type ="jpg";
				$img = ImageCreateFromJpeg($filename);
				break;
			case 'jpeg' :
				$type ="jpg";
				$img = ImageCreateFromJpeg($filename);
				break;
			default :
				return false;
				break;
		}

		list($org_width, $org_height) = getimagesize($filename);
		$xoffset = 0;
		$yoffset = 0;
	
		if (strpos($height, 'in') !== false AND strpos($width, 'in') !== false) {
			$height_temp = substr($height, 2);
			$width_temp = substr($width, 2);
			$h_index = ($org_height / $height_temp);
			$w_index = ($org_width / $width_temp);
			
			$index = $h_index;
			if ($h_index < $w_index) {
				$index = $w_index;
			}
			$width = round($org_width / $index);
			$height = round($org_height / $index);
		} else {
			if (strpos($height, 'in' === 0)) {
				$height = substr($height, 2);
			} elseif (strpos($width, 'in' === 0)) {
				$width = substr($width, 2);
			}
		}
		if($height=='auto'){
			$height=round($width* ($org_height/$org_width));
		}else{
			if($width=='auto'){
				$width=round($height* ($org_width/$org_height));
			}
		}
		if ($width / $height <   $org_width / $org_height) {
			$dy=0;
			$xtmp = $org_width;
			$org_width= ($width*$org_height)/$height;

			$dx = 0.5*(  	$xtmp - $org_width);
			$xoffset=$dx;
			$yoffset=$dy;
		} else {
			$dx=0;
			$ytmp = $org_height;
			$org_height= ($height*$org_width)/$width;

			$dy = 0.5*($ytmp - $org_height);
			$xoffset=$dx;
			$yoffset=$dy;
		}

		$img_n=imagecreatetruecolor ($width, $height);
		imagealphablending($img_n, false);
		imagesavealpha($img_n, true);
		$black = imagecolorallocate($img_n, 0, 0, 0);
		$black2 = imagecolorallocate($img, 0, 0, 0);
		imageSaveAlpha($img, true);
		
		imagecopyresampled($img_n, $img, 0, 0, $xoffset, $yoffset, $width, $height, $org_width, $org_height);
  
		if($type=="gif") {
			imagegif($img_n, $dest);
		} elseif($type=="jpg") {
			imagejpeg($img_n, $dest, 100);
		} elseif($type=="png") {
			imagepng($img_n, $dest);
		} elseif($type=="bmp") {
			imagewbmp($img_n, $dest);
		}
		chmod($dest, 0777);
	}
	return $preview_adress;
}



function h($html)
{
	return htmlspecialchars($html);
}

function hnl2br($html)
{
	return nl2br(htmlspecialchars($html));
}

function et($string)
{
	$string=str_replace(array('"',"'",'\\',' ','.','*','/','`',')'),array('','','','','','','','',''),$string);
	return $string;
}
function e($string)
{
	return d()->db->quote($string);
}
//получение множественного числа
function to_o($plural)
{
	return ActiveRecord::plural_to_one($plural);
}
//Получение единственного числа
function to_p($one)
{
	return ActiveRecord::one_to_plural($one);
}

function to_camel($string)
{
 
	return strtoupper(substr($string,0,1)).substr($string,1);
}

function t($text)
{
	if(!empty(d()->translate[$text])){
		return d()->translate[$text];
	}
	return $text;
}


/**
 * Склонение числительных
 *
 * <code>
 * declOfNum(4, array('помидор', 'помидора', 'помидоров')
 * </code>
 *
 * @param $number
 * @param $titles
 * @return string
 */
function declOfNum($number, $words=false,$word2=false,$word3=false)
{
	if(is_array($number)){
		$arr = $number;
		$number = $arr[0];
		if(isset($arr[1])){
			$words = $arr[1];
		}
		if(isset($arr[2])){
			$word2 = $arr[2];
		}
		if(isset($arr[3])){
			$word3 = $arr[3];
		}


	}
	$words_result = $words;	
	$checks = array (2, 0, 1, 1, 1, 2);
	if(is_string($words) && is_string($word2) && is_string($word3)){
		$words_result=array($words,$word2,$word3);
	}
	if(is_string($words) && $word2==false){
		//Режим магии
		$word1 = $words;
		$word2=$words;
		$word3=$words; 
		if(preg_match('#[a-z]#i',$word1)){
			if($number==1){
				return $word1;
			}else{
				return to_p($word1);
			}
		}elseif(mb_substr($word1,-2,null,"UTF-8")=='ий'){
			$word2 = mb_substr($word1,0,-2,"UTF-8").'ия';
			$word3 = mb_substr($word1,0,-2,"UTF-8").'иев';
		}elseif(mb_substr($word1,-3,null,"UTF-8")=='еец'){

			$word2 = mb_substr($word1,0,-3,"UTF-8").'ейца';
			$word3 = mb_substr($word1,0,-3,"UTF-8").'йцев';

		}elseif(mb_substr($word1,-3,null,"UTF-8")=='нец'){

			$word2 = mb_substr($word1,0,-3,"UTF-8").'нца';
			$word3 = mb_substr($word1,0,-3,"UTF-8").'нцов';

		}elseif(mb_substr($word1,-2,null,"UTF-8")=='ец'){

			$word2 = mb_substr($word1,0,-2,"UTF-8").'ца';
			$word3 = mb_substr($word1,0,-2,"UTF-8").'цев';

		}elseif(mb_substr($word1,-1,null,"UTF-8")=='р'){

			$word2 = mb_substr($word1,0,-1,"UTF-8").'ра';
			$word3 = mb_substr($word1,0,-1,"UTF-8").'ров';

		}elseif(mb_substr($word1,-1,null,"UTF-8")=='о'){

			$word2 = mb_substr($word1,0,-1,"UTF-8").'а';
			$word3 = mb_substr($word1,0,-1,"UTF-8").'ов';

		}elseif(mb_substr($word1,-1,null,"UTF-8")=='ц'){

			$word2 = mb_substr($word1,0,-1,"UTF-8").'ца';
			$word3 = mb_substr($word1,0,-1,"UTF-8").'цев';

		}elseif(in_array(mb_substr($word1,-1,null,"UTF-8"),array('б','в','г', 'д', 'ж', 'з', 'к', 'л', 'м', 'н', 'п', 'р', 'с', 'т', 'ф', 'х',   'ч', 'ш', 'щ' ))){

			$word2 = $word1.'а';
			$word3 = $word1.'ов';

		}elseif(mb_substr($word1,-2,null,"UTF-8")=='сь'){

			$word2 = mb_substr($word1,0,-2,"UTF-8").'си';
			$word3 = mb_substr($word1,0,-2,"UTF-8").'сей';

		}elseif(mb_substr($word1,-2,null,"UTF-8")=='ть'){

			$word2 = mb_substr($word1,0,-2,"UTF-8").'ти';
			$word3 = mb_substr($word1,0,-2,"UTF-8").'тей';

		}elseif(mb_substr($word1,-1,null,"UTF-8")=='ь'){

			$word2 = mb_substr($word1,0,-1,"UTF-8").'я';
			$word3 = mb_substr($word1,0,-1,"UTF-8").'ей';

		}elseif(mb_substr($word1,-1,null,"UTF-8")=='ы'){

			$word2 = mb_substr($word1,0,-1,"UTF-8").'';
			$word3 = mb_substr($word1,0,-1,"UTF-8").'';

		}elseif(mb_substr($word1,-2,null,"UTF-8")=='ия'){

			$word2 = mb_substr($word1,0,-2,"UTF-8").'ии';
			$word3 = mb_substr($word1,0,-2,"UTF-8").'ий';

		}elseif(mb_substr($word1,-1,null,"UTF-8")=='а'){

			$word2 = mb_substr($word1,0,-1,"UTF-8").'ы';
			$word3 = mb_substr($word1,0,-1,"UTF-8").'';

		}elseif(mb_substr($word1,-1,null,"UTF-8")=='и'){

			$word2 = mb_substr($word1,0,-1,"UTF-8").'ов';
			$word3 = mb_substr($word1,0,-1,"UTF-8").'ов';

		}
		$words_result=array($word1,$word2,$word3);

	}
	return $words_result[($number%100>4 && $number%100<20)? 2 : $checks[min($number%10, 5)]];
}


function userdate($date)
{
	return d()->Date($date)->user();
}
function userdate_mini($date)
{
	return d()->Date($date)->ru_user_mini();
}

function ml_userdate($date)
{
	return d()->Date($date)->ml_user();
}

/**
 * Функция, проверяющая валидность адреса электронной почты. Используется в валидаторах.
 *
 * @param $value Адрес электроннйо почты
 * @param $params Массив параметров валидатора (в данном случае пустой)
 * @return bool false, если адрес некорректен.
 */
function valid_email($value,$params)
{
	$value=strtolower($value);
	return ( 1 == preg_match(
		'/^[-a-z0-9\!\#\$\%\&\'\*\+\/\=\?\^\_\`\{\|\}\~]+(?:\.[-a-z0-9!' .
			'\#\$\%\&\'\*\+\/\=\?\^\_\`{|}~]+)*@(?:[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])?\.)*'.
			'(?:aero|arpa|asia|biz|cat|com|coop|edu|gov|info|int|jobs|mil|mobi|museum|name|net|org|pro|tel|travel|[a-z][a-z])$/' ,$value));
}

function must_be_empty($value,$params)
{
	return $value=='';
}

function antispam($value,$params)
{
	$msg=strtolower($value);
	if ( strpos( $msg,'<a')!==false  ||  strpos( $msg,'лbных клиентов')!==false ||  strpos( $msg,'лbных клиентoв')!==false ||  strpos( $msg,'пoтенциалbных клиентов')!==false  ||  strpos( $msg,'базу данных потенциальных клиентов')!==false ||  strpos( $msg,'база данных потенциальных клиентов')!==false  ||  strpos( $msg,'база дaнныx пoтенциальных клиентов')!==false  ||  strpos( $msg,'базу дaнныx пoтенциальных клиентов')!==false  ||  strpos( $msg,'потeнциaлbных клиентoв')!==false  ||  strpos( $msg,'клиeнтская бaзa')!==false ||  strpos( $msg,'клиeнтская база')!==false || strpos( $msg,'клиeнтcкие базы')!==false || strpos( $msg,'ские бaзы')!==false || strpos( $msg,'ские базы')!==false ||strpos( $msg,'cкие бaзы')!==false  ||strpos( $msg,'cкие базы')!==false ||strpos( $msg,'скиe бaзы')!==false ||strpos( $msg,'скиe базы')!==false ||strpos( $msg,'cкиe бaзы')!==false ||strpos( $msg,'cкиe базы')!==false ||   strpos( $msg,'клиeнтские бaзы')!==false  ||  strpos( $msg,'клиeнтские базы')!==false || strpos( $msg,'ентсkи')!==false || strpos( $msg,'prodawez')!==false || strpos( $msg,'kлиент')!==false ||  strpos( $msg,'[url')!==false || strpos( $msg,'http:/')!==false || strpos( $msg,'sex')!==false || strpos( $msg,'poker')!==false || strpos($msg,'casino')!==false    )  {
		return false;
	}
	return true;
}

function br($str=false)
{
	if($str===false){
		print '<br />';
	}else{
		print nl2br($str);
	}
}

function container($param)
{
	$result='';
	$rows=explode("\n",$param);
	foreach($rows as $row){
		$row = trim($row);
		if($row=='[container]' || $row == ''){
			continue;
		}
		$row = explode("?",$row);
		if(count($row)!=2){
			continue;
		}
		if(d()->container[$row[0]]){
			d()->plugin_id = $row[1];
			$result .=  d()->call($row[0]);
		}
	}
	return $result;
}