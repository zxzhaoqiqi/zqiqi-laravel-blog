<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory as ExcelIOFactory;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use PhpOffice\PhpWord\TemplateProcessor;


class ExpReport extends Command
{
    private $expPath;
    private $sanzhanPath;
    private $yuyuePath;
    private $finishPath;
    private $tplPath;
    private $reportPath;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exp:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->expPath = public_path() . '/' . 'exp/clx/report';
        //洽谈记录三站
        $this->sanzhanPath = $this->expPath .  '/sanzhan.xlsx';
        //展商预约和实际洽谈统计
        $this->yuyuePath = $this->expPath .  '/yueyue.xlsx';
        //完成现场洽谈买家信息-1
        $this->finishPath = $this->expPath .  '/finish_0515.xlsx';
        //模板
        $this->tplPath = $this->expPath . '/test_tpl.docx';
        //导出文件路径
        $this->reportPath = public_path() . '/' . 'exp/report/';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $arrStation = $this->getDataSanZhan();
        $zsArr = $this->getDataYuyue();
        $tmp = $zsArr[0];
        $zsArr = [];
        $zsArr[] = $tmp;

        $bar = $this->output->createProgressBar(count($zsArr));

        foreach ($zsArr as $k => $v){
            $word =  $this->getFileWordTplObj();
            $word->setValue('com', $v['A']);
            $word->setValue('qt_total', $v['B']);

            //计算平均分
            $avei = 0;
            if(is_numeric($v['C'])){
                $avei++;
            }
            if(is_numeric($v['D'])){
                $avei++;
            }
            if(is_numeric($v['E'])){
                $avei++;
            }

            if($avei > 0){
                //四舍五入取整
                $ave = $v['B'] / $avei;
                $ave = round($ave);
            }else{
                $ave = '-';
            }
            $word->setValue('qt_ave', $ave);
            $word->setValue('qt_beijing', $v['C']);
            $word->setValue('qt_shanghai', $v['D']);
            $word->setValue('qt_shenzhen', $v['E']);
            if($arrStation[$v['A']]){
                //展商详细
                $detailArr = $arrStation[$v['A']];
                if(isset($detailArr['北京站'])){
                    $this->makeDataDetailCity($word, $detailArr['北京站'], 'a', 'E');
                }else{
                    $word->deleteBlock('E');
                }
                if(isset($detailArr['上海站'])){
                    $this->makeDataDetailCity($word, $detailArr['上海站'], 'b', 'F');
                }else{
                    $word->deleteBlock('F');
                }
                if(isset($detailArr['深圳站'])){
                    $this->makeDataDetailCity($word, $detailArr['深圳站'], 'c', 'G');
                }else{
                    $word->deleteBlock('G');
                }
            }else{
                $word->deleteBlock('E');
                $word->deleteBlock('F');
                $word->deleteBlock('G');
            }

            //完成现场洽谈的买家信息
            $this->makeDataDetailFinish($word, $v['A']);

            //保存文件信息
            if($v['A'] == '厦门悦华酒店/厦门国际会议中心酒店'){
                $v['A'] = '厦门悦华酒店_厦门国际会议中心酒店';
            }
            $file = 'MICE China EXPO 2018春季场报告_' . $v['A'] . '.docx';
            $res = $this->reportPath . $file;
            $word->saveAs($res);
            $bar->advance();
        }

        $bar->finish();

    }

    private function makeDataDetailFinish(&$word, $com){

        $data = $this->getFileExcelObj($this->finishPath);
        unset($data[1]);

        $tmpArr = [];
        foreach ($data as $k => $v){
            if($v['V'] == $com){
                $tmpArr[] = $v;
            }
        }
        if(!$tmpArr){
            $word->deleteBlock('H');
            return;
        }
        $word->cloneBlock('H');
        $word->cloneRow( 'd1', count($tmpArr));
        foreach ($tmpArr as $a => $b){
            $tmp = [
                $b['B'],
                $b['C'],
                $b['D'],
                $b['E'],
                $b['F'],
                $b['G'],
                $b['H'],
                $b['I'],
                $b['J'],
                $b['K'],
                $b['L'],
                $b['M'],
                $b['N'],
                $b['O'],
                $b['P'],
                $b['Q'],
                $b['R'],
                $b['S']
            ];
            $col = count($tmp);
            for ($bi = 1; $bi < $col+1; $bi++){
                $num = $a+1;
                $name = 'd' . $bi . '#' . $num;
                $con = $bi-1;
                $word->setValue($name, $tmp[$con]);
            }
        }
    }

    private function makeDataDetailCity(&$word, $arr, $type, $clone){
        $word->cloneBlock($clone);
        $word->cloneRow($type . '1', count($arr));
        foreach ($arr as $a => $b){
            $tmp = [
                $a+1,
                $b['C'],
                $b['E'],
                $b['F'],
                $b['G'],
                $b['J'],
                $b['K']
            ];
            $col = count($tmp);
            for ($bi = 1; $bi < $col+1; $bi++){
                $num = $a+1;
                $name = $type . $bi . '#' . $num;
                $con = $bi-1;
                $word->setValue($name, $tmp[$con]);
            }
        }
    }

    private function getDataYuyue(){
        $zsArr = $this->getFileExcelObj($this->yuyuePath);
        unset($zsArr[1]);
        unset($zsArr[66]);
        unset($zsArr[67]);
        unset($zsArr[68]);
        unset($zsArr[69]);
        unset($zsArr[70]);
        return array_values($zsArr);
    }

    private function getDataSanZhan(){
        $arr = $this->getFileExcelObj($this->sanzhanPath);
        unset($arr[1]);
        $tmp = [];
        foreach ($arr as $k => $v){
            $tmp[$v['B']][$v['A']][] = $v;
        }
        return $tmp;
    }

    private function getFileExcelObj($path)
    {
        $obj = ExcelIOFactory::load($path);
        return $obj->getActiveSheet()->toArray(null, true, true, true);
    }

    private function getFileWordTplObj()
    {
        $obj = new TemplateProcessor($this->tplPath);
        return $obj;
    }
}
