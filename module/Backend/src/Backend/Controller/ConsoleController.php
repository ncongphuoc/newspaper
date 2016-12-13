<?php

namespace Backend\Controller;

use My\General,
    My\Controller\MyController,
    Sunra\PhpSimple\HtmlDomParser;

class ConsoleController extends MyController
{

    protected static $_arr_worker = [
        'content', 'keyword'
    ];

    public function __construct()
    {
//        if (PHP_SAPI !== 'cli') {
//            die('Only use this controller from command line!');
//        }
        ini_set('default_socket_timeout', -1);
        ini_set('max_execution_time', -1);
        ini_set('mysql.connect_timeout', -1);
        ini_set('memory_limit', -1);
        ini_set('output_buffering', 0);
        ini_set('zlib.output_compression', 0);
        ini_set('implicit_flush', 1);
    }

    public function indexAction()
    {
        die();
    }

    private function flush()
    {
        ob_end_flush();
        ob_flush();
        flush();
    }

    public function migrateAction()
    {
        $params = $this->request->getParams();
        $intIsCreateIndex = (int)$params['createindex'];

        if (empty($params['type'])) {
            return General::getColoredString("Unknown type \n", 'light_cyan', 'red');
        }

        switch ($params['type']) {
            case 'logs':
                $this->__migrateLogs($intIsCreateIndex);
                break;

            case 'content':
                $this->__migrateContent($intIsCreateIndex);
                break;

            case 'category' :
                $this->__migrateCategory($intIsCreateIndex);
                break;

            case 'user' :
                $this->__migrateUser($intIsCreateIndex);
                break;

            case 'general' :
                $this->__migrateGeneral($intIsCreateIndex);
                break;
            case 'keyword' :
                $this->__migrateKeyword($intIsCreateIndex);
                break;
            case 'group' :
                $this->__migrateGroup($intIsCreateIndex);
                break;
            case 'permission' :
                $this->__migratePermission($intIsCreateIndex);
                break;
            case 'all-table' :
                $instanceSearch = new \My\Search\Logs();
                $instanceSearch->createIndex();
                $instanceSearch = new \My\Search\Content();
                $instanceSearch->createIndex();
                $instanceSearch = new \My\Search\Category();
                $instanceSearch->createIndex();
                $instanceSearch = new \My\Search\User();
                $instanceSearch->createIndex();
                $instanceSearch = new \My\Search\Keyword();
                $instanceSearch->createIndex();
                $instanceSearch = new \My\Search\GeneralSearch();
                $instanceSearch->createIndex();
                $instanceSearch = new \My\Search\Group();
                $instanceSearch->createIndex();
                $instanceSearch = new \My\Search\Permission();
                $instanceSearch->createIndex();
                break;
        }
        echo General::getColoredString("Index ES sucess", 'light_cyan', 'yellow');
        return true;
    }

    public function __migratePermission($intIsCreateIndex)
    {
        $service = $this->serviceLocator->get('My\Models\Permission');
        $intLimit = 1000;
        $instanceSearch = new \My\Search\Permission();

        for ($intPage = 1; $intPage < 10000; $intPage++) {
            $arrList = $service->getListLimit([], $intPage, $intLimit, 'perm_id ASC');

            if (empty($arrList)) {
                break;
            }

            if ($intPage == 1) {
                if ($intIsCreateIndex) {
                    $instanceSearch->createIndex();
                } else {
                    $result = $instanceSearch->removeAllDoc();
                    if (empty($result)) {
                        $this->flush();
                        return General::getColoredString("Cannot delete old search index \n", 'light_cyan', 'red');
                    }
                }
            }
            $arrDocument = [];
            foreach ($arrList as $arr) {
                $id = (int)$arr['perm_id'];

                $arrDocument[] = new \Elastica\Document($id, $arr);
                echo General::getColoredString("Created new document with id = " . $id . " Successfully", 'cyan');

                $this->flush();
            }

            unset($arrList); //release memory
            echo General::getColoredString("Migrating " . count($arrDocument) . " documents, please wait...", 'yellow');
            $this->flush();

            $instanceSearch->add($arrDocument);
            echo General::getColoredString("Migrated " . count($arrDocument) . " documents successfully", 'blue', 'cyan');

            unset($arrDocument);
            $this->flush();
        }

        die('done');
    }

    public function __migrateGroup($intIsCreateIndex)
    {
        $service = $this->serviceLocator->get('My\Models\Group');
        $intLimit = 1000;
        $instanceSearch = new \My\Search\Group();

        for ($intPage = 1; $intPage < 10000; $intPage++) {
            $arrList = $service->getListLimit([], $intPage, $intLimit, 'group_id ASC');

            if (empty($arrList)) {
                break;
            }

            if ($intPage == 1) {
                if ($intIsCreateIndex) {
                    $instanceSearch->createIndex();
                } else {
                    $result = $instanceSearch->removeAllDoc();
                    if (empty($result)) {
                        $this->flush();
                        return General::getColoredString("Cannot delete old search index \n", 'light_cyan', 'red');
                    }
                }
            }
            $arrDocument = [];
            foreach ($arrList as $arr) {
                $id = (int)$arr['group_id'];

                $arrDocument[] = new \Elastica\Document($id, $arr);
                echo General::getColoredString("Created new document with id = " . $id . " Successfully", 'cyan');

                $this->flush();
            }

            unset($arrList); //release memory
            echo General::getColoredString("Migrating " . count($arrDocument) . " documents, please wait...", 'yellow');
            $this->flush();

            $instanceSearch->add($arrDocument);
            echo General::getColoredString("Migrated " . count($arrDocument) . " documents successfully", 'blue', 'cyan');

            unset($arrDocument);
            $this->flush();
        }

        die('done');
    }

    public function __migrateGeneral($intIsCreateIndex)
    {
        $service = $this->serviceLocator->get('My\Models\GeneralBqn');
        $intLimit = 1000;
        $instanceSearch = new \My\Search\GeneralSearch();

        for ($intPage = 1; $intPage < 10000; $intPage++) {
            $arrList = $service->getListLimit([], $intPage, $intLimit, 'gene_id ASC');

            if (empty($arrList)) {
                break;
            }

            if ($intPage == 1) {
                if ($intIsCreateIndex) {
                    $instanceSearch->createIndex();
                } else {
                    $result = $instanceSearch->removeAllDoc();
                    if (empty($result)) {
                        $this->flush();
                        return General::getColoredString("Cannot delete old search index \n", 'light_cyan', 'red');
                    }
                }
            }
            $arrDocument = [];
            foreach ($arrList as $arr) {
                $id = (int)$arr['gene_id'];

                $arrDocument[] = new \Elastica\Document($id, $arr);
                echo General::getColoredString("Created new document with id = " . $id . " Successfully", 'cyan');

                $this->flush();
            }

            unset($arrList); //release memory
            echo General::getColoredString("Migrating " . count($arrDocument) . " documents, please wait...", 'yellow');
            $this->flush();

            $instanceSearch->add($arrDocument);
            echo General::getColoredString("Migrated " . count($arrDocument) . " documents successfully", 'blue', 'cyan');

            unset($arrDocument);
            $this->flush();
        }

        die('done');
    }

    public function __migrateUser($intIsCreateIndex)
    {
        $service = $this->serviceLocator->get('My\Models\User');
        $intLimit = 1000;
        $instanceSearch = new \My\Search\User();

        for ($intPage = 1; $intPage < 10000; $intPage++) {
            $arrList = $service->getListLimit([], $intPage, $intLimit, 'user_id ASC');

            if (empty($arrList)) {
                break;
            }

            if ($intPage == 1) {
                if ($intIsCreateIndex) {
                    $instanceSearch->createIndex();
                } else {
                    $result = $instanceSearch->removeAllDoc();
                    if (empty($result)) {
                        $this->flush();
                        return General::getColoredString("Cannot delete old search index \n", 'light_cyan', 'red');
                    }
                }
            }
            $arrDocument = [];
            foreach ($arrList as $arr) {
                $id = (int)$arr['user_id'];

                $arrDocument[] = new \Elastica\Document($id, $arr);
                echo General::getColoredString("Created new document with id = " . $id . " Successfully", 'cyan');

                $this->flush();
            }

            unset($arrList); //release memory
            echo General::getColoredString("Migrating " . count($arrDocument) . " documents, please wait...", 'yellow');
            $this->flush();

            $instanceSearch->add($arrDocument);
            echo General::getColoredString("Migrated " . count($arrDocument) . " documents successfully", 'blue', 'cyan');

            unset($arrDocument);
            $this->flush();
        }

        die('done');
    }

    public function __migrateCategory($intIsCreateIndex)
    {
        $service = $this->serviceLocator->get('My\Models\Category');
        $intLimit = 1000;
        $instanceSearch = new \My\Search\Category();
//        $instanceSearch->createIndex();
//        die();
        for ($intPage = 1; $intPage < 10000; $intPage++) {
            $arrList = $service->getListLimit([], $intPage, $intLimit, 'cate_id ASC');
            if (empty($arrList)) {
                break;
            }

            if ($intPage == 1) {
                if ($intIsCreateIndex) {
                    $instanceSearch->createIndex();
                } else {
                    $result = $instanceSearch->removeAllDoc();
                    if (empty($result)) {
                        $this->flush();
                        return General::getColoredString("Cannot delete old search index \n", 'light_cyan', 'red');
                    }
                }
            }
            $arrDocument = [];
            foreach ($arrList as $arr) {
                $id = (int)$arr['cate_id'];

                $arrDocument[] = new \Elastica\Document($id, $arr);
                echo General::getColoredString("Created new document with id = " . $id . " Successfully", 'cyan');

                $this->flush();
            }

            unset($arrList); //release memory
            echo General::getColoredString("Migrating " . count($arrDocument) . " documents, please wait...", 'yellow');
            $this->flush();

            $instanceSearch->add($arrDocument);
            echo General::getColoredString("Migrated " . count($arrDocument) . " documents successfully", 'blue', 'cyan');

            unset($arrDocument);
            $this->flush();
        }

        die('done');
    }

    public function __migrateLogs($intIsCreateIndex)
    {
        $serviceLogs = $this->serviceLocator->get('My\Models\Logs');
        $intLimit = 1000;
        $instanceSearchLogs = new \My\Search\Logs();
        for ($intPage = 1; $intPage < 10000; $intPage++) {
            $arrLogsList = $serviceLogs->getListLimit([], $intPage, $intLimit, 'log_id ASC');
            if (empty($arrLogsList)) {
                break;
            }

            if ($intPage == 1) {
                if ($intIsCreateIndex) {
                    $instanceSearchLogs->createIndex();
                } else {
                    $result = $instanceSearchLogs->removeAllDoc();
                    if (empty($result)) {
                        $this->flush();
                        return General::getColoredString("Cannot delete old search index \n", 'light_cyan', 'red');
                    }
                }
            }
            $arrDocument = [];
            foreach ($arrLogsList as $arrLogs) {
                $logId = (int)$arrLogs['log_id'];

                $arrDocument[] = new \Elastica\Document($logId, $arrLogs);
                echo General::getColoredString("Created new document with log_id = " . $logId . " Successfully", 'cyan');

                $this->flush();
            }

            unset($arrLogsList); //release memory
            echo General::getColoredString("Migrating " . count($arrDocument) . " documents, please wait...", 'yellow');
            $this->flush();

            $instanceSearchLogs->add($arrDocument);
            echo General::getColoredString("Migrated " . count($arrDocument) . " documents successfully", 'blue', 'cyan');

            unset($arrDocument);
            $this->flush();
        }

        die('done');
    }

    public function __migrateContent($intIsCreateIndex)
    {
        $serviceContent = $this->serviceLocator->get('My\Models\Content');
        $intLimit = 200;
        $instanceSearchContent = new \My\Search\Content();
//        $instanceSearchContent->createIndex();
//        die();

        for ($intPage = 1; $intPage < 10000; $intPage++) {
            $arrContentList = $serviceContent->getListLimit([], $intPage, $intLimit, 'cont_id ASC');
            if (empty($arrContentList)) {
                break;
            }

            if ($intPage == 1) {
                if ($intIsCreateIndex) {
                    $instanceSearchContent->createIndex();
                } else {
                    $result = $instanceSearchContent->removeAllDoc();
                    if (empty($result)) {
                        $this->flush();
                        return General::getColoredString("Cannot delete old search index \n", 'light_cyan', 'red');
                    }
                }
            }
            $arrDocument = [];
            foreach ($arrContentList as $arrContent) {
                $id = (int)$arrContent['cont_id'];

                $arrDocument[] = new \Elastica\Document($id, $arrContent);
                echo General::getColoredString("Created new document with cont_id = " . $id . " Successfully", 'cyan');

                $this->flush();
            }

            unset($arrContentList); //release memory
            echo General::getColoredString("Migrating " . count($arrDocument) . " documents, please wait...", 'yellow');
            $this->flush();

            $instanceSearchContent->add($arrDocument);
            echo General::getColoredString("Migrated " . count($arrDocument) . " documents successfully", 'blue', 'cyan');

            unset($arrDocument);
            $this->flush();
        }

        die('done');
    }

    public function __migrateKeyword($intIsCreateIndex)
    {

        $serviceKeyword = $this->serviceLocator->get('My\Models\Keyword');
        $intLimit = 2000;
        $instanceSearchKeyword = new \My\Search\Keyword();
        for ($intPage = 1; $intPage < 10000; $intPage++) {
            $arrList = $serviceKeyword->getListLimit([], $intPage, $intLimit, 'key_id ASC');

            if (empty($arrList)) {
                break;
            }

            if ($intPage == 1) {
                if ($intIsCreateIndex) {
                    $instanceSearchKeyword->createIndex();
                } else {
                    $result = $instanceSearchKeyword->removeAllDoc();
                    if (empty($result)) {
                        $this->flush();
                        return General::getColoredString("Cannot delete old search index \n", 'light_cyan', 'red');
                    }
                }
            }
            $arrDocument = [];
            foreach ($arrList as $arr) {
                $id = (int)$arr['key_id'];
                $arrDocument[] = new \Elastica\Document($id, $arr);
                echo General::getColoredString("Created new document with cont_id = " . $id . " Successfully", 'cyan');

                $this->flush();
            }

            unset($arrList); //release memory
            echo General::getColoredString("Migrating " . count($arrDocument) . " documents, please wait...", 'yellow');
            $this->flush();

            $instanceSearchKeyword->add($arrDocument);
            echo General::getColoredString("Migrated " . count($arrDocument) . " documents successfully", 'blue', 'cyan');

            unset($arrDocument);
            $this->flush();
        }
        die('done');
    }

    public function workerAction()
    {
        $params = $this->request->getParams();

        //stop all job
        if ($params['stop'] === 'all') {
            if ($params['type'] || $params['background']) {
                return General::getColoredString("Invalid params \n", 'light_cyan', 'red');
            }
            exec("ps -ef | grep -v grep | grep 'type=" . WORKER_PREFIX . "-*' | awk '{ print $2 }'", $PID);

            if (empty($PID)) {
                return General::getColoredString("Cannot found PID \n", 'light_cyan', 'red');
            }

            foreach ($PID as $worker) {
                shell_exec("kill " . $worker);
                echo General::getColoredString("Kill worker with PID = {$worker} stopped running in background \n", 'green');
            }

            return true;
        }

        $arr_worker = self::$_arr_worker;
        if (in_array(trim($params['stop']), $arr_worker)) {
            if ($params['type'] || $params['background']) {
                return General::getColoredString("Invalid params \n", 'light_cyan', 'red');
            }
            $stopWorkerName = WORKER_PREFIX . '-' . trim($params['stop']);
            exec("ps -ef | grep -v grep | grep 'type={$stopWorkerName}' | awk '{ print $2 }'", $PID);
            $PID = current($PID);
            if ($PID) {
                shell_exec("kill " . $PID);
                return General::getColoredString("Job {$stopWorkerName} is stopped running in background \n", 'green');
            } else {
                return General::getColoredString("Cannot found PID \n", 'light_cyan', 'red');
            }
        }

        $worker = General::getWorkerConfig();
        switch ($params['type']) {
            case WORKER_PREFIX . '-logs':
                //start job in background
                if ($params['background'] === 'true') {
                    $PID = shell_exec("nohup php " . PUBLIC_PATH . "/index.php worker --type=" . WORKER_PREFIX . "-logs >/dev/null & echo 2>&1 & echo $!");
                    if (empty($PID)) {
                        echo General::getColoredString("Cannot deamon PHP process to run job " . WORKER_PREFIX . "-logs in background. \n", 'light_cyan', 'red');
                        return;
                    } else {
                        echo General::getColoredString("Job " . WORKER_PREFIX . "-logs is running in background ... \n", 'green');
                    }
                }

                $funcName1 = SEARCH_PREFIX . 'writeLog';
                $methodHandler1 = '\My\Job\JobLog::writeLog';
                $worker->addFunction($funcName1, $methodHandler1, $this->serviceLocator);

                break;

            case WORKER_PREFIX . '-content':
                //start job in background
                if ($params['background'] === 'true') {
                    $PID = shell_exec("nohup php " . PUBLIC_PATH . "/index.php worker --type=" . WORKER_PREFIX . "-content >/dev/null & echo 2>&1 & echo $!");
                    if (empty($PID)) {
                        echo General::getColoredString("Cannot deamon PHP process to run job " . WORKER_PREFIX . "-content in background. \n", 'light_cyan', 'red');
                        return;
                    }
                    echo General::getColoredString("Job " . WORKER_PREFIX . "-content is running in background ... \n", 'green');
                }

                $funcName1 = SEARCH_PREFIX . 'writeContent';
                $methodHandler1 = '\My\Job\JobContent::writeContent';
                $worker->addFunction($funcName1, $methodHandler1, $this->serviceLocator);

                $funcName2 = SEARCH_PREFIX . 'editContent';
                $methodHandler2 = '\My\Job\JobContent::editContent';
                $worker->addFunction($funcName2, $methodHandler2, $this->serviceLocator);

                $funcName3 = SEARCH_PREFIX . 'multiEditContent';
                $methodHandler3 = '\My\Job\JobContent::multiEditContent';
                $worker->addFunction($funcName3, $methodHandler3, $this->serviceLocator);

                break;

            case WORKER_PREFIX . '-mail':
                //start job in background
                if ($params['background'] === 'true') {
                    $PID = shell_exec("nohup php " . PUBLIC_PATH . "/index.php worker --type=" . WORKER_PREFIX . "-mail >/dev/null & echo 2>&1 & echo $!");
                    if (empty($PID)) {
                        echo General::getColoredString("Cannot deamon PHP process to run job " . WORKER_PREFIX . "-mail in background. \n", 'light_cyan', 'red');
                        return;
                    }
                    echo General::getColoredString("Job " . WORKER_PREFIX . "-mail is running in background ... \n", 'green');
                }

                $funcName1 = SEARCH_PREFIX . 'sendMail';
                $methodHandler1 = '\My\Job\JobMail::sendMail';
                $worker->addFunction($funcName1, $methodHandler1, $this->serviceLocator);

                break;

            case WORKER_PREFIX . '-category':
                //start job in background
                if ($params['background'] === 'true') {
                    $PID = shell_exec("nohup php " . PUBLIC_PATH . "/index.php worker --type=" . WORKER_PREFIX . "-category >/dev/null & echo 2>&1 & echo $!");
                    if (empty($PID)) {
                        echo General::getColoredString("Cannot deamon PHP process to run job " . WORKER_PREFIX . "-category in background. \n", 'light_cyan', 'red');
                        return;
                    }
                    echo General::getColoredString("Job " . WORKER_PREFIX . "-category is running in background ... \n", 'green');
                }

                $funcName1 = SEARCH_PREFIX . 'writeCategory';
                $methodHandler1 = '\My\Job\JobCategory::writeCategory';
                $worker->addFunction($funcName1, $methodHandler1, $this->serviceLocator);

                $funcName2 = SEARCH_PREFIX . 'editCategory';
                $methodHandler2 = '\My\Job\JobCategory::editCategory';
                $worker->addFunction($funcName2, $methodHandler2, $this->serviceLocator);

                $funcName3 = SEARCH_PREFIX . 'multiEditCategory';
                $methodHandler3 = '\My\Job\JobCategory::multiEditCategory';
                $worker->addFunction($funcName3, $methodHandler3, $this->serviceLocator);

                break;

            case WORKER_PREFIX . '-user':
                //start job in background
                if ($params['background'] === 'true') {
                    $PID = shell_exec("nohup php " . PUBLIC_PATH . "/index.php worker --type=" . WORKER_PREFIX . "-user >/dev/null & echo 2>&1 & echo $!");
                    if (empty($PID)) {
                        echo General::getColoredString("Cannot deamon PHP process to run job " . WORKER_PREFIX . "-user in background. \n", 'light_cyan', 'red');
                        return;
                    }
                    echo General::getColoredString("Job " . WORKER_PREFIX . "-user is running in background ... \n", 'green');
                }

                $funcName1 = SEARCH_PREFIX . 'writeUser';
                $methodHandler1 = '\My\Job\JobUser::writeUser';
                $worker->addFunction($funcName1, $methodHandler1, $this->serviceLocator);

                $funcName2 = SEARCH_PREFIX . 'editUser';
                $methodHandler2 = '\My\Job\JobUser::editUser';
                $worker->addFunction($funcName2, $methodHandler2, $this->serviceLocator);

                $funcName3 = SEARCH_PREFIX . 'multiEditUser';
                $methodHandler3 = '\My\Job\JobUser::multiEditUser';
                $worker->addFunction($funcName3, $methodHandler3, $this->serviceLocator);

                break;

            case WORKER_PREFIX . '-general':
                //start job in background
                if ($params['background'] === 'true') {
                    $PID = shell_exec("nohup php " . PUBLIC_PATH . "/index.php worker --type=" . WORKER_PREFIX . "-general >/dev/null & echo 2>&1 & echo $!");
                    if (empty($PID)) {
                        echo General::getColoredString("Cannot deamon PHP process to run job " . WORKER_PREFIX . "-general in background. \n", 'light_cyan', 'red');
                        return;
                    }
                    echo General::getColoredString("Job " . WORKER_PREFIX . "-general is running in background ... \n", 'green');
                }

                $funcName1 = SEARCH_PREFIX . 'writeGeneral';
                $methodHandler1 = '\My\Job\JobGeneral::writeGeneral';
                $worker->addFunction($funcName1, $methodHandler1, $this->serviceLocator);

                $funcName2 = SEARCH_PREFIX . 'editGeneral';
                $methodHandler2 = '\My\Job\JobGeneral::editGeneral';
                $worker->addFunction($funcName2, $methodHandler2, $this->serviceLocator);

                break;

            case WORKER_PREFIX . '-keyword':
                //start job in background
                if ($params['background'] === 'true') {
                    $PID = shell_exec("nohup php " . PUBLIC_PATH . "/index.php worker --type=" . WORKER_PREFIX . "-keyword >/dev/null & echo 2>&1 & echo $!");
                    if (empty($PID)) {
                        echo General::getColoredString("Cannot deamon PHP process to run job " . WORKER_PREFIX . "-keyword in background. \n", 'light_cyan', 'red');
                        return;
                    }
                    echo General::getColoredString("Job " . WORKER_PREFIX . "-keyword is running in background ... \n", 'green');
                }

                $funcName1 = SEARCH_PREFIX . 'writeKeyword';
                $methodHandler1 = '\My\Job\JobKeyword::writeKeyword';
                $worker->addFunction($funcName1, $methodHandler1, $this->serviceLocator);

                $funcName2 = SEARCH_PREFIX . 'editKeyword';
                $methodHandler2 = '\My\Job\JobKeyword::editKeyword';
                $worker->addFunction($funcName2, $methodHandler2, $this->serviceLocator);

                break;

            case WORKER_PREFIX . '-group':
                //start job group in background
                if ($params['background'] === 'true') {
                    $PID = shell_exec("nohup php " . PUBLIC_PATH . "/index.php worker --type=" . WORKER_PREFIX . "-group >/dev/null & echo 2>&1 & echo $!");
                    if (empty($PID)) {
                        echo General::getColoredString("Cannot deamon PHP process to run job " . WORKER_PREFIX . "-group in background. \n", 'light_cyan', 'red');
                        return;
                    }
                    echo General::getColoredString("Job " . WORKER_PREFIX . "-group is running in background ... \n", 'green');
                }

                $funcName1 = SEARCH_PREFIX . 'writeGroup';
                $methodHandler1 = '\My\Job\JobGroup::writeGroup';
                $worker->addFunction($funcName1, $methodHandler1, $this->serviceLocator);

                $funcName2 = SEARCH_PREFIX . 'editGroup';
                $methodHandler2 = '\My\Job\JobGroup::editGroup';
                $worker->addFunction($funcName2, $methodHandler2, $this->serviceLocator);

                break;

            case WORKER_PREFIX . '-permission':
                //start job group in background
                if ($params['background'] === 'true') {
                    $PID = shell_exec("nohup php " . PUBLIC_PATH . "/index.php worker --type=" . WORKER_PREFIX . "-permission >/dev/null & echo 2>&1 & echo $!");
                    if (empty($PID)) {
                        echo General::getColoredString("Cannot deamon PHP process to run job " . WORKER_PREFIX . "-permission in background. \n", 'light_cyan', 'red');
                        return;
                    }
                    echo General::getColoredString("Job " . WORKER_PREFIX . "-permission is running in background ... \n", 'green');
                }

                $funcName1 = SEARCH_PREFIX . 'writePermission';
                $methodHandler1 = '\My\Job\JobPermission::writePermission';
                $worker->addFunction($funcName1, $methodHandler1, $this->serviceLocator);

                $funcName2 = SEARCH_PREFIX . 'editPermission';
                $methodHandler2 = '\My\Job\JobPermission::editPermission';
                $worker->addFunction($funcName2, $methodHandler2, $this->serviceLocator);

                break;

            default:
                return General::getColoredString("Invalid or not found function \n", 'light_cyan', 'red');
        }

        if (empty($params['background'])) {
            echo General::getColoredString("Waiting for job...\n", 'green');
        } else {
            return;
        }
        $this->flush();
        while (@$worker->work() || ($worker->returnCode() == GEARMAN_IO_WAIT) || ($worker->returnCode() == GEARMAN_NO_JOBS)) {
            if ($worker->returnCode() != GEARMAN_SUCCESS) {
                echo "return_code: " . $worker->returnCode() . "\n";
                break;
            }
        }
    }

    public function checkWorkerRunningAction()
    {
        $arr_worker = self::$_arr_worker;
        foreach ($arr_worker as $worker) {
            $worker_name = WORKER_PREFIX . '-' . $worker;
            exec("ps -ef | grep -v grep | grep 'type={$worker_name}' | awk '{ print $2 }'", $PID);
            $PID = current($PID);

            if (empty($PID)) {
                $command = 'nohup php ' . PUBLIC_PATH . '/index.php worker --type=' . $worker_name . ' >/dev/null & echo 2>&1 & echo $!';
                $PID = shell_exec($command);
                if (empty($PID)) {
                    echo General::getColoredString("Cannot deamon PHP process to run job {$worker_name} in background. \n", 'light_cyan', 'red');
                } else {
                    echo General::getColoredString("PHP process run job {$worker_name} in background with PID : {$PID}. \n", 'green');
                }
            }
        }
    }

    public function crontabAction()
    {
        $params = $this->request->getParams();

        if (empty($params['type'])) {
            return General::getColoredString("Unknown type or id \n", 'light_cyan', 'red');
        }

        switch ($params['type']) {

            case 'update-vip-content':
                $this->_jobUpdateVipContent();
                break;

            default:
                echo General::getColoredString("Unknown type or id \n", 'light_cyan', 'red');

                break;
        }

        return true;
    }

    public function crawlerKeywordAction()
    {
        $this->getKeyword();
        return;
    }

    public function getKeyword()
    {
        $match = [
            '', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
        ];
        $instanceSearchKeyWord = new \My\Search\Keyword();
        $arr_keyword = current($instanceSearchKeyWord->getListLimit(['is_crawler' => 0], 1, 1, ['key_weight' => ['order' => 'desc'], 'key_id' => ['order' => 'asc']]));

        unset($instanceSearchKeyWord);
        if (empty($arr_keyword)) {
            return;
        }

        $keyword = $arr_keyword['key_name'];
        $count = str_word_count($keyword);
        if ($count > 6) {
            $serviceKeyword = $this->serviceLocator->get('My\Models\Keyword');
            $int_result = $serviceKeyword->edit(array('is_crawler' => 1, 'key_weight' => 1), $arr_keyword['key_id']);
            unset($serviceKeyword);

            if ($int_result) {
                echo \My\General::getColoredString("Crawler success keyword_id = {$arr_keyword['key_id']}", 'green');
            }
            $this->getKeyword();

        }

        foreach ($match as $key => $value) {
            if ($key == 0) {
                $key_match = $keyword . $value;
                $url = 'http://www.google.com/complete/search?output=search&client=chrome&q=' . rawurlencode($key_match) . '&hl=vi&gl=vn';
                $return = General::crawler($url);
                $this->add_keyword(json_decode($return)[1], $arr_keyword);
                continue;
            } else {
                for ($i = 0; $i < 2; $i++) {
                    if ($i == 0) {
                        $key_match = $keyword . ' ' . $value;
                    } else {
                        $key_match = $value . ' ' . $keyword;
                    }
                    $url = 'http://www.google.com/complete/search?output=search&client=chrome&q=' . rawurlencode($key_match) . '&hl=vi&gl=vn';
                    $return = General::crawler($url);
                    $this->add_keyword(json_decode($return)[1], $arr_keyword);
                    continue;
                }
            }
            sleep(1);
        };

        $serviceKeyword = $this->serviceLocator->get('My\Models\Keyword');
        $int_result = $serviceKeyword->edit(array('is_crawler' => 1, 'key_weight' => 1), $arr_keyword['key_id']);
        unset($serviceKeyword);

        if ($int_result) {
            echo \My\General::getColoredString("Crawler success keyword_id = {$arr_keyword['key_id']}", 'green');
        }

        sleep(1);
        $this->getKeyword();
    }

    public function add_keyword($arr_key, $keyword_detail)
    {
        if (empty($arr_key)) {
            return false;
        }

        $arr_block_string = General::blockString();

        $instanceSearchKeyWord = new \My\Search\Keyword();
        foreach ($arr_key as $key_word) {

            $word_slug = trim(General::getSlug($key_word));
            $is_exsit = $instanceSearchKeyWord->getDetail(['key_slug' => $word_slug]);

            if ($is_exsit) {
                echo \My\General::getColoredString("Exsit keyword: " . $word_slug, 'red');
                continue;
            }
            $block = false;
            foreach ($arr_block_string as $string) {
                if (strpos($key_word, $string) !== false) {
                    $block = true;
                }
            }

            if ($block) {
                continue;
            }

            $arr_data = [
                'key_name' => $key_word,
                'key_slug' => $word_slug,
                'created_date' => time(),
                'is_crawler' => 0,
                'cate_id' => ($keyword_detail['cate_id'] == -2) ? -1 : $keyword_detail['cate_id'],
                'key_weight' => 1,
            ];

            $serviceKeyword = $this->serviceLocator->get('My\Models\Keyword');
            $int_result = $serviceKeyword->add($arr_data);
            unset($serviceKeyword);
            if ($int_result) {
                echo \My\General::getColoredString("Insert success 1 row with id = {$int_result}", 'green');
            }
            $this->flush();
        }
        unset($instanceSearchKeyWord);
        return true;
    }

    public function sitemapAction()
    {
        $this->sitemapOther();
        $this->siteMapCategory();
        //$this->siteMapContent();
        $this->siteMapSearch();

        $xml = '<?xml version="1.0" encoding="UTF-8"?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></sitemapindex>';
        $xml = new \SimpleXMLElement($xml);

        $all_file = scandir(PUBLIC_PATH . '/xml/');
        sort($all_file, SORT_NATURAL | SORT_FLAG_CASE);
//        sort($all_file);
        foreach ($all_file as $file_name) {
            if (strpos($file_name, 'xml') !== false) {
                $sitemap = $xml->addChild('sitemap', '');
                $sitemap->addChild('loc', BASE_URL . '/xml/' . $file_name);
                //$sitemap->addChild('lastmod', date('c', time()));
            }
        }

        $result = file_put_contents(PUBLIC_PATH . '/xml/sitemap-tintuc360.xml', $xml->asXML());
        if ($result) {
            echo General::getColoredString("Create sitemap.xml completed!", 'blue', 'cyan');
            $this->flush();
        }
        echo General::getColoredString("DONE!", 'blue', 'cyan');
        return true;
    }

    public function siteMapCategory()
    {
        $doc = '<?xml version="1.0" encoding="UTF-8"?>';
        $doc .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $doc .= '</urlset>';
        $xml = new \SimpleXMLElement($doc);
        $this->flush();
        $instanceSearchCategory = new \My\Search\Category();
        $arrCategoryList = $instanceSearchCategory->getList(['cate_status' => 1], [], ['cate_sort' => ['order' => 'asc'], 'cate_id' => ['order' => 'asc']]);

        $arrCategoryParentList = [];
        $arrCategoryByParent = [];
        if (!empty($arrCategoryList)) {
            foreach ($arrCategoryList as $arrCategory) {
                if ($arrCategory['parent_id'] == 0) {
                    $arrCategoryParentList[$arrCategory['cate_id']] = $arrCategory;
                } else {
                    $arrCategoryByParent[$arrCategory['parent_id']][] = $arrCategory;
                }
            }
        }

        ksort($arrCategoryByParent);

        foreach ($arrCategoryParentList as $value) {
            $strCategoryURL = BASE_URL . '/danh-muc/' . $value['cate_slug'] . '-' . $value['cate_id'] . '.html';
            $url = $xml->addChild('url');
            $url->addChild('loc', $strCategoryURL);
//            $url->addChild('lastmod', date('c', time()));
            $url->addChild('changefreq', 'daily');
//            $url->addChild('priority', 0.9);

            if (!empty($value['cate_img_url'])) {
                $image = $url->addChild('image:image', null, 'http://www.google.com/schemas/sitemap-image/1.1');
                $image->addChild('image:loc', STATIC_URL . $value['cate_img_url'], 'http://www.google.com/schemas/sitemap-image/1.1');
                $image->addChild('image:caption', $value['cate_name'] . General::TITLE_META, 'http://www.google.com/schemas/sitemap-image/1.1');
            }
        }
        foreach ($arrCategoryByParent as $key => $arr) {
            foreach ($arr as $value) {
                $strCategoryURL = BASE_URL . '/danh-muc/' . $value['cate_slug'] . '-' . $value['cate_id'] . '.html';
                $url = $xml->addChild('url');
                $url->addChild('loc', $strCategoryURL);
//                $url->addChild('lastmod', date('c', time()));
                $url->addChild('changefreq', 'daily');
//                $url->addChild('priority', 0.9);
                if (!empty($value['cate_img_url'])) {
                    $image = $url->addChild('image:image', null, 'http://www.google.com/schemas/sitemap-image/1.1');
                    $image->addChild('image:loc', STATIC_URL . $value['cate_img_url'], 'http://www.google.com/schemas/sitemap-image/1.1');
                    $image->addChild('image:caption', $value['cate_name'] . General::TITLE_META, 'http://www.google.com/schemas/sitemap-image/1.1');
                }
            }
        }

        unlink(PUBLIC_PATH . '/xml/category.xml');
        $result = file_put_contents(PUBLIC_PATH . '/xml/category.xml', $xml->asXML());
        if ($result) {
            echo General::getColoredString("Sitemap category done", 'blue', 'cyan');
            $this->flush();
        }

        return true;
    }

    public function siteMapContent()
    {
        $instanceSearchContent = new \My\Search\Content();
        $intLimit = 2000;
        for ($intPage = 1; $intPage < 100; $intPage++) {

            $file = PUBLIC_PATH . '/xml/content-' . $intPage . '.xml';
            $arrContentList = $instanceSearchContent->getListLimit(['not_cont_status' => -1], $intPage, $intLimit, ['cont_id' => ['order' => 'desc']]);

            if (empty($arrContentList)) {
                break;
            }

            $doc = '<?xml version="1.0" encoding="UTF-8"?>';
            $doc .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            $doc .= '</urlset>';
            $xml = new \SimpleXMLElement($doc);
            $this->flush();

            foreach ($arrContentList as $arr) {
                $href = BASE_URL . '/bai-viet/' . $arr['cont_slug'] . '-' . $arr['cont_id'] . '.html';
                $url = $xml->addChild('url');
                $url->addChild('loc', $href);
//                $url->addChild('title', $arr['cont_title']);
//                $url->addChild('lastmod', date('c', time()));
                $url->addChild('changefreq', 'daily');
//                $url->addChild('priority', 0.7);

                if (!empty($arr['cont_main_image'])) {
                    $image = $url->addChild('image:image', null, 'http://www.google.com/schemas/sitemap-image/1.1');
                    $image->addChild('image:loc', $arr['cont_main_image'], 'http://www.google.com/schemas/sitemap-image/1.1');
                    $image->addChild('image:caption', $arr['cont_title'], 'http://www.google.com/schemas/sitemap-image/1.1');
                }
            }

            unlink($file);
            $result = file_put_contents($file, $xml->asXML());

            if ($result) {
                echo General::getColoredString("Site map complete content page {$intPage}", 'yellow', 'cyan');
                $this->flush();
            }

        }

        return true;
    }

    public function siteMapSearch()
    {
        $instanceSearchKeyword = new \My\Search\Keyword();
        $intLimit = 4000;
        for ($intPage = 1; $intPage < 10000; $intPage++) {
            $file = PUBLIC_PATH . '/xml/keyword-' . $intPage . '.xml';
            $arrKeyList = $instanceSearchKeyword->getListLimit(['not_cate_id' => -2], $intPage, $intLimit, ['key_id' => ['order' => 'asc']]);

            if (empty($arrKeyList)) {
                break;
            }

            $doc = '<?xml version="1.0" encoding="UTF-8"?>';
            $doc .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            $doc .= '</urlset>';
            $xml = new \SimpleXMLElement($doc);
            $this->flush();

            foreach ($arrKeyList as $arr) {
                $href = BASE_URL . '/tu-khoa/' . $arr['key_slug'] . '-' . $arr['key_id'] . '.html';
                $url = $xml->addChild('url');
                $url->addChild('loc', $href);
//                $url->addChild('lastmod', date('c', time()));
                $url->addChild('changefreq', 'daily');
//                $url->addChild('priority', 0.7);
            }

            unlink($file);
            $result = file_put_contents($file, $xml->asXML());

            if ($result) {
                echo General::getColoredString("Site map complete keyword page {$intPage}", 'yellow', 'cyan');
                $this->flush();
            }
        }
        return true;
    }

    private function sitemapOther()
    {
        $doc = '<?xml version="1.0" encoding="UTF-8"?>';
        $doc .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $doc .= '</urlset>';
        $xml = new \SimpleXMLElement($doc);
        $this->flush();
        $arrData = ['https://tintuc360.me/'];
        foreach ($arrData as $value) {
            $href = $value;
            $url = $xml->addChild('url');
            $url->addChild('loc', $href);
            $url->addChild('lastmod', date('c', time()));
            $url->addChild('changefreq', 'daily');
            $url->addChild('priority', 1);
        }

        unlink(PUBLIC_PATH . '/xml/other.xml');
        $result = file_put_contents(PUBLIC_PATH . '/xml/other.xml', $xml->asXML());
        if ($result) {
            echo General::getColoredString("Sitemap orther done", 'blue', 'cyan');
            $this->flush();
        }
    }

    public function crawlerAction()
    {
        $params = $this->request->getParams();
        $type = $params['type'];
        if (empty($type)) {
            $this->__khoahocTV();
        }

        if ($type == 'khoahocTV') {
            $this->__khoahocTV();
//            shell_exec("nohup php " . PUBLIC_PATH . "/index.php sitemap >/dev/null & echo 2>&1 & echo $!");
            return true;
        }

        //crawler xong thì tạo sitemap
//        shell_exec("nohup php " . PUBLIC_PATH . "/index.php sitemap >/dev/null & echo 2>&1 & echo $!");
        return true;
    }

    public function crawlerContentAction()
    {
//        $instanceSearchCategory = new \My\Search\Category();
//        $arr_category = $instanceSearchCategory->getList(['cate_status' => 1], [], ['cate_id' => ['order' => 'asc']]);

        $arr_category = [6, 1, 2, 3, 5, 7, 4];
        for ($i = 1; $i < 2; $i++) {
            foreach ($arr_category as $cate_id) {
                switch ($cate_id) {
                    case 1:
                        if ($i > 0 && $i < 11) {
                            $this->__kenh14Crawler($i, $cate_id);
                        }
                        break;
                    case 2:
                        $this->__emdepCrawler($i, 'http://emdep.vn/thoi-trang', $cate_id);
                        break;
                    case 3:
                        $this->__afamilyCrawler($i, 'http://afamily.vn/suc-khoe', $cate_id);
                        $this->__emdepCrawler($i, 'http://emdep.vn/song-khoe', $cate_id);
                        break;
                    case 4:
                        $this->__afamilyCrawler($i, 'http://afamily.vn/dep', $cate_id);
                        $this->__emdepCrawler($i, 'http://emdep.vn/lam-dep', $cate_id);
                        break;
                    case 5:
                        if ($i > 0 && $i < 11) {
                            $this->__24hCrawler($i, $cate_id);
                        }
                        $this->__emdepCrawler($i, 'http://emdep.vn/mon-ngon', $cate_id);
                        break;
                    case 6:
                        $this->__emdepCrawler($i, 'http://emdep.vn/lam-me', $cate_id);
                        break;
                    case 7:
                        $this->__ivivuCrawler($i, $cate_id);
                        break;
                }
            }
            echo \My\General::getColoredString("Finish page " . $i, 'white');
            sleep(5);
        }

        echo \My\General::getColoredString("DONE time: " . date('H:i:s'), 'light_cyan');
    }

    public function __khoahocTV()
    {
        $instanceSearchCategory = new \My\Search\Category();
        $arr_category = $instanceSearchCategory->getList(['cate_status' => 1], [], ['cate_sort' => ['order' => 'asc'], 'cate_id' => ['order' => 'asc']]);
        unset($instanceSearchCategory);
        $instanceSearchContent = new \My\Search\Content();

        $arr_pass = [
            'http://khoahoc.tv/chua-du-co-so-de-xac-dinh-nien-dai-thoc-thanh-den-29433',
            'http://khoahoc.tv/phat-hien-dia-bay-kim-loai-bay-gan-trai-dat-69779'
        ];

        $arr_pass_cate = [
            'http://khoahoc.tv/yhoc?p=223'
        ];
        foreach ($arr_category as $category) {
            try {
                if (empty($category['cate_crawler_url'])) {
                    continue;
                }
                for ($i = 1; $i >= 1; $i--) {
                    $source_url = $category['cate_crawler_url'] . '?p=' . $i;

                    if (in_array($source_url, $arr_pass_cate)) {
                        echo \My\General::getColoredString("Continue page cate = {$source_url} \n", 'red');
                        continue;
                    }

                    echo \My\General::getColoredString("Crawler page cate = {$source_url} \n", 'green');

                    $page_cate_content = General::crawler($source_url);
                    $page_cate_dom = HtmlDomParser::str_get_html($page_cate_content);

                    try {
                        $item_content_in_cate = $page_cate_dom->find('.listitem');
                    } catch (\Exception $exc) {
                        echo \My\General::getColoredString("Exception url = {$source_url} \n", 'red');
                        continue;
                    }

                    if (empty($item_content_in_cate)) {
                        continue;
                    }

                    foreach ($item_content_in_cate as $item_content) {
                        $arr_data_content = [];
                        $item_content_dom = HtmlDomParser::str_get_html($item_content->outertext);

                        try {
                            $item_content_source = 'http://khoahoc.tv' . $item_content_dom->find('a', 0)->href;
                        } catch (\Exception $exc) {
                            echo \My\General::getColoredString("Exception item cate url = {$source_url} \n", 'red');
                            continue;
                        }

                        if (in_array($item_content_source, $arr_pass)) {
                            echo \My\General::getColoredString("Pass url = {$item_content_source} \n", 'red');
                            continue;
                        }


                        echo \My\General::getColoredString("get url = {$item_content_source} \n", 'green');

                        try {
                            $item_content_title = trim($item_content_dom->find('.title', 0)->plaintext);
                        } catch (\Exception $exc) {
                            echo \My\General::getColoredString("Exception cannot get title url = {$item_content_source} \n", 'red');
                            continue;
                        }

                        $arr_data_content['cont_title'] = html_entity_decode($item_content_title);
                        $arr_data_content['cont_slug'] = General::getSlug(html_entity_decode($item_content_title));

                        try {
                            $item_content_description = html_entity_decode(trim($item_content_dom->find('.desc', 0)->plaintext));
                        } catch (\Exception $exc) {
                            echo \My\General::getColoredString("Exception cannot get description", 'red');
//                            continue;
                        }

                        try {
                            $img_avatar_url = $item_content_dom->find('img', 0)->src;
                        } catch (\Exception $exc) {
                            echo \My\General::getColoredString("Exception image title = {$item_content_title} \n", 'red');
//                            continue;
                        }

                        $arr_detail = $instanceSearchContent->getDetail(['cont_slug' => $arr_data_content['cont_slug'], 'not_cont_status' => -1]);

                        if (!empty($arr_detail)) {
                            continue;
                        }

                        //lấy hình đại diện
                        if (empty($img_avatar_url) || $img_avatar_url == 'http://img.khoahoc.tv/photos/image/blank.png') {
                            $arr_data_content['cont_main_image'] = STATIC_URL . '/f/v1/img/black.png';
                        } else {
                            $extension = end(explode('.', end(explode('/', $img_avatar_url))));
                            $name = $arr_data_content['cont_slug'] . '.' . $extension;
                            file_put_contents(STATIC_PATH . '/uploads/content/' . $name, General::crawler($img_avatar_url));
                            $arr_data_content['cont_main_image'] = STATIC_URL . '/uploads/content/' . $name;
                        }

                        //crawler nội dung bài đọc
                        $content_detail_page_dom = HtmlDomParser::str_get_html(General::crawler($item_content_source));

                        try {
                            $script = $content_detail_page_dom->find('script');
                        } catch (\Exception $exc) {
                            echo $exc->getMessage();
                            $script = null;
                            echo \My\General::getColoredString("Empty Script", 'red');
                        }
                        if (!empty($script)) {
                            foreach ($content_detail_page_dom->find('script') as $item) {
                                $item->outertext = '';
                            }
                            unset($script);
                        }

                        try {
                            $adbox = $content_detail_page_dom->find('.adbox');
                        } catch (\Exception $exc) {
                            $adbox = null;
                            echo \My\General::getColoredString("Empty adbox", 'red');
                        }

                        if (!empty($adbox)) {
                            foreach ($content_detail_page_dom->find('.adbox') as $item) {
                                $item->outertext = '';
                            }
                            unset($adbox);
                        }

                        try {
                            $content_detail_html = $content_detail_page_dom->find('.content-detail', 0);
                        } catch (\Exception $exc) {
                            echo \My\General::getColoredString("Empty .adbox", 'red');
                            continue;
                        }

                        try {
                            $content_detail_outertext = $content_detail_page_dom->find('.content-detail', 0)->outertext;
                        } catch (\Exception $exc) {
                            echo \My\General::getColoredString("Empty content-detail", 'red');
                            continue;
                        }

                        try {
                            $img_all = $content_detail_html->find("img");
                        } catch (\Exception $exc) {
                            $img_all = [];
                            echo \My\General::getColoredString("Empty images", 'red');
//                            continue;
                        }

                        //lấy hình ảnh trong bài
                        if (count($img_all) > 0) {
                            foreach ($img_all as $key => $im) {
                                $extension = end(explode('.', end(explode('/', $im->src))));
                                $name = $arr_data_content['cont_slug'] . '-' . ($key + 1) . '.' . $extension;
                                file_put_contents(STATIC_PATH . '/uploads/content/' . $name, General::crawler($im->src));
                                $content_detail_outertext = str_replace($im->src, STATIC_URL . '/uploads/content/' . $name, $content_detail_outertext);
                            }
                        }

                        //REPLACE ALL HREF TAG  A
                        $content_detail_outertext = str_replace('http://khoahoc.tv', BASE_URL, $content_detail_outertext);
                        $content_detail_outertext = str_replace('khoahoc.tv', 'khampha.tech', $content_detail_outertext);

                        $content_detail_outertext = trim(strip_tags($content_detail_outertext, '<a><div><img><b><p><br><span><br /><strong><h2><h1><h3><h4><table><td><tr><th><tbody><iframe>'));
                        $arr_data_content['cont_detail'] = html_entity_decode($content_detail_outertext);
                        $arr_data_content['created_date'] = time();
                        $arr_data_content['user_created'] = 1;
                        $arr_data_content['cate_id'] = $category['cate_id'];
                        $arr_data_content['cont_description'] = $item_content_description;
                        $arr_data_content['cont_status'] = 1;
                        $arr_data_content['cont_views'] = rand(1, rand(100, 1000));
                        $arr_data_content['method'] = 'crawler';
                        $arr_data_content['from_source'] = $item_content_source;
                        $arr_data_content['meta_keyword'] = str_replace(' ', ',', $arr_data_content['cont_title']);
                        $arr_data_content['updated_date'] = time();
                        unset($content_detail_outertext);
                        unset($img_all);
                        unset($img_avatar_url);
                        unset($content_detail_html);
                        unset($content_detail_page_dom);
                        unset($item_content_dom);

                        $serviceContent = $this->serviceLocator->get('My\Models\Content');
                        $id = $serviceContent->add($arr_data_content);

                        if ($id) {
                            echo \My\General::getColoredString("Crawler success 1 post id = {$id} \n", 'green');
                        } else {
                            echo \My\General::getColoredString("Can not insert content db", 'red');
                        }

                        unset($serviceContent);
                        unset($arr_data_content);
                        $this->flush();
                        continue;
                    }
                }
            } catch (\Exception $exc) {
                continue;
            }

        }
        echo \My\General::getColoredString("Crawler to success", 'green');
        return true;
    }

    public function initKeywordOldAction()
    {
        $instanceSearchKeyWord = new \My\Search\Keyword();
        $instanceSearchKeyWordOld = new \My\Search\KeywordOld();

        $intLimit = 2000;
        for ($intPage = 1; $intPage < 10000; $intPage++) {
            $arrList = $instanceSearchKeyWordOld->getListLimit(['full' => 1], $intPage, $intLimit, ['key_id' => ['order' => 'asc']]);

            if (empty($arrList)) {
                break;
            }

            foreach ($arrList as $arr) {
                //find in DB có tồn tại hay ko?
                $is_exits = $instanceSearchKeyWord->getDetail(['key_slug' => trim(General::getSlug($arr['key_name']))]);

                if ($is_exits) {
                    continue;
                }

                $arr_data = [
                    'key_name' => $arr['key_name'],
                    'key_slug' => trim(General::getSlug($arr['key_name'])),
                    'created_date' => time(),
                    'is_crawler' => 0
                ];

                $serviceKeyword = $this->serviceLocator->get('My\Models\Keyword');
                $int_result = $serviceKeyword->add($arr_data);
                unset($serviceKeyword);
                if ($int_result) {
                    echo \My\General::getColoredString("Insert success 1 row with id = {$int_result}", 'yellow');
                }
                $this->flush();
            }

            unset($arrList); //release memory
            $this->flush();
        }

        $instanceSearchKeyWord = new \My\Search\Keyword();
        $instanceSearchKeyWordOld = new \My\Search\KeywordOld();
        unset($instanceSearchKeyWord);
        unset($instanceSearchKeyWordOld);

        return true;
    }

    public function __afamilyCrawler($page, $url, $cate_id)
    {
        $num = 1;
        $instanceSearchContent = new \My\Search\Content();
        $upload_dir = General::mkdirUpload();

        $url_page = $url . '/trang-' . $page . '.chn';
        $content = General::crawler($url_page);
        $dom = HtmlDomParser::str_get_html($content);

        $results = $dom->find('div.catalogies div.sub_hot .sub_hotct h2 a, div.catalogies div.sub_hot .sub_hotct2 li h3 a, div.catalogies div.list-news1 h4 a');
        if (count($results) <= 0) {
            return 0;
        }
        foreach ($results as $key => $item) {
            $content = General::crawler('http://afamily.vn/' . $item->href);
            //$content = curl('http://afamily.vn/day-con-biet-boi-ngay-tai-nha-chi-voi-4-buoc-don-gian-2016060811132636.chn');

            if ($content == false) {
                continue;
            }
            $html = HtmlDomParser::str_get_html($content);

            $arr_data = array();
            if ($html->find('.detail_content', 0)) {

                $cont_title = html_entity_decode($html->find("h1.d-title", 0)->plaintext);
                $arr_data['cont_title'] = $cont_title;
                $arr_data['cont_slug'] = General::getSlug($cont_title);

                //check post exist
                $arr_detail = $instanceSearchContent->getDetail(
                    array(
                        'cont_slug' => $arr_data['cont_slug'],
                        'not_cont_status' => -1
                    )
                );
                if (!empty($arr_detail)) {
                    echo \My\General::getColoredString("Exist this content:" . $arr_data['cont_slug'], 'red');
                    continue;
                }

                //get content detail
                $cont_description = $html->find('.sapo', 0)->plaintext;
                $cont_detail = $html->find('.detail_content', 0)->outertext;
                $cont_detail = str_replace("<script>beforeAfter('.before-after');</script>", " ", $cont_detail);

                $link_content = $html->find("div.detail_content a");
                if (count($link_content) > 0) {
                    foreach ($link_content as $key => $link) {
                        $href = $link->href;
                        $cont_detail = str_replace($href, BASE_URL, $cont_detail);
                    }
                }
                //get image
                $arr_image = $html->find("div.detail_content img");
                $arr_data['cont_main_image'] = 'https://static.tintuc360.me/f/v1/images/no-image-available.jpg';
                $arr_data['cont_resize_image'] = 'https://static.tintuc360.me/f/v1/images/no-image-available.jpg';
                if (count($arr_image) > 0) {
                    foreach ($arr_image as $key => $img) {
                        $src = $img->src;
                        $extension = end(explode('.', end(explode('/', $src))));
                        $name_img = $arr_data['cont_slug'] . '_' . ($key + 1) . '.' . $extension;
                        $image_content = General::crawler($src);
                        if($image_content) {
                            file_put_contents($upload_dir['path'] . '/' . $name_img, $image_content);
                        } else {
                            $image_content = General::crawler('https://static.tintuc360.me/f/v1/images/no-image-available.jpg');
                            file_put_contents($upload_dir['path'] . '/' . $name_img, $image_content);
                        }
                        $cont_detail = str_replace($src, $upload_dir['url'] . '/' . $name_img, $cont_detail);
                        if ($key == 0) {
                            $arr_data['cont_main_image'] = $upload_dir['url'] . '/' . $name_img;
                            $arr_data['cont_resize_image'] = $upload_dir['url'] . '/' . $name_img;
                            $results = $this->resizeImage($upload_dir,$arr_data['cont_slug'], $extension, $cate_id);
                            if($results) {
                                $arr_data['cont_resize_image'] = $results;
                            }
                        }

                    }
                }

                $arr_data['cont_detail'] = html_entity_decode($cont_detail);
                $arr_data['cont_description'] = $cont_description;
                $arr_data['created_date'] = time();
                $arr_data['cate_id'] = $cate_id;
                $arr_data['cont_views'] = 0;
                $arr_data['cont_status'] = 1;
                $arr_data['from_source'] = 'afamily';

                //insert Data
                $serviceContent = $this->serviceLocator->get('My\Models\Content');
                $id = $serviceContent->add($arr_data);

                if ($id) {
                    echo \My\General::getColoredString("Crawler success 1 post id = {$id} \n", 'green');
                } else {
                    echo \My\General::getColoredString("Can not insert content db", 'red');
                }
            }
            sleep(1);
        }
    }

    public function __emdepCrawler($page, $url, $cate_id)
    {
        $instanceSearchContent = new \My\Search\Content();
        $upload_dir = General::mkdirUpload();

        $url_page = $url . '/page-' . $page . '.htm';
        $content = General::crawler($url_page);
        $dom = HtmlDomParser::str_get_html($content);
        $results = $dom->find('div.list-news li.news-item a');

        if (count($results) <= 0) {
            return;
        }

        foreach ($results as $key => $item) {
            $content = General::crawler('http://emdep.vn/' . $item->href);
            //$content = curl('http://afamily.vn/day-con-biet-boi-ngay-tai-nha-chi-voi-4-buoc-don-gian-2016060811132636.chn');

            if ($content == false) {
                continue;
            }
            $html = HtmlDomParser::str_get_html($content);

            $arr_data = array();
            if ($html->find('.article-content', 0)) {

                $cont_title = html_entity_decode($html->find("h1.lh", 0)->plaintext);
                $arr_data['cont_title'] = $cont_title;
                $arr_data['cont_slug'] = General::getSlug($cont_title);

                //check post exist
                $arr_detail = $instanceSearchContent->getDetail(
                    array(
                        'cont_slug' => $arr_data['cont_slug'],
                        'not_cont_status' => -1
                    )
                );
                if (!empty($arr_detail)) {
                    echo \My\General::getColoredString("Exist this content:" . $arr_data['cont_slug'], 'red');
                    continue;
                }

                //get content detail
                $cont_description = $html->find('div.top-content div.sapo span.xsubject', 0)->plaintext;
                $cont_description = str_replace('(Emdep.vn) - ', '', $cont_description);

                $html->find('.article-content .hide', 0)->innertext = '';
                $cont_detail = $html->find('.article-content', 0)->outertext;

                $link_content = $html->find("div.article-content a");
                if (count($link_content) > 0) {
                    foreach ($link_content as $key => $link) {
                        $href = $link->href;
                        $cont_detail = str_replace($href, BASE_URL, $cont_detail);
                    }
                }
                //get image
                $arr_data['cont_main_image'] = 'https://static.tintuc360.me/f/v1/images/no-image-available.jpg';
                $arr_data['cont_resize_image'] = 'https://static.tintuc360.me/f/v1/images/no-image-available.jpg';
                $arr_image = $html->find("div.article-content img");
                if (count($arr_image) > 0) {
                    foreach ($arr_image as $key => $img) {
                        $src = $img->src;
                        $extension = end(explode('.', end(explode('/', $src))));
                        $name_img = $arr_data['cont_slug'] . '_' . ($key + 1) . '.' . $extension;
                        $image_content = General::crawler($src);
                        if($image_content) {
                            file_put_contents($upload_dir['path'] . '/' . $name_img, $image_content);
                        } else {
                            $image_content = General::crawler('https://static.tintuc360.me/f/v1/images/no-image-available.jpg');
                            file_put_contents($upload_dir['path'] . '/' . $name_img, $image_content);
                        }
                        $cont_detail = str_replace($src, $upload_dir['url'] . '/' . $name_img, $cont_detail);
                        if ($key == 0) {
                            $arr_data['cont_main_image'] = $upload_dir['url'] . '/' . $name_img;
                            $arr_data['cont_resize_image'] = $upload_dir['url'] . '/' . $name_img;
                            $results = $this->resizeImage($upload_dir,$arr_data['cont_slug'], $extension, $cate_id);
                            if($results) {
                                $arr_data['cont_resize_image'] = $results;
                            }
                        }
                        sleep(1);
                    }
                }

                $arr_data['cont_detail'] = html_entity_decode($cont_detail);
                $arr_data['cont_description'] = $cont_description;
                $arr_data['created_date'] = time();
                $arr_data['cate_id'] = $cate_id;
                $arr_data['cont_views'] = 0;
                $arr_data['cont_status'] = 1;
                $arr_data['from_source'] = 'emdep';

                //insert Data
                $serviceContent = $this->serviceLocator->get('My\Models\Content');
                $id = $serviceContent->add($arr_data);

                if ($id) {
                    echo \My\General::getColoredString("Crawler success 1 post id = {$id} \n", 'green');
                } else {
                    echo \My\General::getColoredString("Can not insert content db", 'red');
                }
            }
            sleep(3);
        }
    }

    public function __24hCrawler($page, $cate_id)
    {
        $url = 'http://www.24h.com.vn/ajax/box_bai_viet_trang_su_kien/index/460/2552/1/13/0';
        $instanceSearchContent = new \My\Search\Content();
        $upload_dir = General::mkdirUpload();

        $url_page = $url . '?page=' . $page;
        $content = General::crawler($url_page);
        $dom = HtmlDomParser::str_get_html($content);
        $results = $dom->find('div.sktd-item div.xem-tiep a');

        if (count($results) <= 0) {
            return;
        }

        foreach ($results as $key => $item) {
            $content = General::crawler('http://www.24h.com.vn/' . $item->href);
            //$content = General::crawler('http://www.24h.com.vn/am-thuc/cu-cai-ham-xuong-ngon-ngot-thom-lung-c460a826908.html');

            if ($content == false) {
                continue;
            }
            $html = HtmlDomParser::str_get_html($content);

            $arr_data = array();
            $video = $html->find('.text-conent div[align=center]');
            if (count($video) == 0) {

                $cont_title = html_entity_decode($html->find("h1.baiviet-title", 0)->plaintext);
                $arr_data['cont_title'] = $cont_title;
                $arr_data['cont_slug'] = General::getSlug($cont_title);

                //check post exist
                $arr_detail = $instanceSearchContent->getDetail(
                    array(
                        'cont_slug' => $arr_data['cont_slug'],
                        'not_cont_status' => -1
                    )
                );
                if (!empty($arr_detail)) {
                    echo \My\General::getColoredString("Exist this content:" . $arr_data['cont_slug'], 'red');
                    continue;
                }

                //get content detail
                $cont_description = $html->find('div.div-baiviet p.baiviet-sapo', 0)->plaintext;

                $html->find('.text-conent div[itemprop=publisher]', 0)->outertext = '';
                $html->find('.text-conent div[itemprop=image]', 0)->outertext = '';
                $html->find('.text-conent .baiviet-bailienquan', 0)->outertext = '';
                $cont_detail = $html->find('.text-conent', 0)->outertext;
                $cont_detail = str_replace('<script type="text/javascript">window.onload = function () {resizeNewsImage("news-image", 500);}</script>', '', $cont_detail);
                $cont_detail = str_replace("return openNewImage(this, '')", 'return true', $cont_detail);


                //get image
                $arr_data['cont_main_image'] = 'https://static.tintuc360.me/f/v1/images/no-image-available.jpg';
                $arr_data['cont_resize_image'] = 'https://static.tintuc360.me/f/v1/images/no-image-available.jpg';
                $arr_image = $html->find("div.text-conent img");
                if (count($arr_image) > 0) {
                    foreach ($arr_image as $key => $img) {
                        $src = $img->src;
                        $extension = end(explode('.', end(explode('/', $src))));
                        $name_img = $arr_data['cont_slug'] . '_' . ($key + 1) . '.' . $extension;
                        $image_content = General::crawler($src);
                        if($image_content) {
                            file_put_contents($upload_dir['path'] . '/' . $name_img, $image_content);
                        } else {
                            $image_content = General::crawler('https://static.tintuc360.me/f/v1/images/no-image-available.jpg');
                            file_put_contents($upload_dir['path'] . '/' . $name_img, $image_content);
                        }
                        $cont_detail = str_replace($src, $upload_dir['url'] . '/' . $name_img, $cont_detail);
                        if ($key == 0) {
                            $arr_data['cont_main_image'] = $upload_dir['url'] . '/' . $name_img;
                            $arr_data['cont_resize_image'] = $upload_dir['url'] . '/' . $name_img;
                            $results = $this->resizeImage($upload_dir,$arr_data['cont_slug'], $extension, $cate_id);
                            if($results) {
                                $arr_data['cont_resize_image'] = $results;
                            }
                        }
                        sleep(1);
                    }
                }

                $arr_data['cont_detail'] = html_entity_decode($cont_detail);
                $arr_data['cont_description'] = $cont_description;
                $arr_data['created_date'] = time();
                $arr_data['cate_id'] = $cate_id;
                $arr_data['cont_views'] = 0;
                $arr_data['cont_status'] = 1;
                $arr_data['from_source'] = '24h';

                //insert Data
                $serviceContent = $this->serviceLocator->get('My\Models\Content');
                $id = $serviceContent->add($arr_data);

                if ($id) {
                    echo \My\General::getColoredString("Crawler success 1 post id = {$id} \n", 'green');
                } else {
                    echo \My\General::getColoredString("Can not insert content db", 'red');
                }
            }
            sleep(2);
        }
    }

    public function __ivivuCrawler($page, $cate_id)
    {
        $url = 'https://www.ivivu.com/blog/category/viet-nam/';
        $instanceSearchContent = new \My\Search\Content();
        $upload_dir = General::mkdirUpload();

        $url_page = $url . '/page/' . $page . '/';
        $content = General::crawler($url_page);
        $dom = HtmlDomParser::str_get_html($content);
        $results = $dom->find('div.archive-postlist h2 a');

        if (count($results) <= 0) {
            return;
        }

        foreach ($results as $key => $item) {
            $content = General::crawler($item->href);
            //$content = curl('http://afamily.vn/day-con-biet-boi-ngay-tai-nha-chi-voi-4-buoc-don-gian-2016060811132636.chn');

            if ($content == false) {
                continue;
            }
            $html = HtmlDomParser::str_get_html($content);

            $arr_data = array();
            if ($html->find('.entry-content', 0)) {

                $cont_title = html_entity_decode($html->find("h1.entry-title", 0)->plaintext);
                $arr_data['cont_title'] = $cont_title;
                $arr_data['cont_slug'] = General::getSlug($cont_title);

                //check post exist
                $arr_detail = $instanceSearchContent->getDetail(
                    array(
                        'cont_slug' => $arr_data['cont_slug'],
                        'not_cont_status' => -1
                    )
                );
                if (!empty($arr_detail)) {
                    echo \My\General::getColoredString("Exist this content:" . $arr_data['cont_slug'], 'red');
                    continue;
                }
                $cont_description = '';

                //get content detail
                $html->find('.entry-content .top-sns-wrap', 0)->innertext = '';
                //$html->find('.entry-content .ltt-contentbox', 0)-> innertext = '';
                $check = true;
                $i = 0;
                while ($check) {
                    $html->find(".entry-content .ltt-contentbox", $i)->innertext = '';
                    $i++;
                    if ($html->find(".entry-content .ltt-contentbox", $i)) {
                        $check = true;
                    } else {
                        $check = false;
                    }
                }
                $html->find('.entry-content .author', 0)->innertext = '';
                $html->find('.entry-content .updated', 0)->innertext = '';
                $html->find('.entry-content .post-rating-wrap', 0)->innertext = '';
                $html->find('.entry-content .bottom-like-share', 0)->innertext = '';
                $cont_detail = $html->find('.entry-content', 0)->outertext;

                $cont_detail = str_replace('Cẩm nang du lịch iVIVU.com', 'Cẩm nang du lịch', $cont_detail);
                $cont_detail = str_replace('iVIVU.com', 'Tintuc360', $cont_detail);
                $cont_detail = str_replace('IVIVU.COM', 'Tintuc360', $cont_detail);
                $link_content = $html->find("div.entry-content a");
                if (count($link_content) > 0) {
                    foreach ($link_content as $key => $link) {
                        $href = $link->href;
                        $cont_detail = str_replace($href, BASE_URL . '/danh-muc/du-lich-7.html', $cont_detail);
                    }
                }
                //get image
                $arr_data['cont_main_image'] = 'https://static.tintuc360.me/f/v1/images/no-image-available.jpg';
                $arr_data['cont_resize_image'] = 'https://static.tintuc360.me/f/v1/images/no-image-available.jpg';
                $arr_image = $html->find("div.entry-content img");
                if (count($arr_image) > 0) {
                    foreach ($arr_image as $key => $img) {
                        $src = $img->src;
                        $extension = end(explode('.', end(explode('/', $src))));
                        $name_img = $arr_data['cont_slug'] . '_' . ($key + 1) . '.' . $extension;
                        $image_content = General::crawler($src);
                        if($image_content) {
                            file_put_contents($upload_dir['path'] . '/' . $name_img, $image_content);
                        } else {
                            $image_content = General::crawler('https://static.tintuc360.me/f/v1/images/no-image-available.jpg');
                            file_put_contents($upload_dir['path'] . '/' . $name_img, $image_content);
                        }
                        $cont_detail = str_replace($src, $upload_dir['url'] . '/' . $name_img, $cont_detail);
                        if ($key == 0) {
                            $arr_data['cont_main_image'] = $upload_dir['url'] . '/' . $name_img;
                            $arr_data['cont_resize_image'] = $upload_dir['url'] . '/' . $name_img;
                            $results = $this->resizeImage($upload_dir,$arr_data['cont_slug'], $extension, $cate_id);
                            if($results) {
                                $arr_data['cont_resize_image'] = $results;
                            }
                        }
                        sleep(0.5);
                    }
                }

                $arr_data['cont_detail'] = html_entity_decode($cont_detail);
                $arr_data['cont_description'] = $cont_description;
                $arr_data['created_date'] = time();
                $arr_data['cate_id'] = $cate_id;
                $arr_data['cont_views'] = 0;
                $arr_data['cont_status'] = 1;
                $arr_data['from_source'] = 'ivivu';

                //insert Data
                $serviceContent = $this->serviceLocator->get('My\Models\Content');
                $id = $serviceContent->add($arr_data);

                if ($id) {
                    echo \My\General::getColoredString("Crawler success 1 post id = {$id} \n", 'green');
                } else {
                    echo \My\General::getColoredString("Can not insert content db", 'red');
                }
            }
            sleep(2);
        }
    }

    public function __kenh14Crawler($page, $cate_id)
    {
        $url = 'http://kenh14.vn/star.chn';

        $instanceSearchContent = new \My\Search\Content();
        $upload_dir = General::mkdirUpload();

        $url_page = $url;

        $content = General::crawler($url_page);
        $dom = HtmlDomParser::str_get_html($content);
        $results = $dom->find('li.ktncli h3.ktncli-title a,li.knswli h3.knswli-title a');

        if (count($results) <= 0) {
            return;
        }
        $results = array_reverse($results);
        foreach ($results as $key => $item) {
            $content = General::crawler('http://kenh14.vn' . $item->href);
            //$content = curl('http://afamily.vn/day-con-biet-boi-ngay-tai-nha-chi-voi-4-buoc-don-gian-2016060811132636.chn');

            if ($content == false) {
                continue;
            }
            $html = HtmlDomParser::str_get_html($content);

            $arr_data = array();
            if ($html->find('.knc-content', 0)) {

                $cont_title = html_entity_decode($html->find("h1.kbwc-title", 0)->plaintext);
                $arr_data['cont_title'] = $cont_title;
                $arr_data['cont_slug'] = General::getSlug($cont_title);

                //check post exist
                $arr_detail = $instanceSearchContent->getDetail(
                    array(
                        'cont_slug' => $arr_data['cont_slug'],
                        'not_cont_status' => -1
                    )
                );
                if (!empty($arr_detail)) {
                    echo \My\General::getColoredString("Exist this content:" . $arr_data['cont_slug'], 'red');
                    continue;
                }

                $cont_description = $html->find('h2.knc-sapo', 0)->plaintext;
                $cont_detail = $html->find('.knc-content', 0)->outertext;
                $cont_detail = str_replace('VCSortableInPreviewMode', '', $cont_detail);

//                    $link_content = $html->find("div.article-content a");
//                    if (count($link_content) > 0) {
//                        foreach ($link_content as $key => $link) {
//                            $href = $link->href;
//                            $cont_detail = str_replace('VCSortableInPreviewMode', '', $cont_detail);
//                        }
//                    }
                //get image
                $arr_data['cont_main_image'] = 'https://static.tintuc360.me/f/v1/images/no-image-available.jpg';
                $arr_data['cont_resize_image'] = 'https://static.tintuc360.me/f/v1/images/no-image-available.jpg';
                $arr_image = $html->find("div.knc-content img");
                if (count($arr_image) > 0) {
                    foreach ($arr_image as $key => $img) {
                        $src = $img->src;
                        $extension = end(explode('.', end(explode('/', $src))));
                        $name_img = $arr_data['cont_slug'] . '_' . ($key + 1) . '.' . $extension;
                        $image_content = General::crawler($src);
                        if($image_content) {
                            file_put_contents($upload_dir['path'] . '/' . $name_img, $image_content);
                        } else {
                            $image_content = General::crawler('https://static.tintuc360.me/f/v1/images/no-image-available.jpg');
                            file_put_contents($upload_dir['path'] . '/' . $name_img, $image_content);
                        }
                        $cont_detail = str_replace($src, $upload_dir['url'] . '/' . $name_img, $cont_detail);
                        if ($key == 0) {
                            $arr_data['cont_main_image'] = $upload_dir['url'] . '/' . $name_img;
                            $arr_data['cont_resize_image'] = $upload_dir['url'] . '/' . $name_img;
                            $results = $this->resizeImage($upload_dir,$arr_data['cont_slug'], $extension, $cate_id);
                            if($results) {
                                $arr_data['cont_resize_image'] = $results;
                            }
                        }
                        sleep(1);
                    }
                }

                $arr_data['cont_detail'] = html_entity_decode($cont_detail);
                $arr_data['cont_description'] = $cont_description;
                $arr_data['created_date'] = time();
                $arr_data['cate_id'] = $cate_id;
                $arr_data['cont_views'] = 0;
                $arr_data['cont_status'] = 1;
                $arr_data['from_source'] = 'kenh14';

                //insert Data
                $serviceContent = $this->serviceLocator->get('My\Models\Content');
                $id = $serviceContent->add($arr_data);

                if ($id) {
                    echo \My\General::getColoredString("Crawler success 1 post id = {$id} \n", 'green');
                } else {
                    echo \My\General::getColoredString("Can not insert content db", 'red');
                }
            }
            sleep(2);
        }
    }

    public function afamilyCrawlerKeyword($page, $url, $cate_id)
    {
        $url_page = $url . '/trang-' . $page . '.chn';
        $content = General::crawler($url_page);
        $dom = HtmlDomParser::str_get_html($content);

        $results = $dom->find('div.catalogies div.sub_hot .sub_hotct h2 a, div.catalogies div.sub_hot .sub_hotct2 li h3 a, div.catalogies div.list-news1 h4 a');
        if (count($results) <= 0) {
            return 0;
        }
        foreach ($results as $key => $item) {
            $content = General::crawler('http://afamily.vn/' . $item->href);
            //$content = curl('http://afamily.vn/day-con-biet-boi-ngay-tai-nha-chi-voi-4-buoc-don-gian-2016060811132636.chn');

            if ($content == false) {
                continue;
            }
            $html = HtmlDomParser::str_get_html($content);
            if ($html->find('.detail_content', 0)) {

                $cont_detail = $html->find('.detail_content', 0)->plaintext;
                $this->addKeywordDemo($cont_detail);
            }
            sleep(1);
        }
        return true;
    }

    public function emdepCrawlerKeyword($page, $url, $cate_id)
    {
        $url_page = $url . '/page-' . $page . '.htm';
        $content = General::crawler($url_page);
        $dom = HtmlDomParser::str_get_html($content);
        $results = $dom->find('div.list-news li.news-item a');

        if (count($results) <= 0) {
            return;
        }

        foreach ($results as $key => $item) {
            $content = General::crawler('http://emdep.vn/' . $item->href);
            //$content = curl('http://afamily.vn/day-con-biet-boi-ngay-tai-nha-chi-voi-4-buoc-don-gian-2016060811132636.chn');

            if ($content == false) {
                continue;
            }
            $html = HtmlDomParser::str_get_html($content);
            if ($html->find('.article-content', 0)) {

                $html->find('.article-content .hide', 0)->innertext = '';
                $cont_detail = $html->find('.article-content', 0)->plaintext;
                $this->addKeywordDemo($cont_detail);
            }
            sleep(1);
        }
    }

    public function _24hCrawlerKeyword($page, $cate_id)
    {
        $url = 'http://www.24h.com.vn/ajax/box_bai_viet_trang_su_kien/index/460/2552/1/13/0';

        $url_page = $url . '?page=' . $page;
        $content = General::crawler($url_page);
        $dom = HtmlDomParser::str_get_html($content);
        $results = $dom->find('div.sktd-item div.xem-tiep a');

        if (count($results) <= 0) {
            return;
        }

        foreach ($results as $key => $item) {
            $content = General::crawler('http://www.24h.com.vn/' . $item->href);
            //$content = General::crawler('http://www.24h.com.vn/am-thuc/cu-cai-ham-xuong-ngon-ngot-thom-lung-c460a826908.html');

            if ($content == false) {
                continue;
            }
            $html = HtmlDomParser::str_get_html($content);

            $arr_data = array();
            $video = $html->find('.text-conent div[align=center]');
            if (count($video) == 0) {
                //get content detail
                $cont_description = $html->find('div.div-baiviet p.baiviet-sapo', 0)->plaintext;

                $html->find('.text-conent div[itemprop=publisher]', 0)->outertext = '';
                $html->find('.text-conent div[itemprop=image]', 0)->outertext = '';
                $html->find('.text-conent .baiviet-bailienquan', 0)->outertext = '';
                $cont_detail = $html->find('.text-conent', 0)->plaintext;

                $this->addKeywordDemo($cont_detail);

            }
            sleep(1);
        }
    }

    public function ivivuCrawlerKeyword($page, $cate_id)
    {
        $url = 'https://www.ivivu.com/blog/category/viet-nam/';

        $url_page = $url . '/page/' . $page . '/';
        $content = General::crawler($url_page);
        $dom = HtmlDomParser::str_get_html($content);
        $results = $dom->find('div.archive-postlist h2 a');

        if (count($results) <= 0) {
            return;
        }

        foreach ($results as $key => $item) {
            $content = General::crawler($item->href);
            //$content = curl('http://afamily.vn/day-con-biet-boi-ngay-tai-nha-chi-voi-4-buoc-don-gian-2016060811132636.chn');

            if ($content == false) {
                continue;
            }
            $html = HtmlDomParser::str_get_html($content);

            if ($html->find('.entry-content', 0)) {

                $cont_detail = $html->find('.entry-content', 0)->plaintext;
                $this->addKeywordDemo($cont_detail);
            }
            sleep(1);
        }
    }

    public function kenh14CrawlerKeyword($page, $cate_id)
    {
        $url = 'http://kenh14.vn/star.chn';

        $url_page = $url;

        $content = General::crawler($url_page);
        $dom = HtmlDomParser::str_get_html($content);
        $results = $dom->find('li.ktncli h3.ktncli-title a,li.knswli h3.knswli-title a');

        if (count($results) <= 0) {
            return;
        }

        foreach ($results as $key => $item) {
            $content = General::crawler('http://kenh14.vn' . $item->href);
            //$content = curl('http://afamily.vn/day-con-biet-boi-ngay-tai-nha-chi-voi-4-buoc-don-gian-2016060811132636.chn');

            if ($content == false) {
                continue;
            }
            $html = HtmlDomParser::str_get_html($content);

            $arr_data = array();
            if ($html->find('.knc-content', 0)) {
                $cont_detail = $html->find('.knc-content', 0)->plaintext;
                $this->addKeywordDemo($cont_detail);
            }
            sleep(1);
        }
    }

    public function keywordContentAction()
    {
        $arr_category = [6, 1, 2, 3, 5, 7, 4];
        for ($i = 1; $i < 5; $i++) {
            foreach ($arr_category as $cate_id) {
                switch ($cate_id) {
                    case 1:
                        if ($i > 0 && $i < 2) {
                            $this->kenh14CrawlerKeyword($i, $cate_id);
                        }
                        break;
                    case 2:
                        $this->emdepCrawlerKeyword($i, 'http://emdep.vn/thoi-trang', $cate_id);
                        break;
                    case 3:
                        $this->afamilyCrawlerKeyword($i, 'http://afamily.vn/suc-khoe', $cate_id);
                        $this->emdepCrawlerKeyword($i, 'http://emdep.vn/song-khoe', $cate_id);
                        break;
                    case 4:
                        $this->afamilyCrawlerKeyword($i, 'http://afamily.vn/dep', $cate_id);
                        $this->emdepCrawlerKeyword($i, 'http://emdep.vn/lam-dep', $cate_id);
                        break;
                    case 5:
                        if ($i > 0 && $i < 11) {
                            $this->_24hCrawlerKeyword($i, $cate_id);
                        }
                        $this->emdepCrawlerKeyword($i, 'http://emdep.vn/mon-ngon', $cate_id);
                        break;
                    case 6:
                        $this->emdepCrawlerKeyword($i, 'http://emdep.vn/lam-me', $cate_id);
                        break;
                    case 7:
                        $this->ivivuCrawlerKeyword($i, $cate_id);
                        break;
                }
            }
            echo \My\General::getColoredString("Finish page " . $i, 'white');
            sleep(2);
        }

        echo \My\General::getColoredString("DONE time: " . date('H:i:s'), 'light_cyan');
    }

    public function addKeywordDemo($textContent)
    {

        $arr_stop_word = ["bị", "bởi", "cả", "các", "cái", "cần", "càng", "chỉ", "chiếc", "cho", "chứ", "chưa", "có", "thể", "cứ", "của", "cùng", "cũng", "đã", "đang", "đây", "để", "nỗi", "đều", "điều", "do", "đó", "được", "dưới", "gì", "khi", "không", "là", "lại", "lên", "lúc", "mà", "mỗi", "một", "này", "nên", "nếu", "ngay", "nhiều", "như", "nhưng", "những", "nơi", "nữa", "phải", "qua", "ra", "rằng", "rằng", "rất", "rất", "rồi", "sau", "sẽ", "so", "sự", "tại", "theo", "thì", "trên", "trước", "từ", "từng", "và", "vẫn", "vào", "vậy", "vì", "việc", "với", "vừa", "2014", "2015", "2016"];

        $arr_word_content = explode(" ", $textContent);
        $arr_word_content = array_filter($arr_word_content);
        $arr_word_content = array_diff($arr_word_content, $arr_stop_word);

        $instanceSearchKeyWord = new \My\Search\Keyword();
        foreach ($arr_word_content as $word) {

            if (preg_match('/[\'^£$%&*().:"}{@#~?><>,|=_+¬-]/', $word)) {
                continue;
            }
            if(strlen($word) > 7){
                continue;
            }

            $word_slug = trim(General::getSlug($word));
            $is_exsit = $instanceSearchKeyWord->getDetail(['key_slug' => $word_slug]);

            if ($is_exsit) {
                echo \My\General::getColoredString("Exsit keyword: " . $word_slug, 'red');
                continue;
            }

            $arr_data = [
                'key_name' => $word,
                'key_slug' => $word_slug,
                'created_date' => time(),
                'is_crawler' => 0,
                'cate_id' => -2,
                'key_weight' => 2,
            ];

            $serviceKeyword = $this->serviceLocator->get('My\Models\Keyword');
            $int_result = $serviceKeyword->add($arr_data);
            unset($serviceKeyword);
            if ($int_result) {
                echo \My\General::getColoredString("Insert success 1 row with id = {$int_result}", 'green');
            }
            $this->flush();
        }
        unset($instanceSearchKeyWord);
        return true;
    }

    public function resizeImage($upload_dir, $cont_slug, $extension, $cate_id){

        $path_old = $upload_dir['path'] . '/' . $cont_slug . '_1.' . $extension;
        if (!file_exists($path_old)) {
            $path_old = STATIC_PATH . '/f/v1/images/no-image-available.jpg';
        }
        $name_main_image = $cont_slug . '_main.' . $extension;
        $result = General::resizeImages($cate_id, $path_old, $name_main_image, $upload_dir['path']);
        if($result) {
            return $upload_dir['url'] . '/' . $cont_slug . '_main.' . $extension;
        } else {
            return false;
        }
    }

    public function postToFanpage($arrParams, $acc_share)
    {
        $config_fb = General::$configFB;
        $url_content = 'https://tintuc360.me/bai-viet/' . $arrParams['cont_slug'] . '-' . $arrParams['cont_id'] . '.html';
        $data = array(
            "access_token" => $config_fb['access_token'],
            "message" => $arrParams['cont_description'],
            "link" => $url_content,
            "picture" => $arrParams['cont_main_image'],
            "name" => $arrParams['cont_title'],
            "caption" => "tintuc360.me",
            "description" => $arrParams['cont_description']
        );
        $post_url = 'https://graph.facebook.com/' . $config_fb['fb_id'] . '/feed';

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $post_url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $return = curl_exec($ch);
            curl_close($ch);
            echo \My\General::getColoredString($return, 'green');
            unset($ch);

            if (!empty($return)) {
                $post_id = explode('_', json_decode($return, true)['id'])[1];
                foreach ($acc_share as $key => $value) {
                    $this->shareToWall([
                        'post_id' => $post_id,
                        'access_token' => $value,
                        'name' => $key
                    ]);
                }
            }

            echo \My\General::getColoredString("Post 1 content to facebook success cont_id = {$arrParams['cont_id']}", 'green');
            unset($ch, $return, $post_id, $data, $post_url, $url_content, $config_fb, $arrParams);
            $this->flush();
            return true;
        } catch (Exception $e) {
            echo \My\General::getColoredString($e->getMessage(), 'red');
            return true;
        }
    }

    public function shareToWall($arrParams)
    {
        $config_fb = General::$configFB;
        try {
            $fb = new \Facebook\Facebook([
                'app_id' => $config_fb['app_id'],
                'app_secret' => $config_fb['app_secret']
            ]);
            $fb->setDefaultAccessToken($arrParams['access_token']);
            $rp = $fb->post('/me/feed', ['link' => 'https://web.facebook.com/tintuc360.me/posts/' . $arrParams['post_id']]);
            echo \My\General::getColoredString(json_decode($rp->getBody(), true), 'green');
            echo \My\General::getColoredString('Share post id ' . $arrParams['post_id'] . ' to facebook ' . $arrParams['name'] . ' SUCCESS', 'green');
            unset($data, $return, $arrParams, $rp, $config_fb);
            return true;
        } catch (\Exception $exc) {
            echo \My\General::getColoredString($exc->getMessage(), 'red');
            echo \My\General::getColoredString('Share post id ' . $arrParams['post_id'] . ' to facebook ' . $arrParams['name'] . ' ERROR', 'red');
            return true;
        }
    }

    public function shareFacebookAction() {
        $instanceSearchContent = new \My\Search\Content();
        $params = $this->request->getParams();

        $cate_id = $params['cateId'];


        $arrContentList = $instanceSearchContent->getList(['not_cont_status' => -1,'cate_id' => $cate_id], ['cont_id' => ['order' => 'asc']], array('cont_id'));
        if (empty($arrContentList)) {
            return false;
        }
        $total = count($arrContentList);
        $index = rand(1,$total);

        $cont_id = $arrContentList[$index]['cont_id'];
        $contentDetail = $instanceSearchContent->getDetail(['cont_id' => $cont_id], array('cont_id','cate_id','cont_main_image','cont_slug','cont_description'));

        switch ($cate_id) {
            case General::CATEGORY_THOI_TRANG:
            case General::CATEGORY_LAM_DEP:
            case General::CATEGORY_DU_LICH:
                $acc_share = General::$acc_share_teen;
                break;
            case General::CATEGORY_SUC_KHOE:
            case General::CATEGORY_ME_VA_BE:
                $acc_share = General::$acc_share_old;
                break;
            default:
                $acc_share = General::$acc_share_teen;
                break;
        }
        $this->postToFanpage($contentDetail, $acc_share);

        return true;
    }

    public function getContentAction(){

        $params = $this->request->getParams();
        $PID = $params['pid'];
        if (!empty($PID)) {
            shell_exec('kill -9 ' . $PID);
        }

        $instanceSearchKeyword = new \My\Search\Keyword();
        $serviceKeyword = $this->serviceLocator->get('My\Models\Keyword');
        //
        $arr_keyword = $instanceSearchKeyword->getListLimit(['content_crawler' => 1], 1, 100, ['key_id' => ['order' => 'asc']]);

        foreach($arr_keyword as $keyword) {
            //$url = 'http://coccoc.com/composer?q=' . rawurlencode($keyword['key_name']) . '&p=0&reqid=UqRAi2nK&_=1480603345568';

            $url = 'https://www.google.com.vn/search?sclient=psy-ab&biw=1366&bih=212&espv=2&q=' . rawurlencode($keyword['key_name']) . '&oq=' . rawurlencode($keyword['key_name']);

            $content = General::crawler($url);
            $dom = HtmlDomParser::str_get_html($content);
            $results = $dom->find('span.st');

            $arr_content_crawler = array();
            foreach ($results as $item) {
                $arr_item = array(
                    'description' => $item->plaintext
                );

                $arr_content_crawler[] = $arr_item;
            }

            $arr_update = array(
                'content_crawler' => json_encode($arr_content_crawler)
            );
            $serviceKeyword->edit($arr_update, $keyword['key_id']);
            sleep(rand(4, 10));
        }
        $this->flush();
        unset($arr_keyword);
        exec("ps -ef | grep -v grep | grep getcontent | awk '{ print $2 }'", $PID);
        return shell_exec('php ' . PUBLIC_PATH . '/index.php getcontent --pid=' . current($PID));
    }

    public function setContentAction(){

        try {
            $filename = "Set_Content";
            $arrData = array();
            $params = $this->request->getParams();
            //
            $instanceSearchKeyword = new \My\Search\Keyword();
            $instanceSearchContent = new \My\Search\Content();
            $serviceKeyword = $this->serviceLocator->get('My\Models\Keyword');

            // $arrKeyList = $instanceSearchKeyword->getListLimit(['key_content' => 0,'not_cate_id' => -2,'key_id_greater' => 922000], 1, 1, ['key_id' => ['order' => 'asc']]);
            // print_r($arrKeyList);die;
            $intLimit = 1000;
            $intPage = $params['page'];

            //for($intPage = 1001; $intPage < 10000;$intPage ++){
            $arrKeyList = $instanceSearchKeyword->getLimit([], $intLimit, ['key_id' => ['order' => 'asc']]);

            if(empty($arrKeyList)) {
                return;
            }
            foreach ($arrKeyList as $keyword){
                $arr_condition_content = array(
                    'cont_status' => 1,
                    'full_text_title' => $keyword['key_name']
                );
                if ($keyword['cate_id'] != -1 && $keyword['cate_id'] != -2) {
                    $arr_condition_content['in_cate_id'] = array($keyword['cate_id']);
                }

                $arrContentList = $instanceSearchContent->getListLimit($arr_condition_content, 1, 15, ['_score' => ['order' => 'desc']],array('cont_id'));

                $text_cont_id = '';
                if(!empty($arrContentList)){
                    $arr_cont_id = array();
                    foreach ($arrContentList as $content){
                        $arr_cont_id[] = $content['cont_id'];
                    }
                    $text_cont_id = implode(',', $arr_cont_id);
                }

                $arr_update = array(
                    'key_content' => $text_cont_id,
                    'content_crawler' => '1'
                );
                $serviceKeyword->edit($arr_update, $keyword['key_id']);

                $arrData['Data']['Keyword'][] = $keyword['key_id'];
                $arrData['Params']['Page'] = $intPage;

                General::writeLog($filename, $arrData);

                $this->flush();
            }
            //}
            $next_page = $intPage + 1;
            return shell_exec("php /var/www/tintuc360/html/public/index.php setcontent --page=" . $next_page);
        } catch (\Exception $exc) {
            echo $exc->getMessage();
            die;
        }
    }

    public function checkProcessAction()
    {
        $params = $this->request->getParams();
        $process_name = $params['name'];
        if (empty($process_name)) {
            return true;
        }

        exec("ps -ef | grep -v grep | grep '.$process_name.' | awk '{ print $2 }'", $PID);
        exec("ps -ef | grep -v grep | grep getcontent | awk '{ print $2 }'", $current_PID);

        if (empty($PID)) {
            switch ($process_name) {
                case 'getcontent':
                    shell_exec('php ' . PUBLIC_PATH . '/index.php getcontent --pid=' . current($current_PID));
                    break;
            }
        }

        return true;
    }
}
