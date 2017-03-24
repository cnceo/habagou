<?php
namespace bjphp\vendor\excel;
require_once bjpathJoin(CLASS_ROOT , '/bjphp/vendor/excel/Classes/PHPExcel.php');

class Excel
{
	private $row_index;
	public function __construct()
	{
		$this->row_index = explode(' ','A B C D E F G H I J K L M N O P Q R S T U V W X Y Z');
	}

	/*
	$field:上传的表单字段 <input type="file" name="$field"......
	$fn:读到一行数据时，回调的函数，原型是：
	public function fn($row)
	{
		bjmeta('mymeta')->add($row);
	}
	*/
	//从文件导入
	public function importFromFile($file,$use_head,$fn)
	{
		$objPHPExcel = \PHPExcel_IOFactory::load($file);

		$objWorksheet = $objPHPExcel->getActiveSheet();
		$highestRow = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();
		$highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
		$heads=[];
		if( $use_head )
		{
			if( $highestRow > 0 ){
				for ($col = 0; $col < $highestColumnIndex; $col++) {
					$cell_value = (string)$objWorksheet->getCellByColumnAndRow($col, 1)->getValue();
					$heads[] = $cell_value;
					
				}
			}
		}
		
		for ($row = $use_head ? 2 : 0; $row <= $highestRow; $row++) {
			$arr = [];
			for ($col = 0; $col < $highestColumnIndex; $col++) {
				$cell_value = (string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
				
				$arr[ $use_head ? $heads[$col] : $col ] = $cell_value;
			}
			
			$fn($arr);
			unset($arr);
		} 
	}

	//从表单导入
	public function import($field,$use_head,$fn)
	{
		if( empty($_FILES[$field]["tmp_name"]) ) bjerror('未上传excel文件');
		$file_uploader = bjcreate('bjphp.vendor.file.Upload');
		$ret = $file_uploader->SaveFile($field);
		$filename = $ret['_newname'];
		$this->ImportFromFile( $filename,$use_head,$fn );
		@unlink($filename);//fix me later : 确保有异常时也能删除文件
	}

	//导出
	/*
		prop: ["Creator"=>'',"LastModifiedBy"=>'',"Title"=>'',"Subject"=>'',"Description"=>'',"Keywords"=>'',"Category"=>'']
		data:[[1,2,3],[4,5,6],...]
	*/
	public function export($filename='export.xls',$prop=[],$head=[],$data=[])
	{
		$objPHPExcel = new \PHPExcel();
		$excelProps = $objPHPExcel->getProperties();
		$prop_key=[];
		foreach($prop_key as $pk)
		{
			if( isset($prop[$pk]) ) $excelProps->{'set'.$pk}($prop[$pk]);
		}

		$sheet = $objPHPExcel->setActiveSheetIndex(0);

		$head_count = count($head);

		//设置表头
		if( $head_count > 0 )
		{
			for($i=0;$i<$head_count;$i++)
			{
				$sheet->setCellValue( $this->getRowIndex($i).'0',$head[$i] );
			}
		}

		$cur=($head_count > 0 ? 1 : 0);
		foreach($data as $row)
		{
			$col=0;
			foreach($row as $k => $v){
				
				$sheet->setCellValue( $this->getRowIndex($cur) . $col,(string)$v );
				++$col;
				$num=$k+1;

				
			}
			unset($k);
			unset($v);
			++$cur;
		}
		unset($row);

		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}

	private function getRowIndex($index)
	{
		$count = count($this->row_index);
		if( $index < $count ) return $this->row_index[$index];
		$m = (int)( $index / $count );
		$n = (int)( $index % $count );
		return $this->row_index[ $m ] . $this->row_index[ $n ];
	}
}
