<link rel="stylesheet" href="/plugins/addon/idcsmart_help/template/admin/css/help_create.css" />
<link rel="stylesheet" href="/plugins/addon/idcsmart_help/template/admin/css/common/reset.css" />
        <!-- =======内容区域======= -->
        <div id="content" class="document" v-cloak>
          <t-card class="add_document">
            <div class="addtitle">{{id?"编辑文档":"新增文档"}}</div>
            <div class="add_form">
              <t-form label-Align="top" :data="detialform" class="add_tform" ref="myform" :rules="requiredRules">
                <t-form-item label="文档名称" name="title" class="inlineflex">
                  <t-input placeholder="请输入" v-model="detialform.title" style="width: 250px;" />
                </t-form-item>
                <t-form-item label="文档类型" name="addon_idcsmart_help_type_id" class="inlineflex">
                  <t-select bordered style="width: 250px;" v-model="detialform.addon_idcsmart_help_type_id">
                    <t-option v-for="(item,index) in typelist" :key="item.id" :label="item.name" :value="item.id" />
                  </t-select>
                </t-form-item>
                <t-form-item label="关键字" name="keywords" class="inlineflex">
                  <t-input placeholder="请输入" style="width: 250px;" v-model="detialform.keywords" />
                </t-form-item>
                <t-form-item label="上传附件" name="attachment">
                  <t-upload theme="custom" multiple v-model="files" :before-upload="beforeUploadfile"
                    action="http://101.35.248.14/console/v1/upload" :format-response="formatResponse" @fail="handleFail"
                    @progress="uploadProgress">
                    <t-button theme="default" class="upload">
                      <t-icon name="attach" color="#ccc"></t-icon> 附件
                    </t-button>
                    <span>{{uploadTip}}</span>
                  </t-upload>

                  <div v-if="files && files.length" class='list-custom'>
                    <ul>
                      <li v-for="(item, index) in files" :key="index">
                        {{ item.name}}
                        <t-icon class="delfile" name="close-circle" color="#ccc" @click="delfiles(item.name)"></t-icon>
                      </li>
                    </ul>
                  </div>
                </t-form-item>
              </t-form>

            </div>
            <div class="add_richtext">
              <form method="post">
                <div style="margin-bottom: 10px;">内容</div>
                <textarea id="tiny" name="content">{{detialform.content}}</textarea>
              </form>
            </div>
            <div class="rich_btns">
              <t-button class="rich_btns" @click="submit">提交</t-button>
              <t-button variant="outline" class="rich_btns rich_btns_save" @click="save">保存</t-button>
              <t-button theme="default" class="rich_btns" @click="cancle">取消</t-button>
            </div>
          </t-card>
        </div>
<script src="/plugins/addon/idcsmart_help/template/admin/api/help.js"></script>
<script src="/plugins/addon/idcsmart_help/template/admin/js/help_create.js"></script>
<script src="/plugins/addon/idcsmart_help/template/admin/js/tinymce/tinymce.min.js"></script>