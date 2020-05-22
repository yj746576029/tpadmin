<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button type="primary" icon="el-icon-plus" @click="handleAdd()">添加</el-button>
    </div>
    <el-table :data="tableData" border style="width: 100%" v-loading="loading">
      <el-table-column label="角色名">
        <template slot-scope="scope">
          <span style="margin-left: 10px">{{ scope.row.role_name }}</span>
        </template>
      </el-table-column>
      <el-table-column label="状态">
        <template slot-scope="scope">
          <el-button v-if="scope.row.status==1" size="mini" type="success">已启用</el-button>
          <el-button v-if="scope.row.status==0" size="mini" type="info">已停用</el-button>
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
    <el-pagination
      class="pagination-container"
      background
      layout="prev, pager, next"
      :total="total"
      :page-size="pageSize"
      @current-change="currentChange"
    ></el-pagination>
    <el-dialog :title="dialogTitle" :visible.sync="dialogFormVisible">
      <el-form :model="ruleForm2" :rules="rules" ref="ruleForm2">
        <el-form-item label="角色名" :label-width="formLabelWidth" prop="roleName">
          <el-input v-model="ruleForm2.roleName" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="状态" :label-width="formLabelWidth" prop="status">
          <el-radio v-model="ruleForm2.status" label="1">开启</el-radio>
          <el-radio v-model="ruleForm2.status" label="0">关闭</el-radio>
        </el-form-item>
        <el-form-item label="权限" :label-width="formLabelWidth" prop="authids">
          <div v-for="(item, i) in authList" :key="i">
            <el-checkbox :label="item.id">{{item.auth_name}}</el-checkbox>
            <div v-for="(item2, i2) in item.children" :key="i2">
              &emsp;<el-checkbox :label="item2.id">{{item2.auth_name}}</el-checkbox>
              <el-checkbox v-for="(item3, i3) in item2.children" :key="i3" :label="item3.id">{{item3.auth_name}}</el-checkbox>
            </div>
          </div>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="submitForm('ruleForm2')">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { list, add, detail, edit, del } from "@/api/role";

export default {
  name: "User",
  data() {
    return {
      loading: false,

      tableData: [],
      total: 1000,
      page: 0,
      pageSize: 10,
      dialogFormVisible: false,
      dialogType: "",
      dialogTitle: "",
      ruleForm2: {
        roleName: "",
        status: "1"
      },
      editId: 0,
      authList: [],
      formLabelWidth: "120px",
      rules: {
        roleName: [{ required: true, message: "角色名不能为空" }]
      }
    };
  },
  created() {
    this.getList();
  },
  methods: {
    handleAdd() {
      this.dialogType = "add";
      this.dialogTitle = "角色添加";
      this.dialogFormVisible = true;
      this.$refs["ruleForm2"] ? this.$refs["ruleForm2"].resetFields() : null;
    },
    handleEdit(index, row) {
      this.editId = row.id;
      this.dialogType = "edit";
      this.dialogTitle = "角色编辑";
      this.dialogFormVisible = true;
      detail(this.editId).then(res => {
        let detail = res.data.detail;
        this.ruleForm2.username = detail.username;
        this.ruleForm2.mobile = detail.mobile;
        this.ruleForm2.email = detail.email;
        this.ruleForm2.authids = detail.auth;
      });
    },
    handleDelete(index, row) {
      del({ id: row.id }).then(res => {
        this.$message({
          message: "删除成功",
          type: "success",
          duration: 5 * 1000
        });
      });
    },
    handleFilter() {
      this.page = 0;
      this.getList();
    },
    getList() {
      this.loading = true;
      let params = {
        page: this.page
      };
      list(params)
        .then(res => {
          this.tableData = res.data.list;
          this.total = res.data.total;
          this.pageSize = res.data.page_size;
          this.authList = res.data.auth_list;
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
        });
    },
    currentChange(e) {
      this.page = e;
      this.getList();
    },
    submitForm(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          let dialogType = this.dialogType;
          if (dialogType == "add") {
            let params = this.ruleForm2;
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
            let params = this.ruleForm2;
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