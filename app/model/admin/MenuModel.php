<?php

namespace app\model\admin;

use think\Model;

class MenuModel extends Model
{
    protected $connection = 'mysql';

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