<?php

namespace My\Models;

class Content extends ModelAbstract {

    private function getParentTable() {
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        return new \My\Storage\storageContent($dbAdapter);
    }

    public function __construct() {
        return;
    }

    public function getList($arrCondition = array()) {
        return $this->getParentTable()->getList($arrCondition);
    }

    public function getListLimit($arrCondition, $intPage, $intLimit, $strOrder) {
        $arrResult = $this->getParentTable()->getListLimit($arrCondition, $intPage, $intLimit, $strOrder);
        return $arrResult;
    }

    public function getLimit($arrCondition = [], $intPage = 1, $intLimit = 15, $strOrder = 'cont_id DESC') {
        $arrResult = $this->getParentTable()->getLimit($arrCondition, $intPage, $intLimit, $strOrder);
        return $arrResult;
    }

    public function getLimitUnion($cateID) {
        return $this->getParentTable()->getLimitUnion($cateID);
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

    public function edit($p_arrParams, $intContentID) {
        $intResult = $this->getParentTable()->edit($p_arrParams, $intContentID);
        return $intResult;
    }

    public function multiEdit($p_arrParams, $arrCondition) {
        $intResult = $this->getParentTable()->multiEdit($p_arrParams, $arrCondition);
        return $intResult;
    }

}
