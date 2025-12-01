# Script to update error message classes to text-sm
$files = Get-ChildItem -Path "resources\views" -Filter "*.blade.php" -Recurse

foreach ($file in $files) {
    $content = Get-Content $file.FullName -Raw
    $original = $content
    
    # Update @error blocks with text-red-500 to text-red-500 text-sm
    $content = $content -replace '@error\([^)]+\)\s*<span\s+class="text-red-500"', '@error($1)<span class="text-red-500 text-sm"'
    $content = $content -replace '@error\([^)]+\)\s*<p\s+class="text-red-500"', '@error($1)<p class="text-red-500 text-sm"'
    $content = $content -replace '@error\([^)]+\)\s*<div\s+class="text-red-500"', '@error($1)<div class="text-red-500 text-sm"'
    
    # Update error classes that don't have text-sm
    $content = $content -replace 'class="text-red-500"([^>]*>)', 'class="text-red-500 text-sm"$1'
    $content = $content -replace 'class="text-red-600"([^>]*>)', 'class="text-red-600 text-sm"$1'
    
    # Remove duplicate text-sm if already exists
    $content = $content -replace 'text-sm\s+text-sm', 'text-sm'
    
    if ($content -ne $original) {
        Set-Content -Path $file.FullName -Value $content -NoNewline
        Write-Host "Updated: $($file.FullName)"
    }
}

Write-Host "`nDone! All error messages updated to text-sm"
