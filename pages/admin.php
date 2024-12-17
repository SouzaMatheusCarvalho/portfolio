<?php
session_start();
$message = '';
$message_type = '';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

if (isset($_POST['delete_project'])) {
    $project_dir = $_POST['project_dir'];
    $project_path = __DIR__ . '/../projetos/' . $project_dir;

    if (is_dir($project_path)) {
        $files = array_diff(scandir($project_path), array('.', '..'));
        foreach ($files as $file) {
            $file_path = $project_path . '/' . $file;
            is_dir($file_path) ? rmdir($file_path) : unlink($file_path);
        }
        rmdir($project_path);
        $_SESSION['message'] = 'Projeto excluído com sucesso.';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Diretório não encontrado.';
        $_SESSION['message_type'] = 'error';
    }
    header('Location: admin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['delete_project'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $directory = __DIR__ . '/../projetos/' . strtolower(str_replace(' ', '-', $title));

    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);

        foreach ($_FILES['media']['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['media']['name'][$key];
            move_uploaded_file($tmp_name, "$directory/$file_name");
        }


/* Parte responsável por gerar o código HTML dos projetos*/        

        $html_content = "
<?php
\$title = '$title';
\$description = '$description';
\$currentDir = __DIR__;
\$mediaFiles = glob(\$currentDir . '/*.{jpg,png,gif,mp4,webm,jpeg}', GLOB_BRACE);

if (!empty(\$mediaFiles)) {
    \$mainMedia = basename(\$mediaFiles[0]);
    \$mainMediaExt = pathinfo(\$mainMedia, PATHINFO_EXTENSION);
    \$mediaHtml = '';

    foreach (\$mediaFiles as \$index => \$file) {
        \$fileName = basename(\$file);
        \$fileExt = pathinfo(\$fileName, PATHINFO_EXTENSION);
        \$mediaHtml .= '<div class=\"mySlides zoom-container\">';
        \$mediaHtml .= '<div class=\"numbertext\">' . (\$index + 1) . ' / ' . count(\$mediaFiles) . '</div>';
        if (in_array(\$fileExt, ['jpg', 'png', 'gif'])) {
            \$mediaHtml .= '<img src=\"' . \$fileName . '\" class=\"zoomable\">';
        } elseif (in_array(\$fileExt, ['mp4', 'webm'])) {
            \$mediaHtml .= '<video src=\"' . \$fileName . '\" class=\"zoomable\" controls></video>';
        }
        \$mediaHtml .= '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang=\"pt-br\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title><?php echo \$title; ?></title>
    <link rel=\"stylesheet\" href=\"../../css/common.css\">
    <script src='../../js/common.js' defer></script>
</head>
<?php include '../../includes/header.php'; ?>
<body>
    <div class='main'>
        <section class='projects-section'>
            <div class='project-card'>
                <h2 class='project-title'><?php echo \$title; ?></h2>
                <div class='media-container'>
                    <?php if (!empty(\$mediaFiles)): ?>
                        <?php if (in_array(\$mainMediaExt, ['jpg', 'png', 'gif', 'jpeg'])): ?>
                            <img src=\"<?php echo \$mainMedia; ?>\" onclick=\"openModal();currentSlide(1)\" class=\"main-media hover-shadow\">
                        <?php elseif (in_array(\$mainMediaExt, ['mp4', 'webm'])): ?>
                            <video src=\"<?php echo \$mainMedia; ?>\" onclick=\"openModal();currentSlide(1)\" class=\"main-media hover-shadow\" controls></video>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div id=\"myModal\" class=\"modal\">
                        <!-- Botão de Fechar -->
                        <span class=\"close cursor\" onclick=\"closeModal()\">&times;</span>
                        <div class=\"modal-content\">
                            <?php echo \$mediaHtml; ?>
                        </div>
                        <a class=\"prev\" onclick=\"plusSlides(-1)\">&#10094;</a>
                        <a class=\"next\" onclick=\"plusSlides(1)\">&#10095;</a>
                    </div>
                </div>
                <p><?php echo \$description; ?></p>
            </div>
        </section>
    </div>
    <script src='../../js/common.js' defer></script>
</body>
</html>
";

file_put_contents("$directory/index.php", $html_content);

        $_SESSION['message'] = "Projeto '$title' criado com sucesso!";
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = "O diretório já existe.";
        $_SESSION['message_type'] = 'error';
    }
    header('Location: admin.php');
    exit();
}

$projects = array_diff(scandir(__DIR__ . '/../projetos'), array('.', '..'));
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="../css/style-admin.css"> 
    <link href='https://unpkg.com/boxicons@2.1.3/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .message {
            opacity: 1;
            transition: opacity 1s ease;
        }
    </style>
</head>
<body>

    <?php if ($message): ?>
        <div id="message" class="message <?= htmlspecialchars($message_type) ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div id='section'>
        <a href="/index.php">
            <i class='bx bxs-home' id="home"></i>
        </a>
        <h1>Painel Administrativo</h1>
        <form action="admin.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Título do Projeto:</label>
                <input type="text" name="title" id="title" required>
            </div>

            <div class="form-group">
                <label for="description">Descrição:</label>
                <textarea name="description" id="description" required></textarea>
            </div>

            <div class="form-group">
                <label for="media">Mídias:</label>
                <input type="file" name="media[]" id="media" multiple required>
            </div>

            <input type="submit" value="Criar Projeto">
        </form>
    </div>

    <h2>Projetos Existentes</h2>
    <form action="" method="post">
        <div class="form-group">
            <select name="project_dir" required>
                <option value="">Selecione um projeto</option>
                <?php
                $projects = glob('../projetos/*', GLOB_ONLYDIR);
                foreach ($projects as $project) {
                    $dir = basename($project);
                    echo "<option value='$dir'>$dir</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" name="delete_project">Excluir Projeto</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const message = document.getElementById('message');
            
            if (message) {
                setTimeout(() => {
                    message.style.opacity = '0'; 
                    setTimeout(() => {
                        message.remove(); 
                    }, 1000); 
                }, 2500); 
        });
    </script>
</body>
</html>
