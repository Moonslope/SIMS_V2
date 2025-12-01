# Script to replace #271AD2 with #0F00CD in all blade files

$oldColor = "#271AD2"
$newColor = "#0F00CD"
$hoverColor = "#0D00B0"

# Find all blade files
$bladeFiles = Get-ChildItem -Path ".\resources\views" -Recurse -Filter "*.blade.php"

Write-Host "Found $($bladeFiles.Count) blade files" -ForegroundColor Green

foreach ($file in $bladeFiles) {
    $content = Get-Content $file.FullName -Raw
    
    if ($content -match [regex]::Escape($oldColor)) {
        Write-Host "Updating: $($file.FullName)" -ForegroundColor Yellow
        
        # Replace the old color with new color
        $content = $content -replace [regex]::Escape($oldColor), $newColor
        
        # Also update hover:bg-primary to hover:bg-[#0D00B0] in button contexts
        $content = $content -replace 'hover:bg-primary\b', "hover:bg-[$hoverColor]"
        
        # Save the file
        Set-Content -Path $file.FullName -Value $content -NoNewline
    }
}

Write-Host "`nColor replacement completed!" -ForegroundColor Green
