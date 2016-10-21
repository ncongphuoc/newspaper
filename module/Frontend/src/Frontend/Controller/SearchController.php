<?php

namespace Frontend\Controller;

use My\Controller\MyController,
    My\General;

class SearchController extends MyController
{

    public function __construct()
    {
//        $this->externalJS = [
//            STATIC_URL . '/f/v1/js/my/??search.js'
//        ];
    }

    public function indexAction()
    {
        try {
            $params = array_merge($this->params()->fromRoute(), $this->params()->fromQuery());

            if (empty($params['keyword'])) {
                return $this->redirect()->toRoute('404');
            }

            $key_name = General::clean($params['keyword']);
            $intPage = (int)$params['page'] > 0 ? (int)$params['page'] : 1;
            $intLimit = 20;

            $arr_condition_content = [
                'cont_status' => 1,
                'full_text_title' => $key_name
            ];

            $instanceSearchContent = new \My\Search\Content();
            $arrContentList = $instanceSearchContent->getListLimit($arr_condition_content, $intPage, $intLimit, ['_score' => ['order' => 'desc']]);

            //phân trang
            $intTotal = $instanceSearchContent->getTotal($arr_condition_content);
            $helper = $this->serviceLocator->get('viewhelpermanager')->get('Paging');
            $paging = $helper($params['module'], $params['__CONTROLLER__'], $params['action'], $intTotal, $intPage, $intLimit, 'search', $params);

            $this->renderer = $this->serviceLocator->get('Zend\View\Renderer\PhpRenderer');
            $this->renderer->headMeta()->appendName('dc.description', $params['keyword']);
            $this->renderer->headTitle('Tìm kiếm - ' . $params['keyword'] . General::TITLE_META);
            $this->renderer->headMeta()->appendName('keywords', General::KEYWORD_DEFAULT . $params['keyword']);
            $this->renderer->headMeta()->appendName('description', $params['keyName']);
            $this->renderer->headMeta()->setProperty('og:url', $this->url()->fromRoute('search', ['keyword' => $params['keyword'], 'page' => $intPage]));
            $this->renderer->headMeta()->setProperty('og:title', $params['keyword']);
            $this->renderer->headMeta()->setProperty('og:description', $params['keyword']);

            //get 50 keyword gần giống nhất
            $instanceSearchKeyword = new \My\Search\Keyword();
            $arrKeywordList = $instanceSearchKeyword->getListLimit(['full_text_keyname' => $key_name], 1, $intLimit, ['_score' => ['order' => 'desc']]);

            return [
                'paging' => $paging,
                'params' => $params,
                'arrContentList' => $arrContentList,
                'arrKeywordList' => $arrKeywordList,
                'intTotal' => $intTotal
            ];
        } catch (\Exception $exc) {
            echo '<pre>';
            print_r([
                'code' => $exc->getCode(),
                'messages' => $exc->getMessage()
            ]);
            echo '</pre>';
            die();
        }
    }

    public function keywordAction()
    {
        try {
            $params = array_merge($this->params()->fromRoute(), $this->params()->fromQuery());
            $key_id = (int)$params['keyId'];
            $key_slug = $params['keySlug'];

            if (empty($key_id)) {
                return $this->redirect()->toRoute('404', array());
            }

            $instanceSearch = new \My\Search\Keyword();
            $arrKeyDetail = $instanceSearch->getDetail(['key_id' => $key_id]);
            if (empty($arrKeyDetail)) {
                return $this->redirect()->toRoute('404', array());
            }

            $intPage = is_numeric($params['page']) ? $params['page'] : 1;
            $intLimit = 20;

            $instanceSearchContent = new \My\Search\Content();
            $arrContentList = $instanceSearchContent->getListLimit(['full_text_title' => $arrKeyDetail['key_name']], $intPage, $intLimit, ['_score' => ['order' => 'desc']]);
            $intTotal = $instanceSearchContent->getTotal(['cont_title' => $arrKeyDetail['key_name']]);
            $helper = $this->serviceLocator->get('viewhelpermanager')->get('Paging');
            $paging = $helper($params['module'], $params['__CONTROLLER__'], $params['action'], $intTotal, $intPage, $intLimit, 'keyword', $params);

            $this->renderer = $this->serviceLocator->get('Zend\View\Renderer\PhpRenderer');
            $this->renderer->headMeta()->appendName('dc.description', html_entity_decode($arrKeyDetail['key_name']) . General::TITLE_META);
            $this->renderer->headMeta()->appendName('dc.subject', html_entity_decode($arrKeyDetail['key_name']) . General::TITLE_META);
            $this->renderer->headTitle('Từ khoá - ' . html_entity_decode($arrKeyDetail['key_name']) . General::TITLE_META);
            $this->renderer->headMeta()->appendName('keywords', html_entity_decode($arrKeyDetail['key_name']));
            $this->renderer->headMeta()->appendName('description', html_entity_decode('Danh sách bài viết trong từ khoá : ' . $arrKeyDetail['key_name'] . General::TITLE_META));
            $this->renderer->headMeta()->appendName('social', null);
            $this->renderer->headMeta()->setProperty('og:url', $this->url()->fromRoute('keyword', array('keySlug' => $arrKeyDetail['key_slug'], 'keyId' => $arrKeyDetail['key_id'], 'page' => $intPage)));
            $this->renderer->headMeta()->setProperty('og:title', html_entity_decode('Danh sách bài viết trong từ khoá : ' . $arrKeyDetail['key_name'] . General::TITLE_META));
            $this->renderer->headMeta()->setProperty('og:description', html_entity_decode('Danh sách bài viết trong từ khoá : ' . $arrKeyDetail['key_name'] . General::TITLE_META));

            /*
             * get 20 keyword tương tự
             */
            $arrKeywordList = $instanceSearch->getListLimit(['full_text_keyname' => $arrKeyDetail['key_name'], 'not_key_id' => $key_id], 1, $intLimit, ['_score' => ['order' => 'desc']]);

            return array(
                'params' => $params,
                'arrKeywordList' => $arrKeywordList,
                'paging' => $paging,
                'intPage' => $intPage,
                'intTotal' => $intTotal,
                'arrContentList' => $arrContentList,
                'arrKeyDetail' => $arrKeyDetail
            );
        } catch (\Exception $exc) {
            echo '<pre>';
            print_r([
                'code' => $exc->getCode(),
                'messages' => $exc->getMessage()
            ]);
            echo '</pre>';
            die();
        }
    }

    public function listKeywordAction()
    {
        try {
            $params = array_merge($this->params()->fromRoute(), $this->params()->fromQuery());
            $intPage = is_numeric($params['page']) ? $params['page'] : 1;
            $intLimit = 100;

            $instanceSearch = new \My\Search\Keyword();

            $arrCondition = array(
                'word_id_less' => round((time() - 1465036100) / 4)
            );
            $arrKeywordList = $instanceSearch->getListLimit($arrCondition, $intPage, $intLimit, ['key_id' => ['order' => 'desc']]);
            $intTotal = $instanceSearch->getTotal($arrCondition);
            $helper = $this->serviceLocator->get('viewhelpermanager')->get('Paging');
            $paging = $helper($params['module'], $params['__CONTROLLER__'], $params['action'], $intTotal, $intPage, $intLimit, 'list-keyword', $params);

            $this->renderer = $this->serviceLocator->get('Zend\View\Renderer\PhpRenderer');
            $this->renderer->headMeta()->appendName('dc.description', html_entity_decode('Danh sách từ khoá trang ' . $intPage) . General::TITLE_META);
            $this->renderer->headMeta()->appendName('dc.subject', html_entity_decode('Danh sách từ khoá trang ' . $intPage) . General::TITLE_META);
            $this->renderer->headTitle('Từ khoá - ' . html_entity_decode('Danh sách từ khoá trang ' . $intPage) . General::TITLE_META);
            $this->renderer->headMeta()->appendName('keywords', html_entity_decode('Danh sách từ khoá trang ' . $intPage));
            $this->renderer->headMeta()->appendName('description', html_entity_decode('Danh sách từ khoá trang ' . $intPage . General::TITLE_META));
            $this->renderer->headMeta()->appendName('social', null);
            $this->renderer->headMeta()->setProperty('og:url', $this->url()->fromRoute('list-keyword', array('page' => $intPage)));
            $this->renderer->headMeta()->setProperty('og:title', html_entity_decode('Danh sách từ khoá trang ' . $intPage . General::TITLE_META));
            $this->renderer->headMeta()->setProperty('og:description', html_entity_decode('Danh sách từ khoá trang ' . $intPage . General::TITLE_META));

            return array(
                'params' => $params,
                'arrKeywordList' => $arrKeywordList,
                'paging' => $paging,
                'intPage' => $intPage,
                'intLimit' => $intLimit,
                'intTotal' => $intTotal,
                'title' => 'Keyword'
            );
        } catch (\Exception $exc) {
            echo '<pre>';
            print_r([
                'code' => $exc->getCode(),
                'messages' => $exc->getMessage()
            ]);
            echo '</pre>';
            die();
        }
    }

}
