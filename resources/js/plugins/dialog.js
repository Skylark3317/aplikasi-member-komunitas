import { createApp, h } from 'vue';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';
import AlertDialog from '@/Components/AlertDialog.vue';
import { useDialog } from '@/composables/useDialog';

export default {
  install(app) {
    // Mount dialog components globally
    const { confirmDialogState, alertDialogState, handleConfirm, handleCancel, handleAlertClose } = useDialog();

    // Create a container for dialogs
    const dialogContainer = document.createElement('div');
    dialogContainer.id = 'dialog-container';
    document.body.appendChild(dialogContainer);

    // Mount ConfirmDialog
    const confirmApp = createApp({
      setup() {
        return () => h(ConfirmDialog, {
          modelValue: confirmDialogState.value.show,
          title: confirmDialogState.value.title,
          message: confirmDialogState.value.message,
          variant: confirmDialogState.value.variant,
          confirmText: confirmDialogState.value.confirmText,
          cancelText: confirmDialogState.value.cancelText,
          loading: confirmDialogState.value.loading,
          'onUpdate:modelValue': (value) => {
            if (!value) handleCancel();
          },
          onConfirm: handleConfirm,
          onCancel: handleCancel
        });
      }
    });

    const confirmEl = document.createElement('div');
    dialogContainer.appendChild(confirmEl);
    confirmApp.mount(confirmEl);

    // Mount AlertDialog
    const alertApp = createApp({
      setup() {
        return () => h(AlertDialog, {
          modelValue: alertDialogState.value.show,
          title: alertDialogState.value.title,
          message: alertDialogState.value.message,
          variant: alertDialogState.value.variant,
          buttonText: alertDialogState.value.buttonText,
          'onUpdate:modelValue': (value) => {
            if (!value) handleAlertClose();
          },
          onClose: handleAlertClose
        });
      }
    });

    const alertEl = document.createElement('div');
    dialogContainer.appendChild(alertEl);
    alertApp.mount(alertEl);

    // Add $dialog to global properties
    app.config.globalProperties.$dialog = useDialog();
  }
};
