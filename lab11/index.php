<?php
// Файл для хранения заметок
$filename = 'notes.json';

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title']) && isset($_POST['text'])) {
    // Получаем данные из формы
    $title = trim($_POST['title']);
    $text = trim($_POST['text']);
    
    // Проверяем, что поля не пустые
    if (!empty($title) && !empty($text)) {
        // Читаем существующие заметки
        $currentData = file_get_contents($filename);
        $notes = json_decode($currentData, true);
        
        // Если файл пустой или данные невалидны, создаем пустой массив
        if (!is_array($notes)) {
            $notes = [];
        }
        
        // Создаем новую заметку
        $newNote = [
            'id' => time(), // Используем временную метку как уникальный ID
            'title' => $title,
            'text' => $text
        ];
        
        // Добавляем новую заметку в массив
        $notes[] = $newNote;
        
        // Сохраняем обновленные данные в файл
        $jsonData = json_encode($notes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($filename, $jsonData);
    }
    
    // Перенаправляем на ту же страницу, чтобы избежать повторной отправки формы при обновлении
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Читаем все заметки для отображения
if (file_exists($filename)) {
    $data = file_get_contents($filename);
    $notes = json_decode($data, true);
    
    // Проверяем, что данные корректны
    if (!is_array($notes)) {
        $notes = [];
    }
} else {
    // Если файл не существует, создаем пустой массив
    $notes = [];
    // Создаем пустой файл
    file_put_contents($filename, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог заметок</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>📝 Каталог заметок</h1>
        
        <!-- Форма добавления заметки -->
        <div class="form-container">
            <h2>Добавить новую заметку</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="title">Заголовок:</label>
                    <input type="text" id="title" name="title" required placeholder="Введите заголовок">
                </div>
                
                <div class="form-group">
                    <label for="text">Текст заметки:</label>
                    <textarea id="text" name="text" required placeholder="Введите текст заметки" rows="5"></textarea>
                </div>
                
                <button type="submit" class="btn">Добавить заметку</button>
            </form>
        </div>
        
        <!-- Список заметок -->
        <div class="notes-container">
            <h2>Список заметок</h2>
            
            <?php if (empty($notes)): ?>
                <p class="empty-message">📭 Заметок пока нет. Добавьте первую заметку!</p>
            <?php else: ?>
                <?php foreach ($notes as $note): ?>
                    <div class="note">
                        <h3><?php echo htmlspecialchars($note['title']); ?></h3>
                        <p class="note-text"><?php echo nl2br(htmlspecialchars($note['text'])); ?></p>
                        <small class="note-id">ID: <?php echo $note['id']; ?></small>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>