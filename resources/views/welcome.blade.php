<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <style>
            #home {
                background: #ffc107;
                color: white;
            }

            .box {
                padding: 60px 0;
                border-bottom: 1px solid #e5e5e5;
            }

            footer p a {
                margin: 5px 15px;
            }
        </style>

        <title>Notes</title>
    </head>
    <body>
        <header>
            <nav class="navbar navbar-expand-sm navbar-light bg-warning">
               <div class="container">
                 <a href="/" class="navbar-brand text-white">
                   Notes
                 </a>
                 @if (Route::has('login'))
                    <div class="float-end">
                        @auth
                            <a class="btn btn-outline-light btn-lg" href="{{ url('/home') }}">Início</a>
                        @else
                            <a class="btn btn-outline-light btn-lg" href="{{ route('login') }}">Entrar</a>
                            @if (Route::has('register'))
                                <a class="btn btn-outline-light btn-lg" href="{{ route('register') }}">Registrar-se</a>
                            @endif
                        @endif
                    </div>
                 @endif
               </div>
            </nav>
          </header>
          <section id="home" class="pt-5">
            <div class="container">
              <div class="row">
                <div class="col-md-6 d-flex">
                  <div class="align-self-center">
                    <h1 class="display-4">Suas notas, em qualquer lugar</h1>
                    <p>Gerencie suas notas de onde estiver à qualquer hora. Use a nossa interface responsiva e intuitiva.</p>
                  </div>
                </div>
                <div class="col-md-6 d-none d-md-block mb-5">
                  <img class="img-fluid float-end" width="450" src="{{ asset('images/note.png') }}">
                </div>
              </div>
            </div>
          </section>
      
          <section class="box">
            <div class="container">
              <div class="row">
                <div class="col-md-6 d-flex">
                  <div class="align-self-center">
                    <h2>Open-Source</h2>
                    <p>Aplicação de código aberto, entre no repositório público e contribua no desenvolvimento. Ajude a melhorar a qualidade do software.</p>
                    <a href="https://github.com/martinsbiel/notes" class="btn btn-primary mt-4" target="_blank">Acessar repositório no GitHub</a>
                  </div>
                </div>
                <div class="col-md-6">
                  <img src="{{ asset('images/github.png') }}" class="img-fluid">
                </div>
              </div>
            </div>
          </section>
      
          <section class="box">
            <div class="container">
              <div class="row">
                <div class="col-md-6">
                  <img src="{{ asset('images/money.png') }}" class="img-fluid">
                </div>
                <div class="col-md-6 d-flex">
                  <div class="align-self-center">
                    <h2>De graça</h2>
                    <p>Mantenha suas notas guardadas e consulte-as à hora que quiser e de onde estiver, tudo isso de graça.</p>
                    <a href="{{ route('register') }}" class="btn btn-primary mt-4">Registre-se</a>
                  </div>
                </div>
              </div>
            </div>
          </section>
      
          <section class="box">
            <div class="container">
              <div class="row">
                <div class="col-md-4">
                  <img src="{{ asset('images/responsive.png') }}" class="img-fluid mb-5" width="300">
                  <h4>Responsivo</h4>
                  <p>A aplicação se adapta de acordo com o tamanho do seu dispositivo, acesse pelo computador, tablet e celular!</p>
                </div>
                <div class="col-md-4">
                  <img src="{{ asset('images/simple.png') }}" class="img-fluid mb-5" width="300">
                  <h4>Simples</h4>
                  <p>Com uma interface simples e intuitiva fica fácil criar e gerenciar suas notas.</p>
                </div>
                <div class="col-md-4">
                  <img src="{{ asset('images/code.png') }}" class="img-fluid" width="350">
                  <h4>Encontrou um erro?</h4>
                  <p>Problemas, ideias? Faça parte do desenvolvimento da aplicação no nosso <a href="https://github.com/martinsbiel/notes" target="_blank">GitHub</a>.</p>
                </div>
              </div>
            </div>
          </section>
      
          <footer class="mt-4 mb-4">
            <div class="container">
              <div class="row">
                <div class="col-md-8">
                  <p>
                    <a href="https://github.com/martinsbiel/notes">GitHub</a>
                    <a href="https://twitter.com/martinsbieel">Twitter</a>
                    <a href="#">Site</a>
                  </p>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                  Copyright &copy;
                </div>
              </div>
            </div>
          </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </body>
</html>