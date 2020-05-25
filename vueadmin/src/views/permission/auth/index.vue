<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button type="primary" icon="el-icon-plus" @click="handleAdd()">添加</el-button>
    </div>
    <el-table
      :data="tableData"
      border
      style="width: 100%"
      v-loading="loading"
      row-key="id"
      :default-expand-all="false"
      :tree-props="{children: 'children', hasChildren: 'hasChildren'}"
    >
      <el-table-column label="节点名">
        <template slot-scope="scope">
          <span style="margin-left: 10px">{{ scope.row.auth_name }}</span>
        </template>
      </el-table-column>
      <el-table-column label="规则">
        <template slot-scope="scope">
          <span style="margin-left: 10px">{{ scope.row.rule }}</span>
        </template>
      </el-table-column>
      <el-table-column label="状态">
        <template slot-scope="scope">
          <el-button
            v-if="scope.row.status==1"
            size="mini"
            type="success"
            @click="status(scope.row.id,0)"
          >已启用</el-button>
          <el-button
            v-if="scope.row.status==0"
            size="mini"
            type="info"
            @click="status(scope.row.id,1)"
          >已停用</el-button>
        </template>
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button size="mini" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
          <el-popconfirm
            title="删除须谨慎，确认要删除吗？"
            icon="el-icon-info"
            iconColor="red"
            @onConfirm="handleDelete(scope.$index, scope.row)"
          >
            <el-button size="mini" type="danger" slot="reference">删除</el-button>
          </el-popconfirm>
        </template>
      </el-table-column>
    </el-table>
    <el-dialog :title="dialogTitle" :visible.sync="dialogFormVisible">
      <el-form :model="ruleForm" :rules="rules" ref="ruleForm">
        <el-form-item label="上级" :label-width="formLabelWidth" prop="parentId">
          <el-cascader
            v-model="ruleForm.parentId"
            :options="tableData"
            :value="'id'"
            :label="'auth_name'"
            :props="{ checkStrictly: true,value:'id',label:'auth_name' }"
            clearable
          ></el-cascader>
          <span>留空为顶级</span>
        </el-form-item>
        <el-form-item label="节点名" :label-width="formLabelWidth" prop="authName">
          <el-input v-model="ruleForm.authName" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="规则" :label-width="formLabelWidth" prop="rule">
          <el-input v-model="ruleForm.rule" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="图标" :label-width="formLabelWidth" prop="icon2">
          <el-input v-model="ruleForm.icon2" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="排序" :label-width="formLabelWidth" prop="sort">
          <el-input v-model="ruleForm.sort" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="状态" :label-width="formLabelWidth" prop="status">
          <el-radio v-model="ruleForm.status" label="1">开启</el-radio>
          <el-radio v-model="ruleForm.status" label="0">关闭</el-radio>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="submitForm('ruleForm')">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { list, add, detail, edit, del } from "@/api/auth";

export default {
  name: "User",
  data() {
    return {
      loading: false,
      tableData: [],
      dialogFormVisible: false,
      dialogType: "",
      dialogTitle: "",
      ruleForm: {
        parentId: "",
        authName: "",
        rule: "",
        icon2: "",
        sort: "",
        status: "1"
      },
      editId: 0,
      formLabelWidth: "120px",
      rules: {
        authName: [{ required: true, message: "节点名不能为空" }]
      }
    };
  },
  created() {
    this.getList();
  },
  methods: {
    handleAdd() {
      this.dialogType = "add";
      this.dialogTitle = "节点添加";
      this.dialogFormVisible = true;
      this.$refs["ruleForm"] ? this.$refs["ruleForm"].resetFields() : null;
    },
    handleEdit(index, row) {
      this.editId = row.id;
      this.dialogType = "edit";
      this.dialogTitle = "节点编辑";
      this.dialogFormVisible = true;
      detail(this.editId).then(res => {
        let detail = res.data.detail;
        this.ruleForm.parentId = detail.parent_id;
        this.ruleForm.authName = detail.auth_name;
        this.ruleForm.rule = detail.rule;
        this.ruleForm.icon = detail.icon;
        this.ruleForm.sort = detail.sort;
      });
    },
    handleDelete(index, row) {
      del({ id: row.id }).then(res => {
        this.$message({
          message: "删除成功",
          type: "success",
          duration: 5 * 1000
        });
        this.getList();
      });
    },
    getList() {
      this.loading = true;
      list({})
        .then(res => {
          this.tableData = res.data.list;
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
        });
    },
    status(id, status) {
      let msg = status == 0 ? "禁用成功" : "启用成功";
      edit({ id, status }).then(res => {
        this.$message({
          message: msg,
          type: "success",
          duration: 5 * 1000
        });
        this.getList();
      });
    },
    submitForm(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          let dialogType = this.dialogType;
          if (dialogType == "add") {
            let params = this.ruleForm;
            add(params).then(res => {
              this.$message({
                message: "添加成功",
                type: "success",
                duration: 5 * 1000
              });
              this.dialogFormVisible = false;
              this.getList();
            });
          }
          if (dialogType == "edit") {
            let params = this.ruleForm;
            params.id = this.editId;
            edit(params).then(res => {
              this.$message({
                message: "编辑成功",
                type: "success",
                duration: 5 * 1000
              });
              this.dialogFormVisible = false;
              this.getList();
            });
          }
        } else {
          console.log("error submit!!");
          return false;
        }
      });
    }
  }
};
</script>

<style scoped>
.filter-container {
  padding-bottom: 10px;
}
.pagination-container {
  background: #fff;
  padding: 32px 16px;
}
</style>