<template>

    <div class="container" >
        <div class="row text-center mb-5">
            <div class="col-md-12">
                <h1>{{ translations['challenges.challenge'] }}</h1>
                <p>{{ challenge.created_date | moment("DD.MM.YYYY") }}</p>

            </div>
        </div>

        <!--
        Display the names of the users in the given challenge
        -->
        <div class="row mb-5">
            <div class="col-md-6 text-left">
                <h2><b>{{ translations['challenges.challenger'] }}: <a :href="'/users/' + challenge.challenger.id">{{ challenge.challenger.user_name }}</a></b></h2>
            </div>
            <div class="col-md-6 text-right">
                <h2><b>{{ translations['challenges.challenged'] }}: <a :href="'/users/' + challenge.asked.id">{{ challenge.asked.user_name }}</a></b></h2>
            </div>
        </div>

        <!--
        Display the the dates for the given challenge
        -->
        <div class="row">
            <div class="col-md-5">
                <div class="col mb-5">
                    <div class="card">
                        <div class="card-header">
                            {{ translations['challenges.dates'] }}
                        </div>
                        <ul v-if="allDates.length > 0" class="list-group list-group-flush pre-scrollable">
                            <li class="list-group-item text-center" v-for="date in allDates" v-if="!date.rejected">
                                <div class="row justify-content-md-center">
                                    <div class="col-md-auto mb-2 mt-1">
                                        <span>{{ date.date | moment("DD.MM.YYYY HH:mm") }}</span>
                                    </div>
                                    <div class="row mx-auto" v-if="!date.rejected && isBeforeDate(date.date)">
                                        <div class="col-6 mx-auto" v-if="current_user.id === challenge.challenger.id">
                                            <button @click.prevent="conformedDate(date)" class="btn btn-success">{{ translations['challenges.confirm'] }}</button>
                                        </div>
                                        <div class="col-6 mx-auto" v-if="current_user.id === challenge.challenger.id">
                                            <button @click.prevent="deleteDate(date.id)" class="btn btn-danger">{{ translations['challenges.delete'] }}</button>
                                        </div>
                                    </div>
                                    <div v-else class="row mx-auto">
                                        <div class="col text-center">
                                            <h5>Nie je možné vybrať</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" v-if="isErrorConfirmedDate && date.id === confirmedDateErrorDate.id">
                                    <div class="col text-center">
                                        <span class="is-invalid-input"></span>
                                        <span class="invalid-feedback">{{ confirmedDateErrorMessage }}</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <p v-else class="text-center mt-2">{{ translations['challenges.no_dates_were_added'] }}</p>
                    </div>
                </div>

                <!--
                Div for adding new date
                -->
                <div class="col mb-5" v-if="current_user.id === challenge.asked.id">

                    <div class="card">
                        <div class="card-header">
                            {{ translations['challenges.add_a_date'] }}
                        </div>
                        <form @submit.prevent="addDate()">
                            <div class="form-group">
                                <vue-datetime-picker :class="{'is-invalid-input': formDate.errors.has('date')}"
                                                     color="red"
                                                     :minDate="dates['start']"
                                                     :maxDate="dates['end']"
                                                     no-weekends-days
                                                     :disabled-dates="dates['notAvailable']"
                                                     v-model="formDate.date"
                                                     :label="translations['challenges.choose_a_date']"
                                                     minute-interval="15" time-zone="Europe/Bratislava"
                                                     :disabled-hours="['00', '01', '02', '03', '04', '05', '06', '07', '22', '23']"
                                                     format="DD.MM.YYYY HH:mm"
                                                     outputFormat="YYYY-MM-DD HH:mm:ss"
                                                     locale="sk"
                                                     noButtonNow>
                                </vue-datetime-picker>
                                <field-error :form="formDate" field="date"></field-error>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ translations['challenges.add'] }}</button>
                        </form>

                    </div>

                </div>
            </div>

            <!--
            Comments for the given challenge
            -->

            <div class="col-md-6 offset-md-1">

                <div class="card">

                    <div class="card-header">
                        {{ translations['challenges.messages'] }}
                    </div>

                    <div class="msg_history card" ref="chatbox" id="chatbox">
                        <template v-for="comment in sortedItems">
                            <div class="outgoing_msg" v-if="comment.user_id === current_user.id">
                                <div class="sent_msg">
                                    <p>{{ comment.text }}</p>
                                    <span class="time_date">{{ comment.date | moment("DD.MM.YYYY HH:mm") }}</span>
                                </div>
                            </div>
                            <div class="incoming_msg" v-else>
                                <div class="incoming_msg_img"> <img :src="'/images/' + challenge.challenger.image" alt="Avatar" > </div>
                                <div class="received_msg">
                                    <div class="received_withd_msg">
                                        <p>{{ comment.text }}</p>
                                        <span class="time_date">{{ comment.date | moment("DD.MM.YYYY HH:mm") }}</span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="type_msg">
                        <div class="input_msg_write">
                            <input @keyup.enter="trigger" v-model="commentText" type="text" class="write_msg pl-2" :placeholder="translations['challenges.type_a_message']" />
                            <button @click.prevent="addComment()" class="msg_send_btn" type="button" ref="sendReply"><font-awesome-icon icon="paper-plane"></font-awesome-icon></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import Form from "../Form";
    import moment from 'moment';

    export default {
        name: "Challenge",
        props: ['challenge', 'current_user', 'translations', 'dates'],
        data: () => {
            return {
                axiosComments: null,
                axiosDates: null,
                commentText: "",
                deletedDates: [],
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                formDate: new Form({
                    date: '',
                    challenge: '',
                }),
                now: new Date,
                confirmedDateErrorMessage: null,
                confirmedDateErrorDate: null
            }
        },
        computed: {
            allDates() {
                if (this.axiosDates) {
                    return this.axiosDates;
                }
                return this.challenge.dates;
            },
            allComments() {
                if (this.axiosComments) {
                    return this.axiosComments;
                }
                return this.challenge.comments;
            },
            sortedItems() {
                return this.allComments.sort((a, b) => new Date(a.date) - new Date(b.date))
            },
            isErrorConfirmedDate() {
                return this.confirmedDateErrorMessage && this.confirmedDateErrorDate;
            }
        },
        methods: {
            addDate() {
                this.formDate.challenge = this.challenge.id;
                this.formDate.post('/dates/store').then(response => {
                    this.axiosDates = response['dates'];
                });
            },
            addComment() {
                if (this.commentText !== "") {
                    var text = this.commentText;
                    this.commentText = "";

                    axios.post('/comments/store', {
                        data: {
                            challenge: this.challenge.id,
                            user_id: this.current_user.id,
                            text: text
                        }
                    }).then(response => {
                        this.axiosComments = response['data']['comments'];
                        this.$nextTick(
                            () => {
                                this.scrollToEnd();
                            }
                        )
                    });
                }
            },
            conformedDate(date) {
                axios.post('/matches/store', {
                    challenge_id: this.challenge.id,
                    date: date.date
                }).then(response => {
                    window.location = response['data']['url'];
                }).catch(error => {
                    this.confirmedDateErrorDate = date;
                    this.confirmedDateErrorMessage = error.response.data.errors.date[0];
                    console.log(error.response.data);
                });
            },
            deleteDate(id) {
                axios.post('/dates/' + id + '/destroy').then(response => {
                    this.axiosDates = response['data']['dates'];
                })
            },
            scrollToEnd: function() {
                var container = this.$el.querySelector("#chatbox");
                container.scrollTop = container.scrollHeight;
            },
            trigger () {
                this.$refs.sendReply.click()
            },
            isBeforeDate(date) {
                return moment(this.now).isBefore(date);
            }
        },
        mounted() {
            this.scrollToEnd();
        },
        created() {
            setInterval(() => {
                this.now = new Date;
            }, 1000 * 10)
        }
    }

</script>