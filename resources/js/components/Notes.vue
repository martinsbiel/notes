<template>
    <div class="container">
        <button type="button" data-toggle="modal" data-target="#modalNoteAdd" class="btn btn-outline-dark btn-lg">Adicionar nota</button>
        <a target="_blank" href="notes/export-pdf" class="btn btn-outline-success btn-lg float-right export-btn">Exportar PDF</a>
        <a href="notes/export-excel" class="btn btn-outline-success btn-lg float-right mr-2 export-btn">Exportar Excel</a>
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
        }" 
        :update="{
            visible: true,
            dataToggle: 'modal',
            dataTarget: '#modalNoteUpdate'
        }"></card-component>
        <infinite-loading @distance="1" :identifier="infiniteId" @infinite="loadMoreData"></infinite-loading>
        <modal-component id="modalNoteAdd" title="Adicionar nota">
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
    export default {
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
            update(){
                let formData = new FormData();
                formData.append('_method', 'patch');
                formData.append('title', this.$store.state.item.title);
                formData.append('content', this.$store.state.item.content);

                axios.post(this.url + '/' + this.$store.state.item.id, formData)
                    .then(response => {
                        toastr.success('Nota atualizada com sucesso');

                        this.refreshInfiniteLoading();
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

                        this.refreshInfiniteLoading();
                    }).catch(errors => {
                        toastr.error(errors.response.data.error);
                    });
            },
            loadMoreData($state){
                this.$http.get(this.url + '?page=' + this.page)
                    .then((response) => {
                        return response.json();
                    }).then(response => {
                        $.each(response.data, (key, v) => {
                            this.notes.push(v);
                        });
                        $state.loaded();
                    }).catch(err => {
                        if(err.status === 404){
                            $('.infinite-loading-container').html('Por enquanto é só :)');
                            $('.infinite-loading-container').addClass('text-muted');
                        }
                    });
                this.page += 1;
            },
            save(){
                let formData = new FormData();
                formData.append('title', this.titleNote);
                formData.append('content', this.contentNote);

                axios.post(this.url, formData)
                    .then(response => {
                        toastr.success('Nota adicionada com sucesso');

                        this.titleNote = '';
                        this.contentNote = '';
                        
                        this.refreshInfiniteLoading();
                    }).catch(errors => {
                        $.each(errors.response.data.errors, (key, v) => {
                            toastr.error(v);
                        });
                    });
            },
        }
    }
</script>
