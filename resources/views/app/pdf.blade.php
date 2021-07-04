<!doctype html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

        <style>
            html, body {
                font-family: Arial, Helvetica, sans-serif;
            }
            #table {
                margin-top: 20px;
                width: 100%;
            }

            #table thead th {
                padding: 5px;
                font-size: 16px;
                border-bottom: 3px solid #F1F1F1;
                margin-bottom: 9px;
            }

            #table tbody th {
                font-weight: normal;
                padding: 14px;
                cursor: pointer;
            }

            #table tbody tr:nth-child(even){
                background-color: #f2f2f2;
            }
        </style>

        <title>Minhas notas</title>
    </head>
    <body>
        <table id="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Conteúdo</th>
                    <th>Data de criação</th>
                    <th>Última atualização</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notes as $key => $note)
                <tr>
                    <td>{{$note->id}}</td>
                    <td>{{$note->title}}</td>
                    <td>{{$note->content}}</td>
                    <td>{{date('d/m/Y', strtotime($note->created_at))}}</td>
                    <td>{{date('d/m/Y', strtotime($note->updated_at))}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>