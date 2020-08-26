<template>
  <card primary outline>
    <card-header title="2 step verification" icon="fa fa-lock"></card-header>
    <card-body>
      <div>
        <div v-if="hasTwoStep">
          <div>You already has 2 step verification</div>
          <el-button type="primary" @click="turnOff">Turn it off</el-button>
        </div>

        <div v-else>
          <div>You don't has 2 step verification</div>
          <el-button type="primary" @click="turnOn">Turn it on</el-button>
        </div>
      </div>

      <el-dialog :visible.sync="visiable">
        <p>Now download the app and scan the qrcode. Input the code to the following input and submit</p>
        <p>
          For Anroid user, install
          <a
            target="_blank"
            href="https://play.google.com/store/apps/details?id=com.azure.authenticator"
          >Authenticator</a>
        </p>

        <p>
          For iOS user, install
          <a
            target="_blank"
            href="https://apps.apple.com/us/app/microsoft-authenticator/id983156458"
          >Authenticator</a>
        </p>
        <img :src="info.image" />

        <div>
          <el-form>
            <el-form-item label="Code">
              <el-input v-model="code" placeholder="6 digits code" minlength="6" maxlength="6" />
            </el-form-item>
            <el-form-item>
              <el-button type="primary" @click="onSubmit">Submit</el-button>
            </el-form-item>
          </el-form>
        </div>
      </el-dialog>
    </card-body>
  </card>
</template>
<script>
module.exports = {
  data() {
    return {
      hasTwoStep: false,
      visiable: false,
      info: {},
      code: "",
    };
  },

  async created() {
    await this.reload();
  },
  methods: {
    async reload() {
      var resp = await this.$gql.query("api", {
        me: {
          hasTwoStepVerification: true,
        },
      });
      resp = resp.data;
      this.hasTwoStep = resp.data.me.hasTwoStepVerification;
    },
    async onSubmit() {
      var resp = await this.$gql.mutation("api", {
        updateTwoStepVerification: {
          __args: {
            code: this.code,
            secret: this.info.secret,
          },
        },
      });
      resp = resp.data;

      if (resp.error) {
        this.$alert(resp.error.message);
        return;
      }

      if (!resp.data.updateTwoStepVerification) {
        this.$alert("code is not correct");
        return;
      }

      await this.$alert("Setup complete and successfully");
      this.code = "";
      this.visiable = false;
      await this.reload();
    },
    async turnOn() {
      var resp = await this.$gql.query("api", {
        me: {
          twoStepVerification: true,
        },
      });
      resp = resp.data;
      this.info = resp.data.me.twoStepVerification;

      this.visiable = true;
    },
    async turnOff() {
      try {
        await this.$confirm("Are you sure turn it off?");
        var resp = await this.$gql.mutation("api", {
          removeTwoStepVerification: true,
        });
        resp = resp.data;
        if (resp.error) {
          this.$alert(resp.error.message);
          return;
        }

        await this.reload();
      } catch (e) {}
    },
  },
};
</script>