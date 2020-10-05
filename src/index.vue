<template>
  <el-card>
    <p
      v-text="message"
      style="text-align: center"
      :class="{ 'text-red': error }"
    ></p>

    <el-form>
      <el-form-item>
        <el-input
          v-model="username"
          placeholder="Username"
          suffix-icon="el-icon-user-solid"
        ></el-input>
      </el-form-item>
      <el-form-item>
        <el-input
          v-model="password"
          placeholder="Password"
          show-password
          suffix-icon="el-icon-lock"
          @keyup.enter.native="signIn"
        ></el-input>
      </el-form-item>
    </el-form>

    <el-row>
      <el-col :span="10">
        <el-checkbox v-model="remember"> Remember Me</el-checkbox>
      </el-col>
      <el-col :span="14">
        <el-button type="primary" style="float: right" @click.prevent="signIn"
          >Sign In</el-button
        >
      </el-col>
    </el-row>
    <el-row>
      <el-col>
        <el-link type="primary" @click.prevent="forgetPassword"
          >I forgot my password</el-link
        >
      </el-col>
    </el-row>
    <el-row>
      <el-col>{{ version }}</el-col>
    </el-row>

    <el-dialog :visible.sync="show2StepDialog" title="2-step verification">
      <div>
        <p>
          Please input your code, if you lost your device, click
          <a href="#" @click.prevent="lost2Step">here</a>
        </p>
      </div>
      <el-input v-model="code" placeholder="6 digits code"></el-input>
      <span slot="footer">
        <el-button @click="show2StepDialog = false">Cancel</el-button>
        <el-button type="primary" @click="signIn">Ok</el-button>
      </span>
    </el-dialog>

    <el-dialog :visible.sync="showForgetDialog" title="Forget password">
      <el-form :model="forgetForm" label-width="auto" ref="forgetForm">
        <el-form-item
          label="Username"
          prop="username"
          :rules="[{ required: true }]"
        >
          <el-input v-model="forgetForm.username"></el-input>
        </el-form-item>
        <el-form-item
          label="Email"
          prop="email"
          :rules="[{ required: true, type: 'email' }]"
        >
          <el-input type="email" v-model="forgetForm.email"></el-input>
        </el-form-item>
      </el-form>

      <span slot="footer">
        <el-button type="primary" @click="submtForgetForm">Submit</el-button>
      </span>
    </el-dialog>
  </el-card>
</template>
<script>
export default {
  data() {
    return {
      version: document.getElementById("version").value,
      message: "Sign in to start your session",

      remember: false,

      username: "",
      password: "",
      showForgetDialog: false,
      show2StepDialog: false,

      error: false,

      code: "",
      forgetForm: {},
    };
  },
  async created() {
    if ("credentials" in navigator) {
      let username = localStorage.getItem("app.fido2");
      if (username) {
        let resp = (
          await this.$gql.query("api", {
            credentialRequestOptions: {
              __args: {
                username,
              },
            },
          })
        ).data;

        if (resp.error) {
          return;
        }

        var a = new WebAuthn.WebAuthn();
        let info;
        try {
          info = await a.authenticate(resp.data.credentialRequestOptions);
        } catch (e) {
          console.log(e.message);
          return;
        }

        resp = (
          await this.$gql.query("api", {
            loginWebAuthn: {
              __args: {
                username: username,
                assertion: JSON.stringify(info),
              },
            },
          })
        ).data;

        if (resp.error) {
          await this.$alert(resp.error.messsage);
          return;
        }

        if (resp.data.loginWebAuthn) {
          window.self.location.reload();
        } else {
          await this.$alert("login error");
        }
        return;
      }
      this.passwordLogin();
    }

    if (localStorage.getItem("app.username")) {
      this.username = localStorage.getItem("app.username");
      this.remember = true;
    }
  },
  methods: {
    async submtForgetForm() {
      try {
        await this.$refs.forgetForm.validate();
      } catch (e) {
        return;
      }

      let resp = (
        await this.$gql.mutation("api", {
          forgotPassword: {
            __args: {
              username: this.forgetForm.username,
              email: this.forgetForm.email,
            },
          },
        })
      ).data;

      if (resp.error) {
        await this.$alert(resp.error.message);
        return;
      }

      await this.$alert("Password sent to you email if informcation correct");

      this.forgetForm = {};
      this.showForgetDialog = false;
    },
    lost2Step() {
      this.show2StepDialog = false;
      this.$prompt("Please input your e-mail", "Lost 2-step device", {
        confirmButtonText: "OK",
        cancelButtonText: "Cancel",
        inputPattern: /[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?/,
        inputErrorMessage: "Invalid Email",
      })
        .then(async ({ value }) => {
          console.log(value);
          let resp = await this.$gql.mutation("api", {
            lost2StepDevice: {
              __args: {
                email: value,
                username: this.username,
                password: this.password,
              },
            },
          });
          resp = resp.data;
          if (resp.error) {
            this.$alert(resp.error.message, { type: "error" });
            return;
          }
          if (resp.data.lost2StepDevice) {
            this.$alert(
              "Login link sent to you email, if you email are correct"
            );
            return;
          }
        })
        .catch(() => {});
    },
    async login(username, password, code = "") {
      let resp = (
        await this.$gql.mutation("api", {
          login: {
            __args: {
              username: username,
              password: password,
              code: code,
            },
            username: true,
          },
        })
      ).data;

      if (resp.error) {
        this.error = true;
        if (resp.error.message == "2-step verification") {
          this.message = "Please input 2-step verification code";
          this.show2StepDialog = true;
        } else {
          this.message = resp.error.message;
        }
        return;
      }

      if (resp.data.login) {
        if (this.$refs.remember.checked) {
          localStorage.setItem("app.username", username);
        } else {
          localStorage.removeItem("app.username");
        }

        let redirect = window.self.location.hash;
        if (redirect != "") {
          window.self.location = redirect.substring(2);
        } else {
          window.self.location.reload();
        }
      }
    },
    async passwordLogin() {
      var creds = await navigator.credentials.get({
        password: true,
      });
      if (creds) {
        //Do something with the credentials.
        this.login(creds.id, creds.password);
      }
    },
    signIn() {
      if (this.username == "") {
        this.error = true;
        this.message = "Please input username";
        return;
      }
      if (this.password == "") {
        this.error = true;
        this.message = "Please input password";
        return;
      }
      return this.login(this.username, this.password, this.code);
    },
    forgetPassword() {
      this.showForgetDialog = true;
    },
    async login(username, password, code = "") {
      let resp = (
        await this.$gql.mutation("api", {
          login: {
            __args: {
              username: username,
              password: password,
              code: code,
            },
            username: true,
          },
        })
      ).data;

      if (resp.error) {
        this.error = true;
        if (resp.error.message == "2-step verification") {
          this.message = "Please input 2-step verification code";
          this.show2StepDialog = true;
        } else {
          this.message = resp.error.message;
        }
        return;
      }

      if (resp.data.login) {
        if (this.remember) {
          localStorage.setItem("app.username", username);
        } else {
          localStorage.removeItem("app.username");
        }

        let redirect = window.self.location.hash;
        if (redirect != "") {
          window.self.location = redirect.substring(2);
        } else {
          window.self.location.reload();
        }
      }
    },
  },
};
</script>