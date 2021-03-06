<?php

namespace Core\Mvc\Controller;

use Core\Exceptions\RuntimeException;
use Core\Views\View;
use App\Services\User as UserService;

/**
 * Web相关的基础控制器
 */
class Web extends Base
{
    /**
     * 当前登录的用户信息
     *
     * @var array
     */
    protected $loginUser = [];

    /**
     * 初始化
     */
    public function init()
    {
        parent::init();

        $this->loginUser = (new UserService)->getCurrentUserInfo();

        $viewPath = $this->_view->getScriptPath();
        if (!$viewPath) {
            $defaultModule = 'Index';
            if (isset($this->config['application']['dispatcher']['defaultController'])) {
                $defaultModule = $this->config['application']['dispatcher']['defaultController'];
            }

            if ($this->getModule() === $defaultModule) {
                $viewPath = APP_PATH . '/views';
            } else {
                $viewPath = APP_PATH . '/modules/' . $this->getModule() . '/views';
            }
        }

        $this->_view = new View($viewPath);
    }

    /**
     * 获取视图
     *
     * @throws RuntimeException
     */
    public function getView()
    {
        throw new RuntimeException('Abandon method!');
    }

    /**
     * 公共数据,数据会分排到模板上
     *
     * @return array
     */
    public function commonVars()
    {
        return [
            'assets' => '/',
            'userInfo' => $this->loginUser,
        ];
    }

    /**
     * 渲染模板并输出
     *
     * @param string $actionName
     * @param array $data
     * @return bool
     */
    public function display($actionName, array $data = [])
    {
        return parent::display($actionName, array_merge($this->commonVars(), $data));
    }
}