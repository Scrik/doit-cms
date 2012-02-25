<?php
/*
	Модуль для работы с текстовыми страницами, для вывода меню, выода подстраниц
*/
class PagesController
{
	function show($url)
	{
		d()->page = d()->Page->find_by_url($url);
		if (d()->page->is_empty) {
			//d()->prepare_content('content',"Страница не существует".d()->add(array('pages','url'=>url(1))));
			d()->message="Страница не существует".d()->add(array('pages','url'=>url(1)));
			return d()->error('404');
		}
		
		print d()->view();
	}
}