<?php
/*

	DoIt! CMS and VarVar framework
	Copyright (C) 2011 Fakhrutdinov Damir (aka Ainu)

	*      This program is free software; you can redistribute it and/or modify
	*      it under the terms of the GNU General Public License as published by
	*      the Free Software Foundation; either version 2 of the License, or
	*      (at your option) any later version.
	*
	*      This program is distributed in the hope that it will be useful,
	*      but WITHOUT ANY WARRANTY; without even the implied warranty of
	*      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	*      GNU General Public License for more details.
	*
	*      You should have received a copy of the GNU General Public License
	*      along with this program; if not, write to the Free Software
	*      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
	*      MA 02110-1301, USA.
 *
На будущее - итератор объектов и объект-массив
	class ResultIterator extends ArrayIterator {
	private $count=3;
		function key()
		{
			return  "!!";
		}
		function append ($str)
		{
			print "#".$str;
		}
		function offsetGet ($str)
		{
			print "#".$str;
		}
		
		function count()
		{
			return 3;
		}
		function next()
		{
		print "2";
		return "2";
		}
		function valid()
		{
			print "1";
			$this->count--;
			if($this->count > 0){
				return true;
			}
		}
	}
	class ResultElement extends ArrayObject {
		private $linktoobject;
		function __construct(&$object)
		{
			$this->linktoobject = &$object;
		}
		public function getIterator()
		{
			return new ResultIterator(    );
		}
		function offsetGet($index) {		
			return $this->linktoobject->shift_to($index);
		}
		function count()
		{
			return $this->linktoobject->size();
		}
	}
 
*/
	
//Функция определения множественной формы написания слова на основе написания единственной.
	
/*

find_by_name_or_author
find_by_name_and_author
search_by_author
search_by_title

search_in_text_or_title

new
create(attributes)
find(id_or_array)
destroy(id_or_array)
destroy_all
delete(id_or_array)
delete_all
update(ids, updates)
update_all(updates)
exists?
where (:conditions)
having (:conditions)
select // select author,comments - соединения разных форм и моделей
group
order
limit
offset
joins
includes (:include)
lock
readonly
from
first
last
all
preload
eager_load
average
minimum
maximum
sum
calculate

*/	
	
	
//Класс Active Record, обеспечивающий простую добычу данных
abstract class ar
{
	public $edit_button;
	public $_options;
	public $_data;
	private $_used_tree_branches;
	private $_shift = 0;
	private $_known_columns=array();
	private $_count_rows = 0;
	private $_future_data=array();
	//TODO: Выполняет limit 1 SQL запрос
	function first()
	{
		return $this;
	}
	

	static function plural_to_one ($string)
	{
		$_p_to_o=array(
			'men' => 'man',
			'women' =>	'woman',
			'mice' =>'mouse',
			'teeth' =>	'tooth',
			'feet' => 'foot',
			'children' =>'child',
			'oxen' => 'ox',
			'geese' =>	'goose',
			'sheep' =>	'sheep',
			'deer' => 'deer',
			'swine' => 'swine',
			'news' => 'news'
		);
		$_arr_p=array(
			'/(^.*)xes$/'=>'$1x',
			'/(^.*)ches$/'=>'$1ch',
			'/(^.*)sses$/'=>'$1ss',
			'/(^.*)quies$/'=>'$1quy',
			'/(^.*)ies$/'=>'$1y',
			'/(^.*)lves$/'=>'$1lf',
			'/(^.*)rves$/'=>'$1rf',
			'/(^.*)ves$/'=>'$1fe',
			'/(^.*)men$/'=>'$1man',
			'/(^.+)people$/'=>'$1person',
			'/(^.*)ses$/'=>'$1sis',
			'/(^.*)ta$/'=>'$1tum',
			'/(^.*)ia$/'=>'$1ium',
			'/(^.*)children$/'=>'$1child',
			'/(^.*)s$/'=>'$1'
		);
		
		//Слова - исключения
		if(isset($_p_to_o[$string])) {
			return $_p_to_o[$string];
		}

		//TODO: (.*s) -> $1;
		foreach($_arr_p as $key=>$value) {
			$new=preg_replace($key,$value,$string);
			if($new != $string) {
				break;
			}
		}
		return $new;
	}
	
	static function one_to_plural ($string)
	{
		$_o_to_p=array(
			'man' => 'men',
			'pike' => 'pike',
			'woman' => 'women',
			'mouse' => 'mice',
			'tooth' => 'teeth',
			'foot' => 'feet',
			'child' => 'children',
			'ox' => 'oxen',
			'goose' => 'geese',
			'sheep' => 'sheep',
			'deer' => 'deer',
			'swine' => 'swine',
			'news' => 'news'
		);
		$_arr_p=array(
			'/(^.*)x$/'=>'$1xes',
			'/(^.*)ch$/'=>'$1ches',
			'/(^.*)ss$/'=>'$1sses',
			'/(^.*)quy$/'=>'$1quies',
			'/(^.*[bcdfghklmnpqrstvxz])y$/'=>'$1ies',
			'/(^.*)fe$/'=>'$1ves',
			'/(^.*)lf$/'=>'$1lves',
			'/(^.*)rf$/'=>'$1rves',
			'/(^.+)person$/'=>'$1people',
			'/(^.*)man$/'=>'$1men',
			'/(^.*)sis$/'=>'$1ses',
			'/(^.*)tum$/'=>'$1ta',
			'/(^.*)ium$/'=>'$1ia',
			'/(^.*)child$/'=>'$1children',
			'/(^.*)$/'=>'$1s'
		);
		
		//Слова - исключения
		if(isset($_o_to_p[$string])) {
			return $_o_to_p[$string];
		}

		//TODO: (.*s) -> $1;
		foreach($_arr_p as $key=>$value) {
			$new=preg_replace($key,$value,$string);
			if($new != $string) {
				break;
			}
		}
		return $new;
	}
	
	public function __toString()
	{
		return $this->show();
	}
	
	function __construct($_options=array())
	{
		//Создание реестра с данными по каждой таблице: имя, поля.
		/*
		//Реестр отключён - слишком много времени на запросы
		//TODO: кеширование содержимого таблиц + миграции
		if(!isset(d()->datapool['tables_information'])) {
			d()->datapool['tables_information']=array();
			
			$result=mysql_query('SHOW TABLES');
			while($line=mysql_fetch_array($result)){
				$result_fields=mysql_query('SHOW COLUMNS FROM '.$line[0]);
				d()->datapool['tables_information'][$line[0]]=array();
				while ($line_fields=mysql_fetch_array($result_fields)) {
					d()->datapool['tables_information'][$line[0]][]=$line_fields[0];
				}
			}
				//d()->datapool['tables_information'][]$line[0]
			//SHOW COLUMNS FROM
		
		}
		*/
		//Опции по умолчанию и переменные
		
		
		
		$this->_options=$_options;
		
		
		
		if(isset($this->_options['data'])) { 
			$this->_options['queryready']=true;
			$this->_data=$this->_options['data'];
		} else {
			$this->_options['queryready']=false;
			$this->_data=array();	
		}
		
	//	$this->_options['queryready']=false;  //Сбрасывается при смене параметров запроса, при true запросы не выполняются
		
		$this->_options['onerow']=true;
		
		//поле, по которому получаем данные. Для текстовых страниц это URL, для товаров это id, для пользователей это username или login и так далее.
		//в подавляющем случае это автоинкрементное числовое поле id
		if(!isset($this->_options['idfield'])) {
			$this->_options['idfield']='id';
		}
		
		if(!isset($this->_options['namefield'])) {
			$this->_options['namefield']='url';
		}
		
		if(!isset($this->_options['condition'])) {
			$this->_options['condition']=array();
		}
		
		if(!isset($this->_options['select'])) {
			$this->_options['select']=' * ';
		}
		
		if(!isset($this->_options['limit'])) {
			$this->_options['limit']='';
		}

		if(!isset($this->_options['order_by'])) {
			$this->_options['order_by']=' ORDER BY `sort` ';
		}
		
		if(!isset($this->_options['new'])) {
			$this->_options['new']=false;
		}
		
		if(!isset($this->_options['tree'])) {
			$this->_options['tree']=false;
		}
		
		if(!isset($this->_options['calc_rows'])) {
			$this->_options['calc_rows']=false;
		}
		
		//TODO: брать таблицу из родительского объекта
		if(!isset($this->_options['table'])) {
			if(strtolower(get_class($this))=='ar') {
				$this->_options['table']='options';
			} else {
				$this->_options['table']=self::one_to_plural(strtolower(get_class($this)));
			}
		}
		
		if(!isset($this->_options['plural_to_one'])) {
			$this->_options['plural_to_one']=self::plural_to_one($this->_options['table']);
		}
	}
	//альтернативная функция бстрого получения данных
	public function getRow($id)
	{
		if ($_line = mysql_fetch_array(mysql_query("select * from `".$this->_options['table']."` where `".$this->_options['idfield']."`='". mysql_real_escape_string ($id)."' limit 1"))) {
			return $_line;
		} else {
			return false;
		}
	}
	//Функция find указывает на то, что необходимо искать нечто по полю ID
	public function find($id)
	{
		if (is_numeric($id)) {
			$this->_options['id']=(int)$id;
			$this->_options['queryready']=false;
			$id = 1 * $id;
			$this->_options['condition'] = array ('( id = '.(int)$id.' )');
		} else {
			$this->_options['queryready']=false;
			$name =  mysql_real_escape_string($id);
			$this->_options['condition']=   array ("( `".$this->_options['namefield']."` = '". $name ."' )");
		}
		return $this;
	}
	
	public function find_by($by,$what)
	{
		$this->_options['queryready']=false;
		$this->_options['condition'] = array("( `".mysql_real_escape_string($by)."` = '".mysql_real_escape_string($what)."' )");
		return $this;
	}
	
	function __call($name,$arguments)
	{
		if(substr($name,0,8)=='find_by_') {
			$by=substr($name,8);
			$this->_options['queryready']=false;
			$this->_options['condition'] = array("( `".$by."` = '".mysql_real_escape_string($arguments[0])."' )");
		}
		return $this;
	}
	
	public function sql($query)
	{
		$this->_options['queryready']=true;
		$this->_data = array();	 
		$_result=mysql_query($query);
		while ($line=mysql_fetch_array($_result,MYSQL_ASSOC)) {
			$this->_data[]=$line;
		}
		return $this;
	}
	
	public function where()
	{
		//TODO: переписать на preg_replace с исполльзованием последнего параметра
		$this->_options['queryready']=false;
		$args = func_get_args();
		$_condition=$args[0];
		$_conditions=explode('?',' '.$_condition.' ');
		$_condition='';
		for ($i=1; $i<= count($_conditions)-1; $i++) {
			$_condition .= $_conditions[$i-1]. " '".mysql_real_escape_string($args[$i])."' "  ;
		}
		$_condition .= $_conditions[$i-1];
		$this->_options['condition'][] = '('.$_condition.')';
		return $this;
	}
	
	public function calc_rows()
	{
		$this->_options['calc_rows']=true;
		return $this;
	}

	
	public function limit($limit)
	{
		$this->_options['queryready']=false;
		$limitstr=strtolower(trim($limit));
		if($limitstr!='') {
			if(substr($limitstr,0,5)=='limit') {
				$this->_options['limit'] = ' '.mysql_real_escape_string($limit).' ';
			} else {
				$this->_options['limit'] = ' LIMIT '.mysql_real_escape_string($limit).' ';
			}
		} else {
			$this->_options['limit'] = '';
		}
		return $this;
	}	

	public function order_by($order_by)
	{
		$this->_options['queryready']=false;
		if(trim($order_by)!='') {
			$this->_options['order_by'] = ' ORDER BY '.mysql_real_escape_string($order_by).' ';
		} else {
			$this->_options['order_by'] = '';
		}
		return $this;
	}
	
	public function select($select)
	{
		$this->_options['queryready']=false;
		if(trim($select)!='') {
			$this->_options['select'] = $select;
		} else {
			$this->_options['select'] = ' * ';
		}
		return $this;
	}
	
	//Общее количество строк в таблице
	function all_rows_count()
	{
		$_count_result = mysql_query("SELECT COUNT(id)FROM ".$this->_options['table']);
		$_countrows_line = mysql_fetch_array($_count_result);
		return $_countrows_line[0];
	}
	
	//Количество строк в найденном запросе
	//TODO: что по поводу LIMIT?
	function found_rows()
	{
		if($this->_options['calc_rows']) {
			return $this->_count_rows;
		} else {
			if ($this->_options['queryready']==false) {
				$this->fetch_data_now();
			}
			return count($this->_data);
		}
		
	}
	
	function fetch_data_now()
	{
		$this->_options['queryready']=true;
		$this->_data = array();
		$_query_string='SELECT ';
		if($this->_options['calc_rows']) {
			$_query_string .= ' SQL_CALC_FOUND_ROWS ';
		}
		$_query_string .= ' ' . $this->_options['select'] . ' FROM `'.$this->_options['table'].'` ';
		if(count($this->_options['condition'])>0) {
			$_condition = implode(' AND ',$this->_options['condition']);
			$_query_string .= 'WHERE '.$_condition;
		}
		if($this->_options['order_by']!='') {
			$_query_string .=  $this->_options['order_by'];
		}
		
		if($this->_options['limit']!='') {
			$_query_string .=  $this->_options['limit'];
		}
	 
		$_result=mysql_query($_query_string);
		if($this->_options['calc_rows']) {
			$_count_result = mysql_query('SELECT FOUND_ROWS()');
			$_countrows_line = mysql_fetch_array($_count_result);
			$this->_count_rows = $_countrows_line[0];
		}
		
		
		while ($line=mysql_fetch_array($_result,MYSQL_ASSOC)) {
			$this->_data[]=$line;
		}
		
		 
	}
	//CRUD
	public function delete()
	{
		if ($this->_options['queryready']==false) {
				$this->fetch_data_now();
		}
			
		if(isset($this->_data[0])){
			$_query_string='delete from `'.$this->_options['table'] . "` where `id` = '".$this->_data[0]['id']."'";
			mysql_query($_query_string);
		}
		return $this;
	}
	public function save()  //CrUd - Create & Update
	{
		if($this->_options['new']==true) {
			//Тут идёт вставка
			if(count($this->_future_data)>0) {
				$fields=array();
				$values=array();
				foreach($this->_future_data as $key => $value) {
					$fields[]=" `$key` ";
					$values[]=" '".mysql_real_escape_string($value)."' ";
				}
				$fields_string=implode (',',$fields);
				$values_string=implode (',',$values);
				$_query_string='insert into `'.$this->_options['table'].'` ('.$fields_string.') values ('.$values_string.')';
				$not_reqursy=0;
				while(!mysql_query($_query_string) && 1054 == mysql_errno()) {
					$error_string=mysql_error();
					$not_reqursy++;
					if($not_reqursy>30) {
						print "Произошла ошибка рекурсии. Пожалуйста, добавьте поля вручную - у меня не получилось. Спасибо.";
						exit();
					}
					foreach($this->_future_data as $key=>$value)
					{
						if(strpos($error_string , "'".$key."'")!==false){
							$result = mysql_query("ALTER TABLE `".$this->_options['table']."` ADD COLUMN `$key` text NULL, DEFAULT CHARACTER SET=utf8" );
						}
					}
				}
				$this->_future_data=array();

			}
		} else {
			if ($this->_options['queryready']==false) {
				$this->fetch_data_now();
			}
			//Тут проверка на апдейт
			if(isset($this->_data[0]) && (count($this->_future_data)>0)){
				$attributes=array();
				foreach($this->_future_data as $key => $value) {
					$attributes[]=" `$key` = '".mysql_real_escape_string($value)."' ";
				}
				$attribute_string=implode (',',$attributes);
				$_query_string='update `'.$this->_options['table'].'` set '.$attribute_string." where `id` = '".$this->_data[0]['id']."'";
				mysql_query($_query_string) or print mysql_errno();
				$this->_future_data=array();
			}
		}
		return $this;
	}
	public function one()
	{
		if ($this->_options['queryready']==false) {
			$this->fetch_data_now();
		}
		return $this;
	}
	public function all()
	{
		if ($this->_options['queryready']==false) {
			$this->fetch_data_now();
		}

		$_tmparr=array();
		$_class_name = get_class($this);
		foreach($this->_data as $element){
			//TODO: Вот тут особенно важно возвращать правильное имя, хотя имеено тут всё верно
			$_tmparr[] = new  $_class_name (array('table'=>$this->_options['table'], 'data'=>array( $element ) ));
		}
		  
		return $_tmparr;
		
		//Старый подход
		foreach($this->_data as $_key => $_value) {
			return $this->_data;
		}
	}
	//Получение шаблона и вывод
	public function show()
	{
		if($this->template!=''){
			d()->this = $this;
			return d()->call($this->template);
		}
		return '';
	}
	

	
	public function columns($tablename='')
	{
		if($tablename=='') {
			$tablename = $this->_options['table'];
		}
		if(!isset (d()->datapool['columns_registry'])) {
			d()->datapool['columns_registry']=array();
		}
		if(isset (d()->datapool['columns_registry'][$tablename])) {
			return d()->datapool['columns_registry'][$tablename];
		}
		if ($tablename=='template') {
			//template - ключевое частозапрашиваемое поле, такой таблицы не существует
			d()->datapool['columns_registry'][$tablename]=false;
			return d()->datapool['columns_registry'][$tablename];
		}
		
		$_res=mysql_query('SHOW COLUMNS FROM `'.$tablename.'`');
		
		if ($_res===false) {
			//Если таблицы не существует
			d()->datapool['columns_registry'][$tablename]=false;
			return d()->datapool['columns_registry'][$tablename];
		}
		
		$result_array=array();
		while ($_tmpline = mysql_fetch_array($_res)) {
			$result_array[] = $_tmpline[0];
		}
		d()->datapool['columns_registry'][$tablename] = $result_array;
		return d()->datapool['columns_registry'][$tablename];
	}

	
	//Рекурсивная функция для быстрой сортировки дерева
	private function get_subtree($id)
	{
		$_tmparr=array();
		$_class_name = get_class($this);
		foreach($this->_data as $element){
 			if(isset($element[$this->_options['plural_to_one']."_id"]) && $element[$this->_options['plural_to_one']."_id"] == $id) {
				if(empty($this->_used_tree_branches[$element['id']])){
					$this->_used_tree_branches[$element['id']]=true;
					$_tmparr[] = new  $_class_name (array('table'=>$this->_options['table'], 'data'=>array( $element ),'tree'=>$this->get_subtree($element['id'])));
				}
 			}
		}
		return $_tmparr;
	}
	
	public function tree($root=false)
	{
		//Если ленивый запрос ещё не произошёл - самое время.
		if ($this->_options['queryready']==false) {
			$this->fetch_data_now();
		}
		
		//Если при создании объекта заранее указали его дерево - возвращаем его
		if ($this->_options['tree']!==false) {
			return $this->_options['tree'];
		}		
		$_tmparr=array();
		$_class_name = get_class($this);
		if (is_object($root)) {
			$root=$root->id;
		}
		$this->_used_tree_branches=array();
		
		if($root === false) {
			foreach($this->_data as $element){
				//Если данный элемент корневой, родительских элементов нет, поле element_id пустое
				if(!isset($element[$this->_options['plural_to_one']."_id"])) {
					//В опцию tree записываем рекурсивно полученные дочерние элементы
					if(empty($this->_used_tree_branches[$element['id']])){
						$this->_used_tree_branches[$element['id']]=true;
						$_tmparr[] = new  $_class_name (array('table'=>$this->_options['table'], 'data'=>array( $element ),'tree'=>$this->get_subtree($element['id'])));
					}	
				}
			}
		} else {
		 
			foreach($this->_data as $element){
				//Если данный элемент корневой, родительских элементов нет, поле element_id == root
				if(isset($element[$this->_options['plural_to_one']."_id"]) && ($element[$this->_options['plural_to_one']."_id"]== $root )) {
					//В опцию tree записываем рекурсивно полученные дочерние элементы
					if(empty($this->_used_tree_branches[$element['id']])){
						$this->_used_tree_branches[$element['id']]=true;
						$_tmparr[] = new  $_class_name (array('table'=>$this->_options['table'], 'data'=>array( $element ),'tree'=>$this->get_subtree($element['id'])));
					}	
				}
			}
		}
		
		$this->_used_tree_branches=array();
		return $_tmparr;
	}
	
	public function is_empty()
	{
		if ($this->_options['queryready']==false) {
			$this->fetch_data_now();
		}
		if(isset($this->_data[0])) {
			return false;
		}
		return true;
	}
	//Возвращает размер массива
	public function size()
	{
		if ($this->_options['queryready']==false) {
			$this->fetch_data_now();
		}
		return count($this->_data);
	}
	
	public function table()
	{
		return $this->_options['table'];
	}
	public function override()
	{
		return '';
	}
	public function expand()
	{
		if ($this->_options['queryready']==false) {
			$this->fetch_data_now();
		}
		if(isset($this->_data[0])) {
			foreach( $this->_data[0] as $_key=>$_value) {
				d()->{$_key} = $_value;
			}
			return true;
		}
		return false;
	}

	public function shift_to($_shift)
	{
		$this->_shift=$_shift;
		return $this;
	}
	
	public function expand_all_to($varname)
	{
		d()->{$varname} = $this->all;
		return $this;
	}
	
	public function expand_to($varname)
	{
		d()->{$varname} = $this->one;
		return $this;
	}
	
	function to_array()
	{
		if ($this->_options['queryready']==false) {
			$this->fetch_data_now();
		}
		return $this->_data;
	}
	
	function __set($name,$value)
	{	
		if(method_exists($this,'set_'.$name)) {
			$this->{'set_'.$name}($value);
		} else {
			$this->set_variable_value($name,$value);
		}
	}

	function set($name,$value)
	{
		//Если создаём, то проверяем, был ли уже new
		//Если редактируем, то предварительно надо сбрасывать в ноль
		$this->_future_data[$name]=$value;
	}
	
	function set_variable_value($name,$value)
	{
		//Если создаём, то проверяем, был ли уже new
		//Если редактируем, то предварительно надо сбрасывать в ноль
		$this->_future_data[$name]=$value;
	}
	
	function get_variable_value($name)
	{
		return $this->get($name);
	}
	
	function __get($name)
	{
	
		//Item.something
		if (method_exists($this,$name)) {
			return $this->{$name}();
		}
		//Item.new
		if ($name=='new') { // Crud - create
			$this->_options['new']=true;
			$this->_future_data = array();
			return $this;
		}
		//Item.expand_to_page
		//DEPRECATED: в дальнейшем будет удалена
		if (substr($name,0,10)=='expand_to_') {
			return $this->expand_to(substr($name,10));
		}
		
		//Item.expand_all_to_pages
		//DEPRECATED: в дальнейшем будет удалена
		if (substr($name,0,14)=='expand_all_to_') {
			return $this->expand_all_to(substr($name,14));
		}
 		
		return $this->get($name);
		
			
	}
	
	/* 
	Получение переменных напрямую
	В случае необходимости получения в модели непосредственно значения переменной
	Например
	class User extends ar {
		function title()
		{
			return '<b>'.$this->get('title').'</b>';
		}
	}
	print d()->User->find(1)->title;
	*/
	public function get($name)
	{

		if ($this->_options['queryready']==false) {
				$this->fetch_data_now();
		}
		
		if (isset($this->_data[0])) {
			//Item.title         //Получение одного свойства
			if (isset($this->_data[0][$name])) {
				return $this->_data[0][$name];
			}


			//Item.user          //Получение связанного объекта
			if (isset($this->_data[0][$name.'_id'])) {
				$_tmp =  activerecord_factory_from_table(ar::one_to_plural($name));
				return $_tmp->find($this->_data[0][$name.'_id']);
			} else {
				//Проверка на факт наличия столбца $name.'_id'
				$columns = $this->columns;
				if($columns !== false) {
					$columns = array_flip($columns);
					if (isset($columns[$name.'_id'])) {
						$_tmp = activerecord_factory_from_table(ar::one_to_plural($name));
						return $_tmp->find($this->_data[0][$name.'_id']);
					}
				}	
			}
			
			//Item.users
			//1. Поиск альтернативных подходящих столбцов
			$foundedfield = false;
			//ищем поле item_id в таблице users

			//$_res=mysql_query('SHOW COLUMNS FROM `'.$name.'`');

            //Ищем таблицу с названием $name (например, users)
			$columns = $this->columns($name);
            
			if ($columns===false && $name=='template') {
				return ''; //template - ключевое частозапрашиваемое поле, данный оборот ускорит работу
			}
			
			if ($columns===false) {
				 
                $_tmpael  = activerecord_factory_from_table($this->_options["table"]);
                return $_tmpael->find_by('url',$name);
			}
        
			foreach($columns as $key=>$value) {
				if ($value == $this->_options['plural_to_one']."_id") {
					$_tmpael  = activerecord_factory_from_table($name);

					return $_tmpael->where($this->_options['plural_to_one']."_id = ?",$this->_data[0]['id']);
				}
			}			
			return '';
		} else {
			//Item.ramambaharum_mambu_rum
			return '';
		}
	}
}

function activerecord_factory($_modelname)
{
	if(is_array($_modelname)) {
		$_modelname=$_modelname[0];
	}
	return new $_modelname ();
	//return new ar(array('table'=>ar::one_to_plural(strtolower($_modelname))));
}
function activerecord_factory_from_table($_tablename)
{
	if(is_array($_tablename)) {
		$_tablename=$_tablename[0];
	}
	
	$_modelname=ar::plural_to_one(strtolower($_tablename));
	$_first_letter=strtoupper(substr($_modelname,0,1));
	$_modelname = $_first_letter.substr($_modelname,1);

	return new $_modelname ();
	//return new ar(array('table'=>ar::one_to_plural(strtolower($_modelname))));
}
