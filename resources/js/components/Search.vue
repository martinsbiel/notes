<template>
    <div class="container">
        <h1 class="d-inline">Buscar nota</h1>
        -
        <h6 v-if="count === 0" class="d-inline text-muted">Nenhum registro por enquanto</h6>
        <h6 v-if="count === 1" class="d-inline text-muted">Foi encontrado apenas 1 registro</h6>
        <h6 v-if="count > 1" class="d-inline text-muted">Foram encontrados <span v-html="count"></span> registros</h6>
        <hr>
        <input class="form-control mb-5" type="text" placeholder="Pesquisar" name="search" id="search" @keyup="search()" v-model="searchText">
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
        }" 
        :update="{
            visible: true,
            dataToggle: 'modal',
            dataTarget: '#modalNoteUpdate'
        }"></card-component>

        <modal-component id="modalNoteView" title="Visualizar nota">
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
                    <label for="dateView">Data de criação:</label>
                    <input class="form-control" type="text" name="dateView" id="dateView" placeholder="Data da nota" :value="$store.state.item.created_at | formatDateGlobal" disabled>
                </div>

                <div v-if="$store.state.item.updated_at != $store.state.item.created_at" class="form-group">
                    <label for="dateUpView">Data da última atualização:</label>
                    <input class="form-control" type="text" name="dateUpView" id="dateUpView" placeholder="Data da última atualização" :value="$store.state.item.updated_at | formatDateGlobal" disabled>
                </div>
            </template>
            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </template>
        </modal-component>

        <modal-component id="modalNoteRemove" title="Remover nota">
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
                    <label for="dateRemove">Data de criação:</label>
                    <input class="form-control" type="text" name="dateRemove" id="dateRemove" placeholder="Data da nota" :value="$store.state.item.created_at | formatDateGlobal" disabled>
                </div>

                <div v-if="$store.state.item.updated_at != $store.state.item.created_at" class="form-group">
                    <label for="dateUpRemove">Data da última atualização:</label>
                    <input class="form-control" type="text" name="dateUpRemove" id="dateUpRemove" placeholder="Data da última atualização" :value="$store.state.item.updated_at | formatDateGlobal" disabled>
                </div>
            </template>
            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remove()" v-if="$store.state.transaction.status != 'success'">Remover</button>
            </template>
        </modal-component>

        <modal-component id="modalNoteUpdate" title="Atualizar nota">
            <template v-slot:content>
                <div class="form-group">
                    <label for="titleUpdate">Título:</label>
                    <input class="form-control" type="text" name="titleUpdate" id="titleUpdate" placeholder="Título da nota" v-model="$store.state.item.title">
                </div>

                <div class="form-group">
                    <label for="contentUpdate">Conteúdo:</label>
                    <textarea style="min-height: 200px;" class="form-control" name="contentUpdate" id="contentUpdate" placeholder="Conteúdo da nota" v-model="$store.state.item.content"></textarea>
                </div>

                <div class="form-group">
                    <label for="dateUpdate">Data de criação:</label>
                    <input class="form-control" type="text" name="dateUpdate" id="dateUpdate" placeholder="Data da nota" :value="$store.state.item.created_at | formatDateGlobal" disabled>
                </div>

                <div v-if="$store.state.item.updated_at != $store.state.item.created_at" class="form-group">
                    <label for="dateUpUpdate">Data da última atualização:</label>
                    <input class="form-control" type="text" name="dateUpUpdate" id="dateUpUpdate" placeholder="Data da última atualização" :value="$store.state.item.updated_at | formatDateGlobal" disabled>
                </div>
            </template>
            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-dark" @click="update()">Atualizar</button>
            </template>
        </modal-component>
    </div>
</template>

<script>
    /**
     * Duplicating code until I find a way to do it properly :)
     */
    export default {
        data(){
            return {
                url: 'http://localhost:8000/api/v1/note',
                urlSearch: 'http://localhost:8000/api/v1/note/search',
                searchText: '',
                notes: [],
                count: 0,
                titleNote: '',
                contentNote: '',
                transactionStatus: '',
                transactionDetails: {}
            }
        },
        methods: {
            update(){
                let formData = new FormData();
                formData.append('_method', 'patch');
                formData.append('title', this.$store.state.item.title);
                formData.append('content', this.$store.state.item.content);

                axios.post(this.url + '/' + this.$store.state.item.id, formData)
                    .then(response => {
                        toastr.success('Nota atualizada com sucesso');
                    }).catch(errors => {
                        $.each(errors.response.data.errors, (key, v) => {
                            toastr.error(v);
                        });
                    });
            },
            remove(){
                let confirmation = confirm('Tem certeza que deseja remover essa nota?');

                if(!confirmation){
                    return false;
                }

                let formData = new FormData();
                formData.append('_method', 'delete');
                
                axios.post(this.url + '/' + this.$store.state.item.id, formData)
                    .then(response => {
                        toastr.success(response.data.msg);
                    }).catch(errors => {
                        toastr.error(errors.response.data.error);
                    });
            },
            search(){
                let formData = new FormData();
                formData.append('search', this.searchText);

                axios.post(this.urlSearch, formData)
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
        }
    }
</script>
