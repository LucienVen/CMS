<?php
/**
 * test action
 */
namespace App\Action;

use Core\Action;
use App\Model\Test as TestModel;

// PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls\Worksheet;

class Test extends \Core\Action
{

    // protected $container;
    // public function __construct(\Slim\Container $container)
    // {
    //     $this->container = $container;
    //     parent::__construct($this->container);
    // }

    /**
     * 测试获取参数与json响应放回
     * @router \hello\{name}
     */
    public function base()
    {
        $name = $this->_args['name'];
        $data = ['name' => $name];
        return $this->_response->withJson($data, 200);
    }

    /**
     * 测试PDO连接，已经容器使用（PDO以及Monolog）
     */
    public function link()
    {
        $pdo = $this->_container->get('pdo');
        // test logger
        $logger = $this->_container->get('logger');
        $logger->addInfo("test");

        // 测试pdo链接
        if (!$pdo) {
            return $this->_response->withJson(['msg: db connect error!'], 500);
        }

        $sql = 'select * from user';
        $stmt = $pdo->query($sql);
        // print_r($stmt->fetchAll());
        $data['pdo'] = $stmt->fetchAll();
        return $this->_response->withJson($data, 200);
    }

    /**
     * 测试自定义中间件使用
     */
    public function middle_test()
    {
        return $this->_response->write('Hello, World!');
    }

    /**
     * 测试post
     */
    public function post()
    {
        $name = $this->_request->getParam('name');
        $age = $this->_request->getParam('age');
        $data = [
            'name' => $name,
            'age' => $age,
        ];
        // $name = $obj['name'];
        // return $this->_response->withJson($data, 200);

        return $this->_response->withJson($data, 200);
    }

    /**
     * 测试pdo依赖注入
     */
    public function linkPdo()
    {
        $email = $this->_request->getParam('email');

        $testModel = new TestModel($this->_container->get('pdo'));

        $data = $testModel->getUserByEmail($email);
        return $this->success($data, 200);
    }

    /**
     * test JWT Middleware
     */
    public function userInfo($request, $response, $args)
    {
        $data = $request->getAttribute('userData');
        print_r($data);
    }

    /**
     * test upload file
     */
    public function upload($request, $response, $args)
    {
        $uploadedFiles = $request->getUploadedFiles();
        // print_r($uploadedFiles);
        // exit;
        if (empty($uploadedFiles)) {
            return $this->error(402);
        }

        $res = [];
        foreach ($uploadedFiles as $file) {
            if ($file->getError() === UPLOAD_ERR_OK) {
                // print_r($file);
                // print_r($file->getClientFilename());
                $fileName = $this->moveUploadedFile($file);
                if ($fileName) {
                    $res[] = $fileName;
                }
            }
        }

        return $this->success(202, $res);
    }

    /**
     * 文件改名以及移动至指定目录
     *
     * @param \Slim\Http\UploadedFile $uploadedFile
     * @return void
     */
    protected function moveUploadedFile(\Slim\Http\UploadedFile $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $randomName = \bin2hex(\random_bytes(8));
        $fileName = $randomName . '.' . $extension;
        // print_r($fileName);
        $uploadedFile->moveTo(UPLOAD_FILE_PATH . DIRECTORY_SEPARATOR . $fileName);
        return $fileName;
    }


    /**
     * 测试获取请求路径
     */
    public function path($request, $response, $args)
    {
        // return $this->success(200);
        print_r('----path----');
        // $route = $this->_container->get('callableResolver');

        
        // $route = $this->_container->get('router')->getNamedRoute('path');
        // $callable = $route->getCallable();
        // print_r($callable);
        // print_r($callable[0]);
        // foreach ($callable as $key => $value) {
        //     print_r($key);
            
        //     print_r($value);
        // }
        // $res1 = (array)$callable
        // print_r($callable);

        // $prodClass = new \ReflectionClass($callable[0]);
        // \Reflection::export($prodClass);
        // print_r($prodClass->getShortName());

        // $resolver = $this->_container->get('callableResolver');
        // $res = $resolver->resolve($callable);
        // print_r($res);
    }

    // 测试容器pdo服务是否单例
    public function pdo()
    {
        $testModel = new TestModel($this->_container->get('pdo'));
        var_dump($testModel);
        $testModel2 = new TestModel($this->_container->get('pdo'));
        var_dump($testModel2);
        $testModel3 = new TestModel($this->_container->get('pdo'));
        var_dump($testModel3);

        $pdo1 = $this->_container->get('pdo');
        var_dump($pdo1);
        $pdo2 = $this->_container->get('pdo');
        var_dump($pdo2);
        $pdo3 = $this->_container->get('pdo');
        var_dump($pdo3);

        // 容器服务，实现单例
    }

    /**
     * PhpSpreadsheet 读写 excel
     */
    public function excel($request, $response, $args)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Welcome to Helloweba.');

        $writer = new Xlsx($spreadsheet);
        $file_path = UPLOAD_FILE_PATH . '/Export/hello.xlsx';
        // $file_path = '/usr/local/var/www/CMS//File/Export/hello.xlsx';
        // print_r($file_path);
        // exit;
        $res = $writer->save($file_path);
    }

    /**
     * 上传文件，并存入数据库
     */
    public function uploadExcel($request, $response, $args)
    {
        $uploadedFiles = $request->getUploadedFiles();
        // print_r($uploadedFiles);
        if (empty($uploadedFiles)) {
            return $this->error(455);
        }

        // 文件上传
        $excel = $uploadedFiles['excel'];
        if ($excel->getError() === UPLOAD_ERR_OK) {            
            $fileName = $this->moveUploadedFile($excel);
        }else {
            return $this->error(456);
        }

        // 读取excel文件
        $spreadsheet = $this->readExcel($fileName);

        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow(); // 总行数
        $highestColumn = $worksheet->getHighestColumn(); // 总列数

        // 判断是否存有数据
        if ($highestRow - 2 <= 0) {
            return $this->error(457);
        }

        // 表格数据转化成数组
        $excel_array = [];
        for($row = 3; $row <= $highestRow; $row++){
            $row_data = [];
            $row_data['department'] = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
            $row_data['teacher'] = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
            $row_data['title'] = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
            $row_data['result'] = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
            $row_data['mark'] = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
            $row_data['reviewer'] = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
            
            $excel_array[] = $row_data;
            
        }

        // print_r($excel_array);
        $testModel = new TestModel($this->_container->get('pdo'));
        $res = $testModel->insertExcel($excel_array);
        print_r($res);

        
    }

    // 读取excel 文件
    protected function readExcel($fileName)
    {
        $file_path = UPLOAD_FILE_PATH . DIRECTORY_SEPARATOR . $fileName;

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file_path);

        return $spreadsheet;
        // print_r($spreadsheet);
        // $worksheet = $spreadsheet->getActiveSheet();
        // $highestRow = $worksheet->getHighestRow(); // 总行数
        // $highestColumn = $worksheet->getHighestColumn(); // 总列数

        // 判断数据表是否为空

    }

    // 选择行号，导出excel
    public function export($request, $response, $args)
    {
        $data = $request->getParam('ids');
        // print_r($data);
        $ids = explode(',', $data);
        // print_r($ids);
    }
}
