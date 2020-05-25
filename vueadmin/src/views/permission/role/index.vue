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
          <div v-for="(item, i) in authListTree" :key="i">
            <el-checkbox
              v-model="ruleForm2.authids"
              :label="item.id"
              @change="(e)=>level1(e,item.id)"
            >{{item.auth_name}}</el-checkbox>
            <div v-for="(item2, i2) in item.children" :key="i2" style="padding-left:20px;">
              <el-checkbox
                v-model="ruleForm2.authids"
                :label="item2.id"
                @change="(e)=>level2(e,item2.id,item.id)"
              >{{item2.auth_name}}</el-checkbox>
              <div style="padding-left:20px;">
                <el-checkbox
                  v-for="(item3, i3) in item2.children"
                  :key="i3"
                  v-model="ruleForm2.authids"
                  :label="item3.id"
                  @change="(e)=>level3(e,item3.id,item2.id,item.id)"
                >{{item3.auth_name}}</el-checkbox>
              </div>
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
        status: "1",
        authids: []
      },
      editId: 0,
      authList: [],
      authListTree: [],
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
        this.ruleForm2.roleName = detail.role_name;
        this.ruleForm2.status = detail.status;
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
        this.getList();
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
          this.authListTree = res.data.auth_list_tree;
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
    },
    level1(e, id) {
      let authids = this.ruleForm2.authids;
      let authList = this.authList;
      authList.map(v => {
        if (e) {
          if (v.parent_id == id) {
            !authids.includes(v.id) ? authids.push(v.id) : "";
            authList.map(vv => {
              if (vv.parent_id == v.id) {
                !authids.includes(vv.id) ? authids.push(vv.id) : "";
              }
            });
          }
        } else {
          if (v.parent_id == id) {
            authids = authids.filter(item => item != v.id);
            authList.map(vv => {
              if (vv.parent_id == v.id) {
                authids = authids.filter(item2 => item2 != vv.id);
              }
            });
          }
        }
      });
      this.ruleForm2.authids = authids;
    },
    level2(e, id, pid) {
      let authids = this.ruleForm2.authids;
      let authList = this.authList;
      authList.map(v => {
        if (e) {
          if (v.parent_id == id || v.id == pid) {
            !authids.includes(v.id) ? authids.push(v.id) : "";
          }
        } else {
          if (v.parent_id == id) {
            authids = authids.filter(item => item != v.id);
            //检测上级还有没有下级被选中
            let count = 0;
            authList.map(item => {
              if (item.parent_id == pid) {
                authids.includes(item.id) ? ++count : "";
              }
            });
            if (count == 0) {
              authids = authids.filter(item => item != pid);
            }
          }
        }
      });
      this.ruleForm2.authids = authids;
    },
    level3(e, id, pid1, pid2) {
      let authids = this.ruleForm2.authids;
      let authList = this.authList;
      authList.map(v => {
        if (e) {
          if (v.parent_id == id || v.id == pid1 || v.id == pid2) {
            !authids.includes(v.id) ? authids.push(v.id) : "";
          }
        } else {
          //检测上级还有没有下级被选中
          let count1 = 0;
          authList.map(item => {
            if (item.parent_id == pid1) {
              authids.includes(item.id) ? ++count1 : "";
            }
          });
          if (count1 == 0) {
            authids = authids.filter(item => item != pid1);
          }
          //检测上上级还有没有下级被选中
          let count2 = 0;
          authList.map(item => {
            if (item.parent_id == pid2) {
              authids.includes(item.id) ? ++count2 : "";
            }
          });
          if (count2 == 0) {
            authids = authids.filter(item => item != pid2);
          }
        }
      });
      this.ruleForm2.authids = authids;
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