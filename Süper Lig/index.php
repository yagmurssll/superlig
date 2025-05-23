<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Süper Lig Takim Girişi</title>
</head>
<body>
    <h1>18 Takimi Girin</h1>
    <form method="post" action="lig.php">
        <?php for ($i = 1; $i <= 18; $i++): ?>
            Takim <?= $i ?>: <input type="text" name="takimlar[]"><br>
        <?php endfor; ?>
        <input type="submit" value="Ligi Başlat">
    </form>
</body>
</html>