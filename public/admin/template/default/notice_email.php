{include file="header"}
<!-- =======内容区域======= -->
<link rel="stylesheet" href="/{$template_catalog}/template/{$themes}/css/setting.css">
<div id="content" class="notice-email" v-cloak>
  <!-- crumb -->
  <!-- <div class="com-crumb">
    <span>{{lang.notice_interface}}</span>
    <t-icon name="chevron-right"></t-icon>
    <a href="notice_sms.html">{{lang.sms_notice}}</a>
    <t-icon name="chevron-right"></t-icon>
    <span class="cur">{{lang.email_interface}}</span>
  </div> -->
  <t-card class="list-card-container">
    <!-- <ul class="common-tab">
      <li>
        <a href="notice_sms.html">{{lang.sms_interface}}</a>
      </li>
      <li class="active">
        <a href="javascript:;">{{lang.email_interface}}</a>
      </li>
      <li>
        <a href="notice_send.html">{{lang.send_manage}}</a>
      </li>
    </ul> -->
    <div class="common-header">
      <div class="header-left">
        <div class="com-transparent change_log" @click="jump">
          <t-button theme="primary">{{lang.email_temp_manage}}</t-button>
          <span class="txt">{{lang.email_temp_manage}}</span>
        </div>
        <t-button theme="default" @click="getMore" class="add">{{lang.get_more_interface}}</t-button>
      </div>
      <!-- <div class="search">
        <div class="com-search">
          <t-input v-model="params.keywords" class="search-input" 
            :placeholder="`${lang.please_search}ID`" 
            @keyup.enter.native="seacrh" :on-clear="clearKey" clearable>
          </t-input>
          <t-icon size="20px" name="search" @click="seacrh" class="com-search-btn" />
        </div>
      </div> -->
    </div>
    <t-table row-key="id" :data="data" size="medium" :columns="columns" :hover="hover" :loading="loading" :table-layout="tableLayout ? 'auto' : 'fixed'" @sort-change="sortChange" :hide-sort-tips="hideSortTips">
      <template #sms_type="{row}">
        <span v-if="row.sms_type.indexOf(1)!==-1">{{lang.international}}</span>
        <span v-if="row.sms_type.indexOf(0)!==-1">/&nbsp;{{lang.domestic}}</span>
      </template>
      <template #status="{row}">
        <t-tag theme="success" class="com-status" v-if="row.status===1" variant="light">{{lang.enable}}</t-tag>
        <t-tag theme="warning" class="com-status" v-if="row.status===0" variant="light">{{lang.disable}}</t-tag>
        <t-tag theme="default" class="com-status" v-if="row.status===3" variant="light">{{lang.not_install}}</t-tag>
      </template>
      <template #op="{row}">
        <a class="common-look" @click="changeStatus(row)" v-if="row.status !== 3">{{row.status ? lang.disable :
          lang.enable}}</a>
        <a class="common-look" v-if="row.help_url" :href="row.help_url" target="_blank">{{lang.apply_interface}}</a>
        <a class="common-look" @click="handleConfig(row)">{{lang.config}}</a>
        <a class="common-look" @click="installHandler(row)">{{ row.status !== 3 ?
          lang.uninstall : lang.install }}</a>
      </template>
    </t-table>
  </t-card>
  <!-- 配置弹窗 -->
  <t-dialog :header="configTip" :visible.sync="configVisble" :footer="false" width="600">
    <t-form :rules="rules" ref="userDialog" @submit="onSubmit" :label-width="170">
      <t-form-item :label="item.title" v-for="item in configData" :key="item.title">
        <!-- text -->
        <t-input v-if="item.type==='text'" v-model="item.value" :placeholder="lang.input+item.title"></t-input>
        <!-- password -->
        <t-input v-if="item.type==='password'" type="password" v-model="item.value"></t-input>
        <!-- textarea -->
        <t-textarea v-if="item.type==='textarea'" v-model="item.value" :placeholder="lang.input+item.title">
        </t-textarea>
        <!-- radio -->
        <t-radio-group v-if="item.type==='radio'" v-model="item.value" :options="computedRadio(item.options)">
        </t-radio-group>
        <!-- checkbox -->
        <t-checkbox-group v-if="item.type==='checkbox'" v-model="item.value" :options="item.options">
        </t-checkbox-group>
        <!-- select -->
        <t-select v-if="item.type==='select'" v-model="item.value" :placeholder="lang.select+item.title">
          <t-option v-for="ele in item.options" :value="ele.value" :label="ele.label" :key="ele.value">
          </t-option>
        </t-select>
      </t-form-item>
      <div class="com-f-btn">
        <t-button theme="primary" type="submit">{{lang.hold}}</t-button>
        <t-button theme="default" variant="base" @click="configVisble=false">{{lang.cancel}}</t-button>
      </div>
    </t-form>
  </t-dialog>

  <!-- 删除弹窗 -->
  <t-dialog theme="warning" :header="installTip" :visible.sync="delVisible">
    <template slot="footer">
      <t-button theme="primary" @click="sureDel">{{lang.sure}}</t-button>
      <t-button theme="default" @click="delVisible=false">{{lang.cancel}}</t-button>
    </template>
  </t-dialog>

  <!-- 启用/停用 -->
  <t-dialog theme="warning" :header="statusTip" :visible.sync="statusVisble">
    <template slot="footer">
      <t-button theme="primary" @click="sureChange">{{lang.sure}}</t-button>
      <t-button theme="default" @click="closeDialog">{{lang.cancel}}</t-button>
    </template>
  </t-dialog>
</div>
<!-- =======页面独有======= -->
<script src="/{$template_catalog}/template/{$themes}/api/setting.js"></script>
<script src="/{$template_catalog}/template/{$themes}/js/notice_email.js"></script>
{include file="footer"}