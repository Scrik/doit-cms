<?php
function stuff_for_test()
{
	return 'result';
}

class CoreTest extends Test
{
	function test_coverage(){
		$this->assertCoverage('cms/cms.php');
		//����������
		d()->test_var='test value';
		$this->assertEquals(d()->test_var,'test value');
		$this->assertEquals(doit()->test_var,'test value');
		
		
		 
		
		$this->assertEquals(d()->url(),url());
		
		$this->assertEquals(d()->stuff_for_test(), 'result');
		$this->assertEquals(d()->render('stuff_for_test'), 'result');
		d()->stuff_for_test = 'another result';
		$this->assertEquals(d()->render('stuff_for_test'), 'another result');
		
		$dummy = new PDODummy();
		$this->assertEquals($dummy, $dummy->someUnusualFunc());
		
		
		
	}

}
 
?>