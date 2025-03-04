<?php
namespace addon\idcsmart_renew\controller;

use addon\idcsmart_renew\model\IdcsmartRenewModel;
use app\event\controller\PluginBaseController;

/**
 * @title 续费
 * @desc 续费
 * @use addon\idcsmart_renew\controller\AdminIndexController
 */
class AdminIndexController extends PluginBaseController
{
    /**
     * 时间 2022-06-02
     * @title 续费页面
     * @desc 续费页面
     * @author wyh
     * @version v1
     * @url /admin/v1/host/:id/renew
     * @method  GET
     * @param int id - 产品ID required
     * @return array host -
     * @return float host[].price 0.01 价格
     * @return string host[].billing_cycle 小时 周期
     * @return int host[].duration 3600 周期时间
     */
    public function renewPage()
    {
        $param = $this->request->param();

        $IdcsmartRenewModel = new IdcsmartRenewModel();

        $IdcsmartRenewModel->isAdmin = true;

        $result = $IdcsmartRenewModel->renewPage($param);

        return json($result);
    }

    /**
     * 时间 2022-06-02
     * @title 续费
     * @desc 续费
     * @author wyh
     * @version v1
     * @url /admin/v1/host/:id/renew
     * @method  POST
     * @param int id - 产品ID required
     * @param string billing_cycle - 周期 required
     * @param string promo_code - 优惠码
     * @param int pay - 标记支付:1是,0否 required
     * @param array promo_code ["fKwUIZ91","nG0aWo55"] 优惠码,数组格式
     */
    public function renew()
    {
        $param = $this->request->param();

        $IdcsmartRenewModel = new IdcsmartRenewModel();

        $IdcsmartRenewModel->isAdmin = true;

        $result = $IdcsmartRenewModel->renew($param);

        return json($result);
    }

    /**
     * 时间 2022-06-02
     * @title 批量续费页面
     * @desc 批量续费页面
     * @author wyh
     * @version v1
     * @url /admin/v1/host/renew/batch
     * @method  GET
     * @param array ids - 产品ID,数组 required
     * @param int client_id - 用户ID required
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
     * @return string list[].billing_cycles - 可续费周期
     * @return string list[].billing_cycles.price - 价格
     * @return string list[].billing_cycles.billing_cycle - 周期
     * @return string list[].billing_cycles.duration - 周期时间
     */
    public function renewBatchPage()
    {
        $param = $this->request->param();

        $IdcsmartRenewModel = new IdcsmartRenewModel();

        $IdcsmartRenewModel->isAdmin = true;

        $result = $IdcsmartRenewModel->renewBatchPage($param);

        return json($result);
    }

    /**
     * 时间 2022-06-02
     * @title 批量续费
     * @desc 批量续费
     * @author wyh
     * @version v1
     * @url /admin/v1/host/renew/batch
     * @method  POST
     * @param array ids - 产品ID,数组 required
     * @param int client_id - 用户ID required
     * @param object billing_cycles - 周期,对象{"id":"小时"} required
     * @param object amount_custom - 金额,对象{"id":"0.01"} required
     * @param int pay - 标记支付:1是,0否 required
     * @param array promo_code ["fKwUIZ91","nG0aWo55"] 优惠码,数组格式
     */
    public function renewBatch()
    {
        $param = $this->request->param();

        $IdcsmartRenewModel = new IdcsmartRenewModel();

        $IdcsmartRenewModel->isAdmin = true;

        $result = $IdcsmartRenewModel->renewBatch($param);

        return json($result);
    }

}