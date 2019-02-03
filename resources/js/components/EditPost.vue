<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header"><h2 class="mb-0">{{ translations['posts.edit'] }}</h2></div>
                            <div class="card-body">
                                <form @submit.prevent="editPost()">

                                    <div class="form-group row">
                                        <label for="title" class="col-md-3 col-sm-4 col-form-label text-md-right">{{ translations['posts.title'] }}</label>

                                        <div class="col-md-7">
                                            <input type="text" id="title" v-model="formPost.title" class="form-control" name="title" autofocus>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="intro_text" class="col-md-3 col-sm-4 col-form-label text-md-right">{{ translations['posts.intro_text'] }}</label>
                                        <div class="col-md-7">
                                            <textarea v-model="formPost.intro_text" class="form-control" rows="3" id="intro_text"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row" v-if="post.image">
                                        <div class="col-md-7 offset-md-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <img :src="'/images/' + post.image" :alt="post.title">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="image" class="col-md-3 col-sm-4 col-form-label text-md-right">{{ translations['posts.image'] }}</label>
                                        <div class="col-md-7">
                                            <input type="file"  name="image" class="form-control-file" id="image" @change="fileChange">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-sm-4 col-form-label text-md-right">{{ translations['posts.text'] }}</label>
                                        <div class="col-md-7">
                                            <ckeditor :editor="editor" v-model="formPost.text" :config="editorConfig" v-if="editorReady"></ckeditor>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-7 offset-md-3 offset-sm-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="hidden" id="hidden" v-model="formPost.hidden">

                                                <label class="form-check-label" for="hidden">
                                                    {{ translations['posts.hidden'] }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button class="btn btn-primary" type="submit">{{ translations['posts.editBtn'] }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    import Form from "../Form.js";

    export default {
        name: "EditPost",
        props: ['post', 'translations', 'rte'],
        computed: {
            getRte() {
                if (this.formPost.text !== null) {
                    return this.rte;
                }
                return '';
            }
        },
        data: () => {
            return {
                editor: ClassicEditor,
                editorConfig: {
                    // The configuration of the editor.
                },
                editorReady: false,
                formPost: new Form({
                    title: '',
                    text: '',
                    intro_text: '',
                    image: '',
                    hidden: false
                })
            }
        },
        methods: {
            fileChange(e) {
                this.formPost.image = e.target.files[0];
            },
            editPost() {
                this.formPost.post('/posts/' + this.post.id + '/update').then(response => {
                    if (response['status'] === 'ok') {
                        window.location.replace(response['url']);
                    }
                })
            }
        },
        mounted() {
            this.$nextTick(function () {
                this.formPost.title = this.$props.post.title;
                this.formPost.intro_text = this.$props.post.intro_text;
                this.formPost.image = '';
                this.formPost.text = this.$props.rte;
                this.formPost.hidden = this.$props.post.hidden;
                this.editorReady = true;
            });
        }
    }
</script>
