<?php
// Directory to scan for software files
$directory = __DIR__ . '/downloads';

// File extensions allowed
$allowed_extensions = ['zip', 'exe', 'pdf', 'rar', 'msi', 'docx'];

// Collect files
$files = [];
if (is_dir($directory)) {
    $scan = scandir($directory);
    foreach ($scan as $file) {
        if ($file != '.' && $file != '..') {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($ext, $allowed_extensions)) {
                $path = "downloads/" . $file;
                $size = filesize($directory . '/' . $file);
                $files[] = [
                    'name' => $file,
                    'path' => $path,
                    'size' => round($size / 1024 / 1024, 2) . ' MB',
                    'date' => date("Y-m-d H:i:s", filemtime($directory . '/' . $file))
                ];
            }
        }
    }
    // Sort by newest first
    usort($files, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Software Directory</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
<style>
  body { background-color: #f8fafc; }
</style>
</head>
<body class="min-h-screen flex flex-col">

<header class="bg-slate-800 text-white p-5">
  <div class="max-w-6xl mx-auto flex justify-between items-center">
    <h1 class="text-2xl font-semibold">Software Directory</h1>
    <input id="searchInput" type="text" placeholder="Search..." class="rounded p-2 text-black w-64">
  </div>
</header>

<main class="flex-1 max-w-6xl mx-auto p-6">
  <div class="bg-white rounded-lg shadow-sm divide-y" id="softwareList">
    <?php if (empty($files)): ?>
      <div class="p-6 text-center text-gray-500">No files found in /downloads folder.</div>
    <?php else: ?>
      <?php foreach ($files as $file): ?>
        <div class="p-4 flex flex-col md:flex-row justify-between md:items-center file-row">
          <div>
            <h3 class="text-lg font-semibold file-name"><?php echo htmlspecialchars($file['name']); ?></h3>
            <p class="text-sm text-gray-600">Size: <?php echo $file['size']; ?></p>
            <p class="text-xs text-gray-500 mt-1">Last updated: <?php echo $file['date']; ?></p>
          </div>
          <a href="<?php echo $file['path']; ?>" class="mt-3 md:mt-0 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700" download>Download</a>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</main>

<footer class="text-center text-gray-600 text-sm py-4">
  © 2025 Software Directory — Auto updates from /downloads folder
</footer>

<script>
  const searchInput = document.getElementById('searchInput');
  const fileRows = document.querySelectorAll('.file-row');

  searchInput.addEventListener('input', () => {
    const query = searchInput.value.toLowerCase();
    fileRows.forEach(row => {
      const name = row.querySelector('.file-name').textContent.toLowerCase();
      row.style.display = name.includes(query) ? '' : 'none';
    });
  });
</script>

</body>
</html>
