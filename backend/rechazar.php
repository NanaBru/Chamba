<!-- confirmacion.php -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>Rechazar</title>
    <link rel="shortcut icon" href="img/cross.png" type="image/x-icon">
    <style>
        body {
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-direction: column;
            font-family: Montserrat;
            background: #FFBD40;
        }

        .mensaje-error {
            color: white;
            font-weight: 500;
            margin-top: 1rem;
            font-size: 0.8;
        }

        a {
            text-decoration: none;
        }

        .fa-circle-xmark {
            color: white;
            font-size: 3rem;
        }

        .fa-reply {
            color: white;
            position: fixed;
            right: 1rem;
            bottom: 1rem;
        }

        @font-face {
            font-family: Montserrat;
            src: url(font/Montserrat.ttf);
        }

        #modal {
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100vw;
            height: 100vh;
            background: #000000d9;
        }

        #contenidoModal {
            width: 35vw;
            height: 75vh;
            display: flex;
            overflow: hidden;
            margin: 5rem auto;
            align-items: center;
            border-radius: 0.4rem;
            flex-direction: column;
            background: darkred;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 10px #00000080;
        }
    </style>
</head>

<body>
<a href="../frontend/index.html">
    <div id="modal">
        <div id="contenidoModal">
            <i class="fa-solid fa-circle-xmark"></i>

            <div class="mensaje-error">
                Usuario ya registrado
            </div>

        </div>
    </div>
</a>
</body>

</html>