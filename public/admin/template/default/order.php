{include file="header"}
<!-- =======内容区域======= -->
<link rel="stylesheet" href="/{$template_catalog}/template/{$themes}/css/client.css">
<div id="content" class="order table" v-cloak>
  <t-card class="list-card-container">
    <div class="common-header">
      <t-button @click="addOrder" class="add">{{lang.create_order}}</t-button>
      <div class="com-search">
        <t-input v-model="params.keywords" class="search-input" 
          :placeholder="`${lang.please_search}ID、${lang.username}、${lang.email}、${lang.phone}`" 
          @keyup.enter.native="seacrh" :on-clear="clearKey" clearable>
        </t-input>
        <t-icon size="20px" name="search" @click="seacrh" class="com-search-btn" />
      </div>
    </div>
    <t-enhanced-table ref="table" row-key="id" drag-sort="row-handler" :data="data" :columns="columns"
      :tree="{ childrenKey: 'list', treeNodeColumnIndex: 0 }" :loading="loading"  :hover="hover"
      :tree-expand-and-fold-icon="treeExpandAndFoldIconRender" @sort-change="sortChange" class="user-order"
      :hide-sort-tips="true" :max-height="maxHeight">
      <template slot="sortIcon">
        <t-icon name="caret-down-small"></t-icon>
      </template>
      <template #id="{row}">
        <span v-if="row.type" @click="itemClick(row)" class="order-id">
          <t-icon :name="row.isExpand ? 'caret-up-small' : 'caret-down-small'"
            v-if="row.order_item_count > 1">
          </t-icon>
          {{row.id}}
        </span>
        <span v-else class="child">-</span>
      </template>
      <template #type="{row}">
        {{lang[row.type]}}
      </template>
      <template #client_name="{row}">
        <span>{{row.client_name}}</span>
        <span v-if="row.phone">(+{{row.phone_code}}-{{row.phone}})</span>
        <span v-else-if="row.email">({{row.email}})</span>
      </template>
      <template #create_time="{row}">
        {{row.type ? moment(row.create_time * 1000).format('YYYY-MM-DD HH:mm') : ''}}
      </template>
      <template #product_names={row}>
        <template v-if="row.product_names">
          <t-tooltip :content="lang[row.type]" theme="light" :show-arrow="false"
          placement="top-right">
            <img :src="`${rootRul}/img/icon/${row.type}.png`" alt="" style="position: relative; top: 3px;">
          </t-tooltip>
          <span>{{row.product_names[0]}}</span>
          <span v-if="row.product_names.length>1">、{{row.product_names[1]}}</span>
          <span v-if="row.product_names.length>2">{{lang.wait}}{{row.product_names.length}}{{lang.products}}</span>
        </template>
        <span v-else class="child-name">
          {{row.product_name ? row.product_name : row.description}}
          <span class="host-name" v-if="row.host_name">({{row.host_name}})</span>
        </span>
      </template>
      <template #amount="{row}">
        {{currency_prefix}}&nbsp;{{row.amount}}
       <!-- 升降机为退款时不显示周期 -->
       <span v-if="row.billing_cycle && Number(row.amount) >= 0">/{{row.billing_cycle}}</span>
      </template>
      <template #status="{row}">
        <t-tag theme="warning" variant="light" v-if="(row.status || row.host_status)==='Unpaid'">{{lang.Unpaid}}
        </t-tag>
        <t-tag theme="primary" variant="light" v-if="row.status==='Paid'">{{lang.Paid}}
        </t-tag>
        <t-tag theme="primary" variant="light" v-if="row.host_status === 'Pending'">
          {{lang.Pending}}</t-tag>
        <t-tag theme="success" variant="light" v-if="(row.status || row.host_status)==='Active'">{{lang.Active}}
        </t-tag>
        <t-tag theme="danger" variant="light" v-if="(row.status || row.host_status)==='Failed'">{{lang.Failed}}
        </t-tag>
        <t-tag theme="default" variant="light" v-if="(row.status || row.host_status)==='Suspended'">
          {{lang.Suspended}}</t-tag>
        <t-tag theme="default" variant="light" v-if="(row.status || row.host_status)==='Deleted'"
          class="delted">{{lang.Deleted}}
        </t-tag>
      </template>
      <template #gateway="{row}">
        <div v-if="row.status==='Paid'">
          <!-- 其他支付方式 -->
          <template v-if="row.credit == 0 && row.amount !=0">
            {{row.gateway}}
          </template>
          <!-- 混合支付 -->
          <template v-if="row.credit>0 && row.credit < row.amount">
            <t-tooltip :content="currency_prefix+row.credit" theme="light" placement="bottom-right">
              <span>{{lang.credit}}</span>
            </t-tooltip>
            <span>{{row.gateway ? '+ ' + row.gateway: '' }}</span>
          </template>
          <template v-if="row.amount*1 != 0 && row.credit==row.amount">
            <t-tooltip :content="currency_prefix+row.credit" theme="light" placement="bottom-right">
              <span>{{lang.credit}}</span>
            </t-tooltip>
          </template>
        </div>
      </template>
      <template #op="{row}">
        <template v-if="row.type">
          <a class="common-look" @click="updatePrice(row)" v-if="row.status!=='Paid'">{{lang.update_price}}</a>
          <a class="common-look" @click="signPay(row)" v-if="row.status!=='Paid'"
            :class="{disable:row.status==='Paid'}">{{lang.sign_pay}}</a>
          <a class="common-look" @click="delteOrder(row)">{{lang.delete}}</a>
        </template>
      </template>
    </t-enhanced-table>
    <t-pagination v-if="total" :total="total" :page-size="params.limit" :current="params.page"
      :page-size-options="pageSizeOptions" :on-change="changePage" />
  </t-card>
  <!-- 标记支付 -->
  <t-dialog :header="lang.sign_pay" :visible.sync="payVisible" width="600" class="sign_pay">
    <template slot="body">
      <t-form :data="signForm">
        <t-form-item :label="lang.order_amount">
          <t-input :label="currency_prefix" v-model="signForm.amount" disabled />
        </t-form-item>
        <t-form-item :label="lang.balance_paid">
          <t-input :label="currency_prefix" v-model="signForm.credit" disabled />
        </t-form-item>
        <t-form-item :label="lang.no_paid">
          <t-input :label="currency_prefix" v-model="(signForm.amount * 1).toFixed(2)" disabled />
        </t-form-item>
        <t-checkbox v-model="use_credit" class="checkDelete">{{lang.use_credit}}</t-checkbox>
      </t-form>
    </template>
    <template slot="footer">
      <div class="common-dialog">
        <t-button @click="sureSign">{{lang.sure}}</t-button>
        <t-button theme="default" @click="payVisible=false">{{lang.cancel}}</t-button>
      </div>
    </template>
  </t-dialog>
  <!-- 调整价格 -->
  <t-dialog :header="lang.update_price" :visible.sync="priceModel" :footer="false">
    <t-form :data="formData" ref="update_price" @submit="onSubmit" :rules="rules">
      <t-form-item :label="lang.change_money" name="amount">
        <t-input v-model="formData.amount" type="tel" :label="currency_prefix"
          :placeholder="lang.input+lang.money"></t-input>
      </t-form-item>
      <t-form-item :label="lang.description" name="description">
        <t-textarea :placeholder="lang.input+lang.description" v-model="formData.description" />
      </t-form-item>
      <div class="com-f-btn">
        <t-button theme="primary" type="submit">{{lang.sure}}</t-button>
        <t-button theme="default" variant="base" @click="priceModel=false">{{lang.cancel}}</t-button>
      </div>
    </t-form>
  </t-dialog>
  <!-- 删除 -->
  <t-dialog :header="lang.deleteOrder" :visible.sync="delVisible" class="delDialog" width="600">
    <template slot="body">
      <p>
        <t-icon name="error-circle" size="18" style="color:var(--td-warning-color);"></t-icon>
        &nbsp;&nbsp;{{lang.sureDelete}}
      </p>
      <div class="check">
        <t-checkbox v-model="delete_host"></t-checkbox>
        <div class="tips">
          <p class="tit">同时删除订单所有产品</p>
          <p class="tip">（若删除产品，将不会执行模块删除任务，可能会导致产品失控，请谨慎操作）</p>
        </div>
      </div>
    </template>
    <template slot="footer">
      <div class="common-dialog">
        <t-button @click="onConfirm">{{lang.sure}}</t-button>
        <t-button theme="default" @click="delVisible=false">{{lang.cancel}}</t-button>
      </div>
    </template>
  </t-dialog>
</div>
<script src="/{$template_catalog}/template/{$themes}/api/client.js"></script>
<script src="/{$template_catalog}/template/{$themes}/js/order.js"></script>
{include file="footer"}