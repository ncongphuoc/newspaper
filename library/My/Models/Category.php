<?php

namespace My\Models;

class Category extends ModelAbstract {

    private function getParentTable() {
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        return new \My\Storage\storageCategory($dbAdapter);
    }

    public function __construct() {
    }

    public function getList($arrCondition = array()) {

        $arrResult = $this->getParentTable()->getList($arrCondition);
        return $arrResult;
    }

    public function getListLimit($arrCondition = [], $intPage = 1, $intLimit = 15, $strOrder = 'cate_id ASC') {
        $arrResult = $this->getParentTable()->getListLimit($arrCondition, $intPage, $intLimit, $strOrder);
        return $arrResult;
    }

    public function getTotal($arrCondition) {
        return $this->getParentTable()->getTotal($arrCondition);
    }

    public function getDetail($arrCondition) {
        $arrResult = $this->getParentTable()->getDetail($arrCondition);
        return $arrResult;
    }

    public function add($p_arrParams) {
        $intResult = $this->getParentTable()->add($p_arrParams);
        return $intResult;
    }

    public function edit($p_arrParams, $intCateID) {
        $intResult = $this->getParentTable()->edit($p_arrParams, $intCateID);
        return $intResult;
    }

    public function updateTree($dataUpdate) {
        return $this->getParentTable()->updateTree($dataUpdate);
    }

    public function updateStatusTree($dataUpdate) {
        return $this->getParentTable()->updateStatusTree($dataUpdate);
    }

}
