var Vue = window.Vue;
import ElementUI from 'element-ui';
//import 'element-ui/lib/theme-chalk/index.css';
Vue.use(ElementUI);

var $ = window.$;
var vm = new Vue({
    el: "#app",
    data: {
        username: "",
        password: "",
        message: "Sign in to start your session",
        code: "",
        error: false,
        showForgetDialog: false,
        forgetForm: {},
        show2StepDialog: false
    },
    async created() {
        if ('credentials' in navigator) {
            let username = localStorage.getItem("app.fido2");
            if (username) {
                let resp = (await this.$gql.query("api", {
                    credentialRequestOptions: {
                        __args: {
                            username
                        }
                    }
                })).data;

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

                resp = (await this.$gql.query("api", {
                    loginWebAuthn: {
                        __args: {
                            username: username,
                            assertion: JSON.stringify(info)
                        }
                    }
                })).data;

                if (resp.error) {
                    alert(resp.error.messsage);
                    return;
                }

                if (resp.data.loginWebAuthn) {
                    window.self.location.reload();
                } else {
                    this.$alert("login error");
                }
                return;
            }
            this.passwordLogin();
        }
    }, mounted() {
        if (localStorage.getItem("app.username")) {
            this.username = localStorage.getItem("app.username");
            this.$refs.remember.checked = true;
        }
    },
    methods: {
        async passwordLogin() {
            var creds = await navigator.credentials.get({
                password: true
            });
            if (creds) {
                //Do something with the credentials.
                this.login(creds.id, creds.password);
            }
        }, async login(username, password, code = "") {
            let resp = (await this.$gql.mutation("api", {
                login: {
                    __args: {
                        username: username,
                        password: password,
                        code: code
                    },
                    username: true
                }
            })).data;

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

        }, signIn() {
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
        }, forgetPassword() {
            this.showForgetDialog = true;
        }, async submtForgetForm() {
            try {
                await this.$refs.forgetForm.validate();
            } catch (e) {
                return;
            }

            let resp = (await this.$gql.mutation("api", {
                forgotPassword: {
                    __args: {
                        username: this.forgetForm.username,
                        email: this.forgetForm.email
                    }
                }
            })).data;

            if (resp.error) {
                await this.$alert(resp.error.message);
                return;
            }

            await this.$alert("Password sent to you email if informcation correct");

            this.forgetForm = {};
            this.showForgetDialog = false;
        }, lost2Step() {

            this.show2StepDialog = false;
            this.$prompt('Please input your e-mail', 'Lost 2-step device', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                inputPattern: /[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?/,
                inputErrorMessage: 'Invalid Email'
            }).then(async ({ value }) => {
                console.log(value);
                let resp = await this.$gql.mutation("api", {
                    lost2StepDevice: {
                        __args: {
                            email: value,
                            username: this.username,
                            password: this.password
                        }
                    }
                });
                resp = resp.data;
                if (resp.error) {
                    this.$alert(resp.error.message, { type: "error" });
                    return;
                }
                if (resp.data.lost2StepDevice) {
                    this.$alert("Login link sent to you email, if you email are correct");
                    return;
                }


            }).catch(() => {

            });

        }
    }
});