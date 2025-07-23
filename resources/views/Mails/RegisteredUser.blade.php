<!DOCTYPE html>
<html>
<head>
    <title>Bienvenido a Nuestro Sitio</title>
<style>
    body {
        color: #fff;
        font-family: sans-serif;
    }
    p {
        color: #fff;
    }
    .container {
        width: 60%;
        margin: auto;
        background-color: #212936;
        padding: 20px;
    }

    .container .cont {
        text-align: center
    }
    .header,.footer {
        text-align: center;
        padding: 20px 0;
    }
    .content {
        margin-top: 20px;
    }
    .footer {
        margin-top: 20px;
        color: #777;
        padding-bottom: 0
    }
    img{
        width: 100px;
    }
    h1{
        margin: 0;
    }
    .primario{
        color: #fff;
    }
    .table_component {
        overflow: auto;
        width: 100%;
    }

    .table_component table {
        border: 1px solid #dededf;
        height: 100%;
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
        border-spacing: 1px;
        text-align: center;
    }

    .table_component caption {
        caption-side: top;
        text-align: left;
    }

    .table_component th {
        border: 1px solid #dededf;
        background-color: #121826;
        color: #fff;
        padding: 10px;
    }

    .table_component td {
        border: 1px solid #dededf;
        background-color: #ffffff;
        color: #000000;
        padding: 10px;
        background-color: #D1DFEC;
    }
    .enlace_plataforma{
        color: #268cf2;
        text-decoration: underline;
    }
</style>
</head>
<body>
    <div class="container">
        <div class="cont">
            <div class="header">
                <h1 class="primario">Hola, {{ $datos['name'] }}!</h1>
            </div>
            <div class="content">
                <p>A continuación, te enviamos tus credenciales de acceso para que puedas iniciar sesión en nuestro sitio web:</p>
                <div class="table_component" role="region" tabindex="0">
                    <table>
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Contraseña temporal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $datos['user'] }}</td>
                                <td>{{ $datos['password'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @php
                    if (App::environment('local')) {
                        $url = env('URL_FRONTEND_DEV');
                    }else if(App::environment('production')) {
                        $url = env('URL_FRONTEND');
                    }
                @endphp
                <p>Puede ingresar dando click en el siguiente enlace <a href="{{$url.'/Login'}}" class="enlace_plataforma">Ir a Plataforma</a></p>
                <p style="margin-bottom: 20px">Te recordamos que puedes cambiar tu contraseña en cuanto inicies sesión.</p>
                <p><small>Si tienes algún problema o necesitas ayuda, no dudes en contactarnos a través de soporte@119emergencias.com.</small></p>
            </div>
            <div class="footer">
                <hr>
                <p>Sistema de inspecciones &copy; {{ date('Y') }} . Todos los derechos reservados.</p>
            </div>
        </div>
    </div>
</body>
</html>
