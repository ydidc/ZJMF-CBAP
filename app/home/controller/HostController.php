<?php
namespace app\home\controller;

use app\common\model\HostModel;

/**
 * @title 产品管理
 * @desc 产品管理
 * @use app\home\controller\HostController
 */
class HostController extends HomeBaseController
{
    /**
     * 时间 2022-05-19
     * @title 产品列表
     * @desc 产品列表
     * @author theworld
     * @version v1
     * @url /console/v1/host
     * @method  GET
     * @param string keywords - 关键字,搜索范围:产品ID,商品名称,标识
     * @param string status - 状态Unpaid未付款Pending开通中Active已开通Suspended已暂停Deleted已删除Failed开通失败
     * @param int page - 页数
     * @param int limit - 每页条数
     * @param string orderby - 排序 id,active_time,due_time
     * @param string sort - 升/降序 asc,desc
     * @return array list - 产品
     * @return int list[].id - 产品ID 
     * @return int list[].product_id - 商品ID 
     * @return string list[].product_name - 商品名称 
     * @return string list[].name - 标识 
     * @return int list[].active_time - 开通时间 
     * @return int list[].due_time - 到期时间
     * @return string list[].first_payment_amount - 金额
     * @return string list[].billing_cycle - 周期
     * @return string list[].status - 状态Unpaid未付款Pending开通中Active已开通Suspended已暂停Deleted已删除Failed开通失败
     * @return int count - 产品总数
     */
	public function hostList()
    {
		// 合并分页参数
        $param = array_merge($this->request->param(), ['page' => $this->request->page, 'limit' => $this->request->limit, 'sort' => $this->request->sort]);
        
        // 实例化模型类
        $HostModel = new HostModel();

        // 获取产品列表
        $data = $HostModel->hostList($param);

        $result = [
            'status' => 200,
            'msg' => lang('success_message'),
            'data' => $data
        ];
        return json($result);
	}

	/**
     * 时间 2022-05-19
     * @title 产品详情
     * @desc 产品详情
     * @author theworld
     * @version v1
     * @url /console/v1/host/:id
     * @method  GET
     * @param int id - 产品ID required
     * @return object host - 产品
     * @return int host.id - 产品ID 
     * @return int host.product_id - 商品ID 
     * @return string host.name - 标识 
     * @return string host.first_payment_amount - 订购金额
     * @return string host.renew_amount - 续费金额
     * @return string host.billing_cycle - 计费周期
     * @return int host.active_time - 开通时间 
     * @return int host.due_time - 到期时间
     * @return string host.status - 状态Unpaid未付款Pending开通中Active已开通Suspended已暂停Deleted已删除Failed开通失败
     * @return string host.suspend_type - 暂停类型,overdue到期暂停,overtraffic超流暂停,certification_not_complete实名未完成,other其他
     * @return string host.suspend_reason - 暂停原因
     */
	public function index()
    {
		// 接收参数
        $param = $this->request->param();
        
        // 实例化模型类
        $HostModel = new HostModel();

        // 获取产品
        $host = $HostModel->indexHost($param['id']);

        $result = [
            'status' => 200,
            'msg' => lang('success_message'),
            'data' => [
                'host' => $host
            ]
        ];
        return json($result);
	}

    /**
     * 时间 2022-05-30
     * @title 产品内页模块
     * @desc 产品内页模块
     * @url /console/v1/host/:id/module
     * @method  GET
     * @author hh
     * @version v1
     * @param   int id - 产品ID required
     * @return  string data.content - 模块输出内容
     */
    public function clientArea()
    {
        $param = $this->request->param();
        
        // 实例化模型类
        $HostModel = new HostModel();

        // 获取产品
        $result = $HostModel->clientArea((int)$param['id']);
        return json($result);
    }

    /**
     * 时间 2022-05-31
     * @title 产品升降级配置
     * @desc 产品升降级配置
     * @url /console/v1/host/:id/upgrade/config_option
     * @method  GET
     * @author hh
     * @version v1
     * @param   int id - 产品ID required
     */
    public function changeConfigOption()
    {
        $param = $this->request->param();

        // 实例化模型类
        $HostModel = new HostModel();
        
        $result = $HostModel->clientChangeConfigOption($param['id']);
        return json($result);
    }

    /**
     * 时间 2022-05-31
     * @title 产品升降级配置计算价格
     * @desc 产品升降级配置计算价格
     * @url /console/v1/host/:id/upgrade/config_option
     * @method  POST
     * @author hh
     * @version v1
     * @param   int id - 产品ID required
     * @param   mixed config_options {"cpu":1} 自定义配置项
     */
    public function changeConfigOptionCalculatePrice(){
        $param = $this->request->param();

        // 实例化模型类
        $HostModel = new HostModel();
        
        $result = $HostModel->changeConfigOptionCalculatePrice($param);
        return json($result);
    }




}