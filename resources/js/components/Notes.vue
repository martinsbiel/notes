<template>
    <div class="container">
        <button type="button" data-toggle="modal" data-target="#modalNoteAdd" class="btn btn-outline-dark btn-lg">Adicionar nota</button>
        <hr>
        <card-component 
        :data="notes" 
        :view="{
            visible: true,
            dataToggle: 'modal',
            dataTarget: '#modalNoteView'
        }" 
        :remove="{
            visible: true,
            dataToggle: 'modal',
            dataTarget: '#modalNoteRemove'
        }"></card-component>
        <infinite-loading @distance="1" :identifier="infiniteId" @infinite="loadMoreData"></infinite-loading>
        <modal-component id="modalNoteAdd" title="Adicionar nota">
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

        <modal-component id="modalNoteView" title="Visualizar nota">
            <template v-slot:alerts>
                
            </template>
            <template v-slot:content>
                <div class="form-group">
                    <label for="titleView">Título:</label>
                    <input class="form-control" type="text" name="titleView" id="titleView" placeholder="Título da nota" :value="$store.state.item.title" disabled>
                </div>

                <div class="form-group">
                    <label for="contentView">Conteúdo:</label>
                    <textarea style="min-height: 200px;" class="form-control" name="contentView" id="contentView" placeholder="Conteúdo da nota" :value="$store.state.item.content" disabled></textarea>
                </div>

                <div class="form-group">
                    <label for="contentRemove">Data de criação:</label>
                    <input class="form-control" type="text" name="dateView" id="dateView" placeholder="Data da nota" :value="$store.state.item.created_at" disabled>
                </div>
            </template>
            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </template>
        </modal-component>

        <modal-component id="modalNoteRemove" title="Remover nota">
            <template v-slot:alerts>
                <alert-component type="success" title="Transação realizada com sucesso" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'success'"></alert-component>
                <alert-component type="danger" title="Erro na transação" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
            </template>
            <template v-slot:content>
                <div class="form-group">
                    <label for="titleRemove">Título:</label>
                    <input class="form-control" type="text" name="titleRemove" id="titleRemove" placeholder="Título da nota" :value="$store.state.item.title" disabled>
                </div>

                <div class="form-group">
                    <label for="contentRemove">Conteúdo:</label>
                    <textarea style="min-height: 200px;" class="form-control" name="contentRemove" id="contentRemove" placeholder="Conteúdo da nota" :value="$store.state.item.content" disabled></textarea>
                </div>

                <div class="form-group">
                    <label for="contentRemove">Data de criação:</label>
                    <input class="form-control" type="text" name="dateRemove" id="dateRemove" placeholder="Data da nota" :value="$store.state.item.created_at" disabled>
                </div>
            </template>
            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remove()" v-if="$store.state.transaction.status != 'success'">Remover</button>
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
                page: 1,
                infiniteId: +new Date()
            }
        },
        methods: {
            refreshInfiniteLoading(){
                this.page = 1;
                this.notes = [];

                this.infiniteId += 1;
            },
            remove(){
                let confirmation = confirm('Tem certeza que deseja remover essa nota?');

                if(!confirmation){
                    return false;
                }

                let formData = new FormData();
                formData.append('_method', 'delete');
                formData.append('user_id', this.user_id);

                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }
                
                axios.post(this.url + '/' + this.$store.state.item.id, formData, config)
                    .then(response => {
                        this.$store.state.transaction.status = 'success';
                        this.$store.state.transaction.message = response.data.msg;

                        this.refreshInfiniteLoading();
                    }).catch(errors => {
                        this.$store.state.transaction.status = 'error';
                        this.$store.state.transaction.message = errors.response.data.error;
                    });
            },
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
