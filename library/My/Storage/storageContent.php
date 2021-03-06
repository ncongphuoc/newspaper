<?php

namespace My\Storage;

use Zend\Db\TableGateway\AbstractTableGateway,
    Zend\Db\Sql\Sql,
    Zend\Db\Adapter\Adapter,
    My\Validator\Validate,
    Zend\Db\TableGateway\TableGateway;

class storageContent extends AbstractTableGateway {

    protected $table = 'tbl_contents';

    public function __construct(Adapter $adapter) {
        $adapter->getDriver()->getConnection()->connect();
        $this->adapter = $adapter;
    }

    public function __destruct() {
        $this->adapter->getDriver()->getConnection()->disconnect();
    }

    public function getList($arrCondition = array(), $arrFields = '*') {
        try {
            $strWhere = $this->_buildWhere($arrCondition);
            $adapter = $this->adapter;
//            $sql = new Sql($adapter);
//            $select = $sql->Select($this->table)
//                    ->where('1=1' . $strWhere)
//                    ->order(array('cont_id DESC'));
//
//            $query = $sql->getSqlStringForSqlObject($select);

            $query = 'select ' . $arrFields
                . ' from ' . $this->table
                . ' where 1=1 ' . $strWhere;
            return $adapter->query($query, $adapter::QUERY_MODE_EXECUTE)->toArray();
        } catch (\Zend\Http\Exception $exc) {
            if (APPLICATION_ENV !== 'production') {
                die($exc->getMessage());
            }
            return array();
        }
    }

    public function getListLimit($arrCondition = [], $intPage = 1, $intLimit = 15, $strOrder, $arrFields = '*') {
        try {
            $strWhere = $this->_buildWhere($arrCondition);
            $adapter = $this->adapter;
//            $sql = new Sql($adapter);
//            $select = $sql->Select($this->table)
//                    ->where('1=1' . $strWhere)
//                    ->order($strOrder)
//                    ->limit($intLimit)
//                    ->offset($intLimit * ($intPage - 1));
//            $query = $sql->getSqlStringForSqlObject($select);
            
            $query = 'select ' . $arrFields
                . ' from ' . $this->table 
                . ' where 1=1 ' . $strWhere
                . ' order by ' . $strOrder
                . ' limit ' . $intLimit
                . ' offset ' . ($intLimit * ($intPage - 1));
            return $adapter->query($query, $adapter::QUERY_MODE_EXECUTE)->toArray();
        } catch (\Zend\Http\Exception $exc) {
            if (APPLICATION_ENV !== 'production') {
                die($exc->getMessage());
            }
            return array();
        }
    }

    public function getDetail($arrCondition = array()) {
        try {
            $strWhere = $this->_buildWhere($arrCondition);
            $adapter = $this->adapter;
            $sql = new Sql($adapter);
            $select = $sql->Select($this->table)
                    ->where('1=1' . $strWhere);
            $query = $sql->getSqlStringForSqlObject($select);

            return current($adapter->query($query, $adapter::QUERY_MODE_EXECUTE)->toArray());
        } catch (\Zend\Http\Exception $exc) {
            if (APPLICATION_ENV !== 'production') {
                die($exc->getMessage());
            }
            return array();
        }
    }

    public function getTotal($arrCondition = []) {
        try {
            $strWhere = $this->_buildWhere($arrCondition);
            $adapter = $this->adapter;
            $sql = new Sql($adapter);
            $select = $sql->Select($this->table)
                    ->columns(array('total' => new \Zend\Db\Sql\Expression('COUNT(*)')))
                    ->where('1=1' . $strWhere);
            $query = $sql->getSqlStringForSqlObject($select);
            return (int) current($adapter->query($query, $adapter::QUERY_MODE_EXECUTE)->toArray())['total'];
        } catch (\Zend\Http\Exception $exc) {
            if (APPLICATION_ENV !== 'production') {
                die($exc->getMessage());
            }
            return false;
        }
    }

    public function add($p_arrParams) {
        try {
            if (!is_array($p_arrParams) || empty($p_arrParams)) {
                return false;
            }
            $adapter = $this->adapter;
            $sql = new Sql($adapter);
            $insert = $sql->insert($this->table)->values($p_arrParams);
            $query = $sql->getSqlStringForSqlObject($insert);
            $adapter->createStatement($query)->execute();
            $cont_id = $adapter->getDriver()->getLastGeneratedValue();
            if ($cont_id) {
                $p_arrParams['cont_id'] = $cont_id;
//                $instanceJob = new \My\Job\JobContent();
//                $instanceJob->addJob(SEARCH_PREFIX . 'writeContent', $p_arrParams);
                $instanceSearch = new \My\Search\Content();
                $arrDocument = new \Elastica\Document($cont_id, $p_arrParams);
                $intResult = $instanceSearch->add($arrDocument);
            }
            return $cont_id;
        } catch (\Exception $exc) {
            echo '<pre>';
            print_r($exc->getMessage());
            echo '</pre>';
            die();
            if (APPLICATION_ENV !== 'production') {
                die($exc->getMessage());
            }
            return false;
        }
    }

    public function edit($p_arrParams, $intProductID) {
        try {
            if (!is_array($p_arrParams) || empty($p_arrParams) || empty($intProductID)) {
                return false;
            }
            $result = $this->update($p_arrParams, 'cont_id=' . $intProductID);
            if ($result) {
//                $p_arrParams['cont_id'] = $intProductID;
//                $instanceJob = new \My\Job\JobContent();
//                $instanceJob->addJob(SEARCH_PREFIX . 'editContent', $p_arrParams);
                $updateData = new \Elastica\Document();
                $updateData->setData($p_arrParams);
                $document = new \Elastica\Document($intProductID, $p_arrParams);
                $document->setUpsert($updateData);

                $instanceSearch = new \My\Search\Content();
                $resutl = $instanceSearch->edit($document);
            }
            return $result;
        } catch (\Exception $exc) {
            if (APPLICATION_ENV !== 'production') {
                die($exc->getMessage());
            }
            return false;
        }
    }

    private function _buildWhere($arrCondition) {
        $strWhere = '';

        if (!empty($arrCondition['cont_slug'])) {
            $strWhere .= " AND cont_slug= '" . $arrCondition['cont_slug'] . "'";
        }

        if (!empty($arrCondition['cont_id'])) {
            $strWhere .= " AND cont_id= " . $arrCondition['cont_id'];
        }

        if (!empty($arrCondition['cont_title'])) {
            $strWhere .= " AND cont_title= " . $arrCondition['cont_title'];
        }

        if (isset($arrCondition['cont_status'])) {
            $strWhere .= " AND cont_status = " . $arrCondition['cont_status'];
        }

        if (isset($arrCondition['not_cont_status'])) {
            $strWhere .= " AND cont_status != " . $arrCondition['not_cont_status'];
        }

        if (!empty($arrCondition['cate_id'])) {
            $strWhere .= " AND cate_id = " . $arrCondition['cate_id'];
        }

        if (!empty($arrCondition['in_cate_id'])) {
            $strWhere .= " AND cate_id IN (" . $arrCondition['in_cate_id'] . ")";
        }

        if (!empty($arrCondition['not_cont_id'])) {
            $strWhere .= " AND cont_id != " . $arrCondition['not_cont_id'];
        }

        if (!empty($arrCondition['in_cont_id'])) {
            $strWhere .= " AND cont_id IN (" . $arrCondition['in_cont_id'] . ")";
        }

        return $strWhere;
    }

}
