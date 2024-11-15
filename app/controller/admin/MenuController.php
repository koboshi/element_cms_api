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

    /**
     * 获取所有菜单项用于渲染(支持指定父id获取)
     * @param int $parentId
     * @return Json
     */
    public function getAllAction(int $parentId = 0)
    {
        $controllerName = $this->request->controller(true);
        $actionName = $this->request->action(true);
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