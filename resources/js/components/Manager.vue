<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <div class="row mb-5">
                    <div class="col">
                        <div class="card" id="addPost">
                            <div class="card-header"><h2 class="mb-0">{{ translations['posts.add'] }}</h2></div>
                            <div class="card-body">
                                <form @submit.prevent="addPost()">

                                    <div class="form-group row">
                                        <label for="title" class="col-md-2 col-sm-4 col-form-label text-md-right">{{ translations['posts.title'] }}</label>

                                        <div class="col-md-7">
                                            <input type="text" id="title" v-model="formPost.title" class="form-control" name="title" autofocus>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="intro_text" class="col-md-2 col-sm-4 col-form-label text-md-right">{{ translations['posts.intro_text'] }}</label>
                                        <div class="col-md-7">
                                            <textarea v-model="formPost.intro_text" class="form-control" rows="3" id="intro_text"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="image" class="col-md-2 col-sm-4 col-form-label text-md-right">{{ translations['posts.image'] }}</label>
                                        <div class="col-md-7">
                                            <input type="file"  name="image" class="form-control-file" id="image" @change="fileChange">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-sm-4 col-form-label text-md-right">{{ translations['posts.text'] }}</label>
                                        <div class="col-md-7">
                                            <ckeditor :editor="editor" v-model="formPost.text" :config="editorConfig"></ckeditor>
                                        </div>
                                    </div>

                                    <button class="btn btn-primary">{{ translations['posts.addBtn'] }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col">
                        <div class="card" id="deactivateUsers">
                            <div class="card-header"><h2 class="mb-0">{{ translations['users.deactivate'] }}</h2></div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-sm-6 offset-sm-3 col-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" v-model="searchCanDeactivateUsers">
                                        </div>
                                    </div>
                                </div>
                                <div class="row" v-if="canShowDeactivateUsers">
                                    <div class="col-md-4 col-sm-6 col-12 mb-3" v-for="user in filteredListCanDeactivateUsers">
                                        <div class="card text-center">
                                            <div class="card-body">
                                                <h5>{{ user.user_name }}</h5>
                                                <button class="btn btn-primary" @click="deactivateUser(user)">{{ translations['users.deactivateBtn'] }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" v-else>
                                    <div class="col text-center" >
                                        <h3>{{ translations['users.noFoundUsers'] }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col">
                        <div class="card" id="reactivateUsers">
                            <div class="card-header"><h2 class="mb-0">{{ translations['users.reactivate'] }}</h2></div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-sm-6 offset-sm-3 col-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" v-model="searchCanReactivateUsers">
                                        </div>
                                    </div>
                                </div>
                                <div class="row" v-if="canShowReactivateUsers">
                                    <div class="col-md-4 col-sm-6 col-12 mb-3"  v-for="user in filteredListCanReactivateUsers">
                                        <div class="card text-center">
                                            <div class="card-body">
                                                <h5>{{ user.user_name }}</h5>
                                                <button class="btn btn-primary" @click="reactivateUser(user)">{{ translations['users.reactivateBtn'] }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" v-else>
                                    <div class="col text-center" >
                                        <h3>{{ translations['users.noFoundUsers'] }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 d-none d-md-block manager-sidebar">
                <scrollactive class="nav flex-column" active-class="active">
                    <a href="#addPost" class="scrollactive-item nav-link">{{ translations['posts.add'] }}</a>
                    <a href="#deactivateUsers" class="scrollactive-item nav-link">{{ translations['users.deactivate'] }}</a>
                    <a href="#reactivateUsers" class="scrollactive-item nav-link">{{ translations['users.reactivate'] }}</a>
                </scrollactive>
            </div>
        </div>
    </div>
</template>

<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    import Form from "../Form.js";

    export default {
        name: "Manager",
        props: ['translations', 'can_deactivate_users', 'can_reactivate_users'],
        data: () => {
            return {
                editor: ClassicEditor,
                editorData: '',
                editorConfig: {
                    // The configuration of the editor.
                },
                formPost: new Form({
                    title: '',
                    text: '',
                    intro_text: '',
                    image: '',
                }),
                axiosCanDeactivateUsers: '',
                searchCanDeactivateUsers: '',
                axiosCanReactivateUsers: '',
                searchCanReactivateUsers: '',
            }
        },
        computed: {
            canShowDeactivateUsers() {
                return this.filteredListCanDeactivateUsers.length > 0;
            },
            canShowReactivateUsers() {
                return this.filteredListCanReactivateUsers.length > 0;
            },
            listCanDeactivateUsers() {
                if (this.axiosCanDeactivateUsers) {
                    return this.axiosCanDeactivateUsers;
                }
                return this.can_deactivate_users;
            },
            listCanReactivateUsers() {
                if (this.axiosCanReactivateUsers) {
                    return this.axiosCanReactivateUsers;
                }
                return this.can_reactivate_users;
            },
            filteredListCanDeactivateUsers() {
                if (this.listCanDeactivateUsers) {
                    return this.listCanDeactivateUsers.filter(user => {
                        return user.user_name.toLowerCase().includes(this.searchCanDeactivateUsers.toLowerCase())
                    });
                }
                return [];
            },
            filteredListCanReactivateUsers() {
                if (this.listCanReactivateUsers) {
                    return this.listCanReactivateUsers.filter(user => {
                        return user.user_name.toLowerCase().includes(this.searchCanReactivateUsers.toLowerCase())
                    });
                }
                return [];
            }
        },
        methods: {
            fileChange(e) {
                this.formPost.image = e.target.files[0];
            },
            addPost() {
                this.formPost.post('/posts/store').then(response => {
                    console.log(response);
                })
            },
            deactivateUser(user) {
                axios.post('/users/' + user.id + '/destroy')
                    .then(response => {
                    this.axiosCanDeactivateUsers = response['data']['canDeactivateUsers'];
                    this.axiosCanReactivateUsers = response['data']['canReactivateUsers'];
                }).catch(error => {
                    console.log(error.response.data);
                })
            },
            reactivateUser(user) {
                axios.post('/users/activate', {
                    'user_id': user.id
                })
                    .then(response => {
                        this.axiosCanDeactivateUsers = response['data']['canDeactivateUsers'];
                        this.axiosCanReactivateUsers = response['data']['canReactivateUsers'];
                    }).catch(error => {
                    console.log(error.response.data);
                })
            }
        }

    }
</script>
