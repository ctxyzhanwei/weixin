<?php  
/** 
 * Created by lonm.shi. 
 * Date: 2012-02-09 
 * Time: 下午4:54 
 * To change this template use File | Settings | File Templates. 
 */  
   
class ExportStatisticsAction extends Action {  
    public function index(){  
        $model= D("Users");  
		$weixinuser=D('wxuser');
		$wxorderdata=$weixinuser->select();
        $OrdersData= $model->select();  
  
        vendor("PHPExcel176.PHPExcel");  
        // Create new PHPExcel object  
        $objPHPExcel = new PHPExcel();  
        // Set properties  
        $objPHPExcel->getProperties()->setCreator("ctos")  
            ->setLastModifiedBy("ctos")  
            ->setTitle("Office 2007 XLSX Test Document")  
            ->setSubject("Office 2007 XLSX Test Document")  
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")  
            ->setKeywords("office 2007 openxml php")  
            ->setCategory("Test result file");  
  
        //set width  
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(50);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);  
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);  
  
        //设置行高度  
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);  
  
        $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);  
  
        //set font size bold  
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);  
        $objPHPExcel->getActiveSheet()->getStyle('A2:J2')->getFont()->setBold(true);  
  
        $objPHPExcel->getActiveSheet()->getStyle('A2:J2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('A2:J2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
  
        //设置水平居中  
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);  
        $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
  
        //  
        $objPHPExcel->getActiveSheet()->mergeCells('A1:J1');  
  
        // set table header content  
        $objPHPExcel->setActiveSheetIndex(0)  
            ->setCellValue('A1', '汇总表')  
            ->setCellValue('A2', '序号')  
            ->setCellValue('B2', '用户名')  
            ->setCellValue('C2', '邮箱')  
            ->setCellValue('D2', '公众号名称')  
            ->setCellValue('E2', '微信号')  
            ->setCellValue('F2', '公众号邮箱')  
            ->setCellValue('G2', '活动创建个数')  
            ->setCellValue('H2', '注册时间')  
            ->setCellValue('I2', '上次登陆时间')  
            ->setCellValue('J2', '到期时间') 
		    ->setCellValue('K2', '邀请码'); 
        // Miscellaneous glyphs, UTF-8  time
        for($i=0;$i<count($OrdersData)-1;$i++){  
            $objPHPExcel->getActiveSheet(0)->setCellValue('A'.($i+3), $OrdersData[$i]['id']);  
            $objPHPExcel->getActiveSheet(0)->setCellValue('B'.($i+3), $OrdersData[$i]['username']);  
            $objPHPExcel->getActiveSheet(0)->setCellValue('C'.($i+3), $OrdersData[$i]['email']);  
			$objPHPExcel->getActiveSheet(0)->setCellValue('D'.($i+3), $wxorderdata[$i]['wxname']); 
			$objPHPExcel->getActiveSheet(0)->setCellValue('E'.($i+3), $wxorderdata[$i]['weixin']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('F'.($i+3), $wxorderdata[$i]['qq']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('G'.($i+3), $OrdersData[$i]['activitynum']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('H'.($i+3), date('Y-m-d H:i:s',$OrdersData[$i]['createtime'])); 
            $objPHPExcel->getActiveSheet(0)->setCellValue('I'.($i+3), date('Y-m-d H:i:s',$OrdersData[$i]['lasttime']));  
            $objPHPExcel->getActiveSheet(0)->setCellValue('J'.($i+3), date('Y-m-d H:i:s',$OrdersData[$i]['viptime']));   
			$objPHPExcel->getActiveSheet(0)->setCellValue('K'.($i+3), $OrdersData[$i]['invitecode']);
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+3).':J'.($i+3))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
            $objPHPExcel->getActiveSheet()->getStyle('A'.($i+3).':J'.($i+3))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
            $objPHPExcel->getActiveSheet()->getRowDimension($i+3)->setRowHeight(16);  
        }  
  
  
        // Rename sheet  
        $objPHPExcel->getActiveSheet()->setTitle('用户表');  
  
  
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet  
        $objPHPExcel->setActiveSheetIndex(0);  
  
  
        // Redirect output to a client’s web browser (Excel5)  
        header('Content-Type: application/vnd.ms-excel');  
        header('Content-Disposition: attachment;filename="订单汇总表('.date('Ymd-His').').xls"');  
        header('Cache-Control: max-age=0');  
  
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        $objWriter->save('php://output');  
  
    }  
}  