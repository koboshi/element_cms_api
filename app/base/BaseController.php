<?php
declare (strict_types = 1);

namespace app\base;

use think\App;
use think\exception\ValidateException;
use think\Validate;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {}

    /**
     * @param int $errCode
     * @param string $errMsg
     * @param string|array $info
     * @return \think\response\Json
     */
    protected function infoJson(int $errCode, string $errMsg, string|array $info)
    {
        $data = array();
        $data['error_code'] = $errCode;
        $data['error_message'] = $errMsg;
        $data['data'] = $info;
        return json($data);
    }

    /**
     * @param int $errCode
     * @param string $errMsg
     * @param int $page
     * @param int $size
     * @param int $total
     * @param array $list
     * @return \think\response\Json
     */
    protected function listJson(int $errCode, string $errMsg, int $page, int $size, int $total, array $list)
    {
        $data = array();
        $data['error_code'] = $errCode;
        $data['error_message'] = $errCode;
        $data['data'] = array(
            'page' => $page,
            'size' => $size,
            'total' => $total,
            'total_page' => ceil($total / $size),
            'list' => $list
        );
        return json($data);
    }

    /**
     * 验证数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, string|array $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }

}
