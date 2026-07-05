import { Html5Qrcode } from 'html5-qrcode';

let html5QrCode = null;
let scannerStarted = false;

async function startQrScanner(onScanSuccess) {
    const container = document.getElementById('qr-reader');
    if (!container || scannerStarted) return;

    try {
        html5QrCode = new Html5Qrcode('qr-reader');

        const devices = await Html5Qrcode.getCameras();
        let cameraId;

        if (devices && devices.length > 0) {
            const rearCamera = devices.find(d => d.label.toLowerCase().includes('back') || d.label.toLowerCase().includes('rear'));
            cameraId = rearCamera ? rearCamera.id : devices[devices.length - 1].id;
        } else {
            cameraId = { facingMode: 'environment' };
        }

        await html5QrCode.start(
            cameraId,
            {
                fps: 10,
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1,
            },
            (decodedText) => {
                onScanSuccess(decodedText);
            },
            () => {}
        );

        scannerStarted = true;
        const statusEl = document.getElementById('scanner-status');
        const placeholderEl = document.getElementById('scanner-placeholder');
        if (statusEl) statusEl.classList.replace('hidden', 'flex');
        if (placeholderEl) placeholderEl.classList.add('hidden');
    } catch (err) {
        console.error('QR Scanner error:', err);
        const errorEl = document.getElementById('scanner-error');
        const placeholderEl = document.getElementById('scanner-placeholder');
        if (errorEl) errorEl.classList.remove('hidden');
        if (placeholderEl) placeholderEl.classList.add('hidden');
    }
}

async function stopQrScanner() {
    if (html5QrCode && scannerStarted) {
        try {
            await html5QrCode.stop();
            html5QrCode.clear();
        } catch (e) {
        }
        scannerStarted = false;
        html5QrCode = null;
        const statusEl = document.getElementById('scanner-status');
        const placeholderEl = document.getElementById('scanner-placeholder');
        if (statusEl) statusEl.classList.add('hidden');
        if (placeholderEl) placeholderEl.classList.remove('hidden');
    }
}

function isScannerRunning() {
    return scannerStarted;
}

window.__qrScanner = { startQrScanner, stopQrScanner, isScannerRunning };
