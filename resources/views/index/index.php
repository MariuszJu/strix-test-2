<!DOCTYPE html>
<html>
<head>
    <title>Strix Task</title>

    <meta name='viewport' content='width=device-width, initial-scale=1.0'>

    <style>
        body {
            padding: 0 !important;
        }
        nav {
            margin-bottom: 30px;
        }
    </style>

    <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>

    <script src='https://code.jquery.com/jquery-3.3.1.slim.min.js' integrity='sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo' crossorigin='anonymous'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js' integrity='sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1' crossorigin='anonymous'></script>
    <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js' integrity='sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM' crossorigin='anonymous'></script>

</head>

<body>
    <nav class='navbar navbar-light bg-info'>
        <a class='navbar-brand' href='/'>
            <img src='https://www.strix.net/fileadmin/templates/images/svg/strix-logotype-white.svg' width='100' class='d-inline-block align-top' alt=''>
            <span class='text-light'>Strix Test Task</span>
        </a>
    </nav>

    <div class='container'>
        <table class='table'>
            <thead>
            <th scope='col'>trip</th>
            <th scope='col'>distance</th>
            <th scope='col'>measure interval</th>
            <th scope='col'>avg speed</th>
            </thead>
            <tbody>
            <?php foreach ($calculation as $item): ?>
                <tr>
                    <td><?= $item['trip'] ?></td>
                    <td><?= $item['distance'] ?></td>
                    <td><?= $item['interval'] ?></td>
                    <td><?= $item['avg_speed'] ?></td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</body>

</html>