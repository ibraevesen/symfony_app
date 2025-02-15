document.addEventListener('turbo:load', function () {
    // Handle Edit Car Modal
    const editCarModal = document.getElementById('editCarModal');
    editCarModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // The button that triggered the modal
        const modelId = button.getAttribute('data-id');
        const brand = button.getAttribute('data-brand');
        const model = button.getAttribute('data-model');
        const description = button.getAttribute('data-description');

        // Fill the form fields
        document.getElementById('modelId').value = modelId;
        document.getElementById('modelBrand').value = brand;
        document.getElementById('carModel').value = model;
        document.getElementById('carDescription').value = description;
    });

    // Handle Edit Car form submission
    document.getElementById('editCarForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const modelId = document.getElementById('modelId').value;
        const model = document.getElementById('carModel').value;
        const description = document.getElementById('carDescription').value;
        const photo = document.getElementById('carPhoto').files[0];

        const formData = new FormData();
        formData.append('id', modelId);
        formData.append('model', model);
        formData.append('description', description);
        if (photo) {
            formData.append('photo', photo);
        }
        console.log(formData);

        fetch('/car/update', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Найти карточку и обновить данные
                    const card = document.querySelector(`button[data-id="${modelId}"]`).closest('.card-body');
                    card.querySelector('.card-subtitle').textContent = 'Модель: ' + model;  // Обновить описание модели
                    card.querySelector('.card-text').textContent = 'Описание: ' + description;  // Обновить описание

                    // Обновить фото, если оно было изменено
                    if (photo && data.updatedData.photo) {
                        const imgElement = card.querySelector('.car-image');
                        if (imgElement) {
                            imgElement.src = data.updatedData.photo;  // Обновить фото
                        }
                    }

                    // Обновить data-атрибуты кнопки "Edit"
                    const editButton = card.querySelector('button[data-bs-toggle="modal"]');
                    editButton.setAttribute('data-model', model);
                    editButton.setAttribute('data-description', description);

                    // Закрыть модальное окно
                    bootstrap.Modal.getInstance(editCarModal).hide();
                } else {
                    alert('Error updating car: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    });
});

