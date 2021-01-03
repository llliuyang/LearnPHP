<!DOCTYPE html>
<html>

<head>
    <title></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>test</title>
    <!-- <link rel="stylesheet" href="https://unpkg.com/vant/lib/vant-css/index.css"> -->
    <!-- 引入样式 -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vant@2.1/lib/index.css"> -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/vant@2.1/lib/index.css">
    <script src="https://cdn.bootcss.com/Base64/1.0.2/base64.min.js"></script>
</head>

<body>
    <div id="app">
        <div class="main" v-if="userInfo.password">
            <input class="input" type="text" name="" v-model="type" placeholder="请输入微信号关联的标识">
            <textarea placeholder="回车添加多个" id="text" v-model="text"></textarea>
            <div class="btn">
                <button @click="submit">提交新增</button>
                <button @click="addUser" v-if="userInfo.is_admin == 1">添加账号</button>
                <button @click="activeUser" v-if="userInfo.is_admin == 1">{{ isUsers ? '微信号列表' : '账号列表' }}</button>
            </div>
            <div class="search">
                <input class="input" type="text" v-model="search" name="" placeholder="搜索标识，微信账号" @keyup.enter="query">
                <button @click="query">搜索</button>
            </div>
            <table width="900" v-if="!isUsers">
                <tr>
                    <th>绑定标识</th>
                    <th>微信号</th>
                    <th>当前状态</th>
                    <th>开关操作</th>
                </tr>
                <tr v-for="item in tableData">
                    <td>{{ item.type }}</td>
                    <td>{{ item.wxid }}</td>
                    <td>
                        {{ item.status == 1 ? '开启' : '关闭' }}
                    </td>
                    <td>
                        <button :style="item.status == 1 ? '' : 'background: #1989fa;'" @click="update(item)">{{ item.status == 1 ? '关闭' : '开启' }}</button>
                        <button style="background: #f44444;" @click="delWx(item)">删除</button>
                    </td>
                </tr>
            </table>
            <table width="900" v-else>
                <tr>
                    <th>密码</th>
                    <th>备注名</th>
                    <th>绑定标识</th>
                    <th>开关操作</th>
                </tr>
                <tr v-for="item in userLists">
                    <td>
                        <input type="text" v-model="item.password" name="">
                    </td>
                    <td><input type="text" v-model="item.name" name=""></td>
                    <td>
                        <input type="text" v-model="item.type" name="">
                    </td>
                    <td>
                        <button style="background: #1989fa" @click="updateUser(item)">更新</button>
                        <button style="background: #f44444;" @click="delUser(item)">删除</button>
                    </td>
                </tr>
            </table>
        </div>
        <div v-else class="login">
            <input type="password" placeholder="请输入密码" class="input" @keyup.enter="login" v-model="password" name="">
            <button @click="login">登录</button>
        </div>
    </div>
</body>
<!-- 引入组件 -->
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vant@2.1/lib/vant.min.js"></script>
<script src="https://cdn.bootcss.com/axios/0.19.0/axios.min.js"></script>

<script>
    var Vue = window.Vue;
    var vant = window.vant;
    var Notify = window.vant.Notify;
    var Dialog = window.vant.Dialog;
    // 注册 Lazyload 组件
    Vue.use(Dialog);
    Vue.use(Notify);
    Vue.use(vant.Lazyload);
    // 调用函数式组件
    //vant.Toast('提示');
    // Notify({
    //   message: '自定义时长',
    //   duration: 1000#1989fa
    // });
    const app = new Vue({
        el: '#app',
        data: {
            text: "",
            type: "",
            search: "",
            tableData: [],
            userLists: [],
            password: "",
            userInfo: {},
            isUsers: false
        },
        created() {
            this.auth();
        },
        methods: {
            activeUser(query = false) {
                if (query == true) {
                    var url = this.search ? ('/weixin.php?query_users=true&key=' + this.search + "&pwd=" + window.atob(this.password)) : '/weixin.php?query_users=true&pwd=' + window.atob(this.password);
                    axios.get(url)
                        .then(res => {
                            this.userLists = res.data;
                            console.log(res.data);
                        })
                } else {
                    if (this.isUsers) {
                        this.isUsers = false;
                    } else {
                        this.isUsers = true;
                        var url = this.search ? ('/weixin.php?query_users=true&key=' + this.search + "&pwd=" + window.atob(this.password)) : '/weixin.php?query_users=true&pwd=' + window.atob(this.password);
                        axios.get(url)
                            .then(res => {
                                this.userLists = res.data;
                                console.log(res.data);
                            })
                    }
                }

            },
            updateUser(item) {
                axios.get('/weixin.php?update_user=true&pwd=' + window.atob(this.password) + "&new_password=" + item.password + "&new_type=" + item.type + "&name=" + item.name + "&id=" + item.id)
                    .then(res => {
                        console.log(res.data);
                        if (res.data == true) {
                            Notify({
                                message: item.password + " 更新成功了啊",
                                duration: 1000,
                                type: "success"
                            });
                            this.activeUser(true);
                        }
                    })
            },
            delUser(item) {
                axios.get('/weixin.php?delete_user=true&pwd=' + window.atob(this.password) + "&id=" + item.id)
                    .then(res => {
                        console.log(res.data);
                        if (res.data == true) {
                            Notify({
                                message: item.password + " 删除成功",
                                duration: 1000,
                                type: "success"
                            });
                            this.activeUser(true);
                        }
                    })
            },
            addUser() {
                var res = this.text.split(",");
                console.log(res);
                if (res[2]) {
                    var url = '/weixin.php?type=user&add_user=' + window.atob(this.password) + "&password=" + res[0] + "&type=" + res[1] + "&is=" + res[2];
                } else {
                    var url = '/weixin.php?type=user&add_user=' + window.atob(this.password) + "&password=" + res[0] + "&type=" + res[1];
                }

                axios.get(url)
                    .then(res => {
                        if (res.data.code == 400) {
                            Notify({
                                message: res.data.message,
                                duration: 1000,
                            });
                        } else {
                            Notify({
                                message: res.data.password + " 账号添加成功",
                                duration: 1000,
                                type: "success"
                            });
                            this.activeUser(true);
                        }
                    })
            },
            login() {
                var pwd = window.btoa(this.password);
                axios.get('/weixin.php?type=user&password=' + this.password)
                    .then(res => {
                        console.log(res);
                        if (res.data.length) {
                            this.userInfo = res.data[0];
                            window.location.href = "/app.php?pwd=" + pwd;
                            this.query();
                        } else {
                            Notify({
                                message: '找不到该用户啊',
                                duration: 1000,
                            });
                        }
                    })
            },
            auth() {
                if (/pwd=(.*)/.test(location.search)) {
                    this.password = /pwd=(.*)/.exec(location.search)[1];
                    axios.get('/weixin.php?type=user&password=' + window.atob(this.password))
                        .then(res => {
                            console.log(res);
                            if (res.data.length) {
                                this.userInfo = res.data[0];
                                this.query();
                            } else {
                                Notify({
                                    message: '找不到该用户啊',
                                    duration: 1000,
                                });
                            }
                        })
                }
            },
            submit() {

                if (this.text.length < 5) {
                    Notify({
                        message: '请输入微信号，微信号不能为空的呀',
                        duration: 1000,
                    });
                    return;
                }

                if (this.type.length == 0) {
                    Notify({
                        message: '请给微信号绑定关联标识',
                        duration: 1000,
                    });
                    return;
                }
                var arr = this.text.split("\n");

                axios.get('/weixin.php?type=insert&status=1&wxid=' + JSON.stringify(arr) + "&rowtype=" + this.type + "&pwd=" + window.atob(this.password))
                    .then(res => {
                        console.log(res);
                        if (res.data.code == 400) {
                            Notify({
                                message: res.data.message,
                                duration: 1000,
                            });
                        } else {
                            Notify({
                                message: '微信号更新成功',
                                duration: 1000,
                                type: 'success'
                            });
                            this.query();
                        }
                    })
            },
            query() {
                var url = this.search ? ("/weixin.php?type=query&key=" + this.search + "&pwd=" + window.atob(this.password)) : "/weixin.php?type=query&pwd=" + window.atob(this.password);
                axios.get(url)
                    .then(res => {
                        console.log(res);
                        this.tableData = this.handleLists(res.data);
                    })
            },
            handleLists(data) {
                var temp = [];
                for (var i = 0; i < data.length; i++) {
                    if (temp[data[i].type]) {
                        temp[data[i].type].push(data[i]);
                    } else {
                        temp[data[i].type] = [];
                        temp[data[i].type].push(data[i]);
                    }
                }
                var lists = [];
                for (var item in temp) {
                    console.log(item);
                    for (var i = 0; i < temp[item].length; i++) {
                        lists.push(temp[item][i]);
                    }
                }

                console.log(lists);
                return lists;

            },
            update(item) {
                axios.get("/weixin.php?type=update&id=" + item.id + "&status=" + (item.status == 1 ? 0 : 1) + "&pwd=" + window.atob(this.password))
                    .then(res => {
                        console.log(res);
                        Notify({
                            message: '状态更新成功',
                            duration: 1000,
                            type: 'success'
                        });
                        this.query();
                    })
            },
            delWx(item) {
                console.log("删除啊");
                axios.get("/weixin.php?type=delete&id=" + item.id + "&pwd=" + window.atob(this.password))
                    .then(res => {
                        console.log(res);
                        Notify({
                            message: '删除成功',
                            duration: 1000,
                        });
                        this.query();
                    })
            }
        }
    });
</script>
<style type="text/css">
    .login {
        max-width: 600px;
        margin: 100px auto;
    }

    .login input {
        width: 500px;
    }

    .login button {
        padding: 10px;
    }

    .input {
        width: 880px;
        border: none;
        box-shadow: 0px 5px 11px #cccccc;
        padding: 10px;
        margin-bottom: 20px;
    }

    .search {
        margin: 10px 0px;
    }

    .search input {
        width: 822px;
    }

    .search button {
        border: none;
        background: red;
        color: #ffffff;
        padding: 10px;
        box-shadow: 0px 6px 12px #ff9999;
        cursor: pointer;
    }

    textarea {
        width: 860px;
        height: 80px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0px 5px 11px #cccccc;
        border: none;
    }

    .main {
        max-width: 900px;
        margin: 50px auto;
    }

    .btn button {
        border: none;
        background: red;
        color: #ffffff;
        padding: 10px;
        box-shadow: 0px 6px 12px #ff9999;
        cursor: pointer;
    }

    table {}

    table tr {
        border-bottom: 1px solid #999999;
        height: 40px;
    }

    table tr td {
        text-align: center;
    }

    table tr td button {
        border: none;
        color: #ffffff;
        background: #07c160;
        font-size: 14px;
        padding: 6px;
        cursor: pointer;
    }

    @media screen and (max-width: 800px) {

        .login input {
            width: 96%;
            margin-left: 2%;
            box-sizing: border-box;
            outline: 1px solid #999;
            height: 60px
        }

        .login button {
            width: 96%;
            margin-left: 2%;
            background: red;
            color: #ffffff;
            padding: 14px;
            border: none;
            letter-spacing: 4px;
            font-size: 20px;
            box-shadow: 0px 6px 12px #ff9999;
            cursor: pointer;
        }


        .input,
        .input:focus,
        .search input,
        .search input:focus {
            width: 96%;
            margin-left: 2%;
            box-sizing: border-box;
            outline: 1px solid #bbb;
            height: 60px
        }

        textarea,
        textarea:focus {
            width: 96%;
            margin-left: 2%;
            box-sizing: border-box;
            outline: 1px solid #bbb;
            height: 200px !important
        }

        div.btn {
            margin-bottom: 20px
        }

        .search button {
            width: 90%;
            margin-left: 5%;
            margin-bottom: 10px
        }

        table {
            width: 100vw
        }


    }
</style>

</html>