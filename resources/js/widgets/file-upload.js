import * as FilePond from 'filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';

FilePond.registerPlugin(FilePondPluginImagePreview, FilePondPluginFileValidateType);

/**
 * Replaces the AIZ file-upload widget (drag-drop, preview, "choose file" /
 * "files selected" states — see window.appStrings for the translated copy).
 *
 * Deliberately configured with `instantUpload: false`: there's no confirmed
 * AJAX per-file upload endpoint on the server to target, so rather than
 * invent one, FilePond just patches the underlying <input type="file">'s
 * FileList — the enclosing <form> still submits as a normal multipart POST,
 * exactly as it did before, so no server-side handling needs to change.
 * Registered as an Alpine directive so it's opt-in per input:
 *
 *   <input type="file" name="images[]" multiple x-file-upload>
 *   <input type="file" name="thumbnail" x-file-upload="{ imagePreviewHeight: 120 }">
 */
export function registerFileUploadDirective(Alpine) {
    Alpine.directive('file-upload', (el, { expression }) => {
        const options = expression ? Alpine.evaluate(el, expression) : {};
        FilePond.create(el, {
            instantUpload: false,
            allowMultiple: el.multiple,
            labelIdle: `${window.appStrings?.drop_files_here_paste_or ?? 'Drop files here, paste or'} <span class="filepond--label-action">${window.appStrings?.browse ?? 'Browse'}</span>`,
            ...options,
        });
    });
}
