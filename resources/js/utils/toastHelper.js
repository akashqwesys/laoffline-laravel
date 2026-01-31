/**
 * Toast Helper - Global Toaster Utility
 * 
 * Usage:
 * import { showErrorToast, showSuccessToast, showWarningToast, showInfoToast } from '@/utils/toastHelper';
 * 
 * showErrorToast('Error message');
 * showSuccessToast('Success message');
 * showWarningToast('Warning message');
 * showInfoToast('Info message');
 */

const toastConfig = {
    position: 'top-right',
    timeOut: 10000,
    ui: 'is-dark'
};

export function showErrorToast(message, config = {}) {
    const options = { ...toastConfig, ...config };
    const formattedMessage = `<span class="fw-bold text-uppercase">Error</span><div class="mt-1">${message}</div>`;
    NioApp.Toast(formattedMessage, 'error', options);
}

export function showSuccessToast(message, config = {}) {
    const options = { ...toastConfig, ...config };
    const formattedMessage = `<span class="fw-bold text-uppercase">Success</span><div class="mt-1">${message}</div>`;
    NioApp.Toast(formattedMessage, 'success', options);
}

export function showWarningToast(message, config = {}) {
    const options = { ...toastConfig, ...config };
    const formattedMessage = `<span class="fw-bold text-uppercase">Warning</span><div class="mt-1">${message}</div>`;
    NioApp.Toast(formattedMessage, 'warning', options);
}

export function showInfoToast(message, config = {}) {
    const options = { ...toastConfig, ...config };
    const formattedMessage = `<span class="fw-bold text-uppercase">Info</span><div class="mt-1">${message}</div>`;
    NioApp.Toast(formattedMessage, 'info', options);
}
