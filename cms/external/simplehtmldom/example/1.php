<?php
// example of how to use basic selector to retrieve HTML contents
include('../simple_html_dom.php');
 
// get DOM from URL or file
$html = str_get_html(
"
<fragment main>
������� ���������
	<fragment row>
	��������
	</fragment>
�����
</fragment>
");;
 
// find all image
foreach($html->find('fragment') as $e)
    echo $e->innertext . '<br>';

?>