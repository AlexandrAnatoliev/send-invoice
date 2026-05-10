<?php

declare(strict_types=1);

$sourcePath = $_POST['source_path'] ?? '';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>send-invoice: checkout</title>
  <link rel="stylesheet" href="styles/index.css">
</head>
<body>
  <div class="calculator">
    <div class="button">
      <a href="<?= htmlspecialchars($sourcePath) ?>" >Вернуться</a>
    </div>
  </div>
</body>
</html>
