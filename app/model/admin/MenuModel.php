<?php

namespace app\model\admin;

use app\base\BaseModel;

class MenuModel extends BaseModel
{
    protected $connection = 'element_cms';

    protected $table = 'admin_menu';

    protected $pk = 'menu_id';

    protected $schema = array(
        'menu_id' => 'int',
        'parent_id' => 'int',
        'name' => 'varchar',
        'route_name' => 'varchar',
        'status' => 'tinyint',
        'editor' => 'varchar',
        'deleted' => 'tinyint',
        'add_time' => 'datetime',
        'edit_time' => 'datetime',
        'delete_time' => 'datetime',
    );
}