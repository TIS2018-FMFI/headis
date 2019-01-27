<template>
    <div class="container">
        <div class="row">
            <div class="col">

                <div class="row mb-5">
                    <div class="col">
                        <h2><b>{{ translations['matches.challenger'] }}: <a :href="'/users/' + match.challenge.challenger.id">{{ match.challenge.challenger.user_name }}</a></b></h2>
                    </div>
                    <div class="col text-right">
                        <h2><b>{{ translations['matches.challenged'] }}: <a :href="'/users/' + match.challenge.asked.id">{{ match.challenge.asked.user_name }}</a></b></h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-3">
                        <div class="card text-center mb-4">
                            <div class="card-header">
                                {{ translations['matches.match'] }}
                            </div>
                            <div class="card-body" >
                                <h3>{{ match.challenge.asked.user_name }}: </h3>
                                <h3>{{ match.challenge.challenger.user_name }}: </h3>
                                <div v-if="current_user.id === match.challenge.asked.id || current_user.isRedactor">
                                    <button v-if="vueFinished || (current_user.isRedactor && !isConfirmed)" @click.prevent="sendSets()" class="btn btn-success">{{ translations['matches.confirm'] }}</button>
                                    <button v-if="vueSets.length > 0 || (current_user.isRedactor && !isConfirmed)" @click.prevent="resetSets()" class="btn btn-danger">{{ translations['matches.reset'] }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <template v-if="current_user.isRedactor && isConfirmed === 0">
                        <div class="col-3" v-for="(set, index) in formRedactor.sets">
                            <div class="card text-center mb-4">
                                <div class="card-header">{{ index+1 }}</div>
                                <div class="card-body" >
                                    <div class="form-group">
                                        <input class="form-control" type="number" v-model.number="formRedactor.sets[index].score_2" min="0" :class="{'is-invalid': formRedactor.errors.has('sets')}">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" type="number" v-model.number="formRedactor.sets[index].score_1" min="0" :class="{'is-invalid': formRedactor.errors.has('sets')}">
                                        <field-error :form="formRedactor" field="sets"></field-error>
                                    </div>

                                    <button v-if="index === 2" @click.prevent="redactorRemoveSet()" class="btn btn-danger">{{ translations['matches.remove_set'] }}</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-3" v-if="formRedactor.sets.length < 3">
                            <div class="card text-center mb-4">
                                <div class="card-header">{{ translations['matches.add_set'] }}</div>
                                <div class="card-body" >
                                    <button @click.prevent="redactorAddSet()" class="btn btn-success">{{ translations['matches.add'] }}</button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template v-else>
                        <div class="col-3" v-for="(set, index) in allSets">
                            <div class="card text-center mb-4">
                                <div class="card-header">{{ index+1 }}</div>
                                <div class="card-body" >
                                    <h3>{{ set.score_2 }}</h3>
                                    <h3>{{ set.score_1 }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-3" v-if="!isFinished && current_user.id === match.challenge.asked.id">
                            <div class="card text-center mb-4">
                                <div class="card-header">{{ translations['matches.add_title'] }}</div>
                                <div class="card-body" >
                                    <form @submit.prevent="addSet()">
                                        <div class="form-group">
                                            <input class="form-control" v-model.number="formSet.score_2" min="0" type="number" :class="{'is-invalid': formSet.errors.has('score_2')}">
                                            <field-error :form="formSet" field="score_2"></field-error>
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" v-model.number="formSet.score_1" min="0" type="number" :class="{'is-invalid': formSet.errors.has('score_1')}">
                                            <field-error :form="formSet" field="score_1"></field-error>
                                        </div>
                                        <button class="btn btn-info" type="submit">{{ translations['matches.add'] }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="row" v-if="isFinished && current_user.id === match.challenge.challenger.id && !isConfirmed">
                    <div class="col-12 col-md-4 offset-md-4">
                        <div class="card text-center mb-4">
                            <div class="card-header">{{ translations['matches.confirm_match_title'] }}</div>
                            <div class="card-body">
                                <button @click.prevent="confirmMatch(true)" class="btn btn-success">{{ translations['matches.confirm'] }}</button>
                                <button @click.prevent="confirmMatch(false)" class="btn btn-danger">{{ translations['matches.reject_match'] }}</button>
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
        props: ['match', 'finished', 'current_user', 'translations'],
        data: () => {
            return {
                axiosSets: null,
                selectedSet: null,
                axiosFinished: null,
                axiosConfirmed: null,
                vueSets: [],
                vueFinished: false,
                formSet: new Form({
                    score_1: '',
                    score_2: ''
                }),
                formRedactor: new Form({
                    sets: [],
                    match_id: ''
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
                if (this.finished) {
                    return this.finished;
                }
                return this.vueFinished;

            },
            isConfirmed(){
                if (this.axiosConfirmed){
                    return this.axiosConfirmed;
                }
                return this.match.confirmed;
            }
        },
        mounted() {
            if (this.$props.match.confirmed === 0 && this.$props.current_user.isRedactor) {
                this.$props.match.sets.forEach(item => {
                    this.formRedactor.sets.push({score_1: item['score_1'], score_2: item['score_2']})
                });
                this.formRedactor.match_id = this.$props.match.id;
            }
        },
        methods: {
            confirmMatch(confirmed) {
                axios.post('/matches/' + this.match.id + '/update', {
                    data: {
                        confirmed: confirmed
                    }
                }).then(response => {
                    this.axiosConfirmed = true;
                });
            },

            addSet() {
                this.formSet.post('/sets/validateSet').then(response => {
                    this.vueSets.push({score_1: response['score_1'], score_2: response['score_2']});
                    this.checkFinishMatch();
                });
            },

            sendSets() {
                if (this.current_user.isRedactor) {
                    axios.post('/sets/update', {
                        match_id: this.match.id,
                        sets: this.formRedactor.sets
                    }).then(response => {
                        this.axiosConfirmed = response['data']['confirmed'];
                        this.axiosFinished = response['data']['finished'];
                        this.axiosSets = response['data']['sets'];
                    }).catch(error => {
                        this.formRedactor.onFail(error.response.data);
                    });
                } else {
                    axios.post('/sets/store', {
                        match_id: this.match.id,
                        sets: this.vueSets
                    }).then(response =>{
                        this.axiosFinished = response['data']['finished'];
                        this.axiosSets = response['data']['sets'];
                        this.vueSets = [];
                        this.vueFinished = false;
                    });
                }
            },

            resetSets() {
                if (this.current_user.isRedactor) {
                    this.formRedactor.sets = [];
                    this.$props.match.sets.forEach(item => {
                        this.formRedactor.sets.push({score_1: item['score_1'], score_2: item['score_2']})
                    });
                } else {
                    this.vueSets = [];
                    this.vueFinished = false;
                }
            },

            checkFinishMatch() {
                let challenger = 0;
                let asked = 0;

                this.vueSets.forEach(item => {
                    if (parseInt(item['score_1']) > parseInt(item['score_2'])) {
                        challenger += 1;
                    } else {
                        asked += 1;
                    }
                });
                if (challenger === 2 || asked === 2) {

                    this.vueFinished = true;
                }
            },

            redactorAddSet() {
                if (this.formRedactor.sets.length < 3) {
                    this.formRedactor.sets.push({score_1: 0, score_2: 0});
                }
            },

            redactorRemoveSet() {
                if (this.formRedactor.sets.length === 3) {
                    this.formRedactor.sets.splice(2);
                }
            }
        }
    }
</script>