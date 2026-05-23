<?php

$dir = new RecursiveDirectoryIterator('resources/views');
$ite = new RecursiveIteratorIterator($dir);

$replacements = [
    'bg-emerald-400' => 'bg-splitwise',
    'bg-emerald-300' => 'bg-splitwise-dark',
    'bg-emerald-500/10' => 'bg-splitwise-light',
    'text-emerald-700' => 'text-splitwise-dark',
    'text-emerald-100' => 'text-splitwise',
    'text-emerald-200' => 'text-splitwise',
    'text-emerald-300' => 'text-splitwise',
    'text-emerald-400' => 'text-splitwise',
    'shadow-emerald-500/30' => 'shadow-splitwise/30',
    'ring-emerald-300/70' => 'ring-splitwise/70',
    'ring-emerald-400/40' => 'ring-splitwise/40',
    'border-emerald-300' => 'border-splitwise',
    'ring-emerald-300/60' => 'ring-splitwise/60',
    'bg-teal-600' => 'bg-splitwise',
    'bg-teal-700' => 'bg-splitwise-dark',
    'bg-teal-50' => 'bg-splitwise-light',
    'text-teal-700' => 'text-splitwise-dark',
    'text-teal-900' => 'text-splitwise-dark',
    'ring-teal-500' => 'ring-splitwise',
    'text-red-700' => 'text-danger',
    'text-red-600' => 'text-danger',
    'text-red-300' => 'text-danger',
    'bg-red-500/10' => 'bg-danger-light',
    'bg-red-50' => 'bg-danger-light',
    'border-red-100' => 'border-danger-light',
    'text-slate-950' => 'text-slate-900',
    'border-emerald-400/30' => 'border-splitwise/30',
    'glass-input' => 'border-slate-300 bg-white placeholder:text-slate-400 focus:border-splitwise focus:ring-splitwise shadow-sm'
];

foreach ($ite as $file) {
    if ($file->isDir()) continue;
    $path = $file->getPathname();
    if (!str_ends_with($path, '.blade.php')) continue;

    if (str_contains($path, 'dashboard\index.blade.php') || str_contains($path, 'dashboard/index.blade.php')) continue; // Already updated manually
    if (str_contains($path, 'layouts\app.blade.php') || str_contains($path, 'layouts/app.blade.php')) continue; // Already updated
    if (str_contains($path, 'layouts\navigation.blade.php') || str_contains($path, 'layouts/navigation.blade.php')) continue; // Already updated
    
    $content = file_get_contents($path);
    $newContent = strtr($content, $replacements);
    if ($content !== $newContent) {
        file_put_contents($path, $newContent);
        echo "Updated " . $path . "\n";
    }
}
echo "Done replacing.\n";
