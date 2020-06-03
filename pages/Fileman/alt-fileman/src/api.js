import jwt_decode from "jwt-decode";
import Vue from 'vue';
export default class API {

    constructor(server, token) {
        this.server = server;
        this.token = token;
        this.payload = jwt_decode(token);
    }

    async listAllDirecotry(path) {
        let resp = await Vue.gql.query(this.getAPIServer(), {
            listAllDirectory: {
                __args: {
                    path: path
                },
                name: true,
                path: true,
                pathname: true,
                children: true
            }
        });

        resp = resp.data;

        if (resp.error) {
            throw resp.error.message;
        }
        return resp.data.listAllDirectory;
    }

    getAPIServer() {
        return this.server + "?token=" + this.token;
    }

    async fetchDataUrl(pathname) {
        var path = `${this.server}/download_file?token=${this.token}&file=${pathname}`;
        var resp = await fetch(path);
        var blob = await resp.blob();

        const url = URL.createObjectURL(blob);
        return url;
    }

    async downloadFile(file) {
        console.log("download", file);
        //var path = `${this.server}/download_file?token=${this.token}&file=${file.pathname}`;
        var path = file.url;
        let resp = await fetch(path);
        let blob = await resp.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.style.display = "none";
        a.href = url;
        // the filename you want
        a.download = file.filename;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
    }

    async getUploadRequest() {
        var xhr = new XMLHttpRequest();
        xhr.open("post", `${this.server}upload_file?token=${this.token}`);

        xhr.setRequestHeader("Accept", "application/json");
        return xhr;
    }

    async renameDirectory(oldname, newname) {
        let resp = await Vue.gql.mutation(this.getAPIServer(), {
            renameDirectory: {
                __args: {
                    oldname: oldname,
                    newname: newname
                }
            }
        });
        resp = resp.data;
        return resp.renameDirectory;
    }

    async renameFile(oldname, newname) {
        console.log("renameFile", oldname, newname);
        let resp = await Vue.gql.mutation(this.getAPIServer(), {
            renameFile: {
                __args: {
                    oldname: oldname,
                    newname: newname
                }
            }
        });
        resp = resp.data;
        if (resp.error) {
            throw resp.error;
        }
        return resp.renameFile;
    }

    async removeFile(filename) {
        let resp = await Vue.gql.mutation(this.getAPIServer(), {
            removeFile: {
                __args: {
                    filename: filename
                }
            }
        });
        resp = resp.data;
        return resp.removeFile;

    }

    async removeDirectory(pathname) {
        let resp = await Vue.gql.mutation(this.getAPIServer(), {
            removeDirectory: {
                __args: {
                    pathname: pathname
                }
            }
        });
        resp = resp.data;
        if (resp.error) throw resp.error;

    }

    async createDirectory(pathname) {
        console.log("create directory", pathname);
        let resp = await Vue.gql.mutation(this.getAPIServer(), {
            createDirectory: {
                __args: {
                    pathname: pathname
                }
            }
        });
        resp = resp.data;
        if (resp.error) {
            throw resp.error;
        }
    }

    async listDirectory(path) {
        let resp = await Vue.gql.query(this.getAPIServer(), {
            listDirectory: {
                __args: {
                    path: path
                },
                name: true,
                pathname: true
            }
        });
        resp = resp.data;

        if (resp.error) {
            throw resp.error.message;
        }
        return resp.data.listDirectory;
    }

    async listFile(path) {
        let resp = await Vue.gql.query(this.getAPIServer(), {
            listFile: {
                __args: {
                    path: path
                },
                filename: true,
                size: true,
                pathname: true,
                url: true
            }
        });

        resp = resp.data;

        return resp.data.listFile;
    }


}