<?php

namespace app\model\admin;

use think\Model;

class UserModel extends Model
{
    protected $connection = 'mysql';

    protected $table = 'admin_user';

    protected $pk = 'admin_id';

    protected $schema = array(
        'admin_id' => 'int',
        'role_id' => 'int',
        'name' => 'varchar',
        'password' => 'varchar',
        'ticket' => 'varchar',
        'expire_time' => 'varchar',
        'status' => 'tinyint',
        'editor' => 'varchar',
        'deleted' => 'tinyint',
        'add_time' => 'datetime',
        'edit_time' => 'datetime',
        'delete_time' => 'datetime',
    );
}