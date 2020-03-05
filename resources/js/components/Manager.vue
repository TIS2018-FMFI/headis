<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <div class="row mb-5">
                    <div class="col">
                        <div class="card" id="addPost">
                            <div class="card-header"><h2 class="mb-0">{{ translations['posts.add'] }}</h2></div>
                            <div class="card-body">
                                <div class="alert alert-success" v-if="successAddedPost" v-html="successAddedPostMessage">
                                </div>
                                <form @submit.prevent="addPost()">
                                    <div class="form-group row">
                                        <label for="title" class="col-md-2 col-sm-4 col-form-label text-md-right">{{ translations['posts.title'] }}</label>

                                        <div class="col-md-7">
                                            <input type="text" id="title" v-model="formPost.title" class="form-control" :class="{'is-invalid': formPost.errors.has('title')}" name="title" autofocus>
                                            <field-error :form="formPost" field="title"></field-error>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="intro_text" class="col-md-2 col-sm-4 col-form-label text-md-right">{{ translations['posts.intro_text'] }}</label>
                                        <div class="col-md-7">
                                            <textarea v-model="formPost.intro_text" class="form-control" rows="3" id="intro_text" :class="{'is-invalid': formPost.errors.has('intro_text')}"></textarea>
                                            <field-error :form="formPost" field="intro_text"></field-error>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="image" class="col-md-2 col-sm-4 col-form-label text-md-right">{{ translations['posts.image'] }}</label>
                                        <div class="col-md-7">
                                            <input type="file"  name="image" class="form-control-file" id="image" @change="fileChange" :class="{'is-invalid': formPost.errors.has('image')}">
                                            <field-error :form="formPost" field="image"></field-error>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-sm-4 col-form-label text-md-right">{{ translations['posts.text'] }}</label>
                                        <div class="col-md-7">
                                            <ckeditor :editor="editor" v-model="formPost.text" :config="editorConfig"></ckeditor>
                                            <span :class="{'is-invalid-input': formPost.errors.has('text')}"></span>
                                            <field-error :form="formPost" field="text"></field-error>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-7 offset-md-2 offset-sm-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="hidden" id="hidden" v-model="formPost.hidden">

                                                <label class="form-check-label" for="hidden">
                                                    {{ translations['posts.hidden'] }}
                                                </label>
                                            </div>
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
                        <div class="card" id="currentMatches">
                            <div class="card-header"><h2 class="mb-0">{{ translations['matches.title'] }}</h2></div>
                            <div class="card-body">
                                <div class="row mb-3" v-for="match in current_matches">
                                    <div class="col">
                                        <div class="card text-center" :class="{'border-danger': match.type === 'requestCancel'}">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-6 align-self-center">
                                                        <h5 class="card-title mb-sm-0" :class="{'text-danger': match.type === 'requestCancel'}" >{{ match.challenge.asked.user_name }} vs. {{ match.challenge.challenger.user_name }}</h5>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <a :href="'/matches/' + match.id" class="btn btn-primary">{{ translations['matches.btnShow'] }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col">
                        <div class="card" id="pyramid">
                            <div class="card-header"><h2 class="mb-0">{{ translations['pyramids.title'] }}</h2></div>
                            <div class="card-body">
                                <div class="alert alert-success" v-if="successUsersUpdate" v-html="successUsersUpdateMessage"></div>
                                <field-error :form="formUsers" field="users"></field-error>
                                <div class="row mb-5">
                                    <div class="offset-sm-3 col-sm-6 col-12">
                                        <draggable
                                            class="list-group"
                                            tag="ul"
                                            v-model="newUsers"
                                            v-bind="dragOptions"
                                            @start="isDragging = true"
                                            @end="isDragging = false"
                                        >
                                            <transition-group type="transition" name="flip-list">
                                                <li class="list-group-item" v-for="user, index in newUsers" :key="user.id" :class="{'mb-3': isLastInLevel(index + 1)}">
                                                    <font-awesome-icon :icon="['fas', 'align-justify']" class="mr-3" /> {{ index + 1 }}. {{ user.user_name }}
                                                </li>
                                            </transition-group>
                                        </draggable>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="offset-sm-3 col-sm-6 col-12 d-flex justify-content-between">
                                        <button class="btn btn-dark" @click="sort">{{ translations['pyramids.reset'] }}</button>
                                        <button class="btn btn-primary" @click="saveUsers">{{ translations['pyramids.save'] }}</button>
                                    </div>
                                </div>
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
                                <div class="row pre-scrollable" v-if="canShowDeactivateUsers">
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
                                <div class="row pre-scrollable" v-if="canShowReactivateUsers">
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

                <div class="row mb-5">
                    <div class="col">
                        <div class="card" id="addSeason">
                            <div class="card-header">
                                <h2 class="mb-0">{{ translations['season.add'] }}</h2>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <vue-datetime-picker :class="{'is-invalid-input': formSeason.errors.has('start')}"
                                                                 color="red"
                                                                 v-model="formSeason.start"
                                                                 :minDate="today"
                                                                 :label="translations['season.choose_start']"
                                                                 :time-zone="time_zone"
                                                                 format="DD.MM.YYYY"
                                                                 formatted="DD.MM.YYYY"
                                                                 outputFormat="YYYY-MM-DD"
                                                                 locale="sk"
                                                                 onlyDate
                                                                 autoClose
                                                                 noButtonNow
                                                                 id="season-add-start">
                                            </vue-datetime-picker>
                                            <field-error :form="formSeason" field="start"></field-error>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <vue-datetime-picker :class="{'is-invalid-input': formSeason.errors.has('end')}"
                                                                 color="red"
                                                                 v-model="formSeason.end"
                                                                 :label="translations['season.choose_end']"
                                                                 :minDate="minEndDateSeason"
                                                                 :time-zone="time_zone"
                                                                 format="DD.MM.YYYY"
                                                                 formatted="DD.MM.YYYY"
                                                                 outputFormat="YYYY-MM-DD"
                                                                 locale="sk"
                                                                 onlyDate
                                                                 autoClose
                                                                 noButtonNow
                                                                 id="season-add-end">
                                            </vue-datetime-picker>
                                            <field-error :form="formSeason" field="end"></field-error>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4" v-if="formSeason.errors.has('range')">
                                    <div class="col">
                                        <span class="is-invalid-input"></span>
                                        <field-error :form="formSeason" field="range"></field-error>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col">
                                        <button class="btn btn-primary" @click="addSeason">{{ translations['season.add'] }}</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h5 class="text-danger">{{ translations['season.can_not_change'] }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col">
                        <div class="card" id="deleteSeason">
                            <div class="card-header">
                                <h2 class="mb-0">{{ translations['season.delete'] }}</h2>
                            </div>
                            <div class="card-body">
                                <div class="row pre-scrollable">
                                    <div class="col-md-4 col-sm-6 col-12 mb-3" v-for="seasonItem in allSeasons">
                                        <div class="card text-center">
                                            <div class="card-body">
                                                <h5>{{ seasonItem.date_from | moment("DD.MM.YYYY") }} - {{ seasonItem.date_to | moment("DD.MM.YYYY") }}</h5>
                                                <template v-if="currentSeason && seasonItem.id === currentSeason.id">
                                                    <h5>{{ translations['season.can_not_delete'] }}</h5>
                                                </template>
                                                <template v-else>
                                                    <button @click.prevent="deleteSeason(seasonItem)" class="btn btn-danger">{{ translations['season.deleteBtn'] }}</button>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col">
                        <div class="card" id="addNotAvailableDates">
                            <div class="card-header">
                                <h2 class="mb-0">{{ translations['not_available_dates.add'] }}</h2>
                            </div>
                            <div class="card-body">
                                <div class="row" v-if="season && currentSeason">
                                    <div class="col-sm-6 offset-sm-1">
                                        <div class="form-group">
                                            <vue-datetime-picker :class="{'is-invalid-input': formNotAvailableDates.errors.has('date')}"
                                                                 color="red"
                                                                 v-model="formNotAvailableDates.date"
                                                                 :label="translations['not_available_dates.choose_date']"
                                                                 :time-zone="time_zone"
                                                                 format="DD.MM.YYYY"
                                                                 formatted="DD.MM.YYYY"
                                                                 outputFormat="YYYY-MM-DD"
                                                                 :minDate="today"
                                                                 :disabled-dates="allNotAvailableDatesPicker"
                                                                 locale="sk"
                                                                 autoClose
                                                                 onlyDate
                                                                 noButtonNow
                                                                 id="not-available-dates-add">
                                            </vue-datetime-picker>
                                            <field-error :form="formNotAvailableDates" field="date"></field-error>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 text-center">
                                        <button class="btn btn-primary" @click="addNotAvailableDate">{{ translations['not_available_dates.addBtn'] }}</button>
                                    </div>
                                </div>
                                <div class="row" v-else>
                                    <div class="col text-center">
                                        <h3>{{ translations['not_available_dates.addNotInCurrentSeason'] }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col">
                        <div class="card" id="deleteNotAvailableDates">
                            <div class="card-header">
                                <h2 class="mb-0">{{ translations['not_available_dates.delete'] }}</h2>
                            </div>
                            <div class="card-body">
                                <div class="row pre-scrollable" v-if="season && currentSeason">
                                    <template v-if="allNotAvailableDates && allNotAvailableDates.length > 0">
                                        <div class="col-md-3 col-sm-4 mb-4" v-for="date in allNotAvailableDates">
                                            <div class="card text-center">
                                                <div class="card-body">
                                                    <h5>{{ date.date | moment("DD.MM.YYYY") }}</h5>
                                                    <button class="btn btn-primary" @click="deleteNotAvailableDate(date)">{{ translations['not_available_dates.deleteBtn'] }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <div class="col text-center" >
                                            <h3>{{ translations['not_available_dates.noFoundDates'] }}</h3>
                                        </div>
                                    </template>
                                </div>
                                <div class="row" v-else>
                                    <div class="col text-center">
                                        <h3>{{ translations['not_available_dates.destroyNotInCurrentSeason'] }}</h3>
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
                    <a href="#currentMatches" class="scrollactive-item nav-link">{{ translations['matches.title'] }}</a>
                    <a href="#pyramid" class="scrollactive-item nav-link">{{ translations['pyramids.title'] }}</a>
                    <a href="#deactivateUsers" class="scrollactive-item nav-link">{{ translations['users.deactivate'] }}</a>
                    <a href="#reactivateUsers" class="scrollactive-item nav-link">{{ translations['users.reactivate'] }}</a>
                    <a href="#addSeason" class="scrollactive-item nav-link">{{ translations['season.add'] }}</a>
                    <a href="#deleteSeason" class="scrollactive-item nav-link">{{ translations['season.delete'] }}</a>
                    <a href="#addNotAvailableDates" class="scrollactive-item nav-link">{{ translations['not_available_dates.add'] }}</a>
                    <a href="#deleteNotAvailableDates" class="scrollactive-item nav-link">{{ translations['not_available_dates.delete'] }}</a>
                </scrollactive>
            </div>
        </div>
    </div>
</template>

<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    import Form from "../Form.js";
    import draggable from "vuedraggable";

    export default {
        name: "Manager",
        props: ['translations', 'can_deactivate_users', 'can_reactivate_users', 'edit_posts', 'season',
            'not_available_dates', 'today', 'time_zone', 'current_matches', 'users'],
        components: {
            draggable
        },
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
                    hidden: false
                }),
                formSeason: new Form({
                    start: '',
                    end: '',
                    range: ''
                }),
                formNotAvailableDates: new Form({
                   date: ''
                }),
                formUsers: new Form({
                    users: ''
                }),
                axiosCanDeactivateUsers: '',
                searchCanDeactivateUsers: '',
                axiosCanReactivateUsers: '',
                searchCanReactivateUsers: '',
                axiosSeasons: '',
                axiosNotAvailableDates: '',
                canAddPost: true,
                successAddedPost: false,
                successAddedPostMessage: '',
                newUsers: [],
                successUsersUpdate: '',
                successUsersUpdateMessage: '',
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
            },
            currentSeason() {
                if (this.axiosSeasons && this.axiosSeasons['current']) {
                    return this.axiosSeasons['current'];
                }
                return this.season['current'];
            },
            allSeasons() {
                if (this.axiosSeasons && this.axiosSeasons['available']) {
                    return this.axiosSeasons['available'];
                }
                return this.season['available'];
            },
            allNotAvailableDatesPicker() {
                if (this.axiosNotAvailableDates && this.axiosNotAvailableDates['picker']) {
                    return this.axiosNotAvailableDates['picker'];
                }
                return this.not_available_dates['picker']
            },
            allNotAvailableDates() {
                if (this.axiosNotAvailableDates && this.axiosNotAvailableDates['dates']) {
                    return this.axiosNotAvailableDates['dates'];
                }
                return this.not_available_dates['dates']
            },
            minEndDateSeason() {
                return this.today < this.formSeason.start ? this.formSeason.start : this.today;
            },
            dragOptions() {
                return {
                    animation: 0,
                    group: "description",
                    disabled: false,
                    ghostClass: "ghost"
                };
            }
        },
        methods: {
            fileChange(e) {
                this.formPost.image = e.target.files[0];
            },
            addPost() {
                if (this.canAddPost) {
                    this.canAddPost = false;
                    this.formPost.post('/posts/store').then(response => {
                        this.canAddPost = true;
                        this.successAddedPost = true;
                        this.successAddedPostMessage = response['message'];
                        setTimeout(() => {
                            this.successAddedPost = false;
                        }, 3000);
                    }).catch(e => {})
                }
            },
            deactivateUser(user) {
                axios.post('/users/' + user.id + '/destroy')
                    .then(response => {
                    this.axiosCanDeactivateUsers = response['data']['canDeactivateUsers'];
                    this.axiosCanReactivateUsers = response['data']['canReactivateUsers'];
                }).catch(error => {});
            },
            reactivateUser(user) {
                axios.post('/users/activate', {
                    'user_id': user.id
                })
                    .then(response => {
                        this.axiosCanDeactivateUsers = response['data']['canDeactivateUsers'];
                        this.axiosCanReactivateUsers = response['data']['canReactivateUsers'];
                    }).catch(error => {});
            },
            addSeason() {
                this.formSeason.post('/seasons/store').then(response => {
                    this.axiosSeasons = response['season'];
                }).catch(e => {});
            },
            deleteSeason(season) {
                axios.post('/seasons/' + season.id + '/destroy').then(response => {
                    this.axiosSeasons = response['data']['season'];
                }).catch(e => {});
            },
            addNotAvailableDate() {
                this.formNotAvailableDates.post('/not_available_dates/store').then(response => {
                    this.axiosNotAvailableDates = response['notAvailableDates'];
                });
            },
            deleteNotAvailableDate(notAvailableDate) {
                axios.post('/not_available_dates/' + notAvailableDate.id + '/destroy').then(response => {
                    this.axiosNotAvailableDates = response['data']['notAvailableDates'];
                }).catch(e => {});
            },
            sort() {
                this.newUsers = this.newUsers.sort((a, b) => a.position - b.position);
            },
            saveUsers() {
                let result = '';
                for (let i = 0; i < this.newUsers.length; i++) {
                    result += i > 0 ? ',' : '';
                    result += this.newUsers[i]['id'];
                }
                this.formUsers.users = result;
                this.formUsers.post('/users/updatePosition').then(response => {
                    this.successUsersUpdate = true;
                    this.successUsersUpdateMessage = response['message'];
                    setTimeout(() => {
                        this.successUsersUpdate = false;
                    }, 3000);
                });
            },
            isLastInLevel(index) {
                return Math.ceil(Math.sqrt(index)) === Math.floor(Math.sqrt(index));
            }
        },
        mounted() {
            if (this.users && this.users.length) {
                for (let i = 0; i < this.users.length; i++) {
                    console.log(this.users[i]);
                    this.newUsers.push(this.users[i]);
                }
            }
        }
    }
</script>
