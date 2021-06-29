<template>
    <div class="container">
        <h1 class="d-inline">Buscar nota</h1>
        -
        <h6 v-if="count === 0" class="d-inline text-muted">Nenhum registro por enquanto</h6>
        <h6 v-if="count === 1" class="d-inline text-muted">Foi encontrado apenas 1 registro</h6>
        <h6 v-if="count > 1" class="d-inline text-muted">Foram encontrados <span v-html="count"></span> registros</h6>
        <hr>
        <input class="form-control mb-5" type="text" placeholder="Pesquisar" name="search" id="search" @keyup="search()" v-model="searchText">
        <card-component :data="notes"></card-component>
    </div>
</template>

<script>
    export default {
        props: ['user_id'],
        data(){
            return {
                url: 'http://localhost:8000/api/v1/note/search',
                searchText: '',
                notes: [],
                count: 0
            }
        },
        methods: {
            search(){
                let formData = new FormData();
                formData.append('search', this.searchText);
                formData.append('user_id', this.user_id);

                let config = {
                    headers: {
                        'Accept': 'application/json'
                    }
                }

                axios.post(this.url, formData, config)
                    .then(response => {
                        if(this.searchText === ''){
                            this.notes = [];
                            this.count = 0;
                        } else {
                            this.count = response.data.length;
                            this.notes = response.data;
                        }
                    }).catch(errors => {
                        this.notes = [];
                        this.count = 0;
                    });
            },
        },
        mounted(){
            //this.loadNotes();
        }
    }
</script>
