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
        $this->sanzhanPath = $this->expPath .  '/sanzhan_0925.xlsx';
        //展商预约和实际洽谈统计
        $this->yuyuePath = $this->expPath .  '/yueyue.xlsx';
        //完成现场洽谈买家信息-1
        $this->finishPath = $this->expPath .  '/finish_0926.xlsx';
        //模板
        $this->tplPath = $this->expPath . '/test_tpl_new.docx';
        //导出文件路径
        $this->reportPath = public_path() . '/' . 'exp/clx/report/xx/';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $arrStation = $this->getDataSanZhan();
        $finishData = $this->getFinishData();
//        reset($arrStation); // 如果确定数组的指针指向第一个元素，可以不使用本语句
//        $tmp = current($arrStation); // $value 的值为：'aaa'\
//        $key = key($arrStation);
//        $arrStation = [];
//        $arrStation[$key] = $tmp;
        $bar = $this->output->createProgressBar(count($arrStation));

        foreach ($arrStation as $k => $v){
            $word =  $this->getFileWordTplObj();
            $word->setValue('com', $k);

            //计算平均分
            $avei = 0;
            $total = 0;
            if (isset($v['北京站'])){
                $avei++;
                $qt_beijing = count($v['北京站']);
                $total += $qt_beijing;
            }else{
                $qt_beijing =  '-';
            }

            if (isset($v['上海站'])){
                $avei++;
                $qt_shanghai = count($v['上海站']);
                $total += $qt_shanghai;
            }else{
                $qt_shanghai =  '-';
            }

            if (isset($v['深圳站'])){
                $avei++;
                $qt_shenzhen = count($v['深圳站']);
                $total += $qt_shenzhen;
            }else{
                $qt_shenzhen =  '-';
            }

            if($avei > 0){
                //四舍五入取整
                $ave = $total / $avei;
                $ave = round($ave);
            }else{
                $ave = '-';
            }
            $word->setValue('qt_total', $total);
            $word->setValue('qt_ave', $ave);
            $word->setValue('qt_beijing', $qt_beijing);
            $word->setValue('qt_shanghai', $qt_shanghai);
            $word->setValue('qt_shenzhen', $qt_shenzhen);
            if($arrStation[$k]){
                //展商详细
                $detailArr = $arrStation[$k];
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
            $this->makeDataDetailFinish($word, $k, $finishData);

            //保存文件名处理
            $k = str_replace('/', '_', $k);

            $file = 'MICE China EXPO 2018秋季场报告_' . $k . '.docx';
            $res = $this->reportPath . $file;

            $word->saveAs($res);
            $bar->advance();
        }

        $bar->finish();

    }

    private function makeDataDetailFinish(&$word, $com, $finishData){
        $tmpArr = isset($finishData[$com]) ? $finishData[$com] : [];
        if(!$tmpArr){
            $word->deleteBlock('H');
            return;
        }
        $word->cloneBlock('H');
        $word->cloneRow( 'd1', count($tmpArr));
        foreach ($tmpArr as $a => $b){
            $tmp = [
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
                $b['S'],
                $b['T'],
                $b['U']
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
                $b['D'],
                $b['E'],
                $b['F'],
                $b['G'],
                $b['H']
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

    private function getFinishData(){
        $data = $this->getFileExcelObj($this->finishPath);
        unset($data[1]);

        $tmpArr = [];
        foreach ($data as $k => $v){
            $tmpArr[$v['A']][] = $v;
        }

        return $tmpArr;
    }

    private function getDataSanZhan(){
        $arr = $this->getFileExcelObj($this->sanzhanPath);
        unset($arr[1]);
        $tmp = [];
        foreach ($arr as $k => $v){
            if (in_array($v['I'], ['上午场', '下午场'])){
                $tmp[$v['B']]['北京站'][] = $v;
            }elseif (in_array($v['J'], ['上午场', '下午场'])){
                $tmp[$v['B']]['上海站'][] = $v;
            }elseif (in_array($v['K'], ['上午场', '下午场'])){
                $tmp[$v['B']]['深圳站'][] = $v;
            }
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
