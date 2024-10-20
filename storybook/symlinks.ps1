# Define the real directories and their corresponding symlink directories
$symlinks = @(
    @{ real = "..\app\public\wp-content\themes\starter-kit-classic\components"; symlink = "components\classic-theme\components" },
    @{ real = "..\app\public\wp-content\themes\starter-kit-classic\modules"; symlink = "components\classic-theme\modules" },
    @{ real = "..\app\public\wp-content\themes\starter-kit-blocks\components"; symlink = "components\block-theme\components" },
    @{ real = "..\app\public\wp-content\themes\starter-kit-blocks\blocks"; symlink = "components\block-theme\blocks" }
)

# Function to create directories if they don't exist
function Ensure-DirectoryExists {
    param (
        [string]$directoryPath
    )
    if (-not (Test-Path $directoryPath)) {
        New-Item -ItemType Directory -Path $directoryPath | Out-Null
        Write-Host "Created directory: $directoryPath"
    }
}

# Function to create symbolic links
function Create-Symlink {
    param (
        [string]$realPath,
        [string]$symlinkPath
    )

    # Resolve the real path
    $resolvedRealPath = Resolve-Path -Path $realPath -ErrorAction SilentlyContinue
    if (-not $resolvedRealPath) {
        Write-Host "Error: Real path does not exist: $realPath"
        return
    }

    # Ensure the directory for the symlink exists
    $symlinkDir = Split-Path -Parent $symlinkPath
    Ensure-DirectoryExists -directoryPath $symlinkDir

    # Check if symlink already exists
    if (!(Test-Path $symlinkPath)) {
        Write-Host "Creating symlink: $symlinkPath -> $resolvedRealPath"
        New-Item -ItemType SymbolicLink -Path $symlinkPath -Target $resolvedRealPath
    } else {
        Write-Host "Symlink already exists: $symlinkPath"
    }

    # Confirm symlink creation
    if (Test-Path $symlinkPath) {
        Write-Host "Created symlink: $symlinkPath -> $resolvedRealPath"
    }
}

# Iterate over each pair of real and symlink directories
foreach ($link in $symlinks) {
    Create-Symlink -realPath $link.real -symlinkPath $link.symlink
}

Write-Host "Symlink creation complete."
