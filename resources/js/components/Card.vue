<template>
    <div class="row">
        <div class="card-columns col-12">
            <div class="" v-for="c in data" :key="c.id">
                <div class="card text-white note mb-3">
                    <div class="card-header">{{c.title}}</div>
                    <div class="card-body">
                        <p class="card-text" style="white-space: break-spaces;">{{c.content}}</p>
                    </div>
                    <div class="card-footer d-flex justify-content-between flex-wrap">
                        <button class="btn btn-primary" :data-toggle="view.dataToggle" :data-target="view.dataTarget" @click="setStore(c)">Visualizar</button>
                        <button class="btn btn-success">Editar</button>
                        <button class="btn btn-danger" :data-toggle="remove.dataToggle" :data-target="remove.dataTarget" @click="setStore(c)">Remover</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['data', 'view', 'remove'],
        methods: {
            setStore(obj){
                this.$store.state.transaction.status = '';
                this.$store.state.transaction.message = '';
                this.$store.state.transaction.data = '';
                this.$store.state.item = obj;
            }
        },
    }
</script>
