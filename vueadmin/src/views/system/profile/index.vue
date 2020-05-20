<template>
  <div class="app-container">
    <el-row :gutter="20">
      <el-col :span="8" :offset="6">
        <el-form :model="ruleForm" :rules="rules" ref="ruleForm">
          <el-form-item label="用户名">
            <el-input v-model="ruleForm.username" :disabled="true"></el-input>
          </el-form-item>
          <el-form-item label="原始密码" prop="password">
            <el-input
              type="password"
              v-model="ruleForm.password"
              :disabled="disabled"
              autocomplete="off"
            ></el-input>
          </el-form-item>
          <el-form-item label="新密码" prop="newPassword">
            <el-input
              type="password"
              v-model="ruleForm.newPassword"
              :disabled="disabled"
              autocomplete="off"
            ></el-input>
          </el-form-item>
          <el-form-item label="确认新密码" prop="checkNewPassword">
            <el-input
              type="password"
              v-model="ruleForm.checkNewPassword"
              :disabled="disabled"
              autocomplete="off"
            ></el-input>
          </el-form-item>
          <el-form-item v-if="disabled===true">
            <el-button type="primary" @click="edit">修改密码</el-button>
          </el-form-item>
          <el-form-item v-if="disabled===false">
            <el-button type="primary" :loading="loading" @click="submitForm('ruleForm')">提交</el-button>
            <el-button @click="resetForm('ruleForm')">重置</el-button>
            <el-button @click="cancel('ruleForm')">取消</el-button>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>
  </div>
</template>
<script>
export default {
  name: "Profile",
  data() {
    var validatePass = (rule, value, callback) => {
      if (value === "") {
        callback(new Error("请输入密码"));
      } else {
        callback();
      }
    };
    var validateNewPass = (rule, value, callback) => {
      if (value === "") {
        callback(new Error("请输入新密码"));
      } else {
        if (this.ruleForm.checkNewPassword !== "") {
          this.$refs.ruleForm.validateField("checkNewPass");
        }
        callback();
      }
    };
    var validateCheckNewPass = (rule, value, callback) => {
      if (value === "") {
        callback(new Error("请再次输入新密码"));
      } else if (value !== this.ruleForm.newPassword) {
        callback(new Error("两次输入密码不一致!"));
      } else {
        callback();
      }
    };
    return {
      loading: false,
      disabled: true,
      ruleForm: {
        username: "",
        password: "",
        newPassword: "",
        checkNewPassword: ""
      },
      rules: {
        password: [{ validator: validatePass, trigger: "blur" }],
        newPassword: [{ validator: validateNewPass, trigger: "blur" }],
        checkNewPassword: [{ validator: validateCheckNewPass, trigger: "blur" }]
      }
    };
  },
  created() {
    this.ruleForm.username = this.$store.getters.name;
  },
  methods: {
    edit() {
      this.disabled = false;
    },
    cancel(formName) {
      this.disabled = true;
      this.$refs[formName].resetFields();
    },
    submitForm(formName) {
      this.loading = true;
      this.$refs[formName].validate(valid => {
        if (valid) {
          let params = this.ruleForm;
          this.$store
            .dispatch("user/editInfo", params)
            .then(() => {
              this.$message({
                message: "修改成功",
                type: "success",
                duration: 5 * 1000
              });
              this.loading = false;
              this.$router.push(`/login?redirect=${this.$route.fullPath}`);
            })
            .catch(() => {
              this.loading = false;
            });
        } else {
          console.log("error submit!!");
          return false;
        }
      });
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
    }
  }
};
</script>