{{--
    x-backend.image-cropper — Reusable Image Cropping Modal Component

    Provides an interactive, luxury cropping modal powered by Cropper.js.
    Supports aspect ratios: 1:1 (Square Profile / Card), 4:3, 16:9, Free.
    Provides live card preview, zoom, rotation, flip, and canvas export.

    Global JS API:
      window.openImageCropper({
          imageSrc: '...',
          title: 'Dopasuj kadr zdjęcia',
          aspectRatio: 1, // 1, 4/3, 16/9, NaN
          exportWidth: 1200,
          exportHeight: 1200,
          onCrop: function(blob, dataUrl) { ... },
          onCancel: function() { ... }
      });
--}}

@props([
    'id' => 'globalImageCropperModal',
    'title' => 'Dopasuj kadr zdjęcia (Profilowe / Wizytówka)',
    'defaultRatio' => 1,
])

{{-- Load Cropper.js CSS & JS via CDN (matching backend CDN stack) --}}
@once
    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" integrity="sha512-08KHElDuWJyqygu96nqMgCNUCQ59QIDAQToVIQUmflgkPkhcI1O03unxF3GVMPW+LCTXBU4S9Qn5KNNAfVvBug==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endpush
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js" integrity="sha512-JyCZjCOZoyeQZSd5+WY48kv582lA09iObTfiMqslVSEKKETGtMEsNT5tYo61tQHn+xSTVU+uCLKWFUSWUxQB5g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @endpush
@endonce

{{-- Standalone CSS fallback in case page doesn't push to stack --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" id="cropperjs-css-fallback">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js" id="cropperjs-script-fallback"></script>

<style>
    .image-cropper-modal .modal-dialog {
        max-width: 960px;
    }
    .image-cropper-canvas-wrap {
        position: relative;
        width: 100%;
        height: 440px;
        max-height: 55vh;
        background-color: #1e293b;
        background-image: 
            linear-gradient(45deg, #0f172a 25%, transparent 25%),
            linear-gradient(-45deg, #0f172a 25%, transparent 25%),
            linear-gradient(45deg, transparent 75%, #0f172a 75%),
            linear-gradient(-45deg, transparent 75%, #0f172a 75%);
        background-size: 20px 20px;
        background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
        border-radius: 10px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .image-cropper-canvas-wrap img {
        max-width: 100%;
        max-height: 100%;
        display: block;
    }
    .image-cropper-preview-card {
        width: 100%;
        aspect-ratio: 1 / 1;
        overflow: hidden;
        border-radius: 14px;
        background-color: #f8fafc;
        border: 2px solid #e2e8f0;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
        position: relative;
    }
    .image-cropper-preview-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        overflow: hidden;
        background-color: #f8fafc;
        border: 2px solid #0d6efd;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
        margin: 0 auto;
    }
    .cropper-view-box,
    .cropper-face {
        border-radius: 4px;
    }
    .cropper-line, .cropper-point {
        background-color: #0d6efd;
    }
</style>

<div class="modal fade image-cropper-modal" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg border-0">
            {{-- Modal Header --}}
            <div class="modal-header bg-light border-bottom py-2 px-3">
                <div class="d-flex align-items-center">
                    <span class="badge bg-primary me-2 p-2 rounded-circle">
                        <i class="fas fa-crop-alt"></i>
                    </span>
                    <div>
                        <h6 class="modal-title fw-bold mb-0" id="{{ $id }}Label">{{ $title }}</h6>
                        <small class="text-muted" style="font-size: 11px;">
                            Przesuń, obróć lub przybliż kadr, aby idealnie wyeksponować sylwetkę i pyszczek.
                        </small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zamknij" id="{{ $id }}BtnClose"></button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body p-3">
                <div class="row g-3">
                    {{-- Left Column: Interactive Cropping Area --}}
                    <div class="col-12 col-lg-8">
                        <div class="image-cropper-canvas-wrap mb-2">
                            <img id="{{ $id }}Image" src="" alt="Obraz do kadrowania">
                        </div>

                        {{-- Control Bar --}}
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 bg-light p-2 rounded border">
                            {{-- Aspect Ratio Presets --}}
                            <div class="btn-group btn-group-sm" role="group" aria-label="Proporcje kadru">
                                <button type="button" class="btn btn-outline-secondary cropper-ratio-btn active" data-ratio="1" title="Kwadrat 1:1 (Wizytówka kota / Profilowe)">
                                    <i class="fas fa-square me-1"></i>1:1 (Karta)
                                </button>
                                <button type="button" class="btn btn-outline-secondary cropper-ratio-btn" data-ratio="1.3333333333" title="Portret 4:3 (Galeria)">
                                    4:3
                                </button>
                                <button type="button" class="btn btn-outline-secondary cropper-ratio-btn" data-ratio="1.7777777778" title="Format panoramiczny 16:9 (Baner / Blog)">
                                    16:9
                                </button>
                                <button type="button" class="btn btn-outline-secondary cropper-ratio-btn" data-ratio="NaN" title="Swobodny kadr bez ograniczeń">
                                    Swobodny
                                </button>
                            </div>

                            {{-- Manipulation Tools: Zoom, Rotate, Flip, Reset --}}
                            <div class="d-flex align-items-center gap-1">
                                <button type="button" class="btn btn-sm btn-light border" id="{{ $id }}BtnZoomIn" title="Przybliż kadr">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-light border" id="{{ $id }}BtnZoomOut" title="Oddal kadr">
                                    <i class="fas fa-search-minus"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-light border" id="{{ $id }}BtnRotateLeft" title="Obróć o 90° w lewo">
                                    <i class="fas fa-undo"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-light border" id="{{ $id }}BtnRotateRight" title="Obróć o 90° w prawo">
                                    <i class="fas fa-redo"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-light border" id="{{ $id }}BtnFlipH" title="Odbij w poziomie">
                                    <i class="fas fa-arrows-alt-h"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-light border text-danger" id="{{ $id }}BtnReset" title="Zresetuj kadr do oryginału">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Live Previews and Tips --}}
                    <div class="col-12 col-lg-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="text-uppercase text-muted fw-bold mb-2" style="font-size: 11px; letter-spacing: 0.05em;">
                                <i class="fas fa-eye me-1"></i> Podgląd na wizytówce
                            </div>

                            {{-- Live Square Card Preview Container --}}
                            <div class="image-cropper-preview-card mb-2" id="{{ $id }}PreviewCard">
                                {{-- Cropper will render live preview here --}}
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-3 px-1">
                                <span class="small text-muted" style="font-size: 11px;">Format wizytówki</span>
                                <span class="badge bg-light text-dark border" id="{{ $id }}Dimensions">1200 &times; 1200 px</span>
                            </div>

                            {{-- Avatar Circle Preview --}}
                            <div class="mb-3 text-center">
                                <div class="text-uppercase text-muted fw-bold mb-1" style="font-size: 10px;">
                                    Format awatara / miniatury
                                </div>
                                <div class="image-cropper-preview-circle" id="{{ $id }}PreviewCircle"></div>
                            </div>
                        </div>

                        {{-- Quality Note / Breeder Tip --}}
                        <div class="alert alert-info py-2 px-3 small mb-0 border-0 bg-opacity-10 bg-info" style="font-size: 11.5px; line-height: 1.45;">
                            <div class="fw-bold mb-1 text-primary">
                                <i class="fas fa-lightbulb me-1"></i> Wskazówka hodowcy:
                            </div>
                            Zostaw odrobinę przestrzeni nad uszami oraz po bokach pyszczka. Dzięki temu kadr będzie wyglądał naturalnie i elegancko na każdym telefonie.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer bg-light border-top py-2 px-3 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">
                    Anuluj
                </button>
                <button type="button" class="btn btn-primary btn-sm px-4 fw-bold" id="{{ $id }}BtnConfirm">
                    <i class="fas fa-check me-1"></i> Zastosuj ten kadr
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('{{ $id }}');
    const imageEl = document.getElementById('{{ $id }}Image');
    const titleEl = document.getElementById('{{ $id }}Label');
    const confirmBtn = document.getElementById('{{ $id }}BtnConfirm');
    const dimBadge = document.getElementById('{{ $id }}Dimensions');

    // Controls
    const zoomInBtn = document.getElementById('{{ $id }}BtnZoomIn');
    const zoomOutBtn = document.getElementById('{{ $id }}BtnZoomOut');
    const rotateLeftBtn = document.getElementById('{{ $id }}BtnRotateLeft');
    const rotateRightBtn = document.getElementById('{{ $id }}BtnRotateRight');
    const flipHBtn = document.getElementById('{{ $id }}BtnFlipH');
    const resetBtn = document.getElementById('{{ $id }}BtnReset');
    const ratioBtns = modalEl.querySelectorAll('.cropper-ratio-btn');

    let cropperInstance = null;
    let currentOptions = null;
    let isFlippedH = false;

    // Initialize Bootstrap Modal instance safely
    function getBsModal() {
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            return bootstrap.Modal.getOrCreateInstance(modalEl);
        }
        return null;
    }

    /**
     * Public global function to open cropper from ANY button/component
     */
    window.openImageCropper = function(options) {
        if (!options || !options.imageSrc) {
            console.error('openImageCropper requires an imageSrc in options');
            return;
        }

        currentOptions = Object.assign({
            imageSrc: '',
            title: 'Dopasuj kadr zdjęcia',
            aspectRatio: 1, // 1:1 default
            exportWidth: 1200,
            exportHeight: 1200,
            exportQuality: 0.92,
            exportFormat: 'image/jpeg',
            onCrop: null,
            onCancel: null
        }, options);

        if (titleEl && currentOptions.title) {
            titleEl.textContent = currentOptions.title;
        }

        // Set active ratio button
        ratioBtns.forEach(btn => {
            const btnRatio = parseFloat(btn.dataset.ratio);
            if (isNaN(btnRatio) && isNaN(currentOptions.aspectRatio)) {
                btn.classList.add('active');
            } else if (!isNaN(btnRatio) && !isNaN(currentOptions.aspectRatio) && Math.abs(btnRatio - currentOptions.aspectRatio) < 0.01) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });

        // Destroy previous instance
        if (cropperInstance) {
            cropperInstance.destroy();
            cropperInstance = null;
        }

        // Set image src and open modal
        imageEl.src = currentOptions.imageSrc;
        isFlippedH = false;

        const bsModal = getBsModal();
        if (bsModal) {
            bsModal.show();
        } else {
            modalEl.classList.add('show');
            modalEl.style.display = 'block';
        }
    };

    // When modal finishes showing, initialize Cropper
    modalEl.addEventListener('shown.bs.modal', function () {
        if (typeof Cropper === 'undefined') {
            alert('Biblioteka kadrowania (Cropper.js) ładuje się... Spróbuj ponownie za sekundę.');
            return;
        }

        if (cropperInstance) {
            cropperInstance.destroy();
        }

        cropperInstance = new Cropper(imageEl, {
            aspectRatio: currentOptions ? currentOptions.aspectRatio : 1,
            viewMode: 1, // Restrict crop box within canvas
            dragMode: 'move',
            autoCropArea: 0.9,
            restore: false,
            guides: true,
            center: true,
            highlight: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
            preview: [
                document.getElementById('{{ $id }}PreviewCard'),
                document.getElementById('{{ $id }}PreviewCircle')
            ],
            crop: function(e) {
                const data = e.detail;
                if (dimBadge) {
                    dimBadge.textContent = `${Math.round(data.width)} × ${Math.round(data.height)} px`;
                }
            }
        });
    });

    // Cleanup when modal closes
    modalEl.addEventListener('hidden.bs.modal', function () {
        if (cropperInstance) {
            cropperInstance.destroy();
            cropperInstance = null;
        }
        imageEl.src = '';
    });

    // Ratio button switches
    ratioBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            if (!cropperInstance) return;
            ratioBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const ratio = parseFloat(this.dataset.ratio);
            cropperInstance.setAspectRatio(isNaN(ratio) ? NaN : ratio);
        });
    });

    // Toolbar manipulation handlers
    if (zoomInBtn) {
        zoomInBtn.addEventListener('click', () => cropperInstance && cropperInstance.zoom(0.1));
    }
    if (zoomOutBtn) {
        zoomOutBtn.addEventListener('click', () => cropperInstance && cropperInstance.zoom(-0.1));
    }
    if (rotateLeftBtn) {
        rotateLeftBtn.addEventListener('click', () => cropperInstance && cropperInstance.rotate(-90));
    }
    if (rotateRightBtn) {
        rotateRightBtn.addEventListener('click', () => cropperInstance && cropperInstance.rotate(90));
    }
    if (flipHBtn) {
        flipHBtn.addEventListener('click', () => {
            if (!cropperInstance) return;
            isFlippedH = !isFlippedH;
            cropperInstance.scaleX(isFlippedH ? -1 : 1);
        });
    }
    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            if (!cropperInstance) return;
            isFlippedH = false;
            cropperInstance.reset();
        });
    }

    // Confirm Crop Handler
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            if (!cropperInstance) return;

            const format = (currentOptions && currentOptions.exportFormat) || 'image/jpeg';
            const quality = (currentOptions && currentOptions.exportQuality) || 0.92;

            // Generate high-resolution canvas
            const canvasOptions = {
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            };

            // If ratio is fixed 1:1, export clean square max 1600x1600
            const canvas = cropperInstance.getCroppedCanvas(canvasOptions);
            if (!canvas) {
                alert('Nie udało się wykadrować obrazu. Spróbuj zmienić zaznaczenie.');
                return;
            }

            const dataUrl = canvas.toDataURL(format, quality);

            canvas.toBlob(function(blob) {
                if (currentOptions && typeof currentOptions.onCrop === 'function') {
                    currentOptions.onCrop(blob, dataUrl);
                }

                const bsModal = getBsModal();
                if (bsModal) {
                    bsModal.hide();
                } else {
                    modalEl.classList.remove('show');
                    modalEl.style.display = 'none';
                }
            }, format, quality);
        });
    }
});
</script>
