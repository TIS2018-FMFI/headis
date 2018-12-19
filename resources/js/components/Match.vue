<template>
    <div class="container">
        <div class="row">
            <div class="col-4">
                <ul>
                    <li v-for="set in allSets"> {{ set.score_1 }}</li>
                </ul>
                <button @click.prevent="">Pridaj</button>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "Match",
        props: ['match'],
        data: () => {
            return {
                axiosSets: null,
                selectedSet: null
            }
        },
        computed: {
            allSets() {
                if (this.axiosSets) {
                    return this.axiosSets;
                }
                console.log(this.match.sets);
                return this.match.sets;
            }
        },
        mounted() {
            console.log(this.$props.match + ' tu');
        },

        methods: {

        }
    }
</script>