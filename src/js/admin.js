import '../scss/admin.scss';
import { handleButtonsActions  } from './admin/pluginActionManager.js';
jQuery.noConflict();

document.addEventListener('DOMContentLoaded', () => {
    handleButtonsActions();
});