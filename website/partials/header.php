<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Development Task Manager</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.classless.min.css">
</head>
<body>

    <header>
        <h1>Game Development Task Manager</h1>

        <nav>
            <a href="index.php"     class="<?= $page=='index.php'     ? 'active' : '' ?>">Home</a>
            <a href="login.php"     class="<?= $page=='login.php'     ? 'active' : '' ?>">Login</a>
            <a href="newService.php"     class="<?= $page=='newService.php'     ? 'active' : '' ?>">New Service</a>
        </nav>
    </header>

    <main>