<template>
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-md-12">
                <h1>Výzva č. {{ challenge.id }}</h1>

            </div>
        </div>

        <!--
        Display the names of the users in the given challenge
        -->
        <div class="row mb-5">
            <div class="col-md-6 text-left">
                <h2><b>Vyzývateľ: {{ challenger.user_name }}</b></h2>
            </div>
            <div class="col-md-6 text-right">
                <h2><b>Vyzývaný: {{ asked.user_name }}</b></h2>
            </div>
        </div>

        <!--
        Display the the dates for the given challenge
        -->
        <div class="row">
            <div class="col-lg-4">
                <div class="col mb-5">
                    <div class="card border-dark">
                        <div class="card-header bg-dark text-white">
                            Dátumy
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item text-center" v-for="date in allDates"> <input class="form-check-input" type="checkbox"> {{ date.date }}  </li>
                        </ul>
                    </div>
                </div>

                <!--
                Div for adding new date
                -->
                <div class="col mb-5">
                    <div class="card-header bg-dark text-white">
                        Pridať dátum
                    </div>
                    <div class="card border-dark">
                        <input type="date" class="input-group date" v-model="selectedDate">
                        <button @click.prevent="addDate()" class="btn-primary">Pridaj</button>
                    </div>

                </div>
            </div>

            <!--
            Comments for the given challenge
            TODO: replace date with a timestamp
            -->
            <div class="col-lg-6 offset-2">
                <div class="col mb-5">
                    <div class="card border-dark">
                        <div class="card-header bg-dark text-white">
                            Správy
                        </div>

                        <!--
                        TODO: use bootstrap rather than new styles
                        -->
                        <div v-for="comment in allComments">
                            <div class="chatcontainer darker" v-if="comment.user_id == challenger.id">
                                <img :src="'/images/' + challenger.image" alt="Avatar" style="width:100%;">
                                <p> <b>{{ challenger.user_name }}:</b> {{ comment.text }} </p>
                                <span class="time-right">{{ comment.date }}</span>
                            </div>

                            <div class="chatcontainer reply" v-else>
                                <img :src="'/images/'+asked.image" alt="Avatar" style="width:100%;">
                                <p> <b>{{ asked.user_name }}:</b> {{ comment.text }} </p>
                                <span class="time-left">{{ comment.date }}</span>
                            </div>

                        </div>

                        <div class="input-group">
                            <input type="text" class="form-control">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="button">Pošli</button>
                            </span>
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
        props: ['challenge', 'dates', 'challenger', 'asked', 'comments'],
        data: () => {
            return {
                axiosComments: null,
                axiosDates: null,
                selectedDate: null
            }
        },
        computed: {
            allDates() {
                if (this.axiosDates) {
                    return this.axiosDates;
                }
                return this.dates;
            },
            allComments() {
                if (this.axiosComments) {
                    return this.axiosComments;
                }
                return this.comments;
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
            }
        }
    }
</script>

<style scoped>

</style>