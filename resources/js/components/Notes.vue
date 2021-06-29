<template>
    <div class="container">
        <button type="button" data-toggle="modal" data-target="#modalNote" class="btn btn-outline-dark btn-lg">Adicionar nota</button>
        <hr>
        <card-component :data="notes"></card-component>
        <infinite-loading @distance="1" @infinite="loadMoreData"></infinite-loading>
        <modal-component id="modalNote" title="Adicionar nota">
            <template v-slot:alerts>
                <alert-component type="success" :details="transactionDetails" title="Cadastro realizado com sucesso" v-if="transactionStatus == 'added'"></alert-component>
                <alert-component type="danger" :details="transactionDetails" title="Erro ao tentar cadastrar a nota" v-if="transactionStatus == 'error'"></alert-component>
            </template>
            <template v-slot:content>
                <div class="form-group">
                    <label for="title">Título:</label>
                    <input class="form-control" type="text" name="title" id="title" placeholder="Título da nota" v-model="titleNote">
                </div>

                <div class="form-group">
                    <label for="content">Conteúdo:</label>
                    <textarea style="min-height: 200px;" class="form-control" name="content" id="content" placeholder="Conteúdo da nota" v-model="contentNote"></textarea>
                </div>
            </template>
            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-dark" @click="save()">Salvar</button>
            </template>
        </modal-component>
    </div>
</template>

<script>
    export default {
        props: ['user_id'],
        computed: {
            token(){
                let token = document.cookie.split(';').find(indice => {
                    return indice.includes('token=');
                });

                token = token.split('=')[1];
                token = 'Bearer ' + token;

                return token;
            }
        },
        data(){
            return {
                url: 'http://localhost:8000/api/v1/note',
                titleNote: '',
                contentNote: '',
                transactionStatus: '',
                transactionDetails: {},
                notes: [],
                page: 1
            }
        },
        methods: {
            loadMoreData($state){
                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                this.$http.get(this.url + '?page=' + this.page + '&user_id=' + this.user_id, config)
                    .then((response) => {
                        return response.json();
                    }).then(response => {
                        $.each(response.data, (key, v) => {
                            this.notes.push(v);
                        });
                        $state.loaded();
                    }).catch(err => {
                        if(err.status === 404){
                            //$('.loading-default').hide();
                            $('.infinite-loading-container').html('Por enquanto é só :)');
                            $('.infinite-loading-container').addClass('text-muted');
                            
                        }
                    });
                this.page += 1;
            },
            save(){
                let formData = new FormData();
                formData.append('user_id', this.user_id);
                formData.append('title', this.titleNote);
                formData.append('content', this.contentNote);

                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                axios.post(this.url, formData, config)
                    .then(response => {
                        this.transactionStatus = 'added';
                        this.transactionDetails = {
                            message: 'ID do registro: ' + response.data.id
                        }
                        console.log(response);
                    }).catch(errors => {
                        this.transactionStatus = 'error';
                        this.transactionDetails = {
                            message: errors.response.data.message,
                            data: errors.response.data.errors
                        }
                    });
            },
            /*
            loadNotes(){
                axios.get(this.url)
                    .then(response => {
                        this.notes = response.data;
                    }).catch(errors => {
                        console.log(errors);
                    });
            }*/
        }
    }
</script>
