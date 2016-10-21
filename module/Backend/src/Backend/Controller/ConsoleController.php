<?php

namespace Backend\Controller;

use My\General,
    My\Controller\MyController,
    Sunra\PhpSimple\HtmlDomParser;

class ConsoleController extends MyController
{

    protected static $_arr_worker = [
        'content', 'logs', 'mail', 'category', 'user', 'general', 'keyword', 'group', 'permission'
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
        $arr_keyword = current($instanceSearchKeyWord->getListLimit(['is_crawler' => 0], 1, 1, ['key_id' => ['order' => 'asc']]));

        unset($instanceSearchKeyWord);
        if (empty($arr_keyword)) {
            return;
        }

        $serviceKeyword = $this->serviceLocator->get('My\Models\Keyword');
        $serviceKeyword->edit(array('is_crawler' => 1), $arr_keyword['key_id']);
        unset($serviceKeyword);

        $keyword = $arr_keyword['key_name'];

        foreach ($match as $key => $value) {
            if ($key == 0) {
                $key_match = $keyword . $value;
                $url = 'http://www.google.com/complete/search?output=search&client=chrome&q=' . rawurlencode($key_match) . '&hl=vi&gl=vn';
                $return = General::crawler($url);
                $this->add_keyword(json_decode($return)[1],$arr_keyword['cate_id']);
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
                    $this->add_keyword(json_decode($return)[1],$arr_keyword['cate_id']);
                    continue;
                }
            }
        };

        print_r("done");
        die;
        sleep(3);
        $this->getKeyword();
    }

    public function add_keyword($arr_key,$cate_id)
    {
        if (empty($arr_key)) {
            return false;
        }

        $arr_block_string = array(
            'http',
            '2013',
            '2014',
            '2015',
            '2016',
        );

        $instanceSearchKeyWord = new \My\Search\Keyword();
        foreach ($arr_key as $key_word) {
            $is_exsit = $instanceSearchKeyWord->getDetail(['key_slug' => trim(General::getSlug($key_word))]);

            if ($is_exsit) {
                continue;
            }
            $block = false;
            foreach ($arr_block_string as $string){
                if(strpos($key_word, $string)){
                    $block = true;
                }
            }

            if($block) {
                continue;
            }

            $arr_data = [
                'key_name' => $key_word,
                'key_slug' => trim(General::getSlug($key_word)),
                'created_date' => time(),
                'is_crawler' => 0,
                'cate_id' => $cate_id,
            ];

            $serviceKeyword = $this->serviceLocator->get('My\Models\Keyword');
            $int_result = $serviceKeyword->add($arr_data);
            unset($serviceKeyword);
            if ($int_result) {
                echo \My\General::getColoredString("Insert success 1 row with id = {$int_result}", 'yellow');
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
        $this->siteMapContent();
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

        $result = file_put_contents(PUBLIC_PATH . '/xml/sitemap-khampha.xml', $xml->asXML());
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
                $image->addChild('image:caption',$value['cate_name']. General::TITLE_META, 'http://www.google.com/schemas/sitemap-image/1.1');
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
                    $image->addChild('image:caption',$value['cate_name']. General::TITLE_META, 'http://www.google.com/schemas/sitemap-image/1.1');
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
        for ($intPage = 1; $intPage < 10000; $intPage++) {

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
            $arrKeyList = $instanceSearchKeyword->getListLimit(['full' => 1], $intPage, $intLimit, ['key_id' => ['order' => 'desc']]);

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
        $arrData = ['http://khampha.tech/'];
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
    public function testAction(){
        echo "<pre>";
        print_r("<div class=\"detail_content fl mgt15\">  <div>Lưu ý nhỏ trước khi<a href=\"http://dev.newspaper.vn\"> tập luyện</a>: Tập đúng số lượng các bài tập và đủ số hiệp liên tiếp nhau, nghỉ khoảng 45-60 giây giữa mỗi động tác. Tập full bài 3-4 ngày trong tuần (không tập trong hai ngày liên tiếp). Đặc biệt, những gì bạn cần để thực hiện 8 động tác này chỉ là một tấm thảm và khăn tập mà thôi! <br><br></div><div><span style=\"font-weight: bold;\">90 Degree Static press</span><br><br></div><div>Nằm ngửa, đầu gối vuông góc với hông, bàn chân gập lại, mở rộng vai và đặt lòng bàn tay lên phần đầu bắp đùi. Khi bạn thở ra, nâng đầu và vai khỏi mặt sàn khi ấn tay vào bắp đùi, hạ thấp phần thân trên khi bạn hít vào. Hít một hơi thật sâu, khi thở ra hóp bụng thật chặt, ấn chặt phần lưng dưới xuống mặt đất khi đưa gan bàn tay ấn vào bắp đùi, giữ nguyên tư thế trong khoảng 10 giây. <br><br></div><div>Thực hiện 3 hiệp mỗi hiệp 10 lần liên tiếp.<br><br><div style=\"text-align:center;\"><img src=\"http://dev.st.newspaper.vn/uploads/content/2016/10/21/bai-tap-dot-chay-mo-bung-duoi-cho-cac-quy-co-van-phong_1.gif\" w=\"480\" h=\"360\" alt=\"bài tập giảm mỡ bụng dưới\" title=\"bài tập giảm mỡ bụng dưới\"><br></div><br></div><div><span style=\"font-weight: bold;\">Resisted Single – Leg Stretch<br></span><br>Nằm ngửa, đầu ngẩng lên, hai chân kéo về phía ngực, bàn chân gập lại. Đan các ngón tay lại với nhau và đặt lên đùi phải, cùng lúc đó duỗi chân trái song song với mặt sàn. Ấn chặt gan bàn tay lên đùi phải, cố gắng đưa đầu gối bên phải lại gần phía ngực. Đổi chân và làm tương tự, đó là một lần. </div><div><br></div><div>Tập 3 hiệp mỗi hiệp 10 lần liên tiếp.<br><br><div style=\"text-align:center;\"><img src=\"http://dev.st.newspaper.vn/uploads/content/2016/10/21/bai-tap-dot-chay-mo-bung-duoi-cho-cac-quy-co-van-phong_2.gif\" w=\"480\" h=\"360\" alt=\"bài tập giảm mỡ bụng dưới\" title=\"bài tập giảm mỡ bụng dưới\"><br></div><br></div><div><span style=\"font-weight: bold;\">U-Boat</span><br><br></div><div>Ngồi trên mặt sàn, ngả người ra phía sau, chống hai khuỷu tay xuống mặt sàn, bàn tay úp xuống. Hóp bụng thật chặt rồi nâng cẳng chân lên tạo thành một góc 90 độ, từ từ đưa hai cẳng chân sang bên trái rồi đưa sang bên phải (Hông vẫn ở nguyên trên mặt sàn). Động tác này nhìn rất giống bạn đang vẽ chữ U với hai đầu gối. <br><br></div><div>Thực hiện động tác này 20 lần.<br><br><div style=\"text-align:center;\"><img src=\"http://dev.st.newspaper.vn/uploads/content/2016/10/21/bai-tap-dot-chay-mo-bung-duoi-cho-cac-quy-co-van-phong_3.gif\" w=\"480\" h=\"360\" alt=\"bài tập giảm mỡ bụng dưới\" title=\"bài tập giảm mỡ bụng dưới\"><br></div><br></div><div><span style=\"font-weight: bold;\">Reverse Plank Hover<br><br></span></div><div>Ngồi duỗi thẳng hai chân, chân gập lại, hai bàn tay đặt phía bên ngoài hông và úp xuống mặt sàn. Hít vào, hóp chặt bụng và dùng lực bả vai nhấc hông khỏi mặt sàn một vài inches, hơi uốn nhẹ đầu gối gót chân vẫn chống xuống sàn. Thở ra, duỗi thẳng chân và cố gắng đưa hông ra phía sau hai vai. Quay trở lại tư thế ban đầu. <br><br></div><div>Thực hiện ba hiệp, mỗi hiệp 10 lần.<br><br><div style=\"text-align:center;\"><img src=\"http://dev.st.newspaper.vn/uploads/content/2016/10/21/bai-tap-dot-chay-mo-bung-duoi-cho-cac-quy-co-van-phong_4.gif\" w=\"480\" h=\"360\" alt=\"bài tập giảm mỡ bụng dưới\" title=\"bài tập giảm mỡ bụng dưới\"><br></div><br></div><div><span style=\"font-weight: bold;\">Criss – Cross Lift and Switch<br><br></span></div><div>Nằm ngửa, khép chặt hai cánh tay trên mặt sàn, vắt chéo hai chân lên phía trên, hít vào hóp bụng thật chặt, hai cẳng chân tạo thành một góc 45 độ. Khi thở ra, đưa hai chân qua đầu, nâng hông khỏi mặt sàn bằng lực của hai cánh tay. Giữ nguyên khoảng 10 giây, sau đó đưa trở lại tư thế ban đầu. <br><br></div><div>Làm 3 hiệp mỗi hiệp 10 lần.<br><br><div style=\"text-align:center;\"><img src=\"http://dev.st.newspaper.vn/uploads/content/2016/10/21/bai-tap-dot-chay-mo-bung-duoi-cho-cac-quy-co-van-phong_5.gif\" w=\"480\" h=\"360\" alt=\"bài tập giảm mỡ bụng dưới\" title=\"bài tập giảm mỡ bụng dưới\"><br></div><span style=\"font-weight: bold;\"><br></span></div><div><span style=\"font-weight: bold;\">Inching elbow plank<br><br></span></div><div>Thực hiện động tác Elbow <a href=\"http://dev.newspaper.vn\">Plank</a>, hai chân rộng bằng vai. Kiễng hai chân, đưa hông lên cao, tạo thành một hình tam giác. </div><div><br></div><div>Thực hiện 3 hiệp mỗi hiệp 5 lần.<br><br><div style=\"text-align:center;\"><img src=\"http://dev.st.newspaper.vn/uploads/content/2016/10/21/bai-tap-dot-chay-mo-bung-duoi-cho-cac-quy-co-van-phong_6.gif\" w=\"480\" h=\"360\" alt=\"bài tập giảm mỡ bụng dưới\" title=\"bài tập giảm mỡ bụng dưới\"><br></div><br></div><div><span style=\"font-weight: bold;\">Full Plank Passe Twist</span><br><br></div><div>Bắt đầu với tư thế Full Plank, gập đầu gối phải và đưa về phía bên trái, sau đó trở lại vị trí ban đầu. Đổi chân và làm tương tự. </div><div><br></div><div>Thực hiện 3 hiệp, mỗi hiệp 10 lần. <br><br><div style=\"text-align:center;\"><img src=\"http://dev.st.newspaper.vn/uploads/content/2016/10/21/bai-tap-dot-chay-mo-bung-duoi-cho-cac-quy-co-van-phong_7.gif\" w=\"480\" h=\"360\" alt=\"bài tập giảm mỡ bụng dưới\" title=\"bài tập giảm mỡ bụng dưới\"><br></div><br></div><div><span style=\"font-weight: bold;\">Frog Press</span><br><br></div><div>Nằm ngửa khỏi mặt đất, hai đầu gối thu vào sát thân mình, gót chân chạm vào nhau. Hít vào và nhấc đầu và vai khỏi mặt đất, mắt nhìn xuống chân. Gan bàn tay úp xuống.</div><div><br></div><div>Thở ra, xoay gót chân ra phía bên ngoài, duỗi thẳng chân. Hít vào và đưa chân lại vị trí cũ. </div><div><br></div><div>Thực hiện 3 hiệp, mỗi hiệp 10 lần.<br><br><div style=\"text-align:center;\"><img src=\"http://dev.st.newspaper.vn/uploads/content/2016/10/21/bai-tap-dot-chay-mo-bung-duoi-cho-cac-quy-co-van-phong_8.gif\" w=\"480\" h=\"360\" alt=\"bài tập giảm mỡ bụng dưới\" title=\"bài tập giảm mỡ bụng dưới\"><br></div><br><div style=\"text-align: right;\"><span style=\"font-style: italic;\">(Phối hợp cùng NShapr Fitness thực hiện)</span></div></div><div style=\"text-align: center;\"><div style=\"margin: 0px; padding: 0px; color: rgb(51, 51, 51); font-size: 16px; -webkit-text-stroke: 0.1px rgba(255, 255, 255, 0.00784314); background-color: rgb(255, 255, 255); text-align: left;\"></div></div>         </div>");
        echo "</pre>";
        die;
        $instanceSearchContent = new \My\Search\Content();

        $upload_dir = General::mkdirUpload();

        for ($i = 1; $i <= 1; $i++){
            $url_child = 'http://afamily.vn/suc-khoe/trang-' . $i . '.chn';

            $content = General::crawler($url_child);
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
                if($html->find('.detail_content', 0)){

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
                        continue;
                    }

                    //get content detail
                    $cont_description = $html->find('.sapo', 0) -> outertext;
                    $cont_detail = $html->find('.detail_content', 0) -> outertext;
                    $cont_detail = str_replace("<script>beforeAfter('.before-after');</script>", " " , $cont_detail);

                    $link_content = $html->find("div.detail_content a");
                    if (count($link_content) > 0) {
                        foreach ($link_content as $key => $link) {
                            $href = $link->href;
                            $cont_detail = str_replace($href, BASE_URL, $cont_detail);
                        }
                    }
                    //get image
                    $arr_image = $html->find("div.detail_content img");
                    if (count($arr_image) > 0) {
                        foreach ($arr_image as $key => $img) {
                            $src = $img->src;
                            $extension = end(explode('.', end(explode('/', $src))));
                            $name_img = $arr_data['cont_slug'] . '_' . ($key + 1) . '.' . $extension;
                            file_put_contents($upload_dir['path'] . '/' . $name_img, General::crawler($src));
                            $cont_detail = str_replace($src, $upload_dir['url'] . '/' . $name_img, $cont_detail);
                            if ($key == 0) {
                                $arr_data['cont_main_image'] = $upload_dir['url'] . '/' . $name_img;
                            }

                        }
                    }

                    $arr_data['cont_detail'] = html_entity_decode($cont_detail);
                    $arr_data['cont_description'] = $cont_description;
                    $arr_data['created_date'] = time();
                    $arr_data['cate_id'] = 1;
                    $arr_data['cont_views'] = 0;
                    $arr_data['cont_status'] = 1;
                    $arr_data['from_source'] = 'afamily/suc-khoe';

                    //insert Data
                    $serviceContent = $this->serviceLocator->get('My\Models\Content');
                    $id = $serviceContent->add($arr_data);

                    if ($id) {
                        echo \My\General::getColoredString("Crawler success 1 post id = {$id} \n", 'green');
                    } else {
                        echo \My\General::getColoredString("Can not insert content db", 'red');
                    }
                }
die("finish");
                sleep(3);
            }
        }
    }
}
