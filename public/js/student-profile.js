// Student profile - Document upload handler
document.addEventListener('DOMContentLoaded', function () {
    const uploadForm = document.getElementById('uploadDocumentForm');

    if (uploadForm) {
        uploadForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);
            const uploadBtn = document.getElementById('upload_btn');
            const progressDiv = document.getElementById('upload_progress');
            const progressBar = document.getElementById('progress_bar');
            const progressText = document.getElementById('progress_text');
            const alertDiv = document.getElementById('upload_alert');
            const uploadMessage = document.getElementById('upload_message');
            const uploadUrl = document.getElementById('upload_url').value;

            // Clear previous errors
            document.getElementById('document_type_error').classList.add('hidden');
            document.getElementById('document_file_error').classList.add('hidden');
            alertDiv.classList.add('hidden');

            // Disable upload button
            uploadBtn.disabled = true;
            progressDiv.classList.remove('hidden');

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || form.querySelector('[name="_token"]').value;

                const response = await fetch(uploadUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    alertDiv.classList.remove('hidden', 'alert-error');
                    alertDiv.classList.add('alert-success');
                    uploadMessage.textContent = result.message;

                    // Reset form
                    form.reset();

                    // Reload page after 1.5 seconds to show new document
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    throw new Error(result.message || 'Upload failed');
                }
            } catch (error) {
                alertDiv.classList.remove('hidden', 'alert-success');
                alertDiv.classList.add('alert-error');
                uploadMessage.textContent = error.message || 'An error occurred during upload';
                uploadBtn.disabled = false;
            } finally {
                progressDiv.classList.add('hidden');
                progressBar.value = 0;
            }
        });
    }
});
