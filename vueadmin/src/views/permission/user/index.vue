<template>
  <div class="app-container">
    <div class="filter-container">
      <el-form :model="ruleForm1" ref="ruleForm1">
        <el-input v-model="ruleForm1.keywords" placeholder="请输入用户名" style="width: 200px;" />
        <el-date-picker
          v-model="ruleForm1.date"
          type="daterange"
          align="right"
          unlink-panels
          range-separator="至"
          start-placeholder="开始日期"
          end-placeholder="结束日期"
          value-format="yyyy-MM-dd"
          :picker-options="pickerOptions"
        ></el-date-picker>
        <el-button type="primary" icon="el-icon-search" @click="handleFilter" :loading="loading">搜索</el-button>
        <el-button type="primary" icon="el-icon-plus" @click="handleAdd()">添加</el-button>
      </el-form>
    </div>
    <el-table :data="tableData" border style="width: 100%" v-loading="loading">
      <el-table-column label="姓名" width="180">
        <template slot-scope="scope">
          <el-popover trigger="hover" placement="top">
            <p>姓名: {{ scope.row.user_name }}</p>
            <p>邮箱: {{ scope.row.email }}</p>
            <div slot="reference" class="name-wrapper">
              <el-tag size="medium">{{ scope.row.user_name }}</el-tag>
            </div>
          </el-popover>
        </template>
      </el-table-column>
      <el-table-column label="日期" width="250">
        <template slot-scope="scope">
          <i class="el-icon-time"></i>
          <span style="margin-left: 10px">{{ scope.row.create_time }}</span>
        </template>
      </el-table-column>
      <el-table-column label="角色">
        <template slot-scope="scope">
          <el-tag
            style="margin-left:5px;"
            v-for="(item, i) in scope.row.role"
            :key="i"
            type="success"
            size="medium"
          >{{ item.role_name }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="操作" width="180">
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
        <el-form-item label="用户名" :label-width="formLabelWidth" prop="userName">
          <el-input v-model="ruleForm2.userName" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="手机号" :label-width="formLabelWidth" prop="mobile">
          <el-input v-model="ruleForm2.mobile" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="邮箱" :label-width="formLabelWidth" prop="email">
          <el-input v-model="ruleForm2.email" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="角色" :label-width="formLabelWidth" prop="roleids">
          <el-checkbox-group v-model="ruleForm2.roleids">
            <el-checkbox v-for="(item, i) in roleList" :label="item.id" :key="i">{{item.role_name}}</el-checkbox>
          </el-checkbox-group>
        </el-form-item>
        <el-form-item label="新密码" :label-width="formLabelWidth" prop="newpassword">
          <el-input v-model="ruleForm2.newpassword" autocomplete="off"></el-input>
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
import { list, add, detail, edit, del } from "@/api/user";

export default {
  name: "User",
  data() {
    return {
      loading: false,
      pickerOptions: {
        shortcuts: [
          {
            text: "最近一周",
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
              picker.$emit("pick", [start, end]);
            }
          },
          {
            text: "最近一个月",
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
              picker.$emit("pick", [start, end]);
            }
          },
          {
            text: "最近三个月",
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
              picker.$emit("pick", [start, end]);
            }
          }
        ]
      },
      tableData: [],
      total: 1000,
      page: 0,
      pageSize: 10,
      ruleForm1: {
        date: ["", ""],
        keywords: ""
      },
      dialogFormVisible: false,
      dialogType: "",
      dialogTitle: "",
      ruleForm2: {
        userName: "",
        mobile: "",
        email: "",
        roleids: [],
        newpassword: ""
      },
      editId: 0,
      roleList: [],
      formLabelWidth: "120px",
      rules: {
        userName: [{ required: true, message: "用户名不能为空" }],
        mobile: [{ required: true, message: "手机不能为空" }],
        email: [{ required: true, message: "邮箱不能为空" }],
        roleids: [{ required: true, message: "角色不能为空" }]
      }
    };
  },
  created() {
    this.getList();
  },
  methods: {
    handleAdd() {
      this.dialogType = "add";
      this.dialogTitle = "用户添加";
      this.dialogFormVisible = true;
      this.$refs["ruleForm2"] ? this.$refs["ruleForm2"].resetFields() : null;
    },
    handleEdit(index, row) {
      this.editId = row.id;
      this.dialogType = "edit";
      this.dialogTitle = "用户编辑";
      this.dialogFormVisible = true;
      detail(this.editId).then(res => {
        let detail = res.data.detail;
        this.ruleForm2.userName = detail.user_name;
        this.ruleForm2.mobile = detail.mobile;
        this.ruleForm2.email = detail.email;
        this.ruleForm2.roleids = detail.role;
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
        page: this.page,
        keywords: this.ruleForm1.keywords,
        start_time: this.ruleForm1.date[0],
        end_time: this.ruleForm1.date[1]
      };
      list(params)
        .then(res => {
          this.tableData = res.data.list;
          this.total = res.data.total;
          this.pageSize = res.data.page_size;
          this.roleList = res.data.role_list;
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