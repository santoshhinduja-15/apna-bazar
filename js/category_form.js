document.addEventListener("DOMContentLoaded", function () {
    const imageInput = document.getElementById("imageInput");
    const previewBox = document.getElementById("imagePreviewBox");
    const preview = document.getElementById("imagePreview");

    if (imageInput) {
        imageInput.addEventListener("change", function () {
            const file = this.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
                previewBox.classList.remove("d-none");
            } else {
                previewBox.classList.add("d-none");
            }
        });
    }
});
