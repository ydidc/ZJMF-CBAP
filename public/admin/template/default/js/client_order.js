/* 用户信息-订单管理 */
(function (window, undefined) {
  var old_onload = window.onload
  window.onload = function () {
    const template = document.getElementsByClassName('client-order')[0]
    Vue.prototype.lang = window.lang
    Vue.prototype.moment = window.moment
    new Vue({
      data () {
        return {
          rootRul: url,
          data: [],
          tableLayout: false,
          bordered: true,
          visible: false,
          delVisible: false,
          priceModel: false,
          payVisible: false,
          hover: true,
          currency_prefix: JSON.parse(localStorage.getItem('common_set')).currency_prefix || '¥',
          columns: [
            {
              colKey: 'id',
              title: 'ID',
              width: 120,
              sortType: 'all',
              sorter: true
            },
            // {
            //   colKey: 'type',
            //   title: lang.type,
            //   width: 130
            // },
            {
              colKey: 'product_names',
              title: lang.product_name,
              ellipsis: true,
              width: 250
            },
            {
              colKey: 'amount',
              title: lang.money_cycle,
              ellipsis: true,
              width: 150
            },
            {
              colKey: 'gateway',
              title: lang.pay_way,
              ellipsis: true,
              width: 170
            },
            {
              colKey: 'create_time',
              title: lang.order_time,
              width: 170
            },
            {
              colKey: 'status',
              title: lang.status,
              width: 120
            },
            {
              colKey: 'op',
              title: lang.operation,
              width: 180
            }
          ],
          params: {
            keywords: '',
            client_id: '', // 用户ID
            page: 1,
            limit: 20,
            orderby: 'id',
            sort: 'desc'
          },
          total: 0,
          pageSizeOptions: [20, 50, 100],
          loading: false,
          title: '',
          delId: '',
          id: '',
          tempData: [],
          promiseArr: [],
          orderNum: 0,
          // 变更价格
          formData: {
            id: '',
            amount: '',
            description: ''
          },
          rules: {
            amount: [
              { required: true, message: lang.input + lang.money, type: 'error' },
              {
                pattern: /^-?\d+(\.\d{0,2})?$/, message: lang.verify10, type: 'warning'
              },
              {
                validator: val => val*1 !== 0, message: lang.verify10, type: 'warning'
              }
            ],
            description: [
              { required: true, message: lang.input + lang.description, type: 'error' },
              {
                validator: val => val.length <= 1000, message: lang.verify3 + 1000, type: 'warning'
              }
            ],
          },
          delete_host: false, // 是否删除产品:0否1是
          signForm: {
            amount: 0,
            credit: 0
          },
          maxHeight: '',
          use_credit: true
        }
      },
      mounted () {
        this.maxHeight = document.getElementById('content').clientHeight - 170
        let timer = null
        window.onresize = () => {
          if (timer) {
            return
          }
          timer = setTimeout(() => {
            this.maxHeight = document.getElementById('content').clientHeight - 170
            clearTimeout(timer)
            timer = null
          }, 300)
        }
      },
      methods: {
        // 调整价格
        updatePrice (row) {
          this.formData.id = row.id
          this.formData.amount = ''
          this.formData.description = ''
          this.$refs.priceForm.clearValidate()
          this.priceModel = true
        },
        async onSubmit ({ validateResult, firstError }) {
          if (validateResult === true) {
            try {
              const res = await updateOrder(this.formData)
              this.$message.success(res.data.msg)
              this.orderNum = 0
              this.priceModel = false
              this.getClientList()
            } catch (error) {
              this.$message.error(error.data.msg);
            }
          } else {
            console.log('Errors: ', validateResult);
            this.$message.warning(firstError);
          }
        },
        closePrice () {
          this.priceModel = false
          this.$refs.priceForm.reset()
        },
        // 删除订单
        delteOrder (row) {
          this.delId = row.id
          this.delVisible = true
          this.delete_host = false
        },
        async onConfirm () {
          try {
            const params = {
              id: this.delId,
              delete_host: this.delete_host ? 1 : 0
            }
            await delOrderDetail(params)
            this.$message.success(window.lang.del_success)
            this.delVisible = false
            this.orderNum = 0
            this.params.page = this.data.length > 1 ? this.params.page : this.params.page - 1
            this.getClientList()
          } catch (error) {
            this.$message.error(error)
          }
        },
        // 标记支付
        signPay (row) {
          if (row.status === 'Paid') {
            return
          }
          this.payVisible = true
          this.delId = row.id
          this.signForm.amount = row.amount
          this.signForm.credit = row.client_credit
        },
        async sureSign () {
          try {
            const params = {
              id: this.delId,
              use_credit: this.use_credit ? 1 : 0
            }
            const res = await signPayOrder(params)
            this.$message.success(res.data.msg)
            this.orderNum = 0
            this.getClientList()
            this.payVisible = false
          } catch (error) {
            this.$message.error(error.data.msg)
            this.payVisible = false
          }
        },
        // 展开行
        changePage (e) {
          this.params.page = e.current
          this.params.limit = e.pageSize
          this.getClientList()
        },
        // 获取订单列表
        async getClientList () {
          try {
            this.loading = true
            const res = await getOrder(this.params)
            this.data = res.data.data.list
            this.total = res.data.data.count
            this.data.forEach(item => {
              item.list = []
              item.isExpand = false
            })
            this.loading = false
          } catch (error) {
            this.$message.error(error.data.msg)
            this.loading = false
          }
        },
        // id点击获取订单详情
        itemClick (row) {
          if (row.order_item_count < 2) {
            return
          }
          row.isExpand = row.isExpand ? false : true
          const rowData = this.$refs.table.getData(row.id);
          this.$refs.table.toggleExpandData(rowData);
          if (row.list.length > 0) {
            return
          }
          this.getOrderDetail(row.id)
        },
        // 订单详情
        async getOrderDetail (id) {
          try {
            const res = await getOrderDetail(id)
            res.data.data.order.items.forEach(item => {
              this.$refs.table.appendTo(id, item)
            })
          } catch (error) {
            this.$message.error(error.data.msg)
          }
        }
      },
      created () {
        this.id = this.params.client_id = location.href.split('?')[1].split('=')[1]
        this.getClientList()
      }
    }).$mount(template)
    typeof old_onload == 'function' && old_onload()
  };
})(window);

