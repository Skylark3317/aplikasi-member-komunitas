import { ref } from 'vue';

// Global state untuk dialog
const confirmDialogState = ref({
  show: false,
  title: '',
  message: '',
  variant: 'danger',
  confirmText: 'Konfirmasi',
  cancelText: 'Batal',
  loading: false,
  resolve: null,
  reject: null
});

const alertDialogState = ref({
  show: false,
  title: '',
  message: '',
  variant: 'info',
  buttonText: 'OK',
  resolve: null
});

export function useDialog() {
  /**
   * Show confirmation dialog
   * @param {Object} options
   * @param {string} options.title - Dialog title
   * @param {string} options.message - Dialog message
   * @param {string} options.variant - 'danger', 'warning', 'info', 'success'
   * @param {string} options.confirmText - Confirm button text
   * @param {string} options.cancelText - Cancel button text
   * @returns {Promise<boolean>}
   */
  function confirm(options) {
    return new Promise((resolve, reject) => {
      confirmDialogState.value = {
        show: true,
        title: options.title || 'Konfirmasi',
        message: options.message,
        variant: options.variant || 'danger',
        confirmText: options.confirmText || 'Konfirmasi',
        cancelText: options.cancelText || 'Batal',
        loading: false,
        resolve,
        reject
      };
    });
  }

  /**
   * Show alert dialog
   * @param {Object} options
   * @param {string} options.title - Dialog title (optional)
   * @param {string} options.message - Dialog message
   * @param {string} options.variant - 'error', 'warning', 'info', 'success'
   * @param {string} options.buttonText - Button text
   * @returns {Promise<void>}
   */
  function alert(options) {
    return new Promise((resolve) => {
      alertDialogState.value = {
        show: true,
        title: options.title || '',
        message: options.message,
        variant: options.variant || 'info',
        buttonText: options.buttonText || 'OK',
        resolve
      };
    });
  }

  function handleConfirm() {
    if (confirmDialogState.value.resolve) {
      confirmDialogState.value.resolve(true);
    }
    confirmDialogState.value.show = false;
  }

  function handleCancel() {
    if (confirmDialogState.value.reject) {
      confirmDialogState.value.reject(false);
    }
    confirmDialogState.value.show = false;
  }

  function handleAlertClose() {
    if (alertDialogState.value.resolve) {
      alertDialogState.value.resolve();
    }
    alertDialogState.value.show = false;
  }

  function setConfirmLoading(loading) {
    confirmDialogState.value.loading = loading;
  }

  return {
    confirm,
    alert,
    confirmDialogState,
    alertDialogState,
    handleConfirm,
    handleCancel,
    handleAlertClose,
    setConfirmLoading
  };
}
