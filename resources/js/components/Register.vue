<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Registrar-se</div>

                    <div class="card-body">
                        <form method="POST" action="" @submit.prevent="register($event)" enctype="multipart/form-data">
                            <input type="hidden" name="_token" :value="csrf_token">
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Nome</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" required autocomplete="name" autofocus v-model="name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-mail</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" required autocomplete="email" v-model="email">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Senha</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password" v-model="password">
                                    <small class="text-muted">A senha deve conter no mínimo 4 caracteres</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirmar senha</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password"  v-model="passwordConfirm">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Registrar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['csrf_token'], // data (semelhante)
        data(){
            return {
                url: 'http://localhost:8000/api/register',
                name: '',
                email: '',
                password: '',
                passwordConfirm: '',
            }
        },
        methods: {
            register(e){
                let formData = new FormData();
                formData.append('name', this.name);
                formData.append('email', this.email);
                formData.append('password', this.password);
                formData.append('password-confirm', this.passwordConfirm);

                let loginUrl = 'http://localhost:8000/api/login';
                let configs = {
                    method: 'post',
                    body: new URLSearchParams({
                        'email': this.email,
                        'password': this.password
                    })
                }
             
                axios.post(this.url, formData)
                    .then(response => {
                        console.log(response);
                    }).catch(errors => {
                        console.log(errors);
                    });

                fetch(loginUrl, configs)
                    .then(response => response.json())
                    .then(data => {
                        if(data.token){
                            document.cookie = 'token=' + data.token;
                            localStorage.setItem('token', data.token);
                        }
                    });
                
                e.target.submit();
            }
        }
    }
</script>
