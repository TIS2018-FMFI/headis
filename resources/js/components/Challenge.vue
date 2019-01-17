<template>

    <div class="container" >
        <div class="row text-center mb-5">
            <div class="col-md-12">
                <h1>Výzva</h1>
                <p>{{ challenge.created_date }}</p>

            </div>
        </div>

        <!--
        Display the names of the users in the given challenge
        -->
        <div class="row mb-5">
            <div class="col-md-6 text-left">
                <h2><b>Vyzývateľ: <a :href="'/users/' + challenge.challenger.id">{{ challenge.challenger.user_name }}</a></b></h2>
            </div>
            <div class="col-md-6 text-right">
                <h2><b>Vyzvaný: <a :href="'/users/' + challenge.asked.id">{{ challenge.asked.user_name }}</a></b></h2>
            </div>
        </div>

        <!--
        Display the the dates for the given challenge
        -->
        <div class="row">
            <div class="col-lg-5">
                <div class="col mb-5">
                    <div class="card">
                        <div class="card-header">
                            Dátumy
                        </div>
                        <ul v-if="allDates.length > 0" class="list-group list-group-flush">
                            <li class="list-group-item text-center" v-for="date in allDates" v-if="!date.rejected">
                                <div class="row">
                                    <div class="col-md-5">
                                        {{ date.date }}
                                    </div>
                                    <div class="col-md-3" v-if="current_user.id === challenge.challenger.id">
                                        <form action="/matches/store" method="post">
                                            <input type="hidden" name="_token" :value="csrf">
                                            <input type="hidden" name="date" :value="date.id">
                                            <input type="hidden" name="challenge_id" :value="challenge.id">
                                            <button class="btn btn-success">Potvrdit</button>
                                        </form>
                                    </div>
                                    <div class="col-md-3" v-if="current_user.id === challenge.challenger.id">
                                        <button @click.prevent="deleteDate(date.id)" class="btn btn-danger">Vymazať</button>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <p v-else class="text-center mt-2">Neboli pridané žiadne dátumy</p>
                    </div>
                </div>

                <!--
                Div for adding new date
                -->
                <div class="col mb-5" v-if="current_user.id === challenge.asked.id">
                    <div class="card-header">
                        Pridať dátum
                    </div>
                    <div class="card">
                        <input type="date" class="input-group date" v-model="selectedDate">
                        <button @click.prevent="addDate()" class="btn-primary">Pridaj</button>
                    </div>

                </div>
            </div>

            <!--
            Comments for the given challenge
            TODO: replace date with a timestamp
            v-if="!current_user.isRedactor"
            -->

            <div class="col-lg-6 offset-1" v-if="!current_user.isRedactor">
                <div class="card-header">
                    Správy
                </div>
                <div class="card">
                    <div class="msg_history card" ref="chatbox" id="chatbox">
                        <div v-for="comment in sortedItems">
                            <div class="outgoing_msg" v-if="comment.user_id == current_user.id">
                                <div class="sent_msg">
                                    <p>{{ comment.text }}</p>
                                    <span class="time_date">{{ comment.date }}</span> </div>
                            </div>
                            <div class="incoming_msg" v-else>
                                <div class="incoming_msg_img"> <img :src="'/images/' + challenge.challenger.image" alt="Avatar" > </div>
                                <div class="received_msg">
                                    <div class="received_withd_msg">
                                        <p>{{ comment.text }}</p>
                                        <span class="time_date">{{ comment.date }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="type_msg">
                        <div class="input_msg_write">
                            <input v-model="commentText" type="text" class="write_msg" placeholder="Type a message" />
                            <button @click.prevent="addComment()" class="msg_send_btn" type="button"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "Challenge",
        props: ['challenge', 'current_user'],
        data: () => {
            return {
                axiosComments: null,
                axiosDates: null,
                selectedDate: null,
                commentText: "",
                deletedDates: [],
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
            }
        },
        methods: {
            addDate() {
                axios.post('/dates/store', {
                    data: {
                        date: this.selectedDate,
                        challenge: this.challenge.id
                    }
                }).then(response => {
                    this.axiosDates = response['data']['dates'];
                    console.log(response);
                });
            },
            addComment() {
                if (this.commentText != "") {
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
            deleteDate(id) {
                axios.post('/dates/' + id + '/destroy', {
                }).then(response => {
                    this.axiosDates = response['data']['dates'];
                    console.log(response);
                })
            },
            scrollToEnd: function() {
                var container = this.$el.querySelector("#chatbox");
                container.scrollTop = container.scrollHeight;
            }
        },
        mounted() {
            this.scrollToEnd();
        }
    }



</script>

<style scoped>

</style>