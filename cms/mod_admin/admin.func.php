<?php
//Помощники
function edit($params){
	if(!is_array($params)) {
		$params=array($params);	
	}
		
	if(!isset($_SESSION['admin'])) {
		return ""; //Проверка на права администратора
	}
		

	print '<a href="/admin/edit/'.$params[0]->table.'/'.$params[0]->id.'" target="_blank" ><img style="border:none;" src="/cms/internal/gfx/edit.png"></a>';
}

function add($params){
	if(!is_array($params)) {
		$params=array($params);	
	}
		
	if(!isset($_SESSION['admin'])) {
		return ""; //Проверка на права администратора
	}
	$params_string='';
	

	foreach($params as $key=>$value){
		if(!is_numeric($key)) {
			if ($params_string=='') {
				$params_string='?';
			} else {
				$params_string='&';
			}
			$params_string.= $key.'='.$value;
		}	
	}
	
	print '<a href="/admin/edit/'.$params[0].'/add'.$params_string.'" target="_blank" ><img style="border:none;" src="/cms/internal/gfx/add.png"></a>';
}

function admin_list()
{
	$result=mysql_query('select * from '.mysql_real_escape_string(url(3)));
	$data=array();
	while ($line=mysql_fetch_array($result)) {
		$data[]=$line;
	}
	d()->objectrow = $data;
	print d()->admin_objectlist();
}

//	Основная функция редактирования, которая получает данные, выводит форму, обрабатывает действия, перезагружает страницу
function admin_edit()
{
	action('admin_save_data');
	$rows=array();
	$fields=d()->admin_get_fields();
	if (url(4)!='add') {
		//TODO: db()->sql();
		if (!($line=mysql_fetch_array(mysql_query("select * from `".mysql_real_escape_string(url(3))."` where `id` = '".mysql_real_escape_string(url(4))."'")))) {
			$line=array();
		}
	} else {
		$line=array();
	}

	foreach ($fields as $field) {
		d()->title=$field['title'];
		d()->name='data['.$field['name'].']';
		if (isset($line[$field['name']])) {
			d()->value=$line[$field['name']];
		}
		$rows[]=d()->call('admin_'.$field['type']);
	}	
	
	d()->tabletitle = 'Редактирование элемента';
	if(url(4)=='add') {
		d()->tabletitle = 'Добавление нового элемента';
	}
	d()->tablerow = $rows;
	print d()->admin_edit_table(); //Эту функцию можно переопределять
}

function admin_save_data($params)
{
	//TODO: Новое API для добавление новых элементов в базу данных;  
	$elemid=url(4);
	if($elemid=='add') {
		//Добавление элементов - делаем малой кровью - предварительно создаём строку в таблице
		$result=mysql_query("insert into `".mysql_real_escape_string(url(3))."`  () values ()");
		$elemid=mysql_insert_id();
	}
	//FIXME: костыль
	if($params['url']=='') {
		$params['url']='page'.$elemid;
	}



	if(substr($params['url'],0,1)=='/') {
		$params['url']=substr($params['url'],1);
	}
	
	$params['url']=str_replace('/','_',$params['url']);
	
//	$data=$this->adminFields();
    $result_str="update `".url(3)."` set  ";
    $i=0;
	foreach($params as $key=>$value) {
		$i++;
        $result_str.=" `" . $key . "`= '".mysql_real_escape_string($value)."' ";
        if ($i<count($params)) $result_str.=', ';
    }
    
    $result_str.=" where `id`=".mysql_real_escape_string($elemid);
    mysql_query($result_str);
	if($_POST['admin_command_redirect_close']=='yes') {
		return  "<script> window.opener.document.location.href=window.opener.document.location.href;window.open('','_self','');window.close();</script>";
	}else{
		header('Location: /admin/list/'.url(3));
		exit();
	}

}


//Функция возвращает массив возможных полей
function admin_get_fields()
{
	$data=array();
	doit()->load_and_parse_ini_file('app/fields/'.url(3).'.ini');
	$rows = doit()->admin['fields'];
	foreach ($rows as $key=>$value) {
		$data[]=array('name'=>$value[1],'type'=>$value[0],'title'=>$value[2]);
	}
	return $data;
}

//Открытие шаблона либо вывод формы авторизации
function admin()
{

	//TODO: переписать на валидаторах
	if(isset($_POST['action']) && $_POST['action']=='admin_login'){
		if(d()->admin['editor']['login'] == $_POST['login'] && d()->admin['editor']['password'] == md5($_POST['password'])) {
			$_SESSION['admin']=$_POST['login'];
			header('Location: /');
			exit();
		}
		d()->notice='Неверный логин или пароль';
	}

	if(!isset($_SESSION['admin'])) {
		return d()->admin_authorisation();
	}
	return d()->admin_tpl();
}
