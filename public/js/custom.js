document.addEventListener('turbo:load', function () {
    // Handle Edit Car Modal
    const editCarModal = document.getElementById('editCarModal');
    editCarModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // The button that triggered the modal
        const modelId = button.getAttribute('data-id');
        const brand = button.getAttribute('data-brand');
        const model = button.getAttribute('data-model');
        const price = button.getAttribute('data-price');
        const description = button.getAttribute('data-description');
        console.log('model id ', modelId, ' brand ', brand, ' model ', model, ' price ', price);

        // Fill the form fields
        document.getElementById('modelId').value = modelId;
        document.getElementById('modelBrand').value = brand;
        document.getElementById('carModel').value = model;
        document.getElementById('carPrice').value = price;
        document.getElementById('carDescription').value = description;
    });

    // Handle Edit Car form submission
    document.getElementById('editCarForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const modelId = document.getElementById('modelId').value;
        const price = document.getElementById('carPrice').value;
        const description = document.getElementById('carDescription').value;
        const photo = document.getElementById('carPhoto').files[0];

        const formData = new FormData();
        formData.append('id', modelId);
        formData.append('price', price);
        formData.append('description', description);
        if (photo) {
            formData.append('photo', photo);
        }

        fetch('/car/update', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {

                    const card = document.querySelector(`button[data-id="${modelId}"]`).closest('.card-body');
                    card.querySelector('.car-price').textContent = 'Цена: ' + price + ' USD';
                    card.querySelector('.card-text').textContent = 'Описание: ' + description;

                    if (photo && data.updatedData.photo) {
                        const imgElement = card.querySelector('.car-image');
                        if (imgElement) {
                            imgElement.src = data.updatedData.photo;
                        }
                    }

                    const editButton = document.querySelector(`button[data-id="${modelId}"][data-bs-toggle="modal"]`);
                    editButton.setAttribute('data-description', data.updatedData.description);
                    editButton.setAttribute('data-price', data.updatedData.price);

                    bootstrap.Modal.getInstance(editCarModal).hide();
                } else {
                    alert('Error updating car: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    });
});

