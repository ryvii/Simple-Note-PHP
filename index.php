<?php
    $error_message = "";
    $success_message = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $title = isset($_POST['title']) ? trim ($_POST['title']) : '';
        $content = isset($_POST['content']) ? trim ($_POST['content']) : '';

        //server-side validation
        if(empty($title) || empty($content)){
            $error_message = "All fields are required.";
        }
        else{
            
            $note_data = "Title: " .  $title . " | Content: " . $content . PHP_EOL;
            file_put_contents('notes.txt', $note_data, FILE_APPEND);
            $success_message = "Note saved successfully!";
            $_POST = [];
            header("Location: index.php");
            exit;
        }
    }   

    $notes =[];
    if(file_exists('notes.txt')){
        $file_content = file_get_contents('notes.txt');
        $lines = explode(PHP_EOL, trim($file_content));
        $notes = array_filter($lines);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>My Notes</title>
</head>
<body>
    <div class="container">
        <h1>My Notes 📝</h1>
        
        <div class="form-box">
            
            <?php if (!empty($error_message)): ?>
                <div class="message error_message">
                    <?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); //safe output of error?> 
                </div>
            <?php endif; ?>

            <?php if (!empty($success_message)): ?>
                <div class="message success_message">
                    <?php echo htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8'); //safe output of success?>
                </div>
            <?php endif; ?>
            
            <form action="index.php" method="POST">
                <div class="form-group">
                    <label for="title">Note Title</label>
                    <input
                    type="text"
                    id="title"
                    name="title"
                    placeholder="Enter note title..."
                    value="<?php echo htmlspecialchars($_POST['title'] ?? '', ENT_QUOTES, 'UTF-8'); //prevent XSS?>"     
                    >
                </div>

                <div class="form-group">
                <label for="content">Note Content</label>
                    <textarea
                    id="content"
                    name="content"
                    placeholder="Enter note content..."
                    <?php echo htmlspecialchars($_POST['content'] ?? '', ENT_QUOTES, 'UTF-8'); //prevent XSS?>></textarea>
                </div>

                <button type="submit">Save Note</button>
            </form>
        </div>

        <?php if(!empty($notes)): ?>
            <div class="notes-section">
                <h2>All Notes (<?php echo count($notes); ?>)</h2>
                <?php foreach ($notes as $note): ?>
                    <div class="note-box">
                        <?php 
                            preg_match('/Title: (.*?) \| Content: (.*)/', $note, $matches);
                            if(isset($matches[1]) && isset($matches[2])):
                        ?>
                        <div class="note-title">
                            <?php echo htmlspecialchars($matches[1], ENT_QUOTES, 'UTF-8'); //prevent XSS?>
                        </div>
                        <div class="note-content">
                            <?php echo htmlspecialchars($matches[2], ENT_QUOTES, 'UTF-8'); //prevent XSS?>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>    
            <div class="no-notes">
                <p>No notes yet. Create your first note above!</p>
            </div>
        <?php endif; ?>

    </div>
</body> 
</html>