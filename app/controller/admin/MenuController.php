<?php

namespace app\controller\admin;

use app\base\BaseController;
use app\business\admin\MenuBusiness;
use think\response\Json;

class MenuController extends BaseController
{
    protected MenuBusiness $menuBusiness;

    protected function initialize()
    {
        parent::initialize();
        $this->menuBusiness = invoke(MenuBusiness::class);
    }

    public function listAction(int $page, int $size = 20)
    {
//        $menuList = $this->menuBusiness->getAllMenus($page, false, $size);
//        $menuCount = $this->menuBusiness->getAllMenus($page, true);
        $menuRes = $this->menuBusiness->getAllMenus($page, $size);
        return $this->listJson(0, '', $page, $size, $menuRes['count'], $menuRes['list']);
    }

    /**
     * 获取所有菜单项用于渲染
     * @return Json
     */
    public function treeAction()
    {
        $data = $this->menuBusiness->getActiveMenus();

        return json($data);
    }

    /**
     * 新增菜单项
     * @return void
     */
    public function addAction()
    {
        //TODO
    }

    /**
     * 修改菜单项
     * @return void
     */
    public function editAction()
    {
        //TODO
    }

    /**
     * 删除菜单项
     * @return void
     */
    public function delAction()
    {
        //TODO
    }
}