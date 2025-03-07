{include file="header"}
<!-- =======内容区域======= -->
<link rel="stylesheet" href="/{$template_catalog}/template/{$themes}/css/product.css">
<div id="content" class="product table" v-cloak>
  <t-card class="list-card-container" :class="{stop: data.status===0}">
    <div class="common-header">
      <div class="left">
        <t-button @click="addGroup" class="add">{{lang.create_group}}</t-button>
        <t-button theme="default" @click="addProduct" class="add">{{lang.create_product}}</t-button>
      </div>
      <div class="common-header">
        <div class="com-search">
          <t-input v-model="params.keywords" class="search-input" :placeholder="`${lang.please_search}${lang.product}`" @keyup.enter.native="seacrh" :on-clear="clearKey" clearable>
          </t-input>
          <t-icon size="20px" name="search" @click="seacrh" class="com-search-btn" />
        </div>
      </div>
    </div>
    <t-enhanced-table ref="table" row-key="key" drag-sort="row-handler" :data="data" :columns="columns" :tree="{ treeNodeColumnIndex: 1 }" :loading="loading" :hover="hover" :tree-expand-and-fold-icon="treeExpandAndFoldIconRender" :before-drag-sort="beforeDragSort" @abnormal-drag-sort="onAbnormalDragSort" @drag-sort="changeSort" class="product-table" :max-height="maxHeight" :row-class-name="rowName">
      <template #drag="{row}">
        <t-icon name="move"></t-icon>
      </template>
      <template #product_group_name="{row}">
        <span v-if="row.name && !row.product_group_name_first && !row.parent_id" class="first-name">
          {{row.name}}
        </span>
        <span v-else-if="row.parent_id" class="second-name">{{row.name}}</span>
      </template>
      <template #name="{row}">
        <a :href="`product_detail.html?id=${row.id}`" v-if="row.qty !== undefined" class="product-name">{{row.name}}
        </a>
        <!-- <template v-else>
          <t-icon :name="row.isExpand ? 'caret-up-small' : 'caret-down-small'"></t-icon>
        </template> -->
      </template>
      <template #hidden="{row}">
        <t-switch size="large" :custom-value="[1,0]" v-model="row.hidden" @change="onChange(row)" v-if="row.qty !== undefined"></t-switch>
      </template>
      <template #op="{row}">
        <a class="common-look" @click="editHandler(row)">{{lang.edit}}</a>
        <a class="common-look" @click="deleteHandler(row)">{{lang.delete}}</a>
      </template>
    </t-enhanced-table>
  </t-card>
  <!-- 修改分组名 -->
  <t-dialog :header="updateNameTip" :visible.sync="updateNameVisble" :footer="false">
    <t-form :data="updataData" ref="groupForm" @submit="submitUpdateName" :rules="rules">
      <t-form-item :label="lang.group_name" name="name">
        <t-input v-model="updataData.name" :placeholder="lang.input+lang.group_name"></t-input>
      </t-form-item>
      <div class="com-f-btn">
        <t-button theme="primary" type="submit">{{lang.hold}}</t-button>
        <t-button theme="default" variant="base" @click="updateNameVisble=false">{{lang.cancel}}</t-button>
      </div>
    </t-form>
  </t-dialog>
  <!-- 新建分组 -->
  <t-dialog :header="lang.create_group" :visible.sync="groupModel" :footer="false" @close="closeGroup">
    <t-form :data="formData" ref="groupForm" @submit="onSubmit" :rules="rules">
      <t-form-item :label="lang.group_name" name="name">
        <t-input v-model="formData.name" :placeholder="lang.input+lang.group_name"></t-input>
      </t-form-item>
      <t-form-item :label="lang.belong_group" name="id">
        <t-select v-model="formData.id" :placeholder="lang.select+lang.group_name" clearable>
          <t-option v-for="item in firstGroup" :value="item.id" :label="item.name" :key="item.id">
          </t-option>
        </t-select>
      </t-form-item>
      <div class="com-f-btn">
        <t-button theme="primary" type="submit">{{lang.hold}}</t-button>
        <t-button theme="default" variant="base" @click="closeGroup">{{lang.cancel}}</t-button>
      </div>
    </t-form>
  </t-dialog>
  <!-- 新建商品 -->
  <t-dialog :header="lang.create_product" :visible.sync="productModel" :footer="false" @close="closeProduct">
    <t-form :data="productData" ref="productForm" @submit="submitProduct" :rules="productRules">
      <t-form-item :label="lang.product_name" name="name">
        <t-input v-model="productData.name" :placeholder="lang.input+lang.product_name"></t-input>
      </t-form-item>
      <t-form-item :label="lang.first_group" name="firstId">
        <t-select v-model="productData.firstId" :placeholder="lang.select+lang.group_name" @change="changeFirId">
          <t-option v-for="item in firstGroup" :value="item.id" :label="item.name" :key="item.id">
          </t-option>
        </t-select>
      </t-form-item>
      <t-form-item :label="lang.second_group" name="product_group_id">
        <t-select v-model="productData.product_group_id" :placeholder="lang.select+lang.group_name">
          <t-option v-for="item in secondGroup" :value="item.id" :label="item.name" :key="item.id">
          </t-option>
        </t-select>
      </t-form-item>
      <div class="com-f-btn">
        <t-button theme="primary" type="submit">{{lang.hold}}</t-button>
        <t-button theme="default" variant="base" @click="closeProduct">{{lang.cancel}}</t-button>
      </div>
    </t-form>
  </t-dialog>
  <!-- 删除分组下面有商品的时候 -->
  <t-dialog :visible.sync="delHasPro" :footer="false" class="connect-group" :on-close="closeMove">
    <template slot="header">
      <b>{{lang.delete_group}}</b>
      <t-icon name="error-circle" size="16px"></t-icon>
      <span>{{lang.tip8}}</span>
    </template>
    <template slot="body">
      <t-form :data="moveProductForm" :rules="rules" ref="groupDialog" @submit="moveProduct">
        <t-form-item :label="lang.product_name" name="fail">
          <t-input disabled v-model="concat_shop"></t-input>
        </t-form-item>
        <t-form-item :label="lang.choose_group" name="target_product_group_id">
          <t-select v-model="moveProductForm.target_product_group_id" :placeholder="lang.select +lang.product_group" :popup-props="popupProps">
            <t-option-group v-for="(list, index) in tempGroup" :key="index" :label="typeof list.name === 'object' ? list.group.label : list.name" divider>
              <t-option v-for="item in list.children" :value="item.id" :label="item.name" :key="item.id">
                {{ item.name }}
              </t-option>
            </t-option-group>
          </t-select>
        </t-form-item>
        <div class="com-f-btn">
          <t-button theme="primary" type="submit">{{lang.hold}}</t-button>
          <t-button theme="default" variant="base" @click="closeMove">{{lang.cancel}}</t-button>
        </div>
      </t-form>
    </template>
  </t-dialog>
  <!-- 删除弹窗 -->
  <t-dialog theme="warning" :header="lang.sureDelete" :visible.sync="delVisible">
    <template slot="footer">
      <t-button theme="primary" @click="sureDel">{{lang.sure}}</t-button>
      <t-button theme="default" @click="delVisible=false">{{lang.cancel}}</t-button>
    </template>
  </t-dialog>
</div>
<!-- =======页面独有======= -->
<script src="/{$template_catalog}/template/{$themes}/api/product.js"></script>
<script src="/{$template_catalog}/template/{$themes}/js/product.js"></script>
{include file="footer"}