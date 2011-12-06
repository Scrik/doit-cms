������� �������� Active Record
==============================

����� ������������ - ������ ��������:

	d()->Page->find(33)->user;

����� �������� ����������� � ���������� ��� ��������� ������:

	d()->Comment(23)->page;

	//������
	$comment = d()->Comment->find(23);
	$current_page_id = $comment->page_id;
	$page=d()->Page->find($current_page_id);

	//�������� ������
	$page->title;

������ ������������ �� �������� � ID 45 � ���������� ��� ��������� ������:

	d()->Page->find(45)->comments;

	//������
	$page=d()->Page->find(45);
	$page_id = $page->id;
	$comments=d()->Comment->where('page_id = ?',$page_id);


����� �������� �������� �� ������� ��������:

	d()->Page->find(45)->pages;
	//SELECT * FROM `pages` where `page_id` = 45


	d()->Page->find(45)->comments;
	//SELECT * from comments where `page_id` = 45


	d()->User->find(23)->pages
	select * from pages where user_id=23

	d()->Page->find_by_url('index');
	d()->Page->find_by_title('������');

	d()->Page->find_by('url','������');

	d()->Page->where("title = '".e($title)."' or text='".e($text).'");

	d()->Page->where("title = ? or text= ? ", $title, $text);


������������ ��������� (���� ������ GET[post]):

	$obj= d()->Page->where("title = ? or text= ? ", $title, $text);
	if(!empty($_GET['page']){
	    $obj->limit($_GET['page']*10.", 10")
	}


��������� ���������� ����������.

	d()->zagolovok = 'sadasd'

���-������:

	$obj = d()->User;
	$obj = new Page();

�������� ������� � ����������:

	$table='User';
	d($table)->find(45);

������� ��� �������:

	d()->Catalog
	d()->News
	d()->Option