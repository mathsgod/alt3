var Vue = window.Vue;
var $ = window.$;
var vm = new Vue({
    el: "#app",
    data: {
        username: "",
        password: "",
        code: "",
        message: "Sign in to start your session",
        error: false
    },
    async created() {
        if ('credentials' in navigator) {
            if (localStorage.getItem("app.fido2")) {
                var username = localStorage.getItem("app.fido2");
                let resp = this.$gql.query("api", {
                    credentialRequestOptions: {
                        __args: {
                            username
                        }
                    }
                });
                resp = resp.data;

                var a = new WebAuthn();
                a.authenticate(resp.data.data.credentialRequestOptions).then(info => {
                    this.$gql.query("api", {
                        loginWebAuthn: {
                            __args: {
                                username: username,
                                assertion: JSON.stringify(info)
                            }
                        }
                    }).then(resp => {
                        if (resp.data.data.loginWebAuthn) {
                            window.self.location.reload();
                        } else {
                            bootbox.alert("login error");
                        }
                    });
                }).catch(resp => {
                    this.passwordLogin();
                });
            } else {
                this.passwordLogin();
            }
        }
    }, mounted() {
        if (localStorage.getItem("app.username")) {
            this.username = localStorage.getItem("app.username");
            this.$refs.remember.checked = true;
        }
    },
    methods: {
        passwordLogin() {
            navigator.credentials.get({
                password: true
            }).then(creds => {
                if (creds) {
                    //Do something with the credentials.
                    this.login(creds.id, creds.password);
                }
            });
        }, async login(username, password, code) {
            let resp = await this.$gql.mutation("api", {
                login: {
                    __args: {
                        username: username,
                        password: password,
                        code: code ? code : ""
                    },
                    username: true
                }
            });
            resp = resp.data;
            if (resp.error) {
                this.error = true;
                if (resp.error.message == "2-step verification") {
                    bootbox.prompt("Please input 2-step verification code", result => {
                        if (result) {
                            this.login(username, password.code);
                        }

                    });
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
                    window.self.location = redirect.substring(1);
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
            return this.login(this.username, this.password);
        }, forgetPassword() {

            let self = this;
            var bb = bootbox.dialog({
                title: "Forget password",
                message: document.getElementById("forget-dialog").innerHTML,
                centerVertical: true,
                buttons: {
                    submit: {
                        label: "Submit",
                        className: "btn-success",
                        async callback() {

                            var form = bb.find("form")[0];
                            if (!form.checkValidity()) return;
                            var resp = await self.$gql.mutation("api", {
                                forgotPassword: {
                                    __args: {
                                        username: form.username.value,
                                        email: form.email.value
                                    }
                                }
                            });
                            resp = resp.data;
                            if (resp.error) {
                                bootbox.alert(resp.error.message);
                            } else {
                                bootbox.alert("Password sent to your email if information correct");
                            }

                        }
                    }
                }
            });
        }
    }
});