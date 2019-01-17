<template>
    <div class="container">
        <div class="row">
            <div class="col">

                <div class="row mb-5">
                    <div class="col">
                        <h2><b>Vyzývateľ: <a :href="'/users/' + match.challenge.challenger.id">{{ match.challenge.challenger.user_name }}</a></b></h2>
                    </div>
                    <div class="col text-right">
                        <h2><b>Vyzvaný: <a :href="'/users/' + match.challenge.asked.id">{{ match.challenge.asked.user_name }}</a></b></h2>
                    </div>
                </div>

                <div class="row row-eq-height">
                    <div class="col-3">
                        <div class="card text-center mb-4">
                            <div class="card-header">
                                Zápas
                            </div>
                            <div class="card-body" >
                                <h3>Vyzývateľ: </h3>
                                <h3>Vyzývaný: </h3>
                                <div v-if="current_user.id === match.challenge.asked.id">
                                    <button v-if="isFinished" @click.prevent="" class="btn btn-success">Potvrdiť</button>
                                    <button v-if="!isFinished && vueSets.length>0" @click.prevent="resetSets()" class="btn btn-danger">Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3" v-for="(set, index) in allSets">
                        <div class="card text-center mb-4">
                            <div class="card-header">{{ index+1 }}</div>
                            <div class="card-body" >
                                <h3>{{ set.score_1 }}</h3>
                                <h3>{{ set.score_2 }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-3" v-if="!isFinished && current_user.id === match.challenge.asked.id">
                        <div class="card text-center mb-4">
                            <div class="card-header">Pridanie setu</div>
                            <div class="card-body" >
                                <form @submit.prevent="addSet()">
                                    <input class="mb-2" v-model="formSet.score1" min="0" type="number">
                                    <field-error :form="formSet" field="score1"></field-error>
                                    <input class="mb-2" v-model="formSet.score2" min="0" type="number">
                                    <field-error :form="formSet" field="score2"></field-error>
                                    <button class="btn btn-info" type="submit">Pridať</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="isFinished && current_user.id === match.challenge.challenger.id && !isConfirmed">
                    <div class="col-12 col-md-4 offset-md-4">
                        <div class="card text-center mb-4">
                            <div class="card-header">Potvrdenie zápasu</div>
                            <div class="card-body">
                                <button @click.prevent="confirmMatch(true)" class="btn btn-success">Potvrdiť</button>
                                <button @click.prevent="confirmMatch(false)" class="btn btn-danger">Odmietnúť</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>

<script>
    import Form from "../Form.js";

    export default {
        name: "Match",
        props: ['match', 'finished', 'current_user', 'confirmed'],
        data: () => {
            return {
                axiosSets: null,
                selectedSet: null,
                axiosFinished: null,
                axiosConfirmed: null,
                vueSets: [],
                formSet: new Form({
                    score1: '',
                    score2: ''
                })
            }
        },
        computed: {
            allSets() {
                if (this.axiosSets) {
                    return this.axiosSets;
                }
                if (this.match.sets && this.match.sets.length > 0) {
                    return this.match.sets;
                }
                return this.vueSets;
            },
            isFinished(){
               if (this.axiosFinished !== null){
                   return this.axiosFinished;
               }
               console.log(this.finished);
               return this.finished;

            },
            isConfirmed(){
                if (this.axiosConfirmed){
                    return this.axiosConfirmed;
                }
                return this.confirmed;
            }
        },
        mounted() {
            console.log(this.$props.match + ' tu');
        },

        methods: {
            confirmMatch($confirmed) {
                axios.post('/matches/' + this.match.id + '/update', {
                    data: {
                        confirmed: $confirmed
                    }
                }).then(response => {
                    this.axiosConfirmed = true;
                });
            },

            addSet() {
                this.formSet.post('/sets/validateSet').catch(error => {
                    console.log(error);
                });
            },

            sendSets(){
                axios.post('/sets/store', {
                    data: {
                        match_id: this.match.id,
                        score1: this.score1,
                        score2: this.score2
                    }
                }).then(response =>{
                    this.score1 = null;
                    this.score2 = null;
                    this.axiosFinished = response['data']['finished'];
                    this.axiosSets = response['data']['sets'];
                });
            },
            resetSets(){
                this.vueSets = [];
            }
        }
    }
</script>