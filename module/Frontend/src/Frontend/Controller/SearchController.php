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

            $instanceSearchContent = new \My\Search\Content();
            $instanceSearchKeyword = new \My\Search\Keyword();

            $key_name = General::clean($params['keyword']);

            $intPage = (int)$params['page'] > 0 ? (int)$params['page'] : 1;
            $intLimit = 10;

            $arr_condition_content = array(
                'cont_status' => 1,
                'full_text_title' => $key_name
            );

            $arrFields = array('cont_id', 'cont_title', 'cont_slug', 'cate_id', 'cont_main_image', 'created_date');

            $arrContentList = $instanceSearchContent->getListLimit($arr_condition_content, $intPage, $intLimit, ['_score' => ['order' => 'desc']], $arrFields);

            //phân trang
            $intTotal = $instanceSearchContent->getTotal($arr_condition_content);
            $helper = $this->serviceLocator->get('viewhelpermanager')->get('Paging');
            $paging = $helper($params['module'], $params['__CONTROLLER__'], $params['action'], $intTotal, $intPage, $intLimit, 'search', $params);

            //get keyword
            $listContent = array();
            if(!empty($arrContentList)) {
                foreach ($arrContentList as $content) {
                    $listContent[$content['cont_id']] = $content;
                    $arrCondition = array(
                        'full_text_keyname' => $content['cont_title'],
                        'in_cate_id' => array($content['cate_id'], -1)
                    );
                    $arrKeywordList = $instanceSearchKeyword->getListLimit($arrCondition, 1, 5, ['_score' => ['order' => 'desc']]);
                    $listContent[$content['cont_id']]['list_keyword'] = $arrKeywordList;
                }
            }

            $this->renderer = $this->serviceLocator->get('Zend\View\Renderer\PhpRenderer');
            $this->renderer->headTitle($params['keyword'] . General::TITLE_META);
            $this->renderer->headMeta()->appendName('robots', 'index');
            $this->renderer->headMeta()->appendName('keywords', General::KEYWORD_DEFAULT . ', ' . $params['keyword']);
            $this->renderer->headMeta()->appendName('description', $params['keyword']);
            $this->renderer->headMeta()->setProperty('og:url', BASE_URL . $this->url()->fromRoute('search', ['keyword' => $params['keyword'], 'page' => $intPage]));
            $this->renderer->headMeta()->setProperty('og:title', $params['keyword']);
            $this->renderer->headMeta()->setProperty('og:description', $params['keyword']);

            $this->renderer->headLink(array('rel' => 'amphtml', 'href' => BASE_URL . $this->url()->fromRoute('search', ['keyword' => $params['keyword']])));
            $this->renderer->headLink(array('rel' => 'canonical', 'href' => BASE_URL . $this->url()->fromRoute('search', ['keyword' => $params['keyword']])));

            return [
                'paging' => $paging,
                'params' => $params,
                'keyword' => $key_name,
                'arrContentList' => $listContent,
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

            $instanceSearchKeyword = new \My\Search\Keyword();
            $serviceContent = $this->serviceLocator->get('My\Models\Content');
            $serviceKeyword = $this->serviceLocator->get('My\Models\Keyword');
            //
            $arrKeyDetail = $instanceSearchKeyword->getDetail(['key_id' => $key_id]);

            if (empty($arrKeyDetail)) {
                return $this->redirect()->toRoute('404', array());
            }

            $arrContentList = array();

            if(!empty($arrKeyDetail['key_content'] && $arrKeyDetail['key_content'] != '0')) {
                $arrFields = 'cont_id, cont_title, cont_slug, cate_id, cont_resize_image, created_date, cont_description';
                $arr_condition_content = array(
                    'cont_status' => 1,
                    'in_cont_id' => $arrKeyDetail['key_content']
                );
                $arrResult = $serviceContent->getList($arr_condition_content, $arrFields);

                if(!empty($arrResult)) {
                    $arr_temp = array();
                    foreach ($arrResult as $content) {
                        $arr_temp[$content['cont_id']] = $content;
                    }

                    $arr_cont_id = explode(',', $arrKeyDetail['key_content']);
                    foreach ($arr_cont_id as $cont_id) {
                        $arrContentList[] = $arr_temp[$cont_id];
                    }
                }
            }

            if (empty($arrContentList)) {
                $arr_condition_content = array(
                    'cont_status' => 1,
                    'full_text_title' => $arrKeyDetail['key_name']
                );
                
                $intPage = 1;
                $intLimit = 15;

                $arrFields = array('cont_id', 'cont_title', 'cont_slug', 'cate_id', 'cont_resize_image', 'created_date', 'cont_description');
                $instanceSearchContent = new \My\Search\Content();
                $arrContentList = $instanceSearchContent->getListLimit($arr_condition_content, $intPage, $intLimit, ['_score' => ['order' => 'desc']], $arrFields);

                $text_cont_id = '';
                if (!empty($arrContentList)) {
                    $arr_cont_id = array();
                    foreach ($arrContentList as $content) {
                        $arr_cont_id[] = $content['cont_id'];
                    }
                    $text_cont_id = implode(',', $arr_cont_id);
                }

                $arr_update = array(
                    'key_content' => $text_cont_id,
                );
                $serviceKeyword->edit($arr_update, $key_id);
            }

            //get keyword
            $listContent = array();
            if (!empty($arrContentList)) {
                foreach ($arrContentList as $content) {
                    $listContent[$content['cont_id']] = $content;
                    $arrCondition = array(
                        'full_text_keyname' => $content['cont_title'],
                        'in_cate_id' => array($content['cate_id'], -1)
                    );
                    $arrKeywordList = $instanceSearchKeyword->getListLimit($arrCondition, 1, 5, ['_score' => ['order' => 'desc']]);
                    $listContent[$content['cont_id']]['list_keyword'] = $arrKeywordList;
                }
            }
			
			$arr_content = array();
			foreach($listContent as $row){
				$arr_content[] = $row;
			}
			
            $helper_title = $this->serviceLocator->get('viewhelpermanager')->get('MyHeadTitle');
            $helper_title->setTitle(html_entity_decode($arrKeyDetail['key_name']) . General::TITLE_META);

            $this->renderer = $this->serviceLocator->get('Zend\View\Renderer\PhpRenderer');
            $this->renderer->headMeta()->appendName('robots', 'index');
            $this->renderer->headMeta()->appendName('dc.description', html_entity_decode($arrKeyDetail['key_name']) . General::TITLE_META);
            $this->renderer->headMeta()->appendName('dc.subject', html_entity_decode($arrKeyDetail['key_name']) . General::TITLE_META);
//            $this->renderer->headTitle(html_entity_decode($arrKeyDetail['key_name']) . General::TITLE_META);
            $this->renderer->headMeta()->appendName('keywords', html_entity_decode($arrKeyDetail['key_name']));
            $this->renderer->headMeta()->appendName('description', html_entity_decode('Danh sách bài viết trong từ khoá : ' . $arrKeyDetail['key_name'] . General::TITLE_META));
            $this->renderer->headMeta()->appendName('social', null);
            $this->renderer->headMeta()->setProperty('og:url', $this->url()->fromRoute('keyword', array('keySlug' => $arrKeyDetail['key_slug'], 'keyId' => $arrKeyDetail['key_id'], 'page' => $intPage)));
            $this->renderer->headMeta()->setProperty('og:title', html_entity_decode('Danh sách bài viết trong từ khoá : ' . $arrKeyDetail['key_name'] . General::TITLE_META));
            $this->renderer->headMeta()->setProperty('og:description', html_entity_decode('Danh sách bài viết trong từ khoá : ' . $arrKeyDetail['key_name'] . General::TITLE_META));
            $this->renderer->headLink(array('rel' => 'amphtml', 'href' => BASE_URL . $this->url()->fromRoute('keyword', array('keySlug' => $arrKeyDetail['key_slug'], 'keyId' => $arrKeyDetail['key_id'], 'page' => $intPage))));
            $this->renderer->headLink(array('rel' => 'canonical', 'href' => BASE_URL . $this->url()->fromRoute('keyword', array('keySlug' => $arrKeyDetail['key_slug'], 'keyId' => $arrKeyDetail['key_id'], 'page' => $intPage))));

            return array(
                'params' => $params,
                'intPage' => $intPage,
                'arrContentList' => $arr_content,
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
        $params = array_merge($this->params()->fromRoute(), $this->params()->fromQuery());
        $intPage = is_numeric($params['page']) ? $params['page'] : 1;
        $intLimit = 100;

        $instanceSearchKeyword = new \My\Search\Keyword();

        //phân trang
        $intTotal = $instanceSearchKeyword->getTotal(array());

        $arrKeywordList = $instanceSearchKeyword->getListLimit(array(), $intPage, $intLimit, ['key_id' => ['order' => 'asc']], array('key_id', 'key_name', 'key_slug'));

        $helper = $this->serviceLocator->get('viewhelpermanager')->get('Paging');
        $paging = $helper($params['module'], $params['__CONTROLLER__'], $params['action'], $intTotal, $intPage, $intLimit, 'list-keyword', $params);

        $helper_title = $this->serviceLocator->get('viewhelpermanager')->get('MyHeadTitle');
        $helper_title->setTitle('Từ khoá - ' . html_entity_decode('Danh sách từ khoá trang ' . $intPage) . General::TITLE_META);

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
    }

}
