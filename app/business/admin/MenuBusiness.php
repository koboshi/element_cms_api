<?php

namespace app\business\admin;

use app\base\BaseBusiness;
use app\model\admin\MenuModel;

/**
 * 后台菜单业务逻辑
 * @author koboshi
 */
class MenuBusiness extends BaseBusiness
{
    protected MenuModel $menuModel;

    public function __construct(MenuModel $adminMenuModel)
    {
        $this->menuModel = $adminMenuModel;
    }

    /**
     * 返回所有生效的菜单用于渲染
     * 子菜单以数组形式存储在['children']
     * @return array
     */
    public function getActiveMenus()
    {
        $menus = $this->menuModel->where('deleted', 0)
            ->column(array('menu_id', 'parent_id', 'name', 'route_name'));
        if (empty($menus)) {
            return array();
        }
        return trans_tree(0, $menus, 'menu_id');
    }

    /**
     * 返回指定parentId下的生效菜单
     * 一般用于前端多级菜单懒加载
     * @return array
     */
    public function getActiveMenusByParentId(int $parentId)
    {
        if (empty($parentId) || $parentId < 1) {
            return array();
        }
        $menus = $this->menuModel->where('parent_id', $parentId)->where('deleted', 0)
            ->column(array('menu_id', 'parent_id', 'name', 'route_name'));
        if (empty($menus)) {
            return array();
        }
        return $menus;
    }

    /**
     * 返回所有菜单,不管是否生效
     * 用于菜单管理渲染
     * @return array
     */
    public function getAllMenus(int $page, int $size)
    {
        $result = array();
        $result['list'] = $this->menuModel->page($page, $size)->column(array('menu_id', 'parent_id', 'name', 'route_name', 'status', 'editor',
            'deleted', 'add_time', 'edit_time', 'delete_time'));
        $result['count'] = $this->menuModel->count();
        return $result;
    }

    /**
     * 新增菜单
     * 返回主键
     * @return int
     */
    public function addMenu(int $parentId, string $name, string $routeName, string $editor, int $deleted)
    {
        $dateTime = date("Y-m-d H:i:s");
        $data = array();
        $data['parent_id'] = $parentId;
        $data['name'] = trim($name);
        $data['route_name'] = trim(strtolower($routeName));
        $data['status'] = 1;
        $data['editor'] = trim($editor);
        $data['deleted'] = $deleted;
        $data['add_time'] = $dateTime;
        $data['edit_time'] = $dateTime;
        $data['delete_time'] = EMPTY_DATETIME;

        $flag = $this->menuModel->save($data);
        if ($flag) {
            return $this->menuModel->getKey();
        }
        return 0;
    }

    /**
     * 返回更新行数
     * @param int $menuId
     * @return bool
     */
    public function delMenu(int $menuId)
    {
        //软删除
        $dateTime = date("Y-m-d H:i:s");
        $data = array();
        $data['deleted'] = 1;
        $data['edit_time'] = $dateTime;
        $data['delete_time'] = $dateTime;

        return $this->menuModel->where('menu_id', $menuId)->limit(1)->save($data);
    }

    /**
     * @param int $menuId
     * @return bool
     */
    public function recoverMenu(int $menuId)
    {
        $dateTime = date("Y-m-d H:i:s");
        $data = array();
        $data['deleted'] = 0;
        $data['edit_time'] = $dateTime;

        return $this->menuModel->where('menu_id', $menuId)->limit(1)->save($data);
    }

    /**
     * @param int $menuId
     * @param $editor
     * @param array $data
     * @return bool
     */
    public function editMenu(int $menuId, $editor, array $data)
    {
        $dateTime = date("Y-m-d H:i:s");
        $updateData = array();
        $updateData['edit_time'] = $dateTime;
        $updateData['editor'] = trim($editor);
        if (isset($data['parent_id'])) {
            $updateData['parent_id'] = intval($data['parent_id']);
        }
        if (isset($data['name'])) {
            $updateData['name'] = trim($data['name']);
        }
        if (isset($data['route_name'])) {
            $updateData['route_name'] = trim($data['route_name']);
        }
        if (isset($data['deleted'])) {
            $updateData['deleted'] = intval($data['deleted']) ? 1: 0;
            if ($updateData['deleted'] == 1) {
                $updateData['edit_time'] = $dateTime;
                $updateData['delete_time'] = $dateTime;
            }
        }

        return $this->menuModel->where('menu_id', $menuId)->limit(1)->save($updateData);
    }
}