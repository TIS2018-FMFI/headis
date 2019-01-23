<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header"><h2 class="mb-0">Pridať novinku</h2></div>
                            <div class="card-body">
                                <form @submit.prevent="addPost()">

                                    <div class="form-group row">
                                        <label for="title" class="col-md-2 col-sm-4 col-form-label text-md-right">Nazov</label>

                                        <div class="col-md-7">
                                            <input type="text" id="title" v-model="formPost.title" class="form-control" name="title" autofocus>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="intro_text" class="col-md-2 col-sm-4 col-form-label text-md-right">Kratky text</label>
                                        <div class="col-md-7">
                                            <textarea v-model="formPost.intro_text" class="form-control" rows="3" id="intro_text"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="image" class="col-md-2 col-sm-4 col-form-label text-md-right">Obrazok</label>
                                        <div class="col-md-7">
                                            <input type="file"  name="image" class="form-control-file" id="image" @change="fileChange">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-sm-4 col-form-label text-md-right">Text</label>
                                        <div class="col-md-7">
                                            <ckeditor :editor="editor" v-model="formPost.text" :config="editorConfig"></ckeditor>
                                        </div>
                                    </div>

                                    <button class="btn btn-primary">Pridať</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 d-none d-md-block">

            </div>
        </div>
    </div>
</template>

<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    import Form from "../Form.js";

    export default {
        name: "Manager",
        props: [''],
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
                })
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
            }
        }

    }
</script>
