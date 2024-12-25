document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('ajaxAddCategoryForm');
    if (!form) return; 

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        const formData = new FormData(form);

        formData.append('action', 'add_ajax');

        fetch('handler_cat.php', {
            method: 'POST',
            body: formData
        })
        .then((response) => response.json())
        .then((data) => {
            const msgDiv = document.getElementById('ajaxCategoryMessage');
            if (data.success) {
                msgDiv.style.color = 'green';
                msgDiv.innerText = data.message;
                form.reset();
            } else {
                msgDiv.style.color = 'red';
                msgDiv.innerText = data.message;
            }
        })
        .catch((error) => {
            console.error(error);
            const msgDiv = document.getElementById('ajaxCategoryMessage');
            msgDiv.style.color = 'red';
            msgDiv.innerText = 'An error occurred while adding category.';
        });
    });
});
