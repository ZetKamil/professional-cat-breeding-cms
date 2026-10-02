param (
    [string]$InputPath,
    [string]$OutputPath,
    [int]$TargetWidth = 1200,
    [int]$TargetHeight = 675
)

Add-Type -AssemblyName System.Drawing

$src = [System.Drawing.Image]::FromFile($InputPath)
$bmp = New-Object System.Drawing.Bitmap($TargetWidth, $TargetHeight)
$g = [System.Drawing.Graphics]::FromImage($bmp)

$g.InterpolationMode = [System.Drawing.Drawing2D.InterpolationMode]::HighQualityBicubic
$g.SmoothingMode = [System.Drawing.Drawing2D.SmoothingMode]::HighQuality
$g.PixelOffsetMode = [System.Drawing.Drawing2D.PixelOffsetMode]::HighQuality
$g.CompositingQuality = [System.Drawing.Drawing2D.CompositingQuality]::HighQuality

# Construct float 2D array for ColorMatrix (contrast and color vibrancy enhancement)
$row0 = [float[]]@(1.12, 0.00, 0.00, 0.00, 0.00)
$row1 = [float[]]@(0.00, 1.12, 0.00, 0.00, 0.00)
$row2 = [float[]]@(0.00, 0.00, 1.12, 0.00, 0.00)
$row3 = [float[]]@(0.00, 0.00, 0.00, 1.00, 0.00)
$row4 = [float[]]@(-0.03, -0.03, -0.03, 0.00, 1.00)

$cmArray = [float[][]]@($row0, $row1, $row2, $row3, $row4)
$cm = New-Object System.Drawing.Imaging.ColorMatrix (,$cmArray)

$attr = New-Object System.Drawing.Imaging.ImageAttributes
$attr.SetColorMatrix($cm, [System.Drawing.Imaging.ColorMatrixFlag]::Default, [System.Drawing.Imaging.ColorAdjustType]::Bitmap)

# Calculate center crop for target ratio
$srcAspect = $src.Width / $src.Height
$targetAspect = $TargetWidth / $TargetHeight

if ($srcAspect -gt $targetAspect) {
    $cropH = $src.Height
    $cropW = [int]($src.Height * $targetAspect)
    $cropX = [int](($src.Width - $cropW) / 2)
    $cropY = 0
} else {
    $cropW = $src.Width
    $cropH = [int]($src.Width / $targetAspect)
    $cropX = 0
    $cropY = [int](($src.Height - $cropH) / 2)
}

$srcRect = New-Object System.Drawing.Rectangle($cropX, $cropY, $cropW, $cropH)
$destRect = New-Object System.Drawing.Rectangle(0, 0, $TargetWidth, $TargetHeight)

$g.DrawImage($src, $destRect, $srcRect.X, $srcRect.Y, $srcRect.Width, $srcRect.Height, [System.Drawing.GraphicsUnit]::Pixel, $attr)

$encoder = [System.Drawing.Imaging.Encoder]::Quality
$encoderParams = New-Object System.Drawing.Imaging.EncoderParameters(1)
$encoderParams.Param[0] = New-Object System.Drawing.Imaging.EncoderParameter($encoder, 92L)
$jpegCodec = [System.Drawing.Imaging.ImageCodecInfo]::GetImageEncoders() | Where-Object { $_.MimeType -eq 'image/jpeg' }

$bmp.Save($OutputPath, $jpegCodec, $encoderParams)

$g.Dispose()
$bmp.Dispose()
$src.Dispose()
Write-Host "Successfully processed and enhanced $InputPath -> $OutputPath"
